/**
 * WH Manager — app.js
 * Handles: dark mode, sidebar toggle/resize, modal CRUD, delete confirm, Chart.js init
 */

import './bootstrap';

// =============================================
// DARK MODE
// =============================================
window.toggleDark = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('darkMode', isDark ? '1' : '0');
    document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
};

// Apply on load — harus sebelum render untuk hindari flash
(function () {
    const isDark = localStorage.getItem('darkMode') === '1';
    if (isDark) {
        document.documentElement.classList.add('dark');
        document.documentElement.style.colorScheme = 'dark';
    } else {
        document.documentElement.classList.remove('dark');
        document.documentElement.style.colorScheme = 'light';
    }
})();

// =============================================
// SIDEBAR — TOGGLE & MOBILE
// =============================================
let sidebarOpen = window.innerWidth >= 768; // Default open on desktop

window.toggleSidebar = function () {
    sidebarOpen = !sidebarOpen;
    applySidebarState();
};

window.closeSidebar = function () {
    sidebarOpen = false;
    applySidebarState();
};

function applySidebarState() {
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebar-overlay');
    const iconOpen  = document.getElementById('hamburger-icon-open');
    const iconClose = document.getElementById('hamburger-icon-close');

    if (!sidebar) return;

    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        // Gunakan classList — hindari konflik dengan Tailwind v4 native CSS translate property
        if (sidebarOpen) {
            sidebar.classList.add('sidebar-open');
        } else {
            sidebar.classList.remove('sidebar-open');
        }

        // Overlay: gunakan style langsung agar tidak konflik dengan Tailwind
        if (overlay) {
            overlay.style.display       = sidebarOpen ? 'block' : 'none';
            overlay.style.pointerEvents = sidebarOpen ? 'auto' : 'none';
        }
    } else {
        // Desktop: kontrol via width
        sidebar.classList.remove('sidebar-open'); // bersihkan class mobile
        sidebar.style.width    = sidebarOpen ? (currentSidebarWidth + 'px') : '0px';
        sidebar.style.overflow = sidebarOpen ? '' : 'hidden';
        if (overlay) {
            overlay.style.display       = 'none';
            overlay.style.pointerEvents = 'none';
        }
    }

    if (iconOpen)  iconOpen.style.display  = sidebarOpen ? 'none' : 'block';
    if (iconClose) iconClose.style.display = sidebarOpen ? 'block' : 'none';
}

// =============================================
// SIDEBAR — RESIZABLE (desktop only)
// =============================================
let currentSidebarWidth = parseInt(localStorage.getItem('sidebarWidth') || '256');
let isResizing = false;
let startX = 0;
let startWidth = 0;

document.addEventListener('DOMContentLoaded', () => {
    const sidebar  = document.getElementById('sidebar');
    const resizer  = document.getElementById('sidebar-resizer');

    if (sidebar && window.innerWidth >= 768) {
        sidebar.style.width = currentSidebarWidth + 'px';
    }

    if (resizer) {
        resizer.addEventListener('mousedown', (e) => {
            isResizing = true;
            startX = e.clientX;
            startWidth = parseInt(sidebar.style.width || currentSidebarWidth);
            resizer.classList.add('is-resizing');
            document.body.style.userSelect = 'none';
        });
    }

    document.addEventListener('mousemove', (e) => {
        if (!isResizing || window.innerWidth < 768) return;
        const sidebar = document.getElementById('sidebar');
        let newWidth = startWidth + (e.clientX - startX);
        newWidth = Math.max(200, Math.min(480, newWidth));
        sidebar.style.width = newWidth + 'px';
        currentSidebarWidth = newWidth;
    });

    document.addEventListener('mouseup', () => {
        if (isResizing) {
            isResizing = false;
            const resizer = document.getElementById('sidebar-resizer');
            if (resizer) resizer.classList.remove('is-resizing');
            document.body.style.userSelect = '';
            localStorage.setItem('sidebarWidth', currentSidebarWidth);
        }
    });

    // Responsive: if resized to mobile, reset sidebar
    window.addEventListener('resize', () => {
        const sidebar = document.getElementById('sidebar');
        if (!sidebar) return;
        if (window.innerWidth < 768) {
            sidebar.style.width = '256px';
            sidebarOpen = false;
            applySidebarState();
        } else {
            sidebar.style.width = currentSidebarWidth + 'px';
            sidebarOpen = true;
            applySidebarState();
        }
    });

    // Initial state
    applySidebarState();

    // Auto-dismiss flash messages setelah 4 detik
    setTimeout(function () {
        ['flash-success', 'flash-error'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity   = '0';
                setTimeout(() => el.remove(), 500);
            }
        });
    }, 4000);
});

