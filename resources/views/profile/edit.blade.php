<x-app-layout>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900">
        <h2 class="text-lg font-medium mb-4">Profile</h2>
        <form method="POST" action="{{ route('profile.update') }}">@csrf @method('PATCH')
            <div class="mb-4"><x-input-label for="name" value="Name" /><x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required /><x-input-error class="mt-2" :messages="$errors->get('name')" /></div>
            <div class="mb-4"><x-input-label for="email" value="Email" /><x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required /><x-input-error class="mt-2" :messages="$errors->get('email')" /></div>
            <x-primary-button>Save</x-primary-button>
        </form>
        <form method="POST" action="{{ route('profile.destroy') }}" class="mt-8">@csrf @method('DELETE')
            <div class="mb-4"><x-input-label for="password" value="Password" /><x-text-input id="password" name="password" type="password" class="mt-1 block w-full" /><x-input-error class="mt-2" :messages="$errors->userDeletion->get('password')" /></div>
            <x-danger-button>Delete Account</x-danger-button>
        </form>
    </div></div></div></div>
</x-app-layout>
