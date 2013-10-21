<div id="simulacion" class="movelimit" >
    <div class="side-by-side clearfix">
        <label><b>Ubicar un sector en el mapa: </b></label><select tabindex="2" data-placeholder="Sectores..." class="chzn-select" name="sectores-loja"  id="sectores-loja">
            <option value=""></option> 
            <?php foreach ($sectors as $sector): ?>
                <option value = "<?php echo $sector->getId() ?>"><?php echo $sector->getNombre() ?></option>
            <?php endforeach; ?>
        </select >
    </div>
    <div  id="mapa" >
    </div>
</div>
<div id="modal-destino-unidad" class="reveal-modal">
    <h1 id="titulo">Destino de Carrera</h1>
    <p>Seleccione el sector de destino.</p>
    <div class="side-by-side clearfix">
        <select tabindex="2" data-placeholder="Sectores..." class="chzn-select" name="sectores-loja"  id="sectores-destino-unidad">
            <option value=""></option> 
            <?php foreach ($sectors as $sector): ?>
                <option value = "<?php echo $sector->getId() ?>"><?php echo $sector->getNombre() ?></option>
            <?php endforeach; ?>
        </select >    <input type="button" value="Aceptar" class="btn-destino-unidad" id="btn-destino-unidad"/>
    </div>
    <a class="close-reveal-modal">&#215;</a>
</div>
<div id="modal-manejo-unidad" class="reveal-modal">
    <h1 id="titulo">Unidades</h1>
    <p>Seleccione una unidad.</p>
    <div id="panel-manejo-taxis">
    </div>
    <div class="side-by-side clearfix">
        <select tabindex="2" data-placeholder="Sectores..." class="chzn-select" name="sectores-loja"  id="sectores-loja-unidad">
            <option value=""></option> 
            <?php foreach ($sectors as $sector): ?>
                <option value = "<?php echo $sector->getId() ?>"><?php echo $sector->getNombre() ?></option>
            <?php endforeach; ?>
        </select >    <input type="button" value="Ubicar" id="btn-ubicar-unidad"/>
    </div>
    <a class="close-reveal-modal">&#215;</a>
</div>
<div id="modal-manejar-unidad" class="reveal-modal">
    <h1 id="titulo">Cambios</h1>
    <a class="close-reveal-modal">&#215;</a>
</div>
<div id="modal-asignar-unidad" class="reveal-modal">
    <h1 id="titulo">Asignación de Unidad</h1>
    <p>Seleccione el tiempo de espera.</p>
    Tiempo:
    <select name="tiempo"  id="asignar-unidad-tiempo"/>
    <?php
    for ($i = 0; $i < 15; $i++):
        ?>
        <option value="<?php echo $i ?>"><?php echo $i; ?></option>
    <?php endfor; ?> </select >
<p>Seleccione la unidad para asignar a la carrera</p>
<p id="titulo-cernanos">Las unidades mas cercanas al sector son:</p>
<div id="panel-taxis-cercanos"></div>
<p>Todas las unidades</p>
<div id="panel-taxis">
</div>
</div>
<div id="modal-ingresar-carrera" class="reveal-modal">
    <h1 id="titulo" >Ingresar Carrera</h1>
    <div >
        <table>
            <tbody>

                <tr >
                    <th><label>Cliente:<b style="color: red;">*</b></label></th>
                    <td><input type="text" name="cliente" value="" id="ingreso-cliente" /></td>
                    <th><label>Teléfono:<b style="color: red;">*</b></label></th>
                    <td ><input type="text" name="telefono" value="" id="ingreso-telefono" /></td>
                </tr>
                <tr>
                    <th><label>Barrio:<b style="color: red;">*</b></label></th>
<!--                    <td><input type="text" name="barrio" value="" id="ingreso-barrio" /></td>-->
                    <td><div class="side-by-side clearfix">
                            <select tabindex="2" data-placeholder="Sectores..." class="chzn-select" name="barrio"  id="ingreso-barrio">
                                <option value=""></option> 
                                <?php foreach ($sectors as $sector): ?>
                                    <option value = "<?php echo $sector->getId() ?>"><?php echo $sector->getNombre() ?></option>
                                <?php endforeach; ?>
                            </select >
                        </div></td>

                    <th><label>Principal:<b style="color: red;">*</b></label></th>
                    <td><input type="text" name="calle1" value="" id="ingreso-calle1" /></td>
                </tr>

                <tr >
                    <th><label>N°- casa:</label></th>
                    <td><input type="text" name="numcasaS" value="" id="ingreso-numCasa" /></td>
                    <th><label>Secundaria:</label></th>
                    <td><input type="text" name="calle2S" value="" id="ingreso-calle2" /></td>
                </tr>

                <tr>
                    <th rowspan="1"><label>Referencia:</label></th>
                    <td colspan="3" rowspan="3"><textarea  cols="52" rows="3" name="observacionS"id="ingreso-referencia" ></textarea></td>
                </tr>
            </tbody>
        </table>

    </div>
    <hr/>
    <div > 
        <table>
            <tbody>
                <tr>
                    <th><label>Unidad:<b style="color: red;">*</b></label></th>
                    <td>
                        <select name="unidad"  id="ingreso-unidad"></select >  
                    </td>
                    <th><label>Conductor:</label></th>
                    <td id="ingreso-propietario"></td>
                </tr>
                <tr>
                    <th><label>Placa:</label></th>
                    <td id="ingreso-placa"></td>
                    <th><label>Modelo:</label></th>
                    <td id="ingreso-modelo"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr/>
    <table>
        <tr>
            <th rowspan="1">Tiempo:</th>
            <td rowspan="1"><select name="tiempo"  id="ingreso-tiempo"/>
                <?php
                for ($i = 0; $i < 15; $i++):
                    ?>
            <option value="<?php echo $i ?>"><?php echo $i; ?></option>
        <?php endfor; ?> </select ></td>
        <th rowspan="1">Detalle:</th>
        <td rowspan="3"><textarea  id="ingreso-detalle" cols="40" rows="3" name="detalle" ></textarea></td>
        </tr>
    </table>
    <hr />
    <input type="submit" id="ingreso-crear" value="Crear Carrera" />
    <a class="close-reveal-modal">&#215;</a>
