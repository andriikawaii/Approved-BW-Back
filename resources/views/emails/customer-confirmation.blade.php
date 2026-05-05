<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BuiltWell CT</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e2b43; margin: 0; padding: 24px; background: #ffffff; }
  .wrap { max-width: 640px; margin: 0 auto; background: #fff; border: 1px solid #e6dccd; border-radius: 8px; overflow: hidden; }
  .header { background: #ffffff; padding: 32px 32px 28px; text-align: center; border-bottom: 1px solid #e6dccd; }
  .header img { max-width: 240px; height: auto; }
  .header .tagline { color: #bc9155; font-size: 18px; line-height: 1.4; margin-top: 16px; font-style: italic; font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-weight: 400; white-space: nowrap; }
  .header .tagline span { color: #1e2b43; }
  .body { padding: 32px; }
  h1 { font-size: 22px; margin: 0 0 16px; color: #1e2b43; font-weight: 600; }
  p { font-size: 16px; line-height: 1.6; margin: 0 0 16px; color: #1e2b43; }
  h2 { font-size: 13px; text-transform: uppercase; letter-spacing: 1.5px; color: #bc9155; margin: 28px 0 10px; font-weight: 700; }
  table { width: 100%; border-collapse: collapse; margin: 8px 0; }
  td { padding: 10px 0; border-bottom: 1px solid #f0e9dc; font-size: 15px; vertical-align: top; }
  td.label { width: 130px; color: #5c677d; font-weight: 600; }
  .calendar-cta { background: #ffffff; padding: 20px; border-left: 4px solid #bc9155; margin-top: 24px; border-radius: 0 6px 6px 0; }
  .calendar-cta h3 { margin: 0 0 6px; color: #1e2b43; font-size: 16px; }
  .calendar-cta p { font-size: 14px; margin: 0; color: #5c677d; }
  .next-steps { margin-top: 28px; }
  .next-steps li { margin: 6px 0; font-size: 15px; color: #1e2b43; }
  .footer { background: #ffffff; padding: 24px 32px; border-top: 1px solid #e6dccd; text-align: center; font-size: 13px; color: #5c677d; line-height: 1.6; }
  .footer a { color: #bc9155; text-decoration: none; font-weight: 600; }
  .footer .divider { color: #d6cebc; margin: 0 8px; }
  .signature { margin-top: 24px; font-style: italic; color: #5c677d; font-size: 14px; line-height: 1.7; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <img src="https://api.builtwellct.com/logos/builtwell-logo-colored-cropped.png" alt="BuiltWell CT" />
    <div class="tagline">The Last Contractor You'll <span>Hire.</span></div>
  </div>

  <div class="body">

    <h1>Thanks, {{ explode(' ', trim($lead->name))[0] }} — we got your request.</h1>

    <p>Our team will review the details below and reach back out within one business day to confirm a time and walk through next steps. If anything's urgent, call or text us at <a href="tel:+12039199616" style="color: #bc9155; font-weight: 600; text-decoration: none;">(203) 919-9616</a>.</p>

    <h2>What you sent us</h2>
    <table>
      <tr><td class="label">Name</td><td>{{ $lead->name }}</td></tr>
      @if($lead->phone)
      <tr><td class="label">Phone</td><td>{{ $lead->phone }}</td></tr>
      @endif
      @if($lead->property_address)
      <tr><td class="label">Property</td><td>{{ $lead->property_address }}{{ $lead->town ? ', ' . $lead->town : '' }}{{ $lead->zip ? ' '.$lead->zip : '' }}</td></tr>
      @elseif($lead->town || $lead->zip)
      <tr><td class="label">Location</td><td>{{ trim(($lead->town ?? '') . ' ' . ($lead->zip ?? '')) }}</td></tr>
      @endif
      @if($lead->best_time)
      <tr><td class="label">Best Time</td><td>{{ $lead->best_time }}</td></tr>
      @endif
      @if(!empty($lead->services))
      <tr><td class="label">Services</td><td>{{ is_array($lead->services) ? implode(', ', $lead->services) : $lead->services }}</td></tr>
      @endif
      @if($lead->contact_method)
      <tr><td class="label">Prefers</td><td>{{ ucfirst($lead->contact_method) }}</td></tr>
      @endif
    </table>

    @if($lead->message)
      <h2>Your Notes</h2>
      <p style="background: #ffffff; padding: 16px; border-left: 3px solid #bc9155; font-size: 15px;">{{ $lead->message }}</p>
    @endif

    @php
        $isVirtual = in_array(strtolower((string) ($lead->consultation_type ?? '')), ['video','virtual','remote','google-meet','google_meet','meet','phone']);
        $meetLink = config('services.builtwell.meet_link');
    @endphp

    <div class="calendar-cta">
      <h3>📅 Tentative consultation hold attached</h3>
      <p>We've attached a calendar file (<strong>builtwell-consultation.ics</strong>) so you can drop a tentative hold on your calendar. We'll confirm the actual time when we reach out.</p>
    </div>

    @if($isVirtual && $meetLink)
    <div style="margin-top: 20px; padding: 20px; background: #1e2b43; border-radius: 8px; text-align: center;">
      <h3 style="color: #ffffff; margin: 0 0 8px; font-size: 16px;">Your Google Meet link</h3>
      <p style="color: #d8b27a; font-size: 14px; margin: 0 0 14px;">Use this link at the scheduled time. We'll be there to take the call.</p>
      <a href="{{ $meetLink }}" style="display: inline-block; background: #bc9155; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 15px;">Join Google Meet</a>
      <p style="color: #8a92a3; font-size: 12px; margin: 12px 0 0; word-break: break-all;">{{ $meetLink }}</p>
    </div>
    @endif

    <h2>What happens next</h2>
    <ol class="next-steps">
      <li>We review your project and any photos you sent</li>
      <li>We call or text you within one business day to confirm a time</li>
      <li>We meet on-site, walk the project, talk scope, and answer questions</li>
      <li>You get a clear, written estimate — no high-pressure selling</li>
    </ol>

    <div class="signature">
      Looking forward to it,<br>
      <strong style="color: #1e2b43;">The BuiltWell CT Team</strong>
    </div>

  </div>

  <div class="footer">
    <strong style="color: #1e2b43;">BuiltWell CT</strong><br>
    206A Boston Post Road, Orange, CT &nbsp;<span class="divider">·</span>&nbsp; CT HIC #0668405<br>
    <a href="tel:+12039199616">(203) 919-9616</a>
    <span class="divider">·</span>
    <a href="mailto:info@builtwellct.com">info@builtwellct.com</a>
    <span class="divider">·</span>
    <a href="https://builtwellct.com/">builtwellct.com</a>
  </div>

</div>
</body>
</html>
