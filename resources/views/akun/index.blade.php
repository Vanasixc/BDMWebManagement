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
@endsection

{{-- ============================================================
     ACCOUNT MODAL — dipush ke body level agar position:fixed
     tidak terganggu oleh CSS transform dari animate-fade-in-up
     ============================================================ --}}
@push('modals')
<div
    id="account-modal-overlay"
    onclick="closeAccountModal(event)"
    style="
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        padding: 1rem;
        overflow-y: auto;
    "
>
    {{-- Modal Box : gunakan CSS yang sama dengan #modal-box global --}}
    <div
        onclick="event.stopPropagation()"
        style="
            position: relative;
            width: 100%;
            max-width: 30rem;
            max-height: calc(100dvh - 2rem);
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            background: #ffffff;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: scaleIn 0.22s ease-out both;
        "
    >
        {{-- Header — gunakan .modal-hdr dari app.css --}}
        <div class="modal-hdr">
            <h3 id="account-modal-title">Tambah Akun</h3>
            <button onclick="closeAccountModal()" class="modal-close-btn" aria-label="Tutup">
                @include('components.icon', ['name' => 'x', 'class' => 'w-5 h-5'])
            </button>
        </div>

        {{-- Form: body + footer di dalam form agar submit bekerja --}}
        <form id="account-modal-form" method="POST" style="display:flex; flex-direction:column; flex:1; min-height:0;">
            @csrf
            <input type="hidden" name="_method" id="account-method" value="POST"/>

            {{-- Body — sama dengan #modal-body dari app.css --}}
            <div style="padding: 1.5rem 2rem; overflow-y: auto; flex: 1; min-height: 0;">
                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="block text-[10px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama / Username</label>
                        <input type="text" name="name" id="account-name" required
                            class="w-full px-3 py-2 border rounded-lg text-sm outline-none transition
                                   bg-white border-gray-300 text-slate-900
                                   dark:bg-slate-700 dark:border-slate-600 dark:text-white
                                   focus:ring-2 focus:ring-blue-500"/>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Email</label>
                        <input type="email" name="email" id="account-email" required
                            class="w-full px-3 py-2 border rounded-lg text-sm outline-none transition
                                   bg-white border-gray-300 text-slate-900
                                   dark:bg-slate-700 dark:border-slate-600 dark:text-white
                                   focus:ring-2 focus:ring-blue-500"/>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Role Akses</label>
                        <select name="role" id="account-role" required
                            class="w-full px-3 py-2 border rounded-lg text-sm outline-none transition
                                   bg-white border-gray-300 text-slate-900
                                   dark:bg-slate-700 dark:border-slate-600 dark:text-white
                                   focus:ring-2 focus:ring-blue-500">
                            <option value="admin">admin</option>
                            <option value="superAdmin">superAdmin</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Password
                            <span id="pw-hint" class="normal-case font-normal text-slate-400">(kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" name="password" id="account-password" autocomplete="new-password"
                            class="w-full px-3 py-2 border rounded-lg text-sm outline-none transition
                                   bg-white border-gray-300 text-slate-900
                                   dark:bg-slate-700 dark:border-slate-600 dark:text-white
                                   focus:ring-2 focus:ring-blue-500"/>
                    </div>
                    <div id="pw-confirm-wrap" class="space-y-1">
                        <label class="block text-[10px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" autocomplete="new-password"
                            class="w-full px-3 py-2 border rounded-lg text-sm outline-none transition
                                   bg-white border-gray-300 text-slate-900
                                   dark:bg-slate-700 dark:border-slate-600 dark:text-white
                                   focus:ring-2 focus:ring-blue-500"/>
                    </div>
                </div>
            </div>

            {{-- Footer — gunakan .modal-ftr dari app.css --}}
            <div class="modal-ftr">
                <button type="button" onclick="closeAccountModal()" class="modal-btn-cancel">BATAL</button>
                <button type="submit" class="modal-btn-save">SIMPAN PERUBAHAN</button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
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
    document.body.style.overflow = 'hidden';
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
    document.body.style.overflow = 'hidden';
}
function closeAccountModal(e) {
    if (e && e.target !== document.getElementById('account-modal-overlay')) return;
    document.getElementById('account-modal-overlay').style.display = 'none';
    document.body.style.overflow = '';
}
</script>
@endpush
