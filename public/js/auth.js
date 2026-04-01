document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-password").forEach(function (icon) {
        const input = icon.closest(".password-wrapper")?.querySelector("input");
        if (!input) return;

        icon.addEventListener("click", function () {
            const isHidden = input.type === "password";

            input.type = isHidden ? "text" : "password";

            if (isHidden) {
                // sekarang terlihat
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                // sekarang disembunyikan
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        });
    });

    const alertBox = document.getElementById("loginAlert");
    const form = document.getElementById("loginForm");

    if (alertBox && form) {
        form.classList.add("shake");

        setTimeout(() => {
            alertBox.style.opacity = "0";
        }, 3000);

        setTimeout(() => {
            alertBox.remove();
        }, 3400);
    }
});
