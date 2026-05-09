@php
    $qrSize = $size ?? 132;
@endphp
<div class="ticket-qr-block text-center" aria-label="Visible QR code for ticket {{ $ticket->ticket_code }}">
    <div class="d-inline-block border rounded bg-white p-2">
        {!! app(\App\Services\Tickets\QrCodeSvgService::class)->svg($ticket, $qrSize) !!}
    </div>
    <div class="small text-muted mt-2">QR / Ticket Code</div>
    <div class="fw-bold small" style="word-break:break-word">{{ $ticket->ticket_code }}</div>
</div>
