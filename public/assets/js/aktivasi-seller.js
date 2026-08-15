document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('gridCheck');
    const submitBtn = document.getElementById('submitBtn');
    const fotoInput = document.getElementById('foto');
    const previewContainer = document.getElementById('preview-container');
    let isProcessing = false;

    // Enable/disable submit button
    checkbox.addEventListener('change', function() {
        submitBtn.disabled = !this.checked;
    });

    // Preview foto dan hapus
    fotoInput.addEventListener('change', function(event) {
        if (isProcessing) return;
        isProcessing = true;

        const files = Array.from(event.target.files);
        const maxSize = 2 * 1024 * 1024;
        const validFiles = [];

        files.forEach(file => {
            if (file.size <= maxSize) validFiles.push(file);
            else alert(`File "${file.name}" lebih dari 2MB dan tidak akan dipilih.`);
        });

        previewContainer.innerHTML = '';

        validFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = `Foto ${index + 1}`;
                
                const icon = document.createElement('span');
                icon.innerHTML = '<i class="bx bx-trash"></i>';
                icon.onclick = function() {
                    validFiles.splice(index, 1);
                    const dataTransfer = new DataTransfer();
                    validFiles.forEach(f => dataTransfer.items.add(f));
                    fotoInput.files = dataTransfer.files;
                    wrapper.remove();
                };

                wrapper.appendChild(img);
                wrapper.appendChild(icon);
                previewContainer.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });

        const dataTransfer = new DataTransfer();
        validFiles.forEach(f => dataTransfer.items.add(f));
        fotoInput.files = dataTransfer.files;

        isProcessing = false;
    });
});
