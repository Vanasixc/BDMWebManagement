// sweetAlerts.js — SweetAlert2 interactions.

import { CSRF } from "./state.js";

// Helper: SweetAlert theme based on current dark mode

function swalTheme() {
    const isDark = document.documentElement.classList.contains("dark");
    return {
        background: isDark ? "#1e293b" : "#ffffff",
        color: isDark ? "#f1f5f9" : "#0f172a",
        popupClass:
            "rounded-2xl shadow-2xl border " +
            (isDark ? "border-slate-700" : "border-gray-100"),
        btnClass: "rounded-xl font-bold text-sm px-5 py-2.5",
        inputClass: isDark
            ? "bg-slate-700 border-slate-600 text-white"
            : "bg-white border-gray-300 text-slate-900",
    };
}

// confirmDelete

window.confirmDelete = function (e, form) {
    e.preventDefault();
    const t = swalTheme();

    Swal.fire({
        title: "Hapus Data?",
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#EF4444",
        cancelButtonColor: "#475569",
        confirmButtonText:
            '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Ya, Hapus!',
        cancelButtonText: "Batal",
        background: t.background,
        color: t.color,
        customClass: {
            popup: t.popupClass,
            title: "font-bold text-base",
            htmlContainer: "text-sm",
            confirmButton: t.btnClass,
            cancelButton: t.btnClass,
        },
        buttonsStyling: true,
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });

    return false;
};

// confirmClear

window.confirmClear = function (e, form) {
    e.preventDefault();
    const t = swalTheme();

    Swal.fire({
        title: "Reset Data?",
        text: "Data yang direset tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#EF4444",
        cancelButtonColor: "#475569",
        confirmButtonText: "Ya, Reset!",
        cancelButtonText: "Batal",
        background: t.background,
        color: t.color,
        customClass: {
            popup: t.popupClass,
            title: "font-bold text-base",
            htmlContainer: "text-sm",
            confirmButton: t.btnClass,
            cancelButton: t.btnClass,
        },
        buttonsStyling: true,
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });

    return false;
};

// Notify helpers (called from modal.js after fetch)

export function notifySubmitSuccess(isAdd) {
    const t = swalTheme();
    Swal.fire({
        icon: "success",
        title: isAdd ? "Data Ditambahkan!" : "Data Diperbarui!",
        text: isAdd
            ? "Data website baru berhasil ditambahkan."
            : "Perubahan data website berhasil disimpan.",
        timer: 1800,
        showConfirmButton: false,
        background: t.background,
        color: t.color,
        customClass: { popup: t.popupClass },
    }).then(() => location.reload());
}

export function notifySubmitError(isAdd, errMsg) {
    const t = swalTheme();
    Swal.fire({
        icon: "error",
        title: isAdd ? "Gagal Menambahkan!" : "Gagal Menyimpan!",
        html: errMsg,
        confirmButtonColor: "#3B82F6",
        confirmButtonText: "Tutup",
        background: t.background,
        color: t.color,
        customClass: {
            popup: t.popupClass,
            confirmButton: t.btnClass,
        },
        didOpen: () => {
            const sc = document.querySelector(".swal2-container");
            if (sc) sc.style.zIndex = "99999";
        },
    });
}

export function notifyConnFail() {
    const t = swalTheme();
    Swal.fire({
        icon: "error",
        title: "Koneksi Gagal",
        text: "Tidak dapat terhubung ke server. Periksa koneksi internet kamu.",
        confirmButtonColor: "#3B82F6",
        background: t.background,
        color: t.color,
    });
}

// Dropdown option manager

window.addDropdownOption = function (page, key) {
    const t = swalTheme();

    Swal.fire({
        title: "Tambah Opsi Dropdown",
        input: "text",
        inputLabel: "Masukkan opsi baru",
        inputPlaceholder: "Contoh: WordPress, Laravel...",
        showCancelButton: true,
        confirmButtonText: "Tambahkan",
        cancelButtonText: "Batal",
        confirmButtonColor: "#3B82F6",
        cancelButtonColor: "#475569",
        background: t.background,
        color: t.color,
        customClass: {
            popup: t.popupClass,
            confirmButton: t.btnClass,
            cancelButton: t.btnClass,
            input:
                "rounded-lg border text-sm px-3 py-2 w-full mt-1 " +
                t.inputClass,
        },
        inputValidator: (value) => {
            if (!value || value.trim() === "") return "Opsi tidak boleh kosong!";
        },
        reverseButtons: true,
        didOpen: () => {
            const swalContainer = document.querySelector(".swal2-container");
            if (swalContainer) swalContainer.style.zIndex = "99999";
        },
    }).then((result) => {
        if (!result.isConfirmed || !result.value) return;

        fetch("/dropdown/add", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": CSRF,
            },
            body: JSON.stringify({ page, key, option: result.value.trim() }),
        })
            .then((r) => r.json().catch(() => ({})))
            .then(() => location.reload())
            .catch(() =>
                Swal.fire({ icon: "error", title: "Gagal", text: "Gagal menambahkan opsi." }),
            );
    });
};

window.removeDropdownOption = function (page, key, option) {
    const t = swalTheme();

    Swal.fire({
        title: `Hapus opsi "${option}"?`,
        text: "Opsi ini akan dihapus dari daftar pilihan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#EF4444",
        cancelButtonColor: "#475569",
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Batal",
        background: t.background,
        color: t.color,
        customClass: {
            popup: t.popupClass,
            confirmButton: t.btnClass,
            cancelButton: t.btnClass,
        },
        reverseButtons: true,
        // Pastikan SweetAlert tampil di atas modal (z-index: 9999)
        didOpen: () => {
            const swalContainer = document.querySelector(".swal2-container");
            if (swalContainer) swalContainer.style.zIndex = "99999";
        },
    }).then((result) => {
        if (!result.isConfirmed) return;

        fetch("/dropdown/remove", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": CSRF,
            },
            body: JSON.stringify({ page, key, option }),
        })
            .then(() => location.reload())
            .catch(() =>
                Swal.fire({ icon: "error", title: "Gagal", text: "Gagal menghapus opsi." }),
            );
    });
};
