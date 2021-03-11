<?php
if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['data']) && !empty($_SESSION['data'])) {
    header('Location:/voucher/vp/0/pr');
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
        <div class="center-card">
            <div class="card login">
                <div class="head-log">
                    <img style="width: 90px;height: 90px;" src="resources/images/voucher.png" alt="">
                    <div class="head-text">VOUCHER</div>
                </div>
                <div style="padding: 15px 15px;">
                    <div class="item-log">
                        <i class="fas fa-user-tie"></i><input id="user" class="form-control" style="width: 80%;" type="text" placeholder="Usuario">
                    </div>
                    <div class="item-log">
                        <i class="fas fa-lock"></i><input id="pass" class="form-control" style="width: 80%;" type="password" placeholder="Contraseña">
                    </div>
                    <div class="item-log">
                        <select id="comp" class="select-edt" name="empresa" data-placeholder="EMPRESA" single>
                            <option disabled selected value> -- EMPRESA -- </option>
                            <option value="1">ORIUNDA</option>
                            <option value="2">TERRANORTE</option>
                        </select>
                    </div>
                    <button id="login" class="item-log btn btn-primary" type="button">INGRESAR</button>
                </div>
            </div>
        </div>
        <script>
            $(function() {
                $('#login').on('click', function(){
                    $.ajax({
                        type: 'POST',
                        url: 'http://192.168.1.130/api/usuario/ingresar',
                        dataType: 'json',
                        contentType: 'application/json; charset=utf-8',
                        xhrFields:{withCredentials:false},
                        crossDomain: true,
                        data: JSON.stringify({'usuario':$('#user').val(),'clave':$('#pass').val(),'empresa':$('#comp').val()}),
                        success: function(json) {
                            if (json.data.tipo.id == 1) {
                                var data = json.data.tipo.id+'-'+json.data.empresa.id+'-'+json.data.nombre
                                window.location.href = 'log/'+data
                            }else {
                                Swal.fire('Advertencia','No tiene permitido ingresar a esta página','warning')
                            }
                        },
                        error: function(xhr,status) {
                            Swal.fire('Error '+xhr.status,xhr.responseText,'error')
                        }
                    })
                })
            })
            function getParameterByName(name, url) {
                if (!url) {
                    url = location.href.split("?pr=").slice(-1)[0];
                }
                return url;
            }
        </script>
    </body>
    <style type="text/css">
        body {
            font-family: 'Montserrat', sans-serif;
        }
        .login {
            width: 350px;
            padding: 20px 0 0 0;
            margin-right: auto;
            margin-left: auto;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
        .head-log {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px 0;
            background-color: #4492c5;
        }
        .head-text {
            color: white;
            font-weight: bold;
            font-size: xx-large;
        }
        .item-log {
            display: flex;
            justify-content: space-evenly;
            align-items: baseline;
            margin-top: 20px;
        }
        .center-card {
            width: 100%;
            height: auto;
            margin-top: 5%;
        }
        .btn {
            width: 200px;
            margin-left: auto;
            margin-right: auto;
            outline: none;
            border: none;
        }
        .select-edt {
            width: 290px;
            height: 40px;
            border-radius: 10px;
            text-align-last: center;
            outline: none;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #336396;
        }
        .btn-primary:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
</html>