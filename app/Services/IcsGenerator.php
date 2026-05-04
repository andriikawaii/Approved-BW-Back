<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Carbon;

class IcsGenerator
{
    public static function forLead(Lead $lead): ?string
    {
        // Try to derive a start time. If we have best_time + a reasonable date,
        // we'll generate a real all-day or timed event. Otherwise fall back
        // to a "tentative" event 24 hours from creation so calendars still
        // show something the user can move.

        $start = self::deriveStart($lead);
        $end = (clone $start)->addHours(2);

        $uid = sprintf('builtwell-lead-%d@builtwellct.com', $lead->id);
        $now = gmdate('Ymd\THis\Z');
        $dtstart = $start->copy()->utc()->format('Ymd\THis\Z');
        $dtend = $end->copy()->utc()->format('Ymd\THis\Z');

        $summary = 'BuiltWell CT — Consultation: ' . self::escape($lead->name);
        $location = self::escape(self::buildLocation($lead));
        $description = self::escape(self::buildDescription($lead));
        $organizer = 'mailto:info@builtwellct.com';
        $attendee = $lead->email
            ? sprintf('ATTENDEE;CN=%s;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION:mailto:%s',
                self::escape($lead->name), $lead->email)
            : null;

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//BuiltWell CT//Lead Booking//EN',
            'METHOD:REQUEST',
            'CALSCALE:GREGORIAN',
            'BEGIN:VEVENT',
            'UID:' . $uid,
            'DTSTAMP:' . $now,
            'DTSTART:' . $dtstart,
            'DTEND:' . $dtend,
            'SUMMARY:' . $summary,
            'LOCATION:' . $location,
            'DESCRIPTION:' . $description,
            'ORGANIZER;CN=BuiltWell CT:' . $organizer,
        ];

        if ($attendee) {
            $lines[] = $attendee;
        }

        $lines = array_merge($lines, [
            'STATUS:TENTATIVE',
            'TRANSP:OPAQUE',
            'BEGIN:VALARM',
            'ACTION:DISPLAY',
            'DESCRIPTION:BuiltWell consultation reminder',
            'TRIGGER:-PT1H',
            'END:VALARM',
            'END:VEVENT',
            'END:VCALENDAR',
        ]);

        return implode("\r\n", $lines) . "\r\n";
    }

    private static function deriveStart(Lead $lead): Carbon
    {
        // For now: tentative slot 24 hours from now in America/New_York,
        // 9 AM. We don't have explicit scheduled fields yet so this is
        // a best-effort placeholder customers and the BuiltWell team can
        // move once they confirm by phone.
        $tz = 'America/New_York';

        $best = (string) ($lead->best_time ?? '');
        $hour = 9;
        if (str_contains($best, '8:00 AM')) $hour = 8;
        elseif (str_contains($best, '10:00 AM')) $hour = 10;
        elseif (str_contains($best, '12:00 PM')) $hour = 12;
        elseif (str_contains($best, '2:00 PM')) $hour = 14;

        return Carbon::tomorrow($tz)->setHour($hour)->setMinute(0)->setSecond(0);
    }

    private static function buildLocation(Lead $lead): string
    {
        $parts = array_filter([
            $lead->town,
            $lead->zip,
            'CT',
        ]);

        return $parts ? implode(', ', $parts) : 'Connecticut';
    }

    private static function buildDescription(Lead $lead): string
    {
        $lines = [
            'BuiltWell CT consultation request from ' . $lead->name . '.',
            '',
        ];

        if ($lead->phone) {
            $lines[] = 'Phone: ' . $lead->phone;
        }
        if ($lead->email) {
            $lines[] = 'Email: ' . $lead->email;
        }
        if (!empty($lead->services)) {
            $services = is_array($lead->services) ? implode(', ', $lead->services) : $lead->services;
            $lines[] = 'Services: ' . $services;
        }
        if ($lead->best_time) {
            $lines[] = 'Best Time: ' . $lead->best_time;
        }
        if ($lead->message) {
            $lines[] = '';
            $lines[] = 'Notes: ' . $lead->message;
        }

        $lines[] = '';
        $lines[] = 'This is a tentative time. The BuiltWell team will confirm by phone.';

        return implode('\\n', $lines);
    }

    private static function escape(string $value): string
    {
        return str_replace(
            ['\\', "\r\n", "\n", "\r", ',', ';'],
            ['\\\\', '\\n', '\\n', '\\n', '\\,', '\\;'],
            $value
        );
    }
}
