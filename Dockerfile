# =========================================================
# Dockerfile – React / Vite (Regalele Lencería)
# Multi-stage: Node 20 → build → Nginx sirve SPA + proxy API
# =========================================================

# ---- Stage 1: Build ----
FROM node:20-alpine AS builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --prefer-offline

COPY . .
RUN npm run build

# ---- Stage 2: Nginx ----
FROM nginx:1.27-alpine

COPY --from=builder /app/dist /usr/share/nginx/html

# Nginx config: SPA routing + proxy /api y /upload al php-api
RUN printf 'server {\n\
    listen 80;\n\
    root /usr/share/nginx/html;\n\
    index index.html;\n\
\n\
    # SPA – redirige 404 a index.html\n\
    location / {\n\
        try_files $uri $uri/ /index.html;\n\
    }\n\
\n\
    # Proxy hacia el servicio php-api\n\
    location /api/ {\n\
        proxy_pass         http://php-api:80/api/;\n\
        proxy_set_header   Host $host;\n\
        proxy_set_header   X-Real-IP $remote_addr;\n\
        proxy_read_timeout 30s;\n\
    }\n\
\n\
    # Proxy de imágenes de productos\n\
    location /upload/ {\n\
        proxy_pass         http://php-api:80/upload/;\n\
        proxy_set_header   Host $host;\n\
        expires            7d;\n\
        add_header Cache-Control "public";\n\
    }\n\
\n\
    # Cache para assets compilados (hash en nombre)\n\
    location ~* \\.(js|css|png|jpg|jpeg|gif|ico|svg|woff2?)$ {\n\
        try_files $uri =404;\n\
        expires 1y;\n\
        add_header Cache-Control "public, immutable";\n\
    }\n\
}\n' > /etc/nginx/conf.d/default.conf

EXPOSE 80
