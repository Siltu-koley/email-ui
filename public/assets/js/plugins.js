const base = window.APP_ASSET_BASE;

document.querySelectorAll("[toast-list]") &&
    document.writeln("<script src='" + base + "assets/js/toastify.js'><\/script>");

document.querySelectorAll("[data-provider]") &&
    document.writeln("<script src='" + base + "assets/js/flatpickr.min.js'><\/script>");

document.querySelectorAll("[data-choices]") &&
    document.writeln("<script src='" + base + "assets/js/choices.min.js'><\/script>");