<x-layouts.app title="Dosen — SI-Pedia" footer="min">
<main class="mx-auto max-w-[1320px] px-8 py-10">
  <div class="flex items-start justify-between">
    <div><h1 class="text-5xl font-black tracking-tight">Dosen</h1>
      <p class="mt-2 text-xl text-gray-700">Manage lecturer data registered in the system.</p></div>
    <a href="{{ route('admin.dosen.create') }}" class="flex items-center gap-3 rounded-2xl border border-gray-300 px-7 py-5 text-xl font-medium shadow-sm hover:bg-gray-50 transition-colors">
      <span class="grid h-9 w-9 place-items-center rounded-full bg-black text-2xl text-white">+</span> Add Lecturer</a>
  </div>
  <div class="mt-8 flex items-center gap-6">
    <form action="{{ route('admin.dosen.index') }}" method="GET" class="flex flex-1 items-center gap-3 rounded-2xl border border-gray-300 px-6 bg-white shadow-sm focus-within:border-brand-600 transition">
        <span class="text-xl">🔍</span>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari dosen by NIDN, username, atau alamat..." class="w-full py-4 text-xl text-gray-800 bg-transparent border-none focus:ring-0">
        <button type="submit" class="hidden"></button>
    </form>
    <div class="flex w-72 items-center justify-between rounded-2xl border border-gray-300 px-6 py-4 text-xl shadow-sm">All <span>⌄</span></div>
  </div>
  <div class="mt-8 overflow-hidden rounded-2xl border border-gray-300 shadow-sm">
    <div class="grid grid-cols-[70px_90px_1fr_1fr_1fr_1.6fr] gap-2 border-b border-gray-300 px-6 py-5 text-xl font-bold italic">
      <div>No</div><div>Photo</div><div>NIDN</div><div>Username</div><div>Address</div><div class="text-left">Action</div>
    </div>
    @forelse($lecturers as $i => $lecturer)
    <div class="grid grid-cols-[70px_90px_1fr_1fr_1fr_1.6fr] items-center gap-2 px-6 py-7 border-b border-gray-300">
      <div class="text-lg">{{ $i + 1 + ($lecturers->currentPage() - 1) * $lecturers->perPage() }}.</div>
      <div>
        @if($lecturer->photo)
            <img src="{{ Storage::url($lecturer->photo) }}" class="h-12 w-12 rounded-full object-cover shadow-sm">
        @else
            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-xl text-gray-500">👤</div>
        @endif
      </div>
      <div class="text-lg">{{ $lecturer->nidn }}</div><div class="text-lg font-medium">{{ $lecturer->username }}</div><div class="text-lg">{{ $lecturer->address }}</div>
      <div class="flex items-center gap-3">
        <a href="{{ route('admin.dosen.edit', $lecturer) }}" class="flex items-center gap-2 rounded-lg border border-gray-800 px-5 py-2 font-medium hover:bg-gray-100 transition-colors">✎ Edit</a>
        <form action="{{ route('admin.dosen.destroy', $lecturer) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus dosen ini?');">
            @csrf @method('DELETE')
            <button type="submit" class="flex items-center gap-2 rounded-lg border border-danger px-5 py-2 font-medium text-danger hover:bg-red-50 transition-colors">🗑 Delete</button>
        </form>
      </div>
    </div>
    @empty
    <div class="px-6 py-10 text-center text-lg text-gray-500">Tidak ada data dosen.</div>
    @endforelse
  </div>
  
  <div class="mt-6 flex justify-between items-center text-xl text-gray-800">
      <div>Display {{ $lecturers->firstItem() ?? 0 }} to {{ $lecturers->lastItem() ?? 0 }} of {{ $lecturers->total() }} data</div>
      <div>{{ $lecturers->links() }}</div>
  </div>
</main>
</x-layouts.app>
