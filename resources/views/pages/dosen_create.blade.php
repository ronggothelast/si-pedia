<x-layouts.app title="{{ isset($lecturer) ? 'Edit Dosen' : 'Create Dosen' }} — SI-Pedia" footer="min">
<main class="mx-auto max-w-[1100px] px-8 py-10">
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.dosen.index') }}" class="text-4xl hover:text-brand-600 transition-colors">←</a>
    <h1 class="text-5xl font-extrabold">{{ isset($lecturer) ? 'Edit Dosen' : 'Create Dosen' }}</h1>
  </div>
  <p class="ml-14 mt-2 text-2xl text-gray-700">Complete the lecturer information to update the system.</p>
  
  <form action="{{ isset($lecturer) ? route('admin.dosen.update', $lecturer) : route('admin.dosen.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($lecturer)) @method('PUT') @endif
    <div class="mt-8 rounded-3xl border border-gray-200 p-12 shadow-sm bg-white">
      <h2 class="text-3xl font-extrabold mb-5">Profile Picture</h2>
      <div class="flex items-end gap-8">
        @if(isset($lecturer) && $lecturer->photo)
            <img src="{{ Storage::url($lecturer->photo) }}" class="h-40 w-40 rounded-full object-cover shadow-sm">
        @else
            <div class="grid h-40 w-40 place-items-center rounded-full bg-gray-200 text-5xl">📷</div>
        @endif
        <label class="cursor-pointer rounded-xl bg-brand-600 px-10 py-4 text-2xl font-bold text-white shadow hover:bg-brand-700 transition">
            Select Photo
            <input type="file" name="photo" accept="image/*" class="hidden">
        </label>
      </div>
      @error('photo') <span class="text-red-500 font-bold mt-2 block">{{ $message }}</span> @enderror
      
      <hr class="my-10 border-gray-200">
      
      <div class="space-y-10">
        <div>
          <label class="mb-3 block text-3xl font-extrabold">NIDN</label>
          <div class="flex items-center gap-5 rounded-2xl border-2 focus-within:border-brand-600 border-gray-300 px-7 py-4 bg-white transition">
            <span class="text-3xl text-gray-400">⠿</span>
            <input type="text" name="nidn" value="{{ old('nidn', $lecturer->nidn ?? '') }}" required placeholder="Masukan NIDN" class="w-full text-2xl font-bold text-gray-800 bg-transparent border-none focus:ring-0 p-0">
          </div>
          @error('nidn') <span class="text-red-500 font-bold mt-2 block">{{ $message }}</span> @enderror
        </div>
        
        <div>
          <label class="mb-3 block text-3xl font-extrabold">Username</label>
          <div class="flex items-center gap-5 rounded-2xl border-2 focus-within:border-brand-600 border-gray-300 px-7 py-4 bg-white transition">
            <span class="text-3xl text-gray-400">👤</span>
            <input type="text" name="username" value="{{ old('username', $lecturer->username ?? '') }}" required placeholder="Masukan username" class="w-full text-2xl font-bold text-gray-800 bg-transparent border-none focus:ring-0 p-0">
          </div>
          @error('username') <span class="text-red-500 font-bold mt-2 block">{{ $message }}</span> @enderror
        </div>
        
        <div>
          <label class="mb-3 block text-3xl font-extrabold">Address</label>
          <div class="flex items-center gap-5 rounded-2xl border-2 focus-within:border-brand-600 border-gray-300 px-7 py-4 bg-white transition">
            <span class="text-3xl text-gray-400">📍</span>
            <input type="text" name="address" value="{{ old('address', $lecturer->address ?? '') }}" required placeholder="Masukan alamat lengkap" class="w-full text-2xl font-bold text-gray-800 bg-transparent border-none focus:ring-0 p-0">
          </div>
          @error('address') <span class="text-red-500 font-bold mt-2 block">{{ $message }}</span> @enderror
        </div>
      </div>
      
      <div class="mt-12 flex items-center justify-between">
        <a href="{{ route('admin.dosen.index') }}" class="rounded-2xl bg-gray-200 px-16 py-5 text-2xl font-bold text-gray-700 hover:bg-gray-300 transition">Cancel</a>
        <button type="submit" class="rounded-2xl bg-brand-600 px-14 py-5 text-2xl font-bold text-white shadow hover:bg-brand-700 transition">Save Changes</button>
      </div>
    </div>
  </form>
</main>
</x-layouts.app>
