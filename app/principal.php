<?php
require_once('process/functions.php');
Functions::checkSession();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="/voucher/resources/icons/logo.ico" type="image/ico">
        <title>Voucher</title>

        <script src="/voucher/resources/scripts/jquery-3.5.1.js"></script>
        <script src="/voucher/resources/scripts/jquery-ui.min.js"></script>
        <script src="/voucher/resources/scripts/bootstrap.bundle.min.js"></script>
        <script src="/voucher/resources/scripts/all.js"></script>
        <script src="/voucher/resources/scripts/sweetalert2@8.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>

        <link rel="stylesheet" href="/voucher/resources/styles/bootstrap.min.css">
        <link rel="stylesheet" href="/voucher/resources/styles/css2.css">
        <link rel="stylesheet" href="/voucher/resources/styles/jquery-ui.css">

        <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="/voucher/resources/styles/panel-left.css">
    </head>
    <body>

        <div class="wrapper">
            <nav id="panel">
                <div id="cerrar">
                    <i class="fas fa-arrow-left"></i>
                </div>
                <div class="sidebar-header">
                    <h3><i class="fas fa-receipt"></i> Vouchers</h3>
                </div>
                <ul class="list-unstyled components">
                    <p>Funciones</p>
                    <li><a href="/voucher/vp/0/pr" aria-expanded="false">Reporte diario</a></li>
                    <li><a href="/voucher/vp/1/pr" aria-expanded="false">Lista vouchers</a></li>
                    <li><a id="cargar" href="javascript:void(0)" aria-expanded="false">Cargar vouchers</a></li>
                    <li><a id="logout" href="javascript:void(0)" aria-expanded="false">Cerrar sesión</a></li>
                </ul>
            </nav>

            <div id="contenido">
                <div class="container">
                    <div class="shadow-sm p-3 mb-5 bg-white rounded sticky-top d-flex" style="z-index: 2!important;">
                        <button type="button" id="panelCollapse" class="btn btn-info"><i class="fas fa-bars"></i></button>
                        <div class="d-flex ml-4 mr-4 display-6 align-items-center">
                            <div class="ml-2 mr-2">Vouchers</div>
                            <div id="user" class="ml-2 mr-2">Usuario</div>
                            <div id="empresa" class="ml-2 mr-2">Empresa</div>
                        </div>
                    </div>
                    <div id="v-info" class="shadow-sm p-3 mb-5 bg-white rounded d-none">
                        <?php include 'app/info.php'; ?>          
                    </div>
                    <div id="v-lista" class="shadow-sm p-3 mb-5 bg-white rounded d-none">
                        <?php include 'app/lista.php'; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="overlay"></div>

        <div id="modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <img id="img-zoom" src="" style="width:100%;"> 
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(function() {
                setUser()
                setToday()
                setView()
                
                $("#panel").mCustomScrollbar({
                    theme: "minimal"
                })
                $('#cerrar, .overlay').on('click', function () {
                    $('#panel').removeClass('active')
                    $('.overlay').removeClass('active')
                })
                $('#panelCollapse').on('click', function () {
                    $('#panel').addClass('active')
                    $('.overlay').addClass('active')
                    $('.collapse.in').toggleClass('in')
                    $('a[aria-expanded=true]').attr('aria-expanded', 'false')
                })
                $('#btn-img').on('click', function() {
                    $('#modal').modal()
                })
                $('#buscar').on('click', function() {
                    var fecha = $('#b-fecha').val()
                    setTabla(fecha)
                })
                $('#generar').on('click', function() {
                    var fecha = $('#fecha-reporte').val()
                    setReporte(fecha)
                })
                $('#cargar').on('click', function() {
                    addVouchers()
                })
                $('#cancelar').on('click', function() {
                    setData(null,0)
                })
                $('#guardar').on('click', function() {
                    validateVoucher()
                })
                $('#logout').on('click',function() {
                    Swal.fire({
                        title: 'Cerrar Sesión',
                        text: 'Desea salir de la página y volver al inicio?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = '/voucher/logout'
                        }
                    })
                })
            })

            function setReporte(fecha) {
                var f = ''
                if (fecha) {
                    f = fecha
                }else {
                    f = $('#fecha-reporte').val()
                }
                $.post('/voucher/process/getDataReporte.php',{fecha:f},function(e) {
                    var json = JSON.parse(e)
                    var total = json.datos.voucher
                    if (total > 0) {
                        $('#r-empty').addClass('d-none')
                        $('#reporte').removeClass('d-none')
                        setCanvas(json.datos)
                        setCardInfo(json.datos)
                    }else {
                        $('#r-empty').removeClass('d-none')
                        $('#reporte').addClass('d-none')
                    }
                })
            }

            function setCanvas(data) {
                var pie = data.chart
                var estado = pie[0].estado
                var personal = pie[1].personal

                var pE = {
                    datasets: [{ data:estado.data, backgroundColor:estado.background }],
                    labels: estado.label
                }

                var pP = {
                    datasets: [{ data:personal.data, backgroundColor:personal.background }],
                    labels: personal.label
                }
                var canvasE = document.getElementById('v-estado').getContext('2d')
                var chartE = new Chart(canvasE, {
                    type: 'pie',
                    data: pE,
                    options: {
                        animation: {
                            animateRotate: true,
                            onComplete: function() {
                                labelSlice(this)
                            }
                        },
                        title: {
                            display: true,
                            fontSize: 15,
                            text: 'ESTADO VOUCHERS',
                            position: 'top'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                })

                var canvasP = document.getElementById('v-tipo').getContext('2d')
                var chartP = new Chart(canvasP, {
                    type: 'pie',
                    data: pP,
                    options: {
                        animation: {
                            animateRotate: true,
                            onComplete: function() {
                                labelSlice(this)
                            }
                        },
                        title: {
                            display: true,
                            fontSize: 15,
                            text: 'VOUCHERS SEGUN PERSONAL',
                            position: 'top'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                })
            }

            function setCardInfo(data) {
                $('#v-total').text('VOUCHERS EN TOTAL '+data.voucher)
                $('#v-monto').text('El monto recaudado entre todos los vouchers recibidos es de s/'+data.monto)
                $('#v-mayor').text('El voucher de mayor valor es de s/'+data.mayor.monto+' del cliente '+data.mayor.cliente)
                $('#v-menor').text('El voucher de menor valor es de s/'+data.menor.monto+' del cliente '+data.menor.cliente)               
                data.banco.forEach(function(item, index) {
                    var html = ''
                    html += '<div class="card text-center bg-light m-2" style="width: 18rem;">'
                    html += '<div class="card-header" style="font-size: 1.25rem !important;">'+item.banco+'</div>'
                    html += '<div class="card-body display-1" style="padding: 0rem;">'+item.cantidad+'</div>'
                    html += '</div>'
                    $('#v-banco').append(html)
                })
            }

            /*
            function setCanvas() {
                var data1 = {
                    datasets: [{
                        data: [10, 11, 12],
                        backgroundColor: [
                                "#6c757d",
                                "#198754",
                                "#dc3545"
                            ]
                    }],
                    labels: [
                        'PENDIENTE',
                        'VALIDO',
                        'NO VALIDO'
                    ]
                }

                var data2 = {
                    datasets: [{
                        data: [30, 23, 10],
                        backgroundColor: [
                                "#fd7e14",
                                "#0d6efd",
                                "#6f42c1"
                            ]
                    }],
                    labels: [
                        'VENDEDOR',
                        'TRANSPORTISTA',
                        'SUPERVISOR'
                    ]
                }

                var ctx = document.getElementById('v-estado').getContext('2d')
                var myChart1 = new Chart(ctx, {
                    type: 'pie',
                    data: data1,
                    options: {
                        animation: {
                            animateRotate: true,
                            onComplete: function() {
                                labelSlice(this)
                            }
                        },
                        title: {
                            display: true,
                            fontSize: 15,
                            text: 'ESTADO VOUCHERS',
                            position: 'top'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                })

                var ctx2 = document.getElementById('v-tipo').getContext('2d')
                var myChart2 = new Chart(ctx2, {
                    type: 'pie',
                    data: data2,
                    options: {
                        animation: {
                            animateRotate: true,
                            onComplete: function() {
                                labelSlice(this)
                            }
                        },
                        title: {
                            display: true,
                            fontSize: 15,
                            text: 'VOUCHERS SEGUN PERSONAL',
                            position: 'top'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                })
            }*/
            
            function setTabla(fecha) {
                var f = ''
                if (fecha) {
                    f = fecha
                }else {
                    f = $('#b-fecha').val()
                }
                $('#tabla').DataTable({
                    destroy: true,
                    responsive: true,
                    scrollCollapse: true,
                    pageLength: 15,
                    lengthMenu: [[15, 35, 50,100,200, -1], [15, 35, 50,100,200, "Todo"]],
                    language: {
                        url: "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
                        decimal: ",",
                        thousands: " ",
                    },
                    ajax: {
                        url: "/voucher/process/getData.php",
                        type: "POST",
                        data: {fecha: f}
                    },
                    columns: [
                        {data:'nomcli'},
                        {data:'monto'},
                        {data:'nombre'},
                        {data:'tipo'},
                        {data:'descripcion'},
                        {data:'fecha'},
                        {orderable:false,
                        render: function(data,time,row) {
                            return '<button type="button" class="btn btn-custom"><i class="fas fa-search"></i>Revisar</button>'
                        }},
                        {data:'empleado'},
                        {data:'id'},
                        {data:'cliente'},
                        {data:'banco'},
                        {data:'movimiento'},
                        {data:'estado'},
                        {data:'usercheck'},
                        {data:'fechacheck'}
                    ],
                    columnDefs: [
                        {targets:1,
                        className: 'text-center'},
                        {targets:3,
                        className: 'text-center'},
                        {targets:4,
                        className: 'text-center'},
                        {targets:5,
                        className: 'text-center'},
                        {targets:6,
                        className: 'text-center'},
                        {targets:7,
                        visible: false,
                        searchable: false},
                        {targets:8,
                        visible: false,
                        searchable: false},
                        {targets:9,
                        visible: false,
                        searchable: false},
                        {targets:10,
                        visible: false,
                        searchable: false},
                        {targets:11,
                        visible: false,
                        searchable: false},
                        {targets:12,
                        visible: false,
                        searchable: false},
                        {targets:13,
                        visible: false,
                        searchable: false},
                        {targets:14,
                        visible: false,
                        searchable: false}
                    ],
                    rowCallback: function(row, data, index) {
                            switch(data.tipo) {
                                case 'V':
                                $(row).find('td:eq(3)').text('VENDEDOR')
                                break;
                                case 'T':
                                $(row).find('td:eq(3)').text('TRANSPORTE')
                                break;
                                case 'S':
                                $(row).find('td:eq(3)').text('SUPERVISOR')
                                break;
                            }
                            switch(data.estado) {
                                case 0:
                                $(row).find('td:eq(4)').css({'color':"blue","font-weight":"bold"})
                                break;
                                case 1:
                                $(row).find('td:eq(4)').css({'color':"green","font-weight":"bold"})
                                break;
                                case 2:
                                $(row).find('td:eq(4)').css({'color':"red","font-weight":"bold"})
                                break;
                            }
                            $(row).find('td:eq(6)').on('click','button',function(){
                                setData(data,1)
                            })
                    },
                    initComplete: function(settings,json) {
                        if ( ! $('#tabla').DataTable().data().any() ) {
                            $('#empty').removeClass('d-none')
                            $('#divtab').addClass('d-none')
                        }else {
                            $('#empty').addClass('d-none')
                            $('#divtab').removeClass('d-none')
                        }
                    }
                })
            }

            function setView() {
                var view = window.location.href
                var opt = view.split('vp')[1]
                var subs = opt.substring(1, 2)

                if(subs == '0') {
                    $('#v-info').removeClass('d-none')
                    $('#v-lista').addClass('d-none')
                    setReporte()
                }else if (subs == '1') {
                    $('#v-info').addClass('d-none')
                    $('#v-lista').removeClass('d-none')
                    setTabla()
                }
            }

            function labelSlice(pie) {
                var ctx = pie.chart.ctx
                ctx.font = "bold 17px 'Helvetica Neue', Helvetica, Arial, sans-serif"
                ctx.textAlign = 'center'
                ctx.textBaseline = 'bottom'

                pie.data.datasets.forEach(function (dataset) {

                    for (var i = 0; i < dataset.data.length; i++) {
                        var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                        total = dataset._meta[Object.keys(dataset._meta)[0]].total,
                        mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
                        start_angle = model.startAngle,
                        end_angle = model.endAngle,
                        mid_angle = start_angle + (end_angle - start_angle)/2

                        var x = mid_radius * Math.cos(mid_angle)
                        var y = mid_radius * Math.sin(mid_angle)

                        ctx.fillStyle = '#fff'

                        var percent = String(Math.round(dataset.data[i]/total*100)) + "%"
                        var isHiddenMeta = dataset._meta[Object.keys(dataset._meta)[0]].hidden   
                        //Don't Display If Legend is hide or value is 0
                        if(dataset.data[i] != 0 && !isHiddenMeta) {
                            ctx.fillText(dataset.data[i], model.x + x, model.y + y)
                            // Display percent in another line, line break doesn't work for fillText
                            ctx.fillText(percent, model.x + x, model.y + y + 15)
                        }
                    }
                })          
            }

            /*function formatDate() {
                var f = ''
                var y = $('#year').val()
                var m = $('#month').val()
                var d = $('#day').val()
                if (y == 0 && m == 0 && d == 0) {
                    Swal.fire('Advertencia','Debe ingresar algun parametro de fecha','warning')
                }else {

                    if (y != 0) {
                        f = y+'-%'
                    }
                    if (m != 0 && m.length == 1) {
                        m = '0'+m
                        (f != '')? f = y+'-'+m+'%' : f = '_____'+m+'-%'
                    }
                    if (d != 0 && d.length == 1) {
                        d = '0'+d
                        if(f != '') {

                            switch(f.length) {
                                case 6:
                                f = y+'-%'+d
                                break;
                                case 8:
                                f = y+'-'+m+'-'+d
                                break;
                                case 9:
                                f = '%-'+m+'-'+d
                                break;
                            }
                        }else {
                            f = '%-'+d
                        }
                    }
                }
            }*/

            /*function dateInput() {
                var dt = new Date()
                if (dt.getMonth().toString().length == 1) {
                    m = '0'+dt.getMonth()
                }else {
                    m = dt.getMonth()
                }
                if (dt.getDate().toString().length == 1) {
                    d = '0'+dt.getDate()
                }else {
                    d = dt.getDate()
                }
                return dt.getFullYear()+'-'+m+'-'+d
            }*/

            function addVouchers() {
                Swal.fire({title:'Cargando datos de vouchers'})
                Swal.showLoading()
                $.post('/voucher/process/addingVouchers.php', function(e) {
                    if (e == 'success') {
                        Swal.fire('Completo','Los nuevos vouchers se agregaron correctamente','success')
                        if (!$('#v-lista').hasClass('d-none')) {
                            setTabla()
                        }
                        if (!$('#v-info').hasClass('d-none')) {
                            setReporte()
                        }
                    }else if (e == 'error') {
                        Swal.fire('Error','Ocurrio un error al momento de guardar los vouchers','error')
                    }else {
                        Swal.fire('Advertencia','No tiene vouchers nuevos','warning')
                    }
                })
            }

            /*function setYear() {
                var d = new Date()
                var n = d.getFullYear()
                var yearlist = [n,n-1,n-2,n-3,n-4]
                yearlist.forEach(item => $('#year').append('<option value="'+item+'">'+item+'</option>'))
            }

            function setDay() {
                var daylist = []
                for(var i = 1; i < 32; i++) {
                    daylist.push(i)
                }
                daylist.forEach(item => $('#day').append('<option value="'+item+'">'+item+'</option>'))
            }*/

            function setToday() {
                $.datepicker.regional['es'] = {
                    closeText: 'Cerrar',
                    prevText: '< Ant',
                    nextText: 'Sig >',
                    currentText: 'Hoy',
                    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                    dayNamesMin: ['D','L','M','X','J','V','S'],
                    weekHeader: 'Sm',
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    changeMonth: true,
                    changeYear: true
                }
                $.datepicker.setDefaults($.datepicker.regional['es'])

                $('#b-fecha').datepicker().datepicker('setDate', 'today')
                $('#fecha-reporte').datepicker().datepicker('setDate', 'today')
            }

            function setUser() {
                $.post('/voucher/process/getUserData.php',function(e) {
                    var bd = e.split('-')[1]
                    var user = e.split('-')[2]
                    if (user.substring(0,4) == 'caja') {
                        $('#user').text('Caja')
                    }else {
                        $('#user').text('Contabilidad')
                    }
                    if (bd == 1) {
                        $('#empresa').css('color','blue')
                        $('#empresa').text('Oriunda')
                    }else {
                        $('#empresa').css('color','red')
                        $('#empresa').text('Terranorte')
                    }                    
                })
            }

            function validateVoucher() {
                var estado = $('#s-estado').val()
                var usuario = $('#user').text()
                var empleado = $('#i-empleado').val().split('-')[0].trim()
                var fecha = $('#i-fecha').val()
                var cliente = $('#i-cliente').val().split('-')[0].trim()
                var id = $('#i-id').val()
                $.post('/voucher/process/updateVoucher.php',{estado:estado,usuario:usuario,empleado:empleado,fecha:fecha,cliente:cliente,id:id},function(e){
                    if (e == 'success') {
                        setData(null,0)
                        Swal.fire('Correcto','Se guardo el estado del voucher','success')
                        setTabla()
                    }else {
                        Swal.fire('Error','Ocurrio un error al guardar los cambios','error')
                    }
                })
            }

            function setData(data,opt) {
                if (opt == 0) {
                    $('#datos').addClass('d-none')
                    $('#i-cliente').val('')
                    $('#i-empleado').val('')
                    $('#i-fecha').val('')
                    $('#i-tipo').val('')
                    $('#i-banco').val('')
                    $('#i-monto').val('')
                    $('#i-operacion').val('')
                    $('#div-estado').html()
                    $('#s-estado').val(0)
                    $('#i-id').val('')
                    $('#u-data').addClass('d-none')
                    $('#i-usercheck').text('')
                    $('#i-fechacheck').text('')
                    $('#img').attr('src', '')
                    $('#img-zoom').attr('src', '')
                }else {
                    $('#datos').removeClass('d-none')
                    $('#i-cliente').val(data.cliente+' - '+data.nomcli)
                    $('#i-empleado').val(data.empleado+' - '+data.nombre)
                    $('#i-fecha').val(data.fecha)
                    if (data.tipo == 'V') {
                        $('#i-tipo').val('VENDEDOR')
                    }else if (data.tipo == 'T') {
                        $('#i-tipo').val('TRANSPORTE')
                    }else if (data.tipo == 'S') {
                        $('#i-tipo').val('SUPERVISOR')
                    }
                    $('#i-banco').val(data.banco)
                    $('#i-monto').val(data.monto)
                    $('#i-operacion').val(data.movimiento)
                    if (data.estado == 0) {
                        $('#div-estado').html()
                        $('#div-estado').html('<span class="badge bg-secondary" style="font-size: 1rem;">'+data.descripcion+'</span>')
                    }else if (data.estado == 1) {
                        $('#div-estado').html()
                        $('#div-estado').html('<span class="badge bg-success" style="font-size: 1rem;">'+data.descripcion+'</span>')
                    }else {
                        $('#div-estado').html()
                        $('#div-estado').html('<span class="badge bg-danger" style="font-size: 1rem;">'+data.descripcion+'</span>')
                    }
                    $('#s-estado').val(data.estado)
                    $('#i-id').val(data.id)
                    if (data.usercheck == null) {
                        $('#u-data').addClass('d-none')
                        $('#i-usercheck').text('')
                        $('#i-fechacheck').text('')
                    }else {
                        $('#u-data').removeClass('d-none')
                        $('#i-usercheck').text(data.usercheck)
                        $('#i-fechacheck').text(data.fechacheck)
                    }
                    var year = data.fecha.split('-')[0]
                    var bd = ($('#empresa').text() == 'Oriunda')? 1 : 2
                    var path = 'http://200.110.40.58:3000/pedimap/archive/images/recibos/'+year+'/'+data.fecha.replace(/\-/g,'')+'-'+bd+'-1-'+data.tipo+'-'+data.empleado+'-'+data.cliente+'-'+data.id+'.png'
                    $('#img').attr('src', path)
                    $('#img-zoom').attr('src', path)
                }
            }
        </script>
    </body>
    <style type="text/css">
        body {
            font-family: 'Montserrat', sans-serif;
            background: url('/voucher/resources/images/bck-img.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            /*background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#ffcc99)) fixed;*/
        }
        .border-4 {
            border-width: 4px !important;
        }
        .user-div {
            width: 100%;
            display: flex;
            justify-content: center;
            background-color: darkmagenta !important;
            font-size: 16px;
            padding-top: 8px;
            padding-bottom: 8px;
        }
        .edt {
            font-size: 13px;
            text-align: center;
        }
        .edt th {
            border-top-color: #E6E6E6 !important;
            border-bottom-color: #ccc !important;
        }
        .table td, .table th {
            vertical-align: middle !important;
        }
        .table td {
            font-size: 12px;
        }
        .btn-center {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            height: fit-content;
        }
        .btn-custom {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            background-color: #2D6D88;
            border: none;
            color: white;
            font-size: 12px;
        }
        .btn-custom:hover {
            background-color: #91E7F6 !important;
        }
        .form-control[readonly] {
            background-color: #fff;
            pointer-events: none;
        }
        div.d-flex div.form-group {
            margin-left: 5px;
            margin-right: 5px;
        }
        button .btn {
            display: flex !important;
            align-items: center;
            justify-content: inherit;
        }
        .font-s {
            font-size: 14px !important;
        }
        /*IMAGE OVERLAY*/
        .img-overlay {
            position: relative;
        }
        .image {
            opacity: 1;
            display: block;
            width: 300px;/*width: 100%;*/
            height: 300px;/*height: auto;*/
            transition: .5s ease;
            backface-visibility: hidden;
        }
        .middle {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }
        .img-overlay:hover .image {
            opacity: 0.3;
        }
        .img-overlay:hover .middle {
            opacity: 1;
        }
        /**/
        /*.container-edt {
            display: flex;
            flex-direction: column;
        }
        .flexing {
            display: flex;
            align-items: center;
            padding: 1.2rem 2.2rem;
            justify-content: space-between;
        }
        .pad-body {
            padding: 1rem 2.2rem;
        }
        .shadow-one-edge {
            box-shadow: 0 3px 6px -1px #D3D3D3;
        }
        .head-back {
            background-color: #33709E;
            color: white;
        }
        .f40 {
            font-size: 40px;
        }
        .f30 {
            font-size: 30px;
        }
        .f25 {
            font-size: 25px;
        }
        .f20 {
            font-size: 20px;
        }
        .m-lr6 {
            margin: 0px 6px;
        }
        .m-u10 {
            margin: 0 10px 10px 10px;
        }
        .titulo-parent {
            display: contents;
        }
        .cuerpo-parent {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
        }
        .cuerpo-child {
            width: 100%;
            margin: 5px 0;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            background-color: #bcf3f74d;
        }
        .cuerpo-item {
            display: flex;
            align-items: center;
        }
        .pie-parent {
            margin: 5px;
        }
        .pie-parent button {
            margin-right: 16px;
        }
        .pie-parent button:last-child {
            margin-right: 5px;
        }
        .move {
            transform: translate3d(-100px, 37px, 0px) !important;
        }
        .align {
            align-items: center;
        }
        .align-space {
            width: 100%;
            align-items: center;
            justify-content: space-between;
        }
        .title {
            font-size: 20px;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        .btn-edt {
            font-size: 13px;
            border: 2px solid;
            font-weight: bold;
        }
        .over-x {
            overflow-x: hidden;
            font-size: .8rem;
            padding: 5px;
            background-color: white;
        }
        .option {
            margin: 5px 10px;
            padding: 5px 0;
            font-size: 13px;
            display: flex;
            align-items: baseline;
            cursor: pointer;
        }
        .modal-body,.modal-footer {
            padding: 0;
        }
        .fa-calendar {
            color: #1E6AD2;
        }
        .fa-power-off {
            color: #CC3636;
        }
        .dropdown-menu {
            margin: 0;
            padding: 0;
        }
        .opt-text {
            font-size: 15px;
            padding: 0px 5px;
        }
        .btn-custom {
            background-color: #2D6D88;
            border: none;
            color: white;
            font-size: 12px;
        }
        .btn-ellipsis {
            background-color: #33709E;
            border: none;
            color: white;
        }
        .btn-custom:hover {
            background-color: #91E7F6 !important;
        }
        .btn-ellipsis:hover {
            color: white;
        }
        .btn-ellipsis:focus,.btn-custom:focus {
            outline: none;
            box-shadow: none;
        }
        .table td {
            padding: 7px 6px;
            vertical-align: middle;
            border-top: none;
            border-bottom: none;
        }
        .table-bordered {
            border-top: none;
            border-bottom: none;
        }
        .edt {
            font-size: 13px;
            text-align: center;
        }
        .edt th {
            border-top-color: #E6E6E6 !important;
            border-bottom-color: #ccc !important;
        }
        .frame {
            width: 300px;
            height: 300px;
            margin: 10px 0 0 0;
            padding: 3px;
            border: 1px solid black;
            border-radius: 4px;
        }
        .zoom {
            position: relative;
            float: left;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .photo {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            transition: transform .5s ease-out;
        }
        .empty-edt {
            position: relative;
            text-align: center;
        }
        .img-back {
            max-width: 100%;
            height: calc(100vh - 83.38px);
        }
        .centered-bottom {
            position: absolute;
            top: 75%;
            left: 50%;
            font-size: x-large;
            font-weight: bold;
            color: #0084c4;
            transform: translate(-50%, -50%);
        }*/
    </style>
</html>