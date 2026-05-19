document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const uploadBtn = document.getElementById('uploadBtn');
    const originalImage = document.getElementById('originalImage');
    const imageCanvas = document.getElementById('imageCanvas');
    const previewCanvas = document.getElementById('previewCanvas');
    const cropBtn = document.getElementById('cropBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveCropBtn = document.getElementById('saveCropBtn');
    const resultMessage = document.getElementById('resultMessage');
    
    const id_prenda = document.querySelector('.id_prenda').value;

    let ctx = imageCanvas.getContext('2d');
    let previewCtx = previewCanvas.getContext('2d');
    let isDrawing = false;
    let startX, startY;
    let cropX, cropY, cropWidth, cropHeight;
    let imageObj = null;
    let scaleFactor = 1;
    
    // Subir imagen
    uploadBtn.addEventListener('click', function() {
        if (imageInput.files && imageInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                originalImage.src = e.target.result;
                originalImage.onload = function() {
                    // Ajustar el tamaño del canvas al de la imagen
                    const maxWidth = 800;
                    const maxHeight = 600;
                    
                    let width = originalImage.width;
                    let height = originalImage.height;
                    
                    if (width > maxWidth) {
                        scaleFactor = maxWidth / width;
                        width = maxWidth;
                        height = height * scaleFactor;
                    }
                    
                    if (height > maxHeight) {
                        scaleFactor = maxHeight / originalImage.height;
                        height = maxHeight;
                        width = originalImage.width * scaleFactor;
                    }
                    
                    imageCanvas.width = width;
                    imageCanvas.height = height;
                    previewCanvas.width = 300;
                    previewCanvas.height = 200;
                    
                    ctx.drawImage(originalImage, 0, 0, width, height);
                    imageObj = originalImage;
                    
                    cropBtn.disabled = false;
                    cancelBtn.disabled = false;
                    saveCropBtn.disabled = true;
                    
                    resetSelection();
                };
            };
            
            reader.readAsDataURL(imageInput.files[0]);
        } else {
            alert('Por favor selecciona una imagen primero.');
        }
    });
    
    // Eventos para el recorte
    imageCanvas.addEventListener('mousedown', startSelection);
    imageCanvas.addEventListener('mousemove', drawSelection);
    imageCanvas.addEventListener('mouseup', endSelection);
    imageCanvas.addEventListener('mouseout', endSelection);
    
    // Botón de recortar
    cropBtn.addEventListener('click', function() {
        if (cropWidth && cropHeight) {
            // Mostrar vista previa del recorte
            previewCtx.clearRect(0, 0, previewCanvas.width, previewCanvas.height);
            
            // Calcular coordenadas originales (sin escalado)
            const originalX = cropX / scaleFactor;
            const originalY = cropY / scaleFactor;
            const originalWidth = cropWidth / scaleFactor;
            const originalHeight = cropHeight / scaleFactor;
            
            // Dibujar el recorte en el canvas de vista previa
            previewCtx.drawImage(
                imageObj, 
                originalX, originalY, originalWidth, originalHeight,
                0, 0, previewCanvas.width, previewCanvas.height
            );
            
            saveCropBtn.disabled = false;
        }
    });
    
    // Botón de cancelar
    cancelBtn.addEventListener('click', function() {
        resetSelection();
        previewCtx.clearRect(0, 0, previewCanvas.width, previewCanvas.height);
        saveCropBtn.disabled = true;
    });
    
    // Botón de guardar recorte
    saveCropBtn.addEventListener('click', function() {
        if (cropWidth && cropHeight) {
            
            const codigo_color = document.querySelector(".codigo_color").value;
            const nombre_estampado = document.querySelector(".nombre_estampado").value;
            const estado = document.querySelector(".estado").value;

            // Crear un canvas temporal para el recorte
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = cropWidth / scaleFactor;
            tempCanvas.height = cropHeight / scaleFactor;
            const tempCtx = tempCanvas.getContext('2d');
            
            // Dibujar el recorte en el canvas temporal
            tempCtx.drawImage(
                imageObj, 
                cropX / scaleFactor, cropY / scaleFactor, 
                cropWidth / scaleFactor, cropHeight / scaleFactor,
                0, 0, 
                tempCanvas.width, tempCanvas.height
            );
            
            // Convertir a Blob y enviar al servidor
            tempCanvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('croppedImage', blob, 'recorte.png');
                formData.append('id_prenda', id_prenda);
                formData.append('codigo_color', codigo_color);
                formData.append('nombre_estampado', nombre_estampado);
                formData.append('estado', estado);

                fetch('upload_estampado.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('Recorte guardado exitosamente!', 'success');
                        window.location.href = "";
                    } else {
                        showMessage(' al guardar el recorte: ' + data.error, 'error');
                    }Error
                })
                .catch(error => {
                    showMessage('Error en la conexión: ' + error, 'error');
                });
            }, 'image/png');
        }
    });
    
    // Funciones auxiliares
    function startSelection(e) {
        isDrawing = true;
        startX = e.offsetX;
        startY = e.offsetY;
        
        // Limpiar selección anterior
        ctx.clearRect(0, 0, imageCanvas.width, imageCanvas.height);
        ctx.drawImage(originalImage, 0, 0, imageCanvas.width, imageCanvas.height);
    }
    
    function drawSelection(e) {
        if (!isDrawing) return;
        
        // Redibujar la imagen original
        ctx.clearRect(0, 0, imageCanvas.width, imageCanvas.height);
        ctx.drawImage(originalImage, 0, 0, imageCanvas.width, imageCanvas.height);
        
        // Calcular dimensiones del rectángulo de selección
        const currentX = e.offsetX;
        const currentY = e.offsetY;
        
        cropX = Math.min(startX, currentX);
        cropY = Math.min(startY, currentY);
        cropWidth = Math.abs(currentX - startX);
        cropHeight = Math.abs(currentY - startY);
        
        // Dibujar rectángulo de selección
        ctx.strokeStyle = '#ffffffff';
        ctx.lineWidth = 2;
        ctx.setLineDash([5, 5]);
        ctx.strokeRect(cropX, cropY, cropWidth, cropHeight);
        
        // Dibujar área semitransparente fuera del recorte
        ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
        ctx.fillRect(0, 0, imageCanvas.width, cropY);
        ctx.fillRect(0, cropY, cropX, cropHeight);
        ctx.fillRect(cropX + cropWidth, cropY, imageCanvas.width - (cropX + cropWidth), cropHeight);
        ctx.fillRect(0, cropY + cropHeight, imageCanvas.width, imageCanvas.height - (cropY + cropHeight));
    }
    
    function endSelection() {
        isDrawing = false;
    }
    
    function resetSelection() {
        ctx.clearRect(0, 0, imageCanvas.width, imageCanvas.height);
        ctx.drawImage(originalImage, 0, 0, imageCanvas.width, imageCanvas.height);
        cropX = cropY = cropWidth = cropHeight = 0;
    }
    
    function showMessage(message, type) {
        resultMessage.textContent = message;
        resultMessage.className = type;
    }
});