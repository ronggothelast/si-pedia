<x-auth-layout title="Forgot Password — SI-Pedia">
    <h1 class="mb-3 text-center text-4xl font-extrabold tracking-tight text-gray-900">RESET</h1>
    <p class="mb-8 text-center text-gray-600">Masukkan email Anda untuk menerima tautan reset password.</p>

    @if (session('status'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm font-medium text-center">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="email" name="email" required placeholder="Email@gmail.com"
               class="h-[58px] w-full rounded-[14px] border-0 bg-field px-6 text-gray-800 placeholder-gray-600 shadow-md focus:ring-2 focus:ring-brand-600">
        <button type="submit"
                class="mt-7 h-[58px] w-full rounded-[12px] bg-brand-600 text-lg font-bold tracking-wide text-white shadow-md transition hover:bg-brand-700">
            Send Reset Link
        </button>
    </form>
    <p class="mt-6 text-center">
        <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline">Back to Login</a>
    </p>
</x-auth-layout>