// =============================================
// DELETE CONFIRM — SweetAlert2
// =============================================
window.confirmDelete = function (e, form) {
    e.preventDefault();

    const isDark = document.documentElement.classList.contains('dark');

    Swal.fire({
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#475569',
        confirmButtonText: '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Ya, Hapus!',
        cancelButtonText: 'Batal',
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f1f5f9' : '#0f172a',
        customClass: {
            popup: 'rounded-2xl shadow-2xl border ' + (isDark ? 'border-slate-700' : 'border-gray-100'),
            title: 'font-bold text-base',
            htmlContainer: 'text-sm',
            confirmButton: 'rounded-xl font-bold text-sm px-5 py-2.5',
            cancelButton: 'rounded-xl font-bold text-sm px-5 py-2.5',
        },
        buttonsStyling: true,
        reverseButtons: true,
    }).then(result => {
        if (result.isConfirmed) form.submit();
    });

    return false;
};

// =============================================
// MODAL — CRUD
// =============================================
let currentModalMode = 'view'; // 'view' | 'edit' | 'add' | 'editTable'
let currentWebsiteId = null;
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

window.openModal = function () {
    document.getElementById('modal-overlay').classList.add('active');
    document.body.style.overflow = 'hidden'; // Cegah body scroll saat modal terbuka
};

window.closeModal = function () {
    document.getElementById('modal-overlay').classList.remove('active');
    document.body.style.overflow = '';
};

window.closeModalOnOverlay = function (e) {
    if (e.target === document.getElementById('modal-overlay')) closeModal();
};

window.openModalView = function (id) {
    currentModalMode = 'view';
    currentWebsiteId = id;
    document.getElementById('modal-title').textContent = 'Detail Data Website';
    document.getElementById('modal-save-btn').style.display = 'none';
    fetchAndRenderForm(id, true);
};

window.openModalEdit = function (id) {
    currentModalMode = 'edit';
    currentWebsiteId = id;
    document.getElementById('modal-title').textContent = 'Edit Data Website';
    document.getElementById('modal-save-btn').style.display = '';
    fetchAndRenderForm(id, false);
};

window.openModalAdd = function () {
    currentModalMode = 'add';
    currentWebsiteId = null;
    document.getElementById('modal-title').textContent = 'Tambah Data Website Baru';
    document.getElementById('modal-save-btn').style.display = '';
    renderForm(null, false);
    openModal();
};

window.openModalEditTable = function () {
    currentModalMode = 'editTable';
    document.getElementById('modal-title').textContent = 'Pengaturan Opsi Dropdown';
    document.getElementById('modal-save-btn').style.display = 'none';
    renderEditTableForm();
    openModal();
};

window.submitModalForm = function () {
    const form = document.getElementById('modal-dynamic-form');
    if (form) form.submit();
};

