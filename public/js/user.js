document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('bukti');
    const preview = document.getElementById('preview-bukti');
    let selectedFiles = [];

    if (!input || !preview) return;

    const syncInputFiles = () => {
        const dt = new DataTransfer();
        selectedFiles.forEach((f) => dt.items.add(f));
        input.files = dt.files;
    };

    const renderPreview = () => {
        preview.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (evt) {
                const div = document.createElement('div');
                div.className = 'preview-item';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'preview-remove';
                removeBtn.setAttribute('aria-label', 'Hapus file');
                removeBtn.textContent = 'x';
                removeBtn.addEventListener('click', () => {
                    selectedFiles.splice(index, 1);
                    syncInputFiles();
                    renderPreview();
                });

                const img = document.createElement('img');
                img.src = evt.target.result;

                div.appendChild(removeBtn);
                div.appendChild(img);
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    };

    input.addEventListener('change', function (e) {
        const newFiles = Array.from(e.target.files || []);
        selectedFiles = selectedFiles.concat(newFiles);

        if (selectedFiles.length > 5) {
            alert('Maksimal 5 foto yang bisa di upload');
            selectedFiles = selectedFiles.slice(0, 5);
        }

        input.value = '';
        syncInputFiles();
        renderPreview();
    });
});
