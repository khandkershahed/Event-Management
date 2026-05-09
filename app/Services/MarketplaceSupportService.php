<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\MarketplaceSupportMessage;
use App\Models\MarketplaceSupportTicket;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MarketplaceSupportService
{
    public function createTicket(array $data, Model $creator, string $guard): MarketplaceSupportTicket
    {
        return DB::transaction(function () use ($data, $creator, $guard) {
            $ticket = MarketplaceSupportTicket::create([
                'user_id' => $data['user_id'] ?? null,
                'organizer_profile_id' => $data['organizer_profile_id'] ?? null,
                'event_id' => $data['event_id'] ?? null,
                'order_id' => $data['order_id'] ?? null,
                'refund_request_id' => $data['refund_request_id'] ?? null,
                'organizer_payout_id' => $data['organizer_payout_id'] ?? null,
                'created_by_type' => $creator::class,
                'created_by_id' => $creator->getKey(),
                'type' => $data['type'] ?? MarketplaceSupportTicket::TYPE_GENERAL,
                'priority' => $data['priority'] ?? MarketplaceSupportTicket::PRIORITY_NORMAL,
                'status' => $data['status'] ?? MarketplaceSupportTicket::STATUS_OPEN,
                'subject' => $data['subject'],
                'description' => $data['description'],
                'last_replied_at' => now(),
            ]);

            $this->addMessage($ticket, $creator, $guard, $data['description']);

            return $ticket->fresh(['messages']);
        });
    }

    public function addMessage(MarketplaceSupportTicket $ticket, Model $sender, string $guard, string $body, bool $internal = false): MarketplaceSupportMessage
    {
        return DB::transaction(function () use ($ticket, $sender, $guard, $body, $internal) {
            $message = MarketplaceSupportMessage::create([
                'marketplace_support_ticket_id' => $ticket->id,
                'sender_type' => $sender::class,
                'sender_id' => $sender->getKey(),
                'sender_guard' => $guard,
                'body' => $body,
                'is_internal' => $internal,
            ]);

            $ticket->forceFill(['last_replied_at' => now()])->save();

            return $message;
        });
    }

    public function updateStatus(MarketplaceSupportTicket $ticket, string $status, ?string $priority = null, ?Admin $admin = null): MarketplaceSupportTicket
    {
        $updates = ['status' => $status];

        if ($priority) {
            $updates['priority'] = $priority;
        }

        if ($admin) {
            $updates['assigned_admin_id'] = $admin->id;
        }

        if ($status === MarketplaceSupportTicket::STATUS_RESOLVED) {
            $updates['resolved_at'] = now();
        }

        if ($status === MarketplaceSupportTicket::STATUS_CLOSED) {
            $updates['closed_at'] = now();
        }

        $ticket->forceFill($updates)->save();

        return $ticket->fresh(['user', 'organizerProfile.user']);
    }

    public function userCanView(User $user, MarketplaceSupportTicket $ticket): bool
    {
        return (int) $ticket->user_id === (int) $user->id;
    }

    public function organizerCanView(OrganizerProfile $profile, MarketplaceSupportTicket $ticket): bool
    {
        return (int) $ticket->organizer_profile_id === (int) $profile->id;
    }
}
