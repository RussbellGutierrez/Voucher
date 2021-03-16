<div class="d-flex flex-row align-items-center mt-2">
    <div class="form-group">
        <div>Fecha</div>
        <div class="input-group">
            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
            <input id="b-fecha" name="b-fecha" type='text' class="form-control" autocomplete="off"/>
        </div>
    </div>
    <button id="buscar" type="button" class="btn btn-success ml-4 mr-4"><i class="mr-3 fas fa-search"></i>BUSCAR</button>
</div>
<hr>
<div id="empty" class="d-none">
    <img style="width: 100%;" src="/voucher/resources/images/empty.png">
</div>
<div id="datos" class="d-none">
    <div class="border-left border-danger border-4 bg-light d-flex mt-3 mb-3 align-items-center">
        <div class="flex-grow-1 p-3">
            <div id="u-data" class="mb-3">
                <div class="badge bg-primary user-div">Voucher revisado por <div id="i-usercheck" class="ml-2 mr-2"></div> el <div id="i-fechacheck" class="ml-2 mr-2"></div></div>
            </div>
            <div class="d-flex mb-3">
                <div class="d-flex flex-fill">
                    <div class="date-font mr-3">Recibido:</div><div id="d-fecha" class="date-font"></div>
                </div>
                <div id="div-estado" class="d-flex align-items-center flex-fill"></div>
            </div>
            <div class="d-flex">
                <div class="form-group flex-grow-1">
                    <div>Empleado</div>
                    <div class="input-group mb-3">
                        <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                        <input id="i-empleado" class="form-control font-s" readonly/>
                    </div>
                </div>
                <div class="form-group" style="width: 180px;">
                    <div>Tipo</div>
                    <div class="input-group mb-3">
                        <div class="input-group-text"><i class="fas fa-user-circle"></i></div>
                        <input id="i-tipo" class="form-control font-s" value="TRANSPORTE" readonly/>
                    </div>
                </div>
            </div>
            <div class="d-flex">
                <div class="form-group flex-grow-1">
                    <div>Cliente</div>
                    <div class="input-group mb-3">
                        <div class="input-group-text"><i class="fas fa-shopping-basket"></i></div>
                        <input id="i-cliente" class="form-control font-s" readonly/>
                    </div>
                </div>
            </div>
            <div class="d-flex">
                <div class="form-group">
                    <div>Banco</div>
                    <div class="input-group mb-3">
                        <div class="input-group-text"><i class="fas fa-university"></i></div>
                        <input id="i-banco" class="form-control font-s"/>
                    </div>
                </div>
                <div class="form-group">
                    <div>Operacion</div>
                    <div class="input-group mb-3">
                        <div class="input-group-text"><i class="fas fa-hashtag"></i></div>
                        <input id="i-operacion" class="form-control font-s"/>
                    </div>
                </div>
                <div class="form-group">
                    <div>Monto</div>
                    <div class="input-group mb-3">
                        <div class="input-group-text"><i class="fas fa-piggy-bank"></i></div>
                        <input id="i-monto" class="form-control font-s"/>
                    </div>
                </div>
            </div>
            <div class="d-flex">
                <div class="form-group input-flex">
                    <div>Estado</div>
                    <div class="input-group mb-3">
                        <div class="input-group-text"><i class="fas fa-cubes"></i></div>
                        <select id="s-estado" class="form-select font-s">
                            <option value="1">CORRECTO</option>
                            <option value="2">OBSERVADO</option>
                        </select>
                    </div>
                </div>
                <div id="d-obs" class="form-group flex-grow-1">
                    <div>Observacion</div>
                    <div class="input-group mb-3">
                        <div class="input-group-text"><i class="fas fa-comment"></i></div>
                        <input id="i-observacion" class="form-control font-s"/>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <input id="i-id" hidden readonly/>
                <div class="d-flex flex-row">
                    <button id="cancelar" type="button" class="btn btn-secondary m-3 btn-center"><i class="mr-2 fas fa-window-close"></i> CANCELAR</button>
                    <button id="guardar" type="button" class="btn btn-danger m-3 btn-center"><i class="mr-2 fas fa-save"></i> GUARDAR</button>
                </div>
            </div>
        </div>
        <div class="p-3">
            <div class="img-overlay">
                <img id="img" src="" class="img-thumbnail image" style="max-width: none;">
                <div class="middle">
                    <button id="btn-img" type="button" class="btn btn-success rounded-circle" style="padding: 20px;"><i class="fas fa-search-plus fa-3x"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="divtab" class="d-none">
    <table id="tabla" class="table table-striped table-bordered" style="width: 100%;">
        <thead>
            <tr class="edt">
                <th style="width: 3%">Codigo</th>
                <th style="width: 25%">Cliente</th>
                <th style="width: 5%">Monto</th>
                <th style="width: 27%">Usuario</th>
                <th style="width: 10%">Tipo</th>
                <th style="width: 10%">Estado</th>
                <th style="width: 10%">Fecha</th>
                <th style="width: 10%"></th>
                <th style="width: 0%">Empleado</th>
                <th style="width: 0%">Id</th>
                <th style="width: 0%">Banco</th>
                <th style="width: 0%">Movimiento</th>
                <th style="width: 0%">Estado</th>
                <th style="width: 0%">Usercheck</th>
                <th style="width: 0%">Fechacheck</th>
                <th style="width: 0%">Observacion</th>
            </tr>
        </thead>
    </table>
</div>