document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('bukti');
    const preview = document.getElementById('preview-bukti');
    const maxFiles = 5;
    const maxPdfFiles = 1;
    const maxImageFileSize = 2 * 1024 * 1024;
    const maxPdfFileSize = 5 * 1024 * 1024;
    const allowedExtensions = ['jpg', 'jpeg', 'png', 'heic', 'pdf'];
    const popupDefaults = {
        success: { title: 'Success!', buttonText: 'Continue', icon: 'v' },
        error: { title: 'Ooops!', buttonText: 'Try Again', icon: 'x' },
    };
    let selectedFiles = [];
    let popupActive = false;
    const popupQueue = [];

    if (!input || !preview) return;

    const popup = createPopup();

    function createPopup() {
        const wrapper = document.createElement('div');
        wrapper.className = 'upload-alert-popup';
        wrapper.setAttribute('aria-hidden', 'true');
        wrapper.innerHTML = `
            <div class="upload-alert-popup__backdrop" data-upload-alert-close></div>
            <div class="upload-alert-popup__card" role="alertdialog" aria-modal="true" aria-labelledby="upload-alert-title" data-upload-alert-card>
                <button type="button" class="upload-alert-popup__close" data-upload-alert-close aria-label="Tutup popup">x</button>
                <div class="upload-alert-popup__icon" data-upload-alert-icon aria-hidden="true">x</div>
                <h3 class="upload-alert-popup__title" id="upload-alert-title" data-upload-alert-title>Ooops!</h3>
                <ul class="upload-alert-popup__list" data-upload-alert-list></ul>
                <button type="button" class="upload-alert-popup__action" data-upload-alert-close>Try Again</button>
            </div>
        `;

        wrapper.addEventListener('click', function (event) {
            if (event.target.closest('[data-upload-alert-close]')) {
                closePopup();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && wrapper.classList.contains('open')) {
                closePopup();
            }
        });

        document.body.appendChild(wrapper);
        return wrapper;
    }

    function closePopup() {
        if (!popupActive) {
            return;
        }

        popup.classList.remove('open');
        popup.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('upload-alert-open');
        popupActive = false;

        window.setTimeout(() => {
            openNextPopup();
        }, 80);
    }

    function normalizePopup(popupData) {
        const type = popupData?.type === 'success' ? 'success' : 'error';
        const defaults = popupDefaults[type];
        const messages = Array.from(new Set((popupData?.messages || []).filter(Boolean)));

        if (!messages.length) {
            return null;
        }

        return {
            type,
            title: popupData?.title || defaults.title,
            buttonText: popupData?.buttonText || defaults.buttonText,
            icon: popupData?.icon || defaults.icon,
            messages,
        };
    }

    function renderPopup(popupData) {
        const cardEl = popup.querySelector('[data-upload-alert-card]');
        const iconEl = popup.querySelector('[data-upload-alert-icon]');
        const titleEl = popup.querySelector('[data-upload-alert-title]');
        const listEl = popup.querySelector('[data-upload-alert-list]');
        const actionEl = popup.querySelector('.upload-alert-popup__action');

        cardEl.classList.remove('is-success', 'is-error');
        cardEl.classList.add(popupData.type === 'success' ? 'is-success' : 'is-error');

        iconEl.textContent = popupData.icon;
        titleEl.textContent = popupData.title;
        actionEl.textContent = popupData.buttonText;
        listEl.innerHTML = '';

        popupData.messages.forEach((message) => {
            const li = document.createElement('li');
            li.textContent = message;
            listEl.appendChild(li);
        });

        popup.classList.add('open');
        popup.setAttribute('aria-hidden', 'false');
        document.body.classList.add('upload-alert-open');
        popupActive = true;
    }

    function openNextPopup() {
        if (popupActive) {
            return;
        }

        const nextPopup = popupQueue.shift();
        if (!nextPopup) {
            return;
        }

        renderPopup(nextPopup);
    }

    function enqueuePopup(popupData) {
        const normalized = normalizePopup(popupData);
        if (!normalized) {
            return;
        }

        popupQueue.push(normalized);
        openNextPopup();
    }

    const getFileExtension = (name) => {
        const parts = (name || '').toLowerCase().split('.');
        return parts.length > 1 ? parts.pop() : '';
    };

    const isPdfFile = (file) => getFileExtension(file.name) === 'pdf';

    const validateFile = (file) => {
        const ext = getFileExtension(file.name);
        const isPdf = ext === 'pdf';

        if (!allowedExtensions.includes(ext)) {
            return `Format file "${file.name}" tidak didukung. Gunakan JPG, JPEG, PNG, HEIC, atau PDF.`;
        }

        if (isPdf && file.size > maxPdfFileSize) {
            return `Ukuran file PDF "${file.name}" melebihi 5 MB.`;
        }

        if (!isPdf && file.size > maxImageFileSize) {
            return `Ukuran file gambar "${file.name}" melebihi 2 MB.`;
        }

        return null;
    };

    const syncInputFiles = () => {
        const dt = new DataTransfer();
        selectedFiles.forEach((f) => dt.items.add(f));
        input.files = dt.files;
    };

    const renderPreview = () => {
        preview.innerHTML = '';
        selectedFiles.forEach((file, index) => {
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

            if (isPdfFile(file)) {
                const fileCard = document.createElement('div');
                fileCard.className = 'preview-file';

                const badge = document.createElement('span');
                badge.className = 'preview-file__badge';
                badge.textContent = 'PDF';

                const name = document.createElement('span');
                name.className = 'preview-file__name';
                name.textContent = file.name;

                fileCard.appendChild(badge);
                fileCard.appendChild(name);

                div.appendChild(removeBtn);
                div.appendChild(fileCard);
                preview.appendChild(div);
                return;
            }

            const reader = new FileReader();
            reader.onload = function (evt) {
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
        const rejectedMessages = [];
        let currentPdfCount = selectedFiles.filter((file) => isPdfFile(file)).length;

        newFiles.forEach((file) => {
            if (selectedFiles.length >= maxFiles) {
                rejectedMessages.push('Maksimal 5 file bukti pendukung.');
                return;
            }

            const message = validateFile(file);
            if (message) {
                rejectedMessages.push(message);
                return;
            }

            if (isPdfFile(file) && currentPdfCount >= maxPdfFiles) {
                rejectedMessages.push('Maksimal 1 file PDF yang bisa diunggah.');
                return;
            }

            selectedFiles.push(file);

            if (isPdfFile(file)) {
                currentPdfCount += 1;
            }
        });

        if (rejectedMessages.length) {
            enqueuePopup({
                type: 'error',
                title: 'Ooops!',
                messages: rejectedMessages,
                buttonText: 'Try Again',
            });
        }

        input.value = '';
        syncInputFiles();
        renderPreview();
    });

    const pageAlerts = Array.isArray(window.__pageAlerts) ? window.__pageAlerts : [];
    pageAlerts.forEach((popupData) => enqueuePopup(popupData));
});
