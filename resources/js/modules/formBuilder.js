// formBuilder.js — Renders dynamic form HTML inside modal body. Depends on: state.js.

import { CSRF } from "./state.js";

// ─── renderForm ───────────────────────────────────────────────────────────────

export function renderForm(data, readonly) {
    const section = window.WHSection || "master";
    const dd = window.WHDropdowns || {};
    const isEdit = data && data.id;
    const action = isEdit ? `/websites/${data.id}` : "/websites";
    const method = isEdit ? "PUT" : "POST";

    // Legenda required hanya untuk section master dan saat bukan readonly
    const reqLegend =
        section === "master" && !readonly
            ? `<p class="text-[10px] text-slate-400 dark:text-slate-500 mb-1 col-span-full"><span class="text-rose-500 font-bold">*</span> Wajib diisi</p>`
            : "";

    let html = `<form id="modal-dynamic-form" method="POST" action="${action}">
        <input type="hidden" name="_token" value="${CSRF}"/>
        <input type="hidden" name="section" value="${section}"/>
        ${isEdit ? '<input type="hidden" name="_method" value="PUT"/>' : ""}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">${reqLegend}`;

    const v = data || {};

    // Field helpers

    function inp(
        label,
        name,
        value,
        type = "text",
        { pattern = "", placeholder = "", required = false } = {},
    ) {
        const val = value !== null && value !== undefined ? value : "";
        const ro = readonly ? "disabled" : "";
        const patAttr = pattern
            ? `pattern="${pattern}" title="Format tidak valid"`
            : "";
        const phAttr = placeholder ? `placeholder="${placeholder}"` : "";
        const reqAttr = required ? `required` : "";
        const reqStar = required
            ? `<span class="text-rose-500 ml-0.5">*</span>`
            : "";
        return `<div class="space-y-1">
            <label class="block text-[10px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">${label}${reqStar}</label>
            <input type="${type}" name="${name}" value="${String(val).replace(/"/g, "&quot;")}" ${ro} ${patAttr} ${phAttr} ${reqAttr}
                class="w-full px-3 py-2 border rounded-lg text-sm outline-none transition
                       bg-white border-gray-300 text-slate-900
                       dark:bg-slate-700 dark:border-slate-600 dark:text-white
                       disabled:bg-slate-50 dark:disabled:bg-slate-800
                       focus:ring-2 focus:ring-blue-500"/>
        </div>`;
    }

    function sel(label, name, value, options, { required = false } = {}) {
        const ro = readonly ? "disabled" : "";
        const reqAttr = required ? `required` : "";
        const reqStar = required
            ? `<span class="text-rose-500 ml-0.5">*</span>`
            : "";
        const opts = options
            .map(
                (o) =>
                    `<option value="${o}" ${o === value ? "selected" : ""}>${o}</option>`,
            )
            .join("");
        return `<div class="space-y-1">
        <label class="block text-[10px] md:text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">${label}${reqStar}</label>
        <select name="${name}" ${ro} ${reqAttr}
            class="w-full px-3 py-2 border rounded-lg text-sm outline-none transition cursor-pointer
                   bg-white border-gray-300 text-slate-900
                   dark:bg-slate-700 dark:border-slate-600 dark:text-white
                   disabled:bg-slate-50 dark:disabled:bg-slate-800
                   focus:ring-2 focus:ring-blue-500">
            ${opts}
        </select>
    </div>`;
    }

    function textarea(label, name, value, span = false) {
        const ro = readonly ? "disabled" : "";
        const colSpan = span ? "col-span-1 md:col-span-2" : "";
        const val = value !== null && value !== undefined ? value : "";
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

    // Build fields per section

    switch (section) {
        case "master": {
            const types = dd["type"] || ["Profile", "Blog", "Berita"];
            const techs = dd["technology"] || ["WordPress", "Laravel"];
            const rawStatuses = dd["status"] || ["Active", "InActive", "Suspend"];
            const statuses = [
                ...rawStatuses
                    .map((s) => (s === "Aktif" ? "Active" : s))
                    .filter((s) => s === "Active"),
                ...rawStatuses
                    .map((s) => (s === "Aktif" ? "Active" : s))
                    .filter((s) => s !== "Active"),
            ];
            const pics = dd["internalPic"] || ["Iqbal"];

            if (!v.status || v.status === "Aktif") v.status = "Active";

            html += inp("Nama Client", "client", v.client, "text", {
                placeholder: "Contoh: PT. Banjar Digital Media",
                required: true,
            });
            html += inp("PIC", "pic", v.pic, "text", {
                placeholder: "Nama penanggung jawab client",
                required: true,
            });
            html += inp("Nama Website", "website", v.website, "text", {
                placeholder: "Contoh: Banjar Digital Media",
                required: true,
            });
            html += inp("URL Website", "url", v.url, "text", {
                placeholder: "https://example.com",
                required: true,
            });
            html += sel("Jenis Website", "type", v.type, types, {
                required: true,
            });
            html += sel("CMS/Teknologi", "technology", v.technology, techs, {
                required: true,
            });
            html += sel("Status", "status", v.status, statuses, {
                required: true,
            });
            html += sel("PIC Internal", "internal_pic", v.internal_pic, pics, {
                required: true,
            });
            html += inp(
                "Service Package",
                "service_package",
                v.service_package,
                "text",
                { placeholder: "Contoh: Basic, Premium, dll." },
            );
            html += inp(
                "Tahun Pembuatan",
                "created_year",
                v.created_year ? String(v.created_year).substring(0, 10) : "",
                "date",
            );
            html += inp("No. WhatsApp", "phone", v.phone, "text", {
                placeholder: "Contoh: 08123456789",
                required: true,
            });
            html += inp("Email", "email", v.email, "email", {
                placeholder: "email@client.com",
            });
            html = html + `</div>`;
            html += `<div class="grid grid-cols-1 gap-4 mt-4">`;
            html += textarea("Catatan", "note", v.note, true);
            break;
        }
        case "domain": {
            html += inp("Domain URL", "url", v.url, "text", {
                placeholder: "https://namadomain.com",
            });
            html += inp(
                "Provider Domain",
                "domain_provider",
                v.domain_provider,
                "text",
                { placeholder: "Contoh: Domainesia, Niagahoster" },
            );
            html += inp(
                "Email Akun Domain",
                "domain_email",
                v.domain_email,
                "email",
                { placeholder: "email@provider.com" },
            );
            html += inp(
                "Harga Domain / Tahun",
                "domain_price",
                v.domain_price ? parseInt(v.domain_price) : "",
                "number",
            );
            html += inp(
                "Tanggal Registrasi",
                "domain_reg_date",
                v.domain_reg_date ? v.domain_reg_date.substring(0, 10) : "",
                "date",
            );
            html += inp(
                "Tanggal Expired",
                "domain_exp_date",
                v.domain_exp_date ? v.domain_exp_date.substring(0, 10) : "",
                "date",
            );
            break;
        }
        case "hosting": {
            const hTypes = dd["hostingType"] || [
                "Dedicated Server",
                "Shared",
                "Redirect",
            ];
            html += sel("Jenis Hosting", "hosting_type", v.hosting_type, hTypes);
            html += inp(
                "Provider Hosting",
                "hosting_provider",
                v.hosting_provider,
                "text",
                { placeholder: "Contoh: OVHcloud, Niagahoster" },
            );
            html += inp(
                "Kapasitas Storage (GB)",
                "storage",
                v.storage,
                "number",
            );
            html += inp("IP Server", "ip_server", v.ip_server, "text", {
                pattern: "^(\\d{1,3}\\.){3}\\d{1,3}$",
                placeholder: "Contoh: 192.168.1.1",
            });
            html += inp("Lokasi Server", "location", v.location, "text", {
                placeholder: "Contoh: Singapore, Jakarta",
            });
            html += inp(
                "Email Hosting",
                "hosting_email",
                v.hosting_email,
                "email",
                { placeholder: "email@provider.com" },
            );
            html += inp(
                "Harga Hosting / Tahun",
                "hosting_price",
                v.hosting_price ? parseInt(v.hosting_price) : "",
                "number",
            );
            html += inp(
                "Tanggal Expired",
                "hosting_exp_date",
                v.hosting_exp_date
                    ? v.hosting_exp_date.substring(0, 10)
                    : "",
                "date",
            );
            break;
        }
        case "akses": {
            html += inp("URL Admin", "admin_url", v.admin_url, "text", {
                placeholder: "https://namadomain.com/wp-admin",
            });
            html += inp(
                "Akses Tambahan",
                "extra_access",
                v.extra_access,
                "text",
                { placeholder: "Contoh: cPanel, FTP, SSH" },
            );
            html += inp(
                "Lokasi Simpan Password",
                "password_loc",
                v.password_loc,
                "text",
                { placeholder: "Contoh: Google Drive, Notion" },
            );
            html =
                html + `</div><div class="grid grid-cols-1 gap-4 mt-4">`;
            html += textarea("Catatan Akses", "note", v.note, true);
            break;
        }
        case "finansial": {
            const paySystems = dd["paySystem"] || ["Tahunan", "Bulanan"];
            const payStatuses = dd["payStatus"] || ["Lunas", "Belum"];
            html += inp(
                "Harga Jual / Tahun",
                "sell_price",
                v.sell_price ? parseInt(v.sell_price) : "",
                "number",
            );
            html += sel(
                "Sistem Pembayaran",
                "pay_system",
                v.pay_system,
                paySystems,
            );
            html += sel(
                "Status Pembayaran",
                "pay_status",
                v.pay_status,
                payStatuses,
            );
            html += inp(
                "Tanggal Invoice",
                "invoice_date",
                v.invoice_date ? v.invoice_date.substring(0, 10) : "",
                "date",
            );
            break;
        }
        case "reminder": {
            html += inp("Website", "website", v.website, "text", {
                placeholder: "Nama website",
            });
            html =
                html + `</div><div class="grid grid-cols-1 gap-4 mt-4">`;
            html += textarea("Catatan Reminder", "note", v.note, true);
            break;
        }
    }

    html += `</div></form>`;
    document.getElementById("modal-body").innerHTML = html;
}

// renderEditTableForm

export function renderEditTableForm() {
    const section = window.WHSection || "master";
    const dd = window.WHDropdowns || {};

    if (Object.keys(dd).length === 0) {
        document.getElementById("modal-body").innerHTML = `<p class="text-sm text-slate-400 dark:text-slate-500">Tidak ada konfigurasi dropdown untuk halaman ini.</p>`;
        return;
    }

    let html = `<p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
        Kelola pilihan dropdown yang tampil pada form tambah/edit data.
    </p>`;

    const labelMap = {
        type: "Jenis Website",
        technology: "CMS/Teknologi",
        status: "Status",
        internalPic: "PIC Internal",
        hostingType: "Jenis Hosting",
        paySystem: "Sistem Pembayaran",
        payStatus: "Status Pembayaran",
        reminderStatus: "Status Reminder",
    };

    for (const [key, opts] of Object.entries(dd)) {
        html += `<div class="p-4 border rounded-xl mb-4 border-gray-200 dark:border-slate-700 bg-gray-50/60 dark:bg-slate-900/50">
            <h4 class="font-bold text-sm mb-3 text-slate-800 dark:text-slate-200">${labelMap[key] || key}</h4>
            <div class="flex flex-wrap gap-2 mb-3" id="opts-${key}">
                ${opts
                    .map(
                        (opt) => `
                    <span class="px-3 py-1 rounded-full text-xs border flex items-center gap-2
                                border-gray-300 bg-white text-slate-700
                                dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        ${opt}
                        <button type="button"
                            onclick="removeDropdownOption('${section}','${key}','${opt.replace(/'/g, "\\'")}')"
                            class="text-rose-500 hover:text-rose-700 transition font-bold leading-none">×</button>
                    </span>
                `,
                    )
                    .join("")}
            </div>
            <button
                type="button"
                onclick="addDropdownOption('${section}','${key}')"
                class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs flex items-center gap-1.5 hover:bg-blue-700 transition">
                + Tambah Opsi
            </button>
        </div>`;
    }

    document.getElementById("modal-body").innerHTML = html;
}
