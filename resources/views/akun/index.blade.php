@extends('layouts.app')

@section('title', 'Manajemen Akun')
@section('page_title', 'Manajemen Akun')
@section('page_subtitle', 'Kelola akun pengguna yang terdaftar di sistem.')

@section('content')
<div class="rounded-xl shadow-sm border bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">

    {{-- Header --}}
    <div class="p-5 md:p-6 border-b border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h3 class="font-bold text-base">Akun Terdaftar</h3>
        <button
            onclick="openAddAccountModal()"
            class="w-full sm:w-auto justify-center bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 hover:bg-blue-700 transition shadow-sm"
        >
            @include('components.icon', ['name' => 'plus', 'class' => 'w-4 h-4'])
            Tambah Akun
        </button>
    </div>

    {{-- Account List --}}
    <div class="p-5 md:p-6 space-y-4">
        @foreach ($users as $user)
        <div class="p-4 border rounded-xl flex items-center justify-between transition
                    border-gray-100 bg-gray-50/50 hover:bg-gray-50
                    dark:border-slate-700 dark:bg-slate-900/50 dark:hover:bg-slate-700/50">
            <div class="flex items-center gap-4">
                <img
                    src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->getLabel()).'&background=3B82F6&color=fff' }}"
                    alt="{{ $user->getLabel() }}"
                    class="w-10 h-10 rounded-full border-2 border-blue-500/30 object-cover"
                />
                <div>
                    <p class="font-bold text-sm md:text-base">{{ $user->getLabel() }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-mono text-[10px] tracking-wide px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700">&#64;{{ $user->name }}</span>
                        &bull;
                        <span class="font-semibold uppercase tracking-wider
                            {{ $user->role === 'superAdmin' ? 'text-blue-600 dark:text-blue-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                            {{ $user->role }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <button
                    onclick='openEditAccountModal({{ json_encode(["id" => $user->id, "name" => $user->name, "email" => $user->email, "role" => $user->role]) }})'
                    class="p-2 text-blue-500 hover:bg-blue-500/10 rounded-lg transition"
                    title="Edit"
                >
                    @include('components.icon', ['name' => 'edit', 'class' => 'w-4 h-4'])
                </button>
                @if ($user->role !== 'superAdmin')
                <form method="POST" action="{{ route('akun.destroy', $user->id) }}" onsubmit="return confirmDelete(event, this)">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="Hapus">
                        @include('components.icon', ['name' => 'trash', 'class' => 'w-4 h-4'])
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Account Modal --}}
<div
    id="account-modal-overlay"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] items-center justify-center p-4"
    style="display:none"
    onclick="closeAccountModal(event)"
>
    <div class="w-full max-w-md rounded-2xl shadow-2xl overflow-hidden bg-white dark:bg-slate-800"
         onclick="event.stopPropagation()">

        <div class="p-5 border-b flex justify-between items-center bg-gray-50 dark:bg-slate-900 border-gray-100 dark:border-slate-700">
            <h3 id="account-modal-title" class="text-base font-bold">Tambah Akun</h3>
            <button onclick="closeAccountModal()" class="p-2 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                @include('components.icon', ['name' => 'x', 'class' => 'w-5 h-5'])
            </button>
        </div>

        <form id="account-modal-form" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="account-method" value="POST"/>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-slate-500 dark:text-slate-400">Nama / Username</label>
                <input type="text" name="name" id="account-name" required
                    class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none transition
                           border-gray-300 bg-white dark:bg-slate-700 dark:border-slate-600 dark:text-white
                           focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-slate-500 dark:text-slate-400">Email</label>
                <input type="email" name="email" id="account-email" required
                    class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none transition
                           border-gray-300 bg-white dark:bg-slate-700 dark:border-slate-600 dark:text-white
                           focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-slate-500 dark:text-slate-400">Role Akses</label>
                <select name="role" id="account-role" required
                    class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none transition
                           border-gray-300 bg-white dark:bg-slate-700 dark:border-slate-600 dark:text-white
                           focus:ring-2 focus:ring-blue-500">
                    <option value="admin">admin</option>
                    <option value="superAdmin">superAdmin</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-slate-500 dark:text-slate-400">Password <span id="pw-hint" class="normal-case font-normal text-slate-400">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" id="account-password" autocomplete="new-password"
                    class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none transition
                           border-gray-300 bg-white dark:bg-slate-700 dark:border-slate-600 dark:text-white
                           focus:ring-2 focus:ring-blue-500"/>
            </div>
            <div id="pw-confirm-wrap">
                <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5 text-slate-500 dark:text-slate-400">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" autocomplete="new-password"
                    class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none transition
                           border-gray-300 bg-white dark:bg-slate-700 dark:border-slate-600 dark:text-white
                           focus:ring-2 focus:ring-blue-500"/>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeAccountModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700 transition">
                    BATAL
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition">
                    SIMPAN
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddAccountModal() {
    document.getElementById('account-modal-title').textContent = 'Tambah Akun Baru';
    document.getElementById('account-modal-form').action = '{{ route("akun.store") }}';
    document.getElementById('account-method').value = 'POST';
    document.getElementById('account-name').value = '';
    document.getElementById('account-email').value = '';
    document.getElementById('account-role').value = 'admin';
    document.getElementById('account-password').value = '';
    document.getElementById('pw-hint').style.display = 'none';
    document.getElementById('account-modal-overlay').style.display = 'flex';
}
function openEditAccountModal(user) {
    document.getElementById('account-modal-title').textContent = 'Edit Akun';
    document.getElementById('account-modal-form').action = `/akun/${user.id}`;
    document.getElementById('account-method').value = 'PUT';
    document.getElementById('account-name').value = user.name;
    document.getElementById('account-email').value = user.email;
    document.getElementById('account-role').value = user.role;
    document.getElementById('account-password').value = '';
    document.getElementById('pw-hint').style.display = '';
    document.getElementById('account-modal-overlay').style.display = 'flex';
}
function closeAccountModal(e) {
    if (e && e.target !== document.getElementById('account-modal-overlay')) return;
    document.getElementById('account-modal-overlay').style.display = 'none';
}
</script>
@endsection
