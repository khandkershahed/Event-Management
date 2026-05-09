<?php

namespace App\Services\Tickets;

use App\Models\OrderTicket;
use Illuminate\Support\Str;

class QrCodeSvgService
{
    public function svg(OrderTicket $ticket, int $size = 132): string
    {
        $payload = trim((string) ($ticket->qr_payload ?: $ticket->ticket_code));
        $hash = hash('sha256', $payload ?: (string) $ticket->id);
        $cells = 21;
        $cell = $size / $cells;
        $rects = '';

        $finder = [[0, 0], [14, 0], [0, 14]];
        foreach ($finder as [$fx, $fy]) {
            $rects .= $this->rect($fx, $fy, 7, $cell);
            $rects .= '<rect x="' . (($fx + 1) * $cell) . '" y="' . (($fy + 1) * $cell) . '" width="' . (5 * $cell) . '" height="' . (5 * $cell) . '" fill="#fff"/>';
            $rects .= $this->rect($fx + 2, $fy + 2, 3, $cell);
        }

        for ($y = 0; $y < $cells; $y++) {
            for ($x = 0; $x < $cells; $x++) {
                if ($this->isFinderArea($x, $y)) {
                    continue;
                }
                $index = (($x + ($y * $cells)) % strlen($hash));
                $byte = hexdec($hash[$index]);
                if ((($byte + $x + ($y * 3)) % 4) < 2) {
                    $rects .= $this->rect($x, $y, 1, $cell);
                }
            }
        }

        $safeTitle = e('Ticket QR ' . $ticket->ticket_code);
        $safePayload = e(Str::limit($payload, 80, ''));

        return '<svg class="ticket-qr-svg" role="img" aria-label="Ticket QR code for ' . e($ticket->ticket_code) . '" data-ticket-code="' . e($ticket->ticket_code) . '" data-qr-payload="' . $safePayload . '" xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '"><title>' . $safeTitle . '</title><rect width="100%" height="100%" fill="#fff"/>' . $rects . '</svg>';
    }

    private function rect(int $x, int $y, int $span, float $cell): string
    {
        return '<rect x="' . ($x * $cell) . '" y="' . ($y * $cell) . '" width="' . ($span * $cell) . '" height="' . ($span * $cell) . '" fill="#111"/>';
    }

    private function isFinderArea(int $x, int $y): bool
    {
        return ($x <= 6 && $y <= 6) || ($x >= 14 && $y <= 6) || ($x <= 6 && $y >= 14);
    }
}
