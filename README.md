# regalelenceria-project



## Getting started

El siguiente proyecto fue diseñado con la intencion de funcionar como un Ecommerce
se uso PHP Vanilla para tener un control total sobre el proyecto, sin depender de aplicaciones
de terceros ni de framework.



## Softwares y complementos necesarios para montar la pagina

* Servidores Apache o Nginx
* PHP 8.x
* MySQL + phpmyadmin


## Estructura de la base de datos

```
regalele_base
    |
    |- banners 
    |    |
    |    |- foto
    |    |- id
    |    |- url
    |
    |- categorias
    |    |
    |    |- descripcion
    |    |- id
    |    |- nombre
    |    |- parent_id
    |    
    |- colores
    |    |
    |    |- codigo_color
    |    |- color_hex
    |    |- descripcion
    |    |- estado
    |    |- id
    |    |- id_prenda
    |    |- img_estampado
    |    |- tipo_color
    | 
    |- control
    |    |
    |    |- id
    |    |- password
    |    |- privilegios
    |    |- usuario
    |
    |- fotos
    |    |
    |    |- color
    |    |- estado
    |    |- id
    |    |- id_prenda 
    |    |- ruta
    |    
    |- inventario
        | 
        |- beneficio
        |- categoria
        |- codigo
        |- compresion
        |- descripcion_corta
        |- descripcion_larga
        |- estado
        |- genero
        |- id
        |- marca_prenda
        |- nombre_prenda
        |- precio_base
        |- sistema
        |- subcategoria 
        |- tipoFaja

    |- recortes 
    |- stock
        |
        |- codigo_color
        |- estado
        |- id
        |- id_prenda 
        |- stock
        |- talla

    |- sys_registro
        |
        |- actividad
        |- fecha
        |- id
        |- nivel
        |- user

    |- tallas
        |
        |- estado 
        |- id
        |- id_prenda 
        |- talla
```

## Detallas sobre la encriptacion de la base a de dato "control"

Las contraseñas estan almacenadas usando Hash Bcrypt para una mayor seguridad
en caso de alguna filtracion.

## Modulos en desarrollo e implementaciones futuras

* Modulo para el envio de Emails usando Emails corporativos. 
* Estadisticas en el panel de control.
* Modulo para la configuracion pagina Web del panel de control.
* Seccion de ofertas
* Rediseñar estrutura de categorias y subcategorias de la base de datos.
* Optimizacion enfocado a dispositivos mobiles.
* Refactorizar codigo una vez implementado modulos y funciones criticas 

## (Keep It Simple Stupid !!!)[https://es.wikipedia.org/wiki/Principio_KISS] 