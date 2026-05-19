<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat,wght@0,100..900;1,100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');
        /* Rajdhani */
        @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap');

        *{
            margin: 0;
            padding: 0;
        }

        header{
            text-align: center;
            margin: 20px;
        }

        header h1{
            font-size: 3em;
            font-family: "Bebas Neue", sans-serif;
        }

        section{
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .container-coleccion{
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
        }

        .coleccion-box{
            width: 50%;
            height: 100vh;
            text-align: center;
            
            background-repeat: no-repeat;
            background-size:cover;

            display: flex;
            flex-direction: column;
            align-items: center;
            align-content: center;

            justify-content: center;
        }

        .coleccion-box h2{
            color:white;
            font-family:'Montserrat';
        }

        .coleccion-box a{
            font-family:'Rajdhani', monospace;
            color: #FFF;
            background: rgba(0, 0, 0, 0.72);

            padding: 10px;
            text-decoration: none;

            letter-spacing: 5px;
        }

        .coleccion-box a:hover{
            background-color: #000;
        }

    </style>
</head>
<body>
    <header>
        <h1>Regale Lenceria</h1>
    </header>
    
    <section>
        <div class="container">
            <div class="coleccion-box" style="background-image: url(src/imgs/prendas/ropainterior.jpg); ">
                <div class="coleccion-detalle">
                    <a class="btn-coleccion" href="#">ROPA INTERIOR</a>
                </div>
            </div>

            <div class="coleccion-box" style="background-image: url(src/imgs/prendas/trajesdebano.jpg);">
                <div class="coleccion-detalle">
                    <a href="#">TRAJES DE BAÑO</a>
                </div>
            </div>

            <div class="coleccion-box" style="background-image: url(src/imgs/prendas/fajas.jpg);">
                <div class="coleccion-detalle">
                    <a href="#">FAJAS</a>
                </div>
            </div>

            <div class="coleccion-box" style="background-image: url(src/imgs/prendas/pijamas.jpg);">
                <div class="coleccion-detalle">
                    <a href="#">PIJAMAS</a>
                </div>
            </div>

            <div class="coleccion-box" style="background-image: url(src/imgs/prendas/hombres.jpg);">
                <div class="coleccion-detalle">
                    <a href="#">HOMBRES</a>
                </div>
            </div>

            <div class="coleccion-box" style="background-image: url(src/imgs/prendas/sombrero.jpg);">
                <div class="coleccion-detalle">
                    <a href="#">ACCESORIOS</a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
