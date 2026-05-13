// darkMode.js — Dark mode toggle & apply-on-load (no flash).

// Apply on load — harus sebelum render untuk hindari flash
(function () {
    const isDark = localStorage.getItem("darkMode") === "1";
    if (isDark) {
        document.documentElement.classList.add("dark");
        document.documentElement.style.colorScheme = "dark";
    } else {
        document.documentElement.classList.remove("dark");
        document.documentElement.style.colorScheme = "light";
    }
})();

window.toggleDark = function () {
    const isDark = document.documentElement.classList.toggle("dark");
    localStorage.setItem("darkMode", isDark ? "1" : "0");
    document.documentElement.style.colorScheme = isDark ? "dark" : "light";
};
