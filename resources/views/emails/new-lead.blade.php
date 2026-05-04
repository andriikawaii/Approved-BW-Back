<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>New Lead</title>
<style>
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #1e2b43; margin: 0; padding: 24px; background: #f5f1e9; }
.wrap { max-width: 640px; margin: 0 auto; background: #fff; padding: 32px; border-radius: 8px; border: 1px solid #e6dccd; }
h1 { font-size: 22px; margin: 0 0 20px; color: #1e2b43; }
h2 { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: #bc9155; margin: 24px 0 8px; }
table { width: 100%; border-collapse: collapse; }
td { padding: 8px 0; border-bottom: 1px solid #f0e9dc; font-size: 15px; vertical-align: top; }
td.label { width: 140px; color: #5c677d; font-weight: 600; }
td.value { color: #1e2b43; }
.msg { background: #f5f1e9; padding: 16px; border-left: 3px solid #bc9155; margin-top: 8px; font-size: 15px; line-height: 1.6; white-space: pre-wrap; }
.cta { margin-top: 24px; }
.cta a { display: inline-block; background: #bc9155; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; }
.meta { margin-top: 24px; font-size: 13px; color: #8a92a3; }
</style>
</head>
<body>
<div class="wrap">
  <div style="background: #1e2b43; padding: 20px; text-align: center; margin: -32px -32px 24px -32px; border-radius: 8px 8px 0 0;">
    <img src="https://api.builtwellct.com/logos/builtwell-logo-white.png" alt="BuiltWell CT" style="max-width: 180px; height: auto;" />
  </div>
  <h1>New BuiltWell Lead</h1>

  <table>
    <tr><td class="label">Name</td><td class="value">{{ $lead->name }}</td></tr>
    <tr><td class="label">Email</td><td class="value"><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></td></tr>
    @if($lead->phone)
    <tr><td class="label">Phone</td><td class="value"><a href="tel:{{ preg_replace('/[^\d+]/', '', $lead->phone) }}">{{ $lead->phone }}</a></td></tr>
    @endif
    @if($lead->town)
    <tr><td class="label">Town</td><td class="value">{{ $lead->town }}{{ $lead->zip ? ' ('.$lead->zip.')' : '' }}</td></tr>
    @endif
    @if($lead->property_address)
    <tr><td class="label">Property</td><td class="value">{{ $lead->property_address }}</td></tr>
    @endif
    @if($lead->contact_method)
    <tr><td class="label">Prefers</td><td class="value">{{ ucfirst($lead->contact_method) }}</td></tr>
    @endif
    @if($lead->best_time)
    <tr><td class="label">Best Time</td><td class="value">{{ $lead->best_time }}</td></tr>
    @endif
    @if($lead->consultation_type)
    <tr><td class="label">Consultation</td><td class="value">{{ ucfirst($lead->consultation_type) }}</td></tr>
    @endif
    @if(!empty($lead->services))
    <tr><td class="label">Services</td><td class="value">{{ is_array($lead->services) ? implode(', ', $lead->services) : $lead->services }}</td></tr>
    @endif
  </table>

  @if($lead->message)
  <h2>Message</h2>
  <div class="msg">{{ $lead->message }}</div>
  @endif

  <div class="cta">
    @if($lead->phone)
    <a href="tel:{{ preg_replace('/[^\d+]/', '', $lead->phone) }}">Call {{ $lead->name }}</a>
    @endif
  </div>

  <div class="meta">
    Submitted {{ $lead->created_at->format('M j, Y g:i A') }}<br>
    @if($lead->source_page_path)Source page: {{ $lead->source_page_path }}<br>@endif
    @if($lead->ip_address)IP: {{ $lead->ip_address }}@endif
  </div>
</div>
</body>
</html>