// Fetch website data via AJAX then render form
function fetchAndRenderForm(id, readonly) {
    fetch(`/websites/${id}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
        .then(r => r.json())
        .then(data => {
            renderForm(data, readonly);
            openModal();
        })
        .catch(() => alert('Gagal memuat data.'));
}

// =============================================
// FORM BUILDER — renders form inside modal body
// =============================================
function renderForm(data, readonly) {
    const section = window.WHSection || 'master';
    const dd = window.WHDropdowns || {};
    const isEdit = data && data.id;
    const action = isEdit ? `/websites/${data.id}` : '/websites';
    const method = isEdit ? 'PUT' : 'POST';

    let html = `<form id="modal-dynamic-form" method="POST" action="${action}">
        <input type="hidden" name="_token" value="${CSRF}"/>
        ${isEdit ? '<input type="hidden" name="_method" value="PUT"/>' : ''}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">`;

    const v = data || {};

    function inp(label, name, value, type = 'text') {
        const val = (value !== null && value !== undefined) ? value : '';
        const ro = readonly ? 'disabled' : '';
        return `<div class="space-y-1">
            <label class="block text-[10px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">${label}</label>
            <input type="${type}" name="${name}" value="${String(val).replace(/"/g,'&quot;')}" ${ro}
                class="w-full px-3 py-2 border rounded-lg text-sm outline-none transition
                       bg-white border-gray-300 text-slate-900
                       dark:bg-slate-700 dark:border-slate-600 dark:text-white
                       disabled:bg-slate-50 dark:disabled:bg-slate-800
                       focus:ring-2 focus:ring-blue-500"/>
        </div>`;
    }

    function sel(label, name, value, options) {
        const ro = readonly ? 'disabled' : '';
        const opts = options.map(o => `<option ${o === value ? 'selected' : ''}>${o}</option>`).join('');
        return `<div class="space-y-1">
            <label class="block text-[10px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">${label}</label>
            <select name="${name}" ${ro}
                class="w-full px-3 py-2 border rounded-lg text-sm outline-none transition
                       bg-white border-gray-300 text-slate-900
                       dark:bg-slate-700 dark:border-slate-600 dark:text-white
                       disabled:bg-slate-50 dark:disabled:bg-slate-800
                       focus:ring-2 focus:ring-blue-500">
                ${opts}
            </select>
        </div>`;
    }

    function textarea(label, name, value, span = false) {
        const ro = readonly ? 'disabled' : '';
        const colSpan = span ? 'col-span-1 md:col-span-2' : '';
        const val = (value !== null && value !== undefined) ? value : '';
        return `<div class="space-y-1 ${colSpan}">
            <label class="block text-[10px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">${label}</label>
            <textarea name="${name}" rows="3" ${ro}
                class="w-full px-3 py-2 border rounded-lg text-sm outline-none resize-none transition
                       bg-white border-gray-300 text-slate-900
                       dark:bg-slate-700 dark:border-slate-600 dark:text-white
                       disabled:bg-slate-50 dark:disabled:bg-slate-800
                       focus:ring-2 focus:ring-blue-500">${val}</textarea>
        </div>`;
    }

    // Build fields based on section
    switch (section) {
        case 'master': {
            const types = dd['type'] || ['Profile', 'Blog', 'Berita'];
            const techs = dd['technology'] || ['WordPress', 'Laravel'];
            const statuses = dd['status'] || ['Aktif', 'InActive', 'Suspend'];
            const pics = dd['internalPic'] || ['Iqbal'];
            html += inp('Nama Client', 'client', v.client);
            html += inp('PIC', 'pic', v.pic);
            html += inp('Nama Website', 'website', v.website);
            html += inp('URL Website', 'url', v.url);
            html += sel('Jenis Website', 'type', v.type, types);
            html += sel('CMS/Teknologi', 'technology', v.technology, techs);
            html += sel('Status', 'status', v.status, statuses);
            html += sel('PIC Internal', 'internal_pic', v.internal_pic, pics);
            html += inp('Service Package', 'service_package', v.service_package);
            html += inp('Tahun Pembuatan', 'created_year', v.created_year ? v.created_year.substring(0,10) : '', 'date');
            html += inp('Telepon', 'phone', v.phone);
            html += inp('Email', 'email', v.email, 'email');
            html = html + `</div>`;
            html += `<div class="grid grid-cols-1 gap-4 mt-4">`;
            html += textarea('Catatan', 'note', v.note, true);
            break;
        }
        case 'domain': {
            html += inp('Domain URL', 'url', v.url);
            html += inp('Provider Domain', 'domain_provider', v.domain_provider);
            html += inp('Email Akun Domain', 'domain_email', v.domain_email, 'email');
            html += inp('Harga Domain / Tahun', 'domain_price', v.domain_price, 'number');
            html += inp('Tanggal Registrasi', 'domain_reg_date', v.domain_reg_date ? v.domain_reg_date.substring(0,10) : '', 'date');
            html += inp('Tanggal Expired', 'domain_exp_date', v.domain_exp_date ? v.domain_exp_date.substring(0,10) : '', 'date');
            break;
        }
        case 'hosting': {
            const hTypes = dd['hostingType'] || ['Dedicated Server', 'Shared', 'Redirect'];
            html += sel('Jenis Hosting', 'hosting_type', v.hosting_type, hTypes);
            html += inp('Provider Hosting', 'hosting_provider', v.hosting_provider);
            html += inp('Kapasitas Storage (GB)', 'storage', v.storage, 'number');
            html += inp('IP Server', 'ip_server', v.ip_server);
            html += inp('Lokasi Server', 'location', v.location);
            html += inp('Email Hosting', 'hosting_email', v.hosting_email, 'email');
            html += inp('Harga Hosting / Tahun', 'hosting_price', v.hosting_price, 'number');
            html += inp('Tanggal Expired', 'hosting_exp_date', v.hosting_exp_date ? v.hosting_exp_date.substring(0,10) : '', 'date');
            break;
        }
        case 'akses': {
            html += inp('URL Admin', 'admin_url', v.admin_url);
            html += inp('Akses Tambahan', 'extra_access', v.extra_access);
            html += inp('Lokasi Simpan Password', 'password_loc', v.password_loc);
            html = html + `</div><div class="grid grid-cols-1 gap-4 mt-4">`;
            html += textarea('Catatan Akses', 'note', v.note, true);
            break;
        }
        case 'finansial': {
            const paySystems = dd['paySystem'] || ['Tahunan', 'Bulanan'];
            const payStatuses = dd['payStatus'] || ['Lunas', 'Belum'];
            html += inp('Harga Jual / Tahun', 'sell_price', v.sell_price, 'number');
            html += sel('Sistem Pembayaran', 'pay_system', v.pay_system, paySystems);
            html += sel('Status Pembayaran', 'pay_status', v.pay_status, payStatuses);
            html += inp('Tanggal Invoice', 'invoice_date', v.invoice_date ? v.invoice_date.substring(0,10) : '', 'date');
            break;
        }
        case 'reminder': {
            html += inp('Website', 'website', v.website);
            html = html + `</div><div class="grid grid-cols-1 gap-4 mt-4">`;
            html += textarea('Catatan Reminder', 'note', v.note, true);
            break;
        }
    }

    html += `</div></form>`;
    document.getElementById('modal-body').innerHTML = html;
}

// =============================================
// EDIT TABLE — Dropdown Config Manager
// =============================================
function renderEditTableForm() {
    const section = window.WHSection || 'master';
    const dd = window.WHDropdowns || {};

    if (Object.keys(dd).length === 0) {
        document.getElementById('modal-body').innerHTML =
            `<p class="text-sm text-slate-400 dark:text-slate-500">Tidak ada konfigurasi dropdown untuk halaman ini.</p>`;
        return;
    }

    let html = `<p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
        Kelola pilihan dropdown yang tampil pada form tambah/edit data.
    </p>`;

    // We iterate based on dropdown keys available
    const labelMap = {
        type: 'Jenis Website', technology: 'CMS/Teknologi', status: 'Status', internalPic: 'PIC Internal',
        hostingType: 'Jenis Hosting', paySystem: 'Sistem Pembayaran', payStatus: 'Status Pembayaran',
        reminderStatus: 'Status Reminder',
    };

    for (const [key, opts] of Object.entries(dd)) {
        html += `<div class="p-4 border rounded-xl mb-4 border-gray-200 dark:border-slate-700 bg-gray-50/60 dark:bg-slate-900/50">
            <h4 class="font-bold text-sm mb-3 text-slate-800 dark:text-slate-200">${labelMap[key] || key}</h4>
            <div class="flex flex-wrap gap-2 mb-3" id="opts-${key}">
                ${opts.map(opt => `
                    <span class="px-3 py-1 rounded-full text-xs border flex items-center gap-2
                                border-gray-300 bg-white text-slate-700
                                dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        ${opt}
                        <button type="button"
                            onclick="removeDropdownOption('${section}','${key}','${opt.replace(/'/g,"\\\'")}')"
                            class="text-rose-500 hover:text-rose-700 transition font-bold leading-none">×</button>
                    </span>
                `).join('')}
            </div>
            <button
                type="button"
                onclick="addDropdownOption('${section}','${key}')"
                class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs flex items-center gap-1.5 hover:bg-blue-700 transition">
                + Tambah Opsi
            </button>
        </div>`;
    }

    document.getElementById('modal-body').innerHTML = html;
}