</div>
<div id="modal-ingresar-carrera-codigo" class="reveal-modal">
    <h1 id="titulo" >Ingresar Carrera</h1>
    <div >
        <table>
            <tbody>
                <tr >
                    <th><label >Codigo:<b style="color: red;">*</b></label></th>
                    <td><input type="text" id="codigo-codigo" name="codigo" value="" /></td>
                    <th>Cliente:</th>
                    <td id="codigo-cliente"></td>
                </tr>
                <tr>
                    <th><label>Barrio:</label></th>
                    <td id="codigo-barrio"></td>
                    <th><label>Principal:</label></th>
                    <td id="codigo-calle1"></td>
                </tr>
                <tr >
                    <th><label>N°- casa:</label></th>
                    <td id="codigo-numCasa"></td>
                    <th><label>Secundaria:</label></th>
                    <td id="codigo-calle2"></td>
                </tr>
                <tr>
                    <th rowspan="1"><label>Referencia:</label></th>
                    <td id="codigo-referencia" colspan="3" rowspan="3"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr />
    <div > 
        <table>
            <tbody>
                <tr>
                    <th><label>Unidad:<b style="color: red;">*</b></label></th>
                    <td>
                        <select name="unidad"  id="codigo-unidad"></select >  
                    </td>
                    <th><label>Conductor:</label></th>
                    <td id="codigo-propietario"></td>
                </tr>
                <tr>
                    <th><label>Placa:</label></th>
                    <td id="codigo-placa"></td>
                    <th><label>Modelo:</label></th>
                    <td id="codigo-modelo"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr />
    <table>
        <tr>
            <th rowspan="1">Tiempo:</th>
            <td rowspan="1"><select name="tiempo"  id="codigo-tiempo"/>
                <?php
                for ($i = 0; $i < 15; $i++):
                    ?>
            <option value="<?php echo $i ?>"><?php echo $i; ?></option>
        <?php endfor; ?> </select ></td>
        <th rowspan="1">Detalle:</th>
        <td rowspan="3"><textarea  id="codigo-detalle" cols="40" rows="3" name="detalle" ></textarea></td>
        </tr>
    </table>
    <hr />
    <input type="submit" id="codigo-crear" value="Crear Carrera" />
    <a class="close-reveal-modal">&#215;</a>
</div>
<div style="display: none;"class="divflotante" id="panel-opciones">

    <div class="panel-opciones-botones"><input type="button" title="ASIGNAR UNIDAD" class="boton-opciones" id="btn-asignar-unidad" value="Asignar Unidad"/>
        <br /><b>Asignar Unidad</b>
    </div>
    <div class="panel-opciones-botones"><input type="button" title="INGRESAR CARRERA" class="boton-opciones" id="btn-ingresar-carrera" value="Ingresar carrera"/>
        <br /><b>Ingresar carrera</b></div>
    <div class="panel-opciones-botones"><input type="button" title="INGRESAR CARRERA (CÓDIGO)" class="boton-opciones" id="btn-ingresar-carrera-codigo" value="Ingresar carrera (Código)"/>
        <br /><b>Ingresar carrera <br />(Código)</b></div>
    <div class="panel-opciones-botones"><input type="button" title="UNIDADES" class="boton-opciones" id="btn-manejo-unidades" value="Ubicar Unidades"/>
        <br /><b>Ubicar Unidades</b></div>
</div>
<div style="display: none;"class="divflotante" id="panel-unidades">
    <?php foreach ($unidades as $unidad): ?>
        <button  class="taxi-panel btn-taxi" id="<?php echo $unidad->getNumero() ?>"><span><?php echo $unidad->getNumero() ?></span></button>
    <?php endforeach; ?>
</div>
<div style="display: none;"class="divflotante" id="panel-notificaciones"></div>
<div style="display: none;"class="divflotante" id="panel-solicitudes"></div>
<div style="display: none;"class="divflotante" id="panel-reservaciones">
</div>
<div style="display: none;"class="divflotante" id="panel-alertas"></div>
