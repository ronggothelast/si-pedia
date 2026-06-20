<x-layouts.app title="Create Dosen ACC — SI-Pedia">
<main class="mx-auto max-w-[1440px] px-8 py-7">
  <div class="flex items-center gap-4 mb-2">
    <a href="{{ route('admin.dosen.index') }}" class="text-4xl hover:text-brand-600 transition-colors">←</a>
    <h1 class="text-5xl font-black tracking-tight">Create Dosen ACC</h1>
  </div>
  <p class="ml-14 text-gray-700">Verify and Approve New Lecturer Registration.</p>
  <div class="mt-6 grid grid-cols-[1.8fr_1fr] gap-6 rounded-2xl border border-gray-200 p-6 shadow-sm">
    <!-- LEFT -->
    <div>
      <h2 class="mb-4 text-lg font-bold">Lecturer Data</h2>
      <div class="grid grid-cols-2 gap-5">
        <div><label class="mb-1 block text-sm font-bold">Full name</label><div class="flex items-center rounded-xl border border-gray-300 px-4 py-3 text-sm">{{ $lecturer->username }}</div></div><div><label class="mb-1 block text-sm font-bold">NIP/NIK</label><div class="flex items-center rounded-xl border border-gray-300 px-4 py-3 text-sm">1976544789436001</div></div>
        <div><label class="mb-1 block text-sm font-bold">NIDN</label><div class="flex items-center rounded-xl border border-gray-300 px-4 py-3 text-sm">{{ $lecturer->nidn }}</div></div><div><label class="mb-1 block text-sm font-bold">Place of Birth</label><div class="flex items-center rounded-xl border border-gray-300 px-4 py-3 text-sm">Jakarta</div></div>
        <div><label class="mb-1 block text-sm font-bold">Email</label><div class="flex items-center rounded-xl border border-gray-300 px-4 py-3 text-sm">-</div></div><div><label class="mb-1 block text-sm font-bold">Date of Birth</label><div class="flex items-center rounded-xl border border-gray-300 px-4 py-3 text-sm">21/05/1985</div></div>
        <div><label class="mb-1 block text-sm font-bold">No. Telephone</label><div class="flex items-center rounded-xl border border-gray-300 px-4 py-3 text-sm">-</div></div><div><label class="mb-1 block text-sm font-bold">Gender</label><div class="flex items-center rounded-xl border border-gray-300 px-4 py-3 text-sm">Laki -Laki<span class="ml-auto text-gray-400">⌄</span></div></div>
      </div>
      <div class="mt-5"><div><label class="mb-1 block text-sm font-bold">Study program</label><div class="flex items-center rounded-xl border border-gray-300 px-4 py-3 text-sm">Sistem Informasi<span class="ml-auto text-gray-400">⌄</span></div></div></div>
      <div class="mt-5"><div><label class="mb-1 block text-sm font-bold">Address</label><div class="flex items-center rounded-xl border border-gray-300 px-4 py-3 text-sm">{{ $lecturer->address }}</div></div></div>
      <div class="mt-5">
        <label class="mb-1 block text-sm font-bold">Lecturer Photo</label>
        <div class="flex gap-4">
          <div class="grid h-[120px] w-[110px] place-items-center rounded-lg bg-gray-200 text-5xl text-gray-500">👤</div>
          <div class="grid h-[120px] w-[150px] place-items-center rounded-lg border-2 border-gray-200 text-center text-[10px] text-gray-500">⊕<br>Click to change image<br>Format: JPG, PNG, WEBP.<br>Max 10 MB</div>
        </div>
      </div>
    </div>
    <!-- RIGHT -->
    <div class="rounded-xl border border-gray-200">
      <div class="rounded-t-xl bg-tablehead px-5 py-3 text-lg font-bold">Verification Information</div>
      <div class="space-y-4 p-5 text-sm">
        <div><p class="text-gray-600">Submitted By</p><p class="text-base font-bold">Agung Rahardi</p><p class="text-gray-600">agungrahardi@gamil.com</p></div>
        <div><p class="font-semibold">Registration Date</p><p>28 Mei 2026</p></div>
        <div><p class="font-semibold">Current Status</p><p class="mt-1 inline-block bg-yellow-300 px-2 font-bold text-danger">Waiting for Verification</p></div>
        <div><p class="font-semibold">Registrant Notes</p><div class="mt-1 rounded-lg bg-gray-200 p-4 text-center text-gray-700">"Please Verify My Data to Access the SI-Pedia System"</div></div>
        <div><p class="font-semibold">Verification History</p><p class="mt-1 flex gap-2"><span class="text-blue-600">●</span> 28 Mei 2026<br>Registration Made By Lecturer</p></div>
      </div>
    </div>
  </div>
  <div class="mt-5 flex justify-end gap-3">
    <button class="rounded-lg border border-danger px-6 py-2 text-sm font-bold text-danger">Reject</button>
    <button class="rounded-lg bg-brand-600 px-6 py-2 text-sm font-bold text-white">Agree & ACC</button>
  </div>
</main>
</x-layouts.app>
