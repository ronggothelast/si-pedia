<x-auth-layout title="Verify Email — SI-Pedia">
    <h2 class="text-3xl font-extrabold text-ink-900 mb-2">Verify Your Email</h2>
    <p class="text-gray-500 mb-6">
        Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm font-medium">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
            class="w-full py-3 rounded-xl bg-[#336cbc] text-white font-semibold hover:bg-blue-700 transition">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="w-full text-center text-sm text-gray-500 hover:text-gray-700 underline">
            Log Out
        </button>
    </form>
</x-auth-layout>
