<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CustomerConfirmation;
use App\Mail\NewLead;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class LeadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $throttleKey = 'leads:' . ($request->ip() ?: 'unknown');
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return response()->json([
                'success' => false,
                'error' => 'Too many submissions. Please try again in a minute.',
            ], 429);
        }
        RateLimiter::hit($throttleKey, 60);

        $data = $request->validate([
            'name'              => ['required', 'string', 'max:120'],
            'email'             => ['required', 'email:rfc', 'max:160'],
            'phone'             => ['nullable', 'string', 'max:32'],
            'town'              => ['nullable', 'string', 'max:80'],
            'zip'               => ['nullable', 'string', 'max:16'],
            'consultation_type' => ['nullable', 'string', 'max:32'],
            'contact_method'    => ['nullable', 'string', 'max:16'],
            'best_time'         => ['nullable', 'string', 'max:64'],
            'services'          => ['nullable', 'array'],
            'services.*'        => ['string', 'max:80'],
            'message'           => ['nullable', 'string', 'max:4000'],
            'source_page'       => ['nullable', 'string', 'max:200'],
            'source_page_path'  => ['nullable', 'string', 'max:200'],
            'hp'                => ['nullable', 'string', 'max:0'],
        ]);

        if (!empty($data['hp'])) {
            return response()->json(['success' => true]);
        }
        unset($data['hp']);

        $lead = Lead::create([
            ...$data,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        try {
            $recipients = array_filter(array_map('trim', explode(',', (string) config('mail.lead_recipients', config('mail.from.address')))));
            if (!empty($recipients)) {
                Mail::to($recipients)->send(new NewLead($lead));
                $lead->update(['emailed_at' => now()]);
            }
        } catch (\Throwable $e) {
            Log::error('Lead email failed', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
        }

        if (!empty($lead->email)) {
            try {
                Mail::to($lead->email)->send(new CustomerConfirmation($lead));
            } catch (\Throwable $e) {
                Log::error('Customer confirmation email failed', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
            }
        }

        return response()->json([
            'success' => true,
            'id'      => $lead->id,
            'message' => 'Thanks, we received your request.',
        ]);
    }
}
