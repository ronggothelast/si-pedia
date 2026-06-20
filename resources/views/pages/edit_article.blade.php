<x-layouts.app :title="$mode === 'create' ? 'Add Article — SI-Pedia' : 'Edit Article — SI-Pedia'">
<main class="mx-auto max-w-[1440px] px-8 py-7">
  <div class="flex items-start justify-between">
    <div><h1 class="text-5xl font-black tracking-tight">{{ $mode === 'create' ? 'Add Article' : 'Edit Article' }}</h1>
      <p class="mt-1 text-gray-700">{{ $mode === 'create' ? 'Create a new article.' : 'Update Article information in the form below.' }}</p></div>
    <a href="{{ route('admin.articles.index') }}" class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow">Return to List</a>
  </div>
  
  <form action="{{ $mode === 'create' ? route('admin.articles.store') : route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @if($mode === 'edit') @method('PUT') @endif
  <div class="mt-6 grid grid-cols-[1.8fr_1fr] gap-6 rounded-2xl border border-gray-200 p-6 shadow-sm bg-white">
    <!-- form -->
    <div class="space-y-5">
      <div class="">
          <label class="mb-1 block text-lg font-bold">Article Title</label>
          <input type="text" name="title" value="{{ old('title', $article->title) }}" required class="w-full rounded-xl border-2 border-gray-200 px-5 py-3 text-sm font-semibold text-gray-800 focus:border-brand-600 focus:ring-0">
          @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
      </div>
      <div class="grid grid-cols-2 gap-5">
          <div class="">
              <label class="mb-1 block text-lg font-bold">Category</label>
              <select name="category_id" required class="w-full rounded-xl border-2 border-gray-200 px-5 py-3 text-sm font-semibold text-gray-800 focus:border-brand-600 focus:ring-0">
                  <option value="">-- Select Category --</option>
                  @foreach($categories as $cat)
                      <option value="{{ $cat->id }}" @selected(old('category_id', $article->category_id) == $cat->id)>{{ $cat->name }}</option>
                  @endforeach
              </select>
              @error('category_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
          </div>
          <div class="">
              <label class="mb-1 block text-lg font-bold">Writer</label>
              <input type="text" name="writer" value="{{ old('writer', $article->writer ?? auth()->user()->name) }}" required class="w-full rounded-xl border-2 border-gray-200 px-5 py-3 text-sm font-semibold text-gray-800 focus:border-brand-600 focus:ring-0">
              @error('writer') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
          </div>
      </div>
      <div class="grid grid-cols-2 gap-5">
          <div class="">
              <label class="mb-1 block text-lg font-bold">Created Date</label>
              <input type="date" name="created_at" value="{{ old('created_at', $article->created_at ? \Carbon\Carbon::parse($article->created_at)->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full rounded-xl border-2 border-gray-200 px-5 py-3 text-sm font-semibold text-gray-800 focus:border-brand-600 focus:ring-0">
          </div>
          <div class="">
              <label class="mb-1 block text-lg font-bold">Status</label>
              <select name="status" class="w-full rounded-xl border-2 border-gray-200 px-5 py-3 text-sm font-semibold text-gray-800 focus:border-brand-600 focus:ring-0">
                  <option value="active" @selected(old('status', $article->status) === 'active')>Active</option>
                  <option value="draft" @selected(old('status', $article->status) === 'draft')>Draft</option>
              </select>
          </div>
      </div>
      <div>
        <label class="mb-1 block text-lg font-bold">Thumbnail</label>
        <div class="flex gap-4">
          @if($article->image)
              <img src="{{ Storage::url($article->image) }}" class="h-[120px] w-[110px] rounded-lg object-cover">
          @else
              <div class="h-[120px] w-[110px] rounded-lg bg-gray-200 flex items-center justify-center text-xs text-gray-500">No Img</div>
          @endif
          <label class="cursor-pointer grid h-[120px] w-[150px] place-items-center rounded-lg border-2 border-gray-200 text-center text-[10px] text-gray-500 hover:bg-gray-50">
              ⊕<br>Click to change image<br>Format: JPG, PNG, WEBP.<br>Max 10 MB
              <input type="file" name="image" accept="image/*" class="hidden">
          </label>
        </div>
        @error('thumbnail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
      </div>
      <div>
        <label class="mb-1 block text-lg font-bold">Article Contents</label>
        <textarea name="content" rows="6" required class="w-full rounded-xl border-2 border-gray-200 p-4 text-sm font-semibold leading-relaxed text-gray-800 focus:border-brand-600 focus:ring-0">{{ old('content', $article->content) }}</textarea>
        @error('content') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
      </div>
    </div>
    <!-- sidebar -->
    <div class="space-y-5">
      <div class="overflow-hidden rounded-xl border border-gray-200">
        <div class="bg-tablehead px-4 py-2 text-lg font-bold">Publication</div>
        <div class="space-y-3 p-4 text-sm">
          <div class="flex justify-between">
            <span class="font-bold text-gray-500">Status</span><span class="font-bold text-gray-900">{{ ucfirst($article->status ?? 'Draft') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="font-bold text-gray-500">Visibility</span><span class="font-bold text-gray-900">Public</span>
          </div>
          <div class="flex justify-between">
            <span class="font-bold text-gray-500">Views</span><span class="font-bold text-gray-900">{{ $article->views ?? 0 }}</span>
          </div>
        </div>
        <div class="bg-gray-50 p-4 text-right">
          <button type="submit" class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-bold text-white shadow hover:bg-indigo-700">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
  </form>
</main>
</x-layouts.app>