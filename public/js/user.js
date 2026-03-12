document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('bukti');
    const preview = document.getElementById('preview-bukti');

    if (!input || !preview) return;

    input.addEventListener('change', function (e) {
        let files = Array.from(e.target.files || []);

        if (files.length > 5) {
            alert('Maksimal 5 foto yang bisa di upload');
            files = files.slice(0, 5);
            const dt = new DataTransfer();
            files.forEach((f) => dt.items.add(f));
            input.files = dt.files;
        }

        preview.innerHTML = '';
        files.forEach((file) => {
            const reader = new FileReader();
            reader.onload = function (evt) {
                const div = document.createElement('div');
                div.className = 'preview-item';
                const img = document.createElement('img');
                img.src = evt.target.result;
                div.appendChild(img);
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
});