window.addDropdownOption = function (page, key) {
    const isDark = document.documentElement.classList.contains('dark');
    Swal.fire({
        title: 'Tambah Opsi Dropdown',
        input: 'text',
        inputLabel: 'Masukkan opsi baru',
        inputPlaceholder: 'Contoh: WordPress, Laravel...',
        showCancelButton: true,
        confirmButtonText: 'Tambahkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#475569',
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f1f5f9' : '#0f172a',
        customClass: {
            popup: 'rounded-2xl shadow-2xl border ' + (isDark ? 'border-slate-700' : 'border-gray-100'),
            confirmButton: 'rounded-xl font-bold text-sm px-5 py-2.5',
            cancelButton: 'rounded-xl font-bold text-sm px-5 py-2.5',
            input: 'rounded-lg border text-sm px-3 py-2 w-full mt-1 ' + (isDark ? 'bg-slate-700 border-slate-600 text-white' : 'bg-white border-gray-300 text-slate-900'),
        },
        inputValidator: (value) => {
            if (!value || value.trim() === '') return 'Opsi tidak boleh kosong!';
        },
        reverseButtons: true,
        // Pastikan SweetAlert tampil di atas modal (z-index: 9999)
        didOpen: () => {
            const swalContainer = document.querySelector('.swal2-container');
            if (swalContainer) swalContainer.style.zIndex = '99999';
        },
    }).then(result => {
        if (!result.isConfirmed || !result.value) return;

        fetch('/dropdown/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ page, key, option: result.value.trim() })
        })
        .then(r => r.json().catch(() => ({})))
        .then(() => location.reload())
        .catch(() => Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menambahkan opsi.' }));
    });
};

window.removeDropdownOption = function (page, key, option) {
    const isDark = document.documentElement.classList.contains('dark');
    Swal.fire({
        title: `Hapus opsi "${option}"?`,
        text: 'Opsi ini akan dihapus dari daftar pilihan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#475569',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f1f5f9' : '#0f172a',
        customClass: {
            popup: 'rounded-2xl shadow-2xl border ' + (isDark ? 'border-slate-700' : 'border-gray-100'),
            confirmButton: 'rounded-xl font-bold text-sm px-5 py-2.5',
            cancelButton: 'rounded-xl font-bold text-sm px-5 py-2.5',
        },
        reverseButtons: true,
        // Pastikan SweetAlert tampil di atas modal (z-index: 9999)
        didOpen: () => {
            const swalContainer = document.querySelector('.swal2-container');
            if (swalContainer) swalContainer.style.zIndex = '99999';
        },
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch('/dropdown/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ page, key, option })
        })
        .then(() => location.reload())
        .catch(() => Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menghapus opsi.' }));
    });
};
