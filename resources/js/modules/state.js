// state.js — Shared state & constants (currentModalMode, currentWebsiteId, CSRF).
export let currentModalMode = "view"; // 'view' | 'edit' | 'add' | 'editTable'
export let currentWebsiteId = null;
export const CSRF =
    document.querySelector('meta[name="csrf-token"]')?.content || "";

/** Update currentModalMode dari modul lain */
export function setModalMode(mode) {
    currentModalMode = mode;
}

/** Update currentWebsiteId dari modul lain */
export function setWebsiteId(id) {
    currentWebsiteId = id;
}
