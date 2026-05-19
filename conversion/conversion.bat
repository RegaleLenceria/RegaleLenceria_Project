@echo off
set /p quality="Introduce la calidad WebP (0-100, recomendado 75): "

:: Procesar JPG
for /r %%f in (*.jpg *.jpeg) do (
    ffmpeg -i "%%f" -q:v %quality% "%%~dpnf.webp"
)

:: Procesar PNG
for /r %%f in (*.png) do (
    ffmpeg -i "%%f" -q:v %quality% "%%~dpnf.webp"
)

echo Conversión finalizada.
pause