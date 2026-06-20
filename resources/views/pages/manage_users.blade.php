<x-layouts.app title="Manage Users - PWL Ensiklopedia">
    <main class="mx-auto max-w-[1440px] px-8 py-8">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-5xl font-black tracking-tight">Manage Users</h1>
                <p class="mt-1 text-gray-700">View and manage system users.</p>
            </div>
            <a href="{{ route('admin.panel') }}" class="rounded-lg bg-gray-600 px-5 py-2.5 text-sm font-bold text-white shadow hover:bg-gray-700">Back to Panel</a>
        </div>

        <div class="mt-8">
            <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-tablehead text-gray-800 text-sm">
                            <th class="py-4 px-6 font-bold">Name</th>
                            <th class="py-4 px-6 font-bold">Email</th>
                            <th class="py-4 px-6 font-bold">Role</th>
                            <th class="py-4 px-6 font-bold">Joined At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="font-bold text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-600">{{ $user->email }}</td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-600">{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </main>
</x-layouts.app>
