// app.js — Entry point. All logic lives in modules/.
import "sweetalert2/dist/sweetalert2.min.css";
import Swal from "sweetalert2";
import Chart from "chart.js/auto";
import "./bootstrap";
import "./modules/darkMode";
import "./modules/sidebar";
import "./modules/sweetAlerts";
import "./modules/formBuilder";
import "./modules/modal";

// Expose globals agar bisa dipakai di inline <script> section views
window.Swal = Swal;
window.Chart = Chart;
// Flush chart callbacks yang diqueue oleh onChartReady() di section views
// (section scripts jalan sebelum module ini karena type="module" adalah deferred)
if (window.__chartCbs) {
    window.__chartCbs.forEach(function(fn) { fn(); });
    window.__chartCbs = [];
}
