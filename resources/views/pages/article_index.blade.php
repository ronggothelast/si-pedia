<x-layouts.app title="Article Data — SI-Pedia">
<main class="mx-auto max-w-[1440px] px-8 py-8">
  <div class="flex items-start justify-between">
    <div><h1 class="text-5xl font-black tracking-tight">Article Data</h1>
      <p class="mt-1 text-gray-700">Manage all Article available on the system.</p></div>
    <a href="{{ route('admin.articles.create') }}" class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow">Add Article</a>
  </div>
  <div class="mt-6 flex h-14 items-center rounded-xl border border-gray-200 px-5 text-gray-400 shadow-sm">🔍 <span class="ml-3">Search Articles by title, category, or author...</span></div>
  <div class="mt-6 grid grid-cols-[60px_140px_1fr_140px_120px_160px_120px_180px] gap-2 rounded-xl bg-tablehead px-4 py-3 text-sm font-bold text-gray-800">
    <div>No</div><div>Thumbnail</div><div>Article Title</div><div>Category</div><div>Writer</div><div>Created Date</div><div>Status</div><div>Action</div>
  </div>
  <div class="mt-3 space-y-3">
    @forelse($articles as $i => $article)
    <div class="grid grid-cols-[60px_140px_1fr_140px_120px_160px_120px_180px] items-center gap-2 rounded-2xl bg-white px-4 py-4 shadow-[0_2px_10px_rgba(0,0,0,0.06)]">
      <div class="text-lg font-bold">{{ $i + 1 + ($articles->currentPage() - 1) * $articles->perPage() }}</div>
      <div>
        @if($article->image)
          <img src="{{ Storage::url($article->image) }}" class="h-[90px] w-[110px] rounded object-cover">
        @else
          <div class="h-[90px] w-[110px] rounded bg-gray-200 flex items-center justify-center text-xs text-gray-500">No Image</div>
        @endif
      </div>
      <div class="pr-4 text-sm font-bold leading-snug text-gray-900">{{ $article->title }}</div>
      <div><span class="rounded-full bg-badge-cat px-4 py-1 text-xs font-semibold text-white">{{ $article->category->name ?? 'Uncategorized' }}</span></div>
      <div class="text-sm font-bold">{{ $article->writer }}</div>
      <div class="text-sm font-bold">{{ \Carbon\Carbon::parse($article->created_at)->translatedFormat('j F Y') }}</div>
      <div><span class="rounded-md {{ $article->status === 'active' ? 'bg-status-active' : 'bg-red-500' }} px-3 py-1 text-xs font-semibold text-white">{{ ucfirst($article->status) }}</span></div>
      <div class="flex gap-2">
        <a href="{{ route('admin.articles.edit', $article) }}" class="rounded-md bg-edit px-4 py-1.5 text-xs font-bold text-black">Edit</a>
        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
            @csrf @method('DELETE')
            <button type="submit" class="rounded-md bg-danger px-4 py-1.5 text-xs font-bold text-white">Delete</button>
        </form>
      </div>
    </div>
    @empty
    <div class="p-8 text-center text-gray-500 bg-white rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.06)]">Tidak ada artikel.</div>
    @endforelse
  </div>
  
  <div class="mt-6">
      {{ $articles->links() }}
  </div>
</main>
</x-layouts.app>
