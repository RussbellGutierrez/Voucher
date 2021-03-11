<div class="d-flex flex-row align-items-center mt-2">
    <div class="form-group">
        <div>Fecha</div>
        <div class="input-group">
            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
            <input id="fecha-reporte" name="fecha-reporte" type='text' class="form-control" autocomplete="off"/>
        </div>
    </div>
    <button id="generar" type="button" class="btn btn-warning ml-4 mr-4"><i class="mr-3 fas fa-clipboard"></i>GENERAR REPORTE</button>
</div>
<hr>
<div id="r-empty" class="d-none">
    <img style="width: 100%;" src="/voucher/resources/images/report-empty.png">
</div>
<div id="reporte" class="d-none">
    <div class="d-flex justify-content-center">
        <div id="v-total" class="display-6">VOUCHERS EN TOTAL</div>
    </div>
    <div class="d-flex mt-3 mb-3">
        <div style="width: 50%; position: relative;">
            <canvas id="v-estado" style="width: 100%; height: 400px;"></canvas>
        </div>
        <div style="width: 50%; position: relative;">
            <canvas id="v-tipo" style="width: 100%; height: 400px;"></canvas>
        </div>
    </div>
    <hr>
    <div class="d-flex justify-content-between">
        <div class="card border-primary m-3" style="width: 30%;">
            <div class="row g-0">
                <div class="d-flex align-items-center justify-content-center col-md-4" style="color: #ffc107;">
                    <i class="fas fa-coins fa-6x"></i>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">MONTO TOTAL</h5>
                        <p id="v-monto" class="card-text text-dark"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-success m-3" style="width: 30%;">
            <div class="row g-0">
                <div class="d-flex align-items-center justify-content-center col-md-4" style="color: #198754;">
                    <i class="fas fa-arrow-circle-up fa-6x"></i>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">MONTO MAYOR</h5>
                        <p id="v-mayor" class="card-text text-dark"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-danger m-3" style="width: 30%;">
            <div class="row g-0">
                <div class="d-flex align-items-center justify-content-center col-md-4" style="color: #dc3545;">
                    <i class="fas fa-arrow-circle-down fa-6x"></i>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">MONTO MENOR</h5>
                        <p id="v-menor" class="card-text text-dark"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="d-flex flex-column mt-3 mb-3">
        <div class="d-flex justify-content-start">
            <div style="font-size: 1.75rem !important;">VOUCHERS POR BANCO</div>
        </div>
        <div id="v-banco" class="d-flex justify-content-center"></div>
    </div>
</div>