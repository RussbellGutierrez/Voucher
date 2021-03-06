<?php
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
    $_SESSION = [];
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="/voucher/resources/icons/logo.ico" type="image/ico">
        <title>Voucher</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
        <script src="/voucher/resources/scripts/all.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    </head>
    <body>
        <div class="container">
            <div class="card border-0 shadow m-5 bg-body rounded">
                <div class="d-flex justify-content-center flex-column">
                    <div class="bg-primary text-white p-3 mt-3 mb-3" style="background-color:#fd7e14!important;">
                        <div class="text-center display-1"><i class="fas fa-meteor"></i></div>
                        <div class="text-center display-1">Problemas base de datos</div>
                    </div>
                    <div class="text-center fs-5">Ocurrio un error al consultar los datos almacenados</div>
                    <div class="text-center fs-5">Por favor vuelva a ingresar haciendo click en el siguiente enlace</div>
                    <div class="text-center"><a href="/voucher">Volver a la página anterior</a></div>
                </div>
            </div>
        </div>
    </body>
</html>