const fileInput = document.querySelector('.file-input');
const preview = document.querySelector('.preview');

fileInput.addEventListener('change', () => {
    // preview.innerHTML = "";
    const files = fileInput.files;
    Array.from(files).forEach(file =>{
        const reader = new FileReader();
        reader.onload=(event) => {
            const img = document.createElement('img');
            img.src = event.target.result;
            preview.appendChild(img);
        };

        reader.readAsDataURL(file);
    });
});