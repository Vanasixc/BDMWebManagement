// sidebar.js — Toggle, mobile overlay, desktop resize, responsive.

let sidebarOpen = window.innerWidth >= 768; // Default open on desktop
let currentSidebarWidth = parseInt(
    localStorage.getItem("sidebarWidth") || "256",
);
let isResizing = false;
let startX = 0;
let startWidth = 0;

// Toggle & close

window.toggleSidebar = function () {
    sidebarOpen = !sidebarOpen;
    applySidebarState();
};

window.closeSidebar = function () {
    sidebarOpen = false;
    applySidebarState();
};

function applySidebarState() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebar-overlay");
    const iconOpen = document.getElementById("hamburger-icon-open");
    const iconClose = document.getElementById("hamburger-icon-close");

    if (!sidebar) return;

    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        // Gunakan classList — hindari konflik dengan Tailwind v4 native CSS translate property
        if (sidebarOpen) {
            sidebar.classList.add("sidebar-open");
        } else {
            sidebar.classList.remove("sidebar-open");
        }

        // Overlay: gunakan style langsung agar tidak konflik dengan Tailwind
        if (overlay) {
            overlay.style.display = sidebarOpen ? "block" : "none";
            overlay.style.pointerEvents = sidebarOpen ? "auto" : "none";
        }
    } else {
        // Desktop: kontrol via width
        sidebar.classList.remove("sidebar-open"); // bersihkan class mobile
        sidebar.style.width = sidebarOpen ? currentSidebarWidth + "px" : "0px";
        sidebar.style.overflow = sidebarOpen ? "" : "hidden";
        if (overlay) {
            overlay.style.display = "none";
            overlay.style.pointerEvents = "none";
        }
    }

    if (iconOpen) iconOpen.style.display = sidebarOpen ? "none" : "block";
    if (iconClose) iconClose.style.display = sidebarOpen ? "block" : "none";
}

// DOMContentLoaded: Resizer & responsive

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const resizer = document.getElementById("sidebar-resizer");

    if (sidebar && window.innerWidth >= 768) {
        sidebar.style.width = currentSidebarWidth + "px";
    }

    if (resizer) {
        resizer.addEventListener("mousedown", (e) => {
            isResizing = true;
            startX = e.clientX;
            startWidth = parseInt(sidebar.style.width || currentSidebarWidth);
            resizer.classList.add("is-resizing");
            document.body.style.userSelect = "none";
        });
    }

    document.addEventListener("mousemove", (e) => {
        if (!isResizing || window.innerWidth < 768) return;
        const sidebar = document.getElementById("sidebar");
        let newWidth = startWidth + (e.clientX - startX);
        newWidth = Math.max(200, Math.min(480, newWidth));
        sidebar.style.width = newWidth + "px";
        currentSidebarWidth = newWidth;
    });

    document.addEventListener("mouseup", () => {
        if (isResizing) {
            isResizing = false;
            const resizer = document.getElementById("sidebar-resizer");
            if (resizer) resizer.classList.remove("is-resizing");
            document.body.style.userSelect = "";
            localStorage.setItem("sidebarWidth", currentSidebarWidth);
        }
    });

        // Responsive: reset sidebar on breakpoint change
    window.addEventListener("resize", () => {
        const sidebar = document.getElementById("sidebar");
        if (!sidebar) return;
        if (window.innerWidth < 768) {
            sidebar.style.width = "256px";
            sidebarOpen = false;
            applySidebarState();
        } else {
            sidebar.style.width = currentSidebarWidth + "px";
            sidebarOpen = true;
            applySidebarState();
        }
    });

    // Sidebar state awal
    applySidebarState();
});
