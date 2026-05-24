// modal.js — Modal open/close/submit. Depends on: state, formBuilder, sweetAlerts.

import {
    CSRF,
    currentModalMode,
    currentWebsiteId,
    setModalMode,
    setWebsiteId,
} from "./state.js";
import { renderForm, renderEditTableForm } from "./formBuilder.js";
import { notifySubmitSuccess, notifySubmitError, notifyConnFail } from "./sweetAlerts.js";

// Open / Close

window.openModal = function () {
    document.getElementById("modal-overlay").classList.add("active");
    document.body.style.overflow = "hidden"; // Cegah body scroll saat modal terbuka
};

window.closeModal = function () {
    document.getElementById("modal-overlay").classList.remove("active");
    document.body.style.overflow = "";
    // Pastikan tidak ada swal container yang menghalangi interaksi
    const swalBg = document.querySelector(".swal2-container");
    if (swalBg) swalBg.style.pointerEvents = "";
};

window.closeModalOnOverlay = function (e) {
    if (e.target === document.getElementById("modal-overlay")) closeModal();
};

// Open variants

window.openModalView = function (id) {
    setModalMode("view");
    setWebsiteId(id);
    document.getElementById("modal-title").textContent = "Detail Data Website";
    document.getElementById("modal-save-btn").style.display = "none";
    fetchAndRenderForm(id, true);
};

window.openModalEdit = function (id) {
    setModalMode("edit");
    setWebsiteId(id);
    document.getElementById("modal-title").textContent = "Edit Data Website";
    document.getElementById("modal-save-btn").style.display = "";
    fetchAndRenderForm(id, false);
};

window.openModalAdd = function () {
    setModalMode("add");
    setWebsiteId(null);
    document.getElementById("modal-title").textContent =
        "Tambah Data Website Baru";
    document.getElementById("modal-save-btn").style.display = "";
    renderForm(null, false);
    openModal();
};

window.openModalEditTable = function () {
    setModalMode("editTable");
    document.getElementById("modal-title").textContent =
        "Pengaturan Opsi Dropdown";
    document.getElementById("modal-save-btn").style.display = "none";
    renderEditTableForm();
    openModal();
};

// Submit

window.submitModalForm = function () {
    const form = document.getElementById("modal-dynamic-form");
    if (!form) return;

    const isAdd = currentModalMode === "add";
    const formData = new FormData(form);

    formData.append("section", window.WHSection || "master");


    if (!form.reportValidity()) return;

    const phoneField = form.querySelector('input[name="phone"]');
    if (phoneField && !/^[0-9]+$/.test(phoneField.value)) {
        phoneField.setCustomValidity('No. WhatsApp hanya boleh berisi angka.');
        phoneField.reportValidity();
        phoneField.setCustomValidity(''); // Reset agar valid berikutnya
        return;
    }

    // Untuk PUT method (edit), FormData sudah include _method=PUT via hidden field
    fetch(form.action, {
        method: "POST", // Laravel method-spoofing lewat _method field
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": CSRF,
        },
        body: formData,
    })
        .then(async (r) => {
            if (r.redirected || r.ok) {
                closeModal();
                notifySubmitSuccess(isAdd);
            } else {
                // Error (mis. validasi 422)
                let errMsg =
                    "Terjadi kesalahan. Periksa kembali data yang diisi.";
                try {
                    const json = await r.json();
                    if (json.errors) {
                        errMsg = Object.values(json.errors).flat().join("<br>");
                    } else if (json.message) {
                        errMsg = json.message;
                    }
                } catch (_) {}

                notifySubmitError(isAdd, errMsg);
            }
        })
        .catch(() => {
            notifyConnFail();
        });
};

// Internal: fetch data & render form

function fetchAndRenderForm(id, readonly) {
    fetch(`/websites/${id}`, {
        headers: { Accept: "application/json", "X-CSRF-TOKEN": CSRF },
    })
        .then((r) => r.json())
        .then((data) => {
            renderForm(data, readonly);
            openModal();
        })
        .catch(() => alert("Gagal memuat data."));
}
