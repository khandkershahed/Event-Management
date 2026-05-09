<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">Please verify your email address by clicking the link we emailed to you.</div>
    @if (session('status') == 'verification-link-sent')<div class="mb-4 font-medium text-sm text-green-600">A new verification link has been sent.</div>@endif
    <form method="POST" action="{{ route('verification.send') }}">@csrf <x-primary-button>Resend Verification Email</x-primary-button></form>
</x-guest-layout>
