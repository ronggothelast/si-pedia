<x-auth-layout title="Reset Password — SI-Pedia">
    <h2 class="text-3xl font-extrabold text-ink-900 mb-2">Reset Password</h2>
    <p class="text-gray-500 mb-6">Enter your new password below.</p>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#336cbc] focus:border-transparent outline-none transition">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input type="password" id="password" name="password" required
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#336cbc] focus:border-transparent outline-none transition">
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#336cbc] focus:border-transparent outline-none transition">
        </div>

        <button type="submit"
            class="w-full py-3 rounded-xl bg-[#336cbc] text-white font-semibold hover:bg-blue-700 transition">
            Reset Password
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        <a href="{{ route('login') }}" class="text-[#336cbc] hover:underline font-medium">Back to Login</a>
    </p>
</x-auth-layout>
