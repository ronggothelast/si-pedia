<x-layouts.app title="Update Profil — SI-Pedia" footer="min">
<main class="mx-auto max-w-[1000px] px-8 py-10">
  <div class="flex items-center gap-4">
    <a href="{{ route('profile.show') }}" class="text-3xl hover:text-brand-600 transition">←</a>
    <h1 class="text-4xl font-extrabold">Update Profil</h1>
  </div>
  <p class="ml-12 mt-1 text-2xl text-gray-700">Update your profile information to keep your account secure and up to date.</p>

  <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-8 rounded-3xl border border-gray-200 p-10 shadow-sm bg-white">
    @csrf
    @method('PUT')
    
    <h2 class="text-2xl font-extrabold">Profile picture</h2>
    <div class="mt-4 grid h-28 w-28 place-items-center rounded-full bg-gray-200 text-3xl overflow-hidden">
      @if($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
      @else
        ⬛
      @endif
    </div>
    <label class="mt-4 inline-block cursor-pointer rounded-lg bg-accent-600 px-7 py-3 text-lg font-bold text-white hover:bg-indigo-700 transition">
        Select Photo
        <input type="file" name="avatar" accept="image/*" class="hidden">
    </label>
    @error('avatar') <p class="text-red-500 mt-2 text-sm">{{ $message }}</p> @enderror

    <hr class="my-8 border-gray-200">
    <div class="space-y-8">
      <div>
        <label class="mb-2 block text-2xl font-extrabold">Email</label>
        <div class="flex items-center gap-4 rounded-xl border-2 border-gray-300 px-5 py-4 focus-within:border-brand-600">
          <span class="text-2xl text-gray-400">✉</span>
          <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border-none p-0 text-xl font-bold text-gray-800 focus:ring-0" placeholder="nama@gmail.com" required>
        </div>
        @error('email') <p class="text-red-500 mt-2 text-sm">{{ $message }}</p> @enderror
      </div>
      <div>
        <label class="mb-2 block text-2xl font-extrabold">Username</label>
        <div class="flex items-center gap-4 rounded-xl border-2 border-gray-300 px-5 py-4 focus-within:border-brand-600">
          <span class="text-2xl text-gray-400">👤</span>
          <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full border-none p-0 text-xl font-bold text-gray-800 focus:ring-0" placeholder="Username">
        </div>
        @error('username') <p class="text-red-500 mt-2 text-sm">{{ $message }}</p> @enderror
      </div>
      <div>
        <label class="mb-2 block text-2xl font-extrabold">Password</label>
        <div class="flex items-center gap-4 rounded-xl border-2 border-gray-300 px-5 py-4 focus-within:border-brand-600">
          <span class="text-2xl text-gray-400">🔒</span>
          <input type="password" name="password" class="w-full border-none p-0 text-xl font-bold text-gray-800 focus:ring-0" placeholder="Kosongkan jika tidak ingin mengubah password">
        </div>
        @error('password') <p class="text-red-500 mt-2 text-sm">{{ $message }}</p> @enderror
      </div>
    </div>
    
    <div class="mt-10 flex justify-end">
        <button type="submit" class="rounded-xl bg-brand-600 px-8 py-4 text-xl font-bold text-white shadow hover:bg-brand-700 transition">Save Changes</button>
    </div>
  </form>
</main>
</x-layouts.app>
