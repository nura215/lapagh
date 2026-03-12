document.addEventListener("DOMContentLoaded", function () {
    // toggle password for all eye icons
    document.querySelectorAll(".toggle-password").forEach(function (icon) {
        const input = icon.closest(".password-wrapper")?.querySelector("input");
        if (!input) return;
        icon.addEventListener("click", function () {
            const type = input.type === "password" ? "text" : "password";
            input.type = type;
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
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
