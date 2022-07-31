<input type="hidden" id="id" value="<?php echo $id;?>" >
<input type="hidden" id="origen" value="<?php echo $origen;?>" >
<input type="hidden" id="usuario" value="<?php echo $usuario;?>" >
<input type="hidden" id="userId" value="<?php echo $userId;?>" >
<div id="ventanaCorreccion">
    <div id="titulo">
        Editar Orden Correccion
    </div>
    <div>
        <form id="form">
            
            
            <table>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">Operador:</td>
                    <td><input type="text" id="operador" style="width: 250px"></td>
                </tr>
<!--                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">codBoleto:</td>
                    <td><div id="codBoleto" ></div></td>
                </tr>-->
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">numBoleto:</td>
                    <td><div id="numBoleto" ></div></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">fechaConcertacion:</td>
                    <td><div id="fechaConcertacion" ></div></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">fechaLiquidacion:</td>
                    <td><div id="fechaLiquidacion" ></div></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">tpOperacionBurs:</td>
                    <td><input type="text" id="tpOperacionBurs" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">Número Comitente:</td>
                    <td><div id="numComitente" ></div></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">Comitente:</td>
                    <td><input type="text" id="comitente" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">Administrador:</td>
                    <td><input type="text" id="administrador" style="width: 250px"></td>
                </tr>
                
               
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">instrumentoAbrev:</td>
                    <td><input type="text" id="instrumentoAbrev" style="width: 250px"></td>
                </tr>
                
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">Moneda:</td>
                    <td><input type="text" id="moneda" style="width: 250px"></td>
                </tr>
                
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">precio:</td>
                    <td><div id="precio" ></div></td>
                </tr>
                
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">cantidad:</td>
                    <td><div id="cantidad" ></div></td>
                </tr>
                <tr>
                    <td  style="padding-left: 10px; padding-right: 10px; padding-bottom: 30px;">arancel:</td>
                    <td><div  id="arancel" ></div></td>
                </tr>

                <tr>
                    <td class="esAnulado" colspan="2" style="padding-right: 10px; padding-bottom: 10px"> <font color="#ff0000">Atención,el boleto está anulado.</font></td>
                    <td class="esAnulado" ></td>
                </tr>
                
                
                <tr>
                <td class="border1" style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px; border-bottom-style: dotted;"><p colspan="2"></p></td>
                </tr>
                
                <tr>
                    <td colspan="2" align="center" style="padding-left: 10px; padding-right: 10px; padding-top: 20px; padding-bottom: 20px">Correcciones:</td>
                </tr>
                
                <tr>
                    <td  style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">Modificará Cantidad?:</td>
                    <td><div id="esCantidad"></div></td>
                </tr>

                <tr>
                    <td class="esCantidad" style="padding-left: 10px; padding-right: 10px; padding-bottom: 25px">cantidadCorregido:</td>
                    <td class="esCantidad"><input type="text" id="cantidadCorregido" style="width: 250px"></div></td>
                </tr>
                
                <tr>
                    <td  style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">Modificará Arancel?:</td>
                    <td><div id="esArancel"></div></td>
                </tr>
                
                <tr>
                    <td class="esArancel" style="padding-left: 10px; padding-right: 10px; padding-bottom: 25px; ">Arancel:</td>
                    <td class="esArancel"><input type="text" id="arancelCorregido" style="width: 250px"></div></td>
                </tr>
                                
                <tr>
                    <td  style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">Modificará Comitente?:</td>
                    <td><div id="esComitente"></div></td>
                </tr>
                
                <tr>
                    <td class="esComitente" style="padding-left: 10px; padding-right: 10px; padding-bottom: 25px">Comitente:</td>
                    <td class="esComitente"><div id="numComitenteCorregido" ></div></td>
                </tr>
                
                <tr>
                    <td class="esComitente" style="padding-left: 10px; padding-right: 10px; padding-bottom: 25px"></td>
                    <td class="esComitente" style="padding-bottom: 25px"><input type="text" id="comitenteCorregido" style="width: 250px"></td>
                </tr>
                
                <tr>
                    <td class="esPrecio" style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">Modificará Precio?:</td>
                    <td class="esPrecio"><div id="esPrecio"></div></td>
                </tr>
                
                <tr>
                    <td class="esPrecioSi" style="padding-left: 10px; padding-right: 10px; padding-bottom: 25px; ">Precio:</td>
                    <td class="esPrecioSi"><div id="precioCorregido"></div></td>
                </tr>
                
                <tr>
                    <td class="esTipoOperacion" style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">Modificará tipo de Operación?:</td>
                    <td class="esTipoOperacion"><div id="esTipoOperacion"></div></td>
                </tr>
                
                <tr>
                    <td class="esTipoOperacionSi" style="padding-left: 10px; padding-right: 10px; padding-bottom: 25px; ">Tipo de Operacion:</td>
                    <td class="esTipoOperacionSi"><input type="text" id="tipoOperacionCorregido" style="width: 250px"></div></td>
                </tr>
                
                <tr>
                    <td class="esEspecie" style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">Modificará la Especie?:</td>
                    <td class="esEspecie"><div id="esEspecie"></div></td>
                </tr>
                
                <tr>
                    <td class="esEspecieSi" style="padding-left: 10px; padding-right: 10px; padding-bottom: 25px; ">Especie:</td>
                    <td class="esEspecieSi"><input type="text" id="especieCorregido" style="width: 250px"></div></td>
                </tr>
                
                
                

                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 25px; padding-top: 25px">observaciones:</td>
                    <td><textarea id="observaciones"></textarea></td>
                </tr>
                
<!--                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 25px">administradorCorregido:</td>
                    <td><input type="text" id="administradorCorregido" style="width: 250px"></td>
                </tr>-->

                
                <tr>
                    <td colspan="2" style="text-align: center; padding-top: 20px">
                        <input type="button" id="aceptarButton" value="Aceptar">
                    </td>
                </tr>
            </table>
        </form>
    </div> 
</div>
<script>
    $(function(){
        var theme = getTheme();
        var formOK = false;
        var comitente = false;
        var comitenteCorregido = false;
        var riesgoComitente = '';
        var plazoCargado = 0;
        var cierre_id = 0;
        var minimos = [];
        var minimo = 0;
        var precio = 0;
        var cantidad = 0;
        var brutoCliente = 0;
        
        var numeroComitente = 0;
        
        var p = 0;
        var pc = 0;
        
        var pm = 0;
        var pcm = 0;

        var boletoAnulado = false;
       
        var rUsuario = '';
       
        $("#ventanaCorreccion").jqxWindow({showCollapseButton: false, height: 1500, width: 500, theme: theme, resizable: false, keyboardCloseKey: -1, maxHeight: 1500});
        
        
        $(".esAnulado").hide();
        
        $(".esCantidad").hide();
        $(".esArancel").hide();
        $(".esComitente").hide();
        
        $(".esPrecio").hide();
        $(".esTipoOperacion").hide();
        $(".esEspecie").hide();
        
        $(".esPrecioSi").hide();
        $(".esTipoOperacionSi").hide();
        $(".esEspecieSi").hide();
        
        
        
        $("#operador").jqxInput({ width: '300px', height: '25px', theme: theme});
//        $("#codBoleto").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 10, groupSeparator: ' ', max: 9999999999, theme: theme});
        $("#numBoleto").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 10, groupSeparator: ' ', max: 9999999999, theme: theme});
        $("#fechaConcertacion").jqxDateTimeInput({ formatString: "yyyy-MM-dd", showTimeButton: true, width: '250px', height: '25px', theme: theme, disabled: true});
        $("#fechaLiquidacion").jqxDateTimeInput({ formatString: "yyyy-MM-dd", showTimeButton: true, width: '250px', height: '25px', theme: theme, disabled: true });
        $("#tpOperacionBurs").jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999, disabled: true});
        $("#comitente").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme, disabled: true});
        $("#administrador").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme, disabled: true});

        
        $("#administrador").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme, disabled: true});

        $("#instrumentoAbrev").jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true}); 
        $("#moneda").jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true}); 
        $("#precio").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 4, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme, disabled: true});
        $("#cantidad").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme, disabled: true});
        $("#arancel").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme, disabled: true});

        $("#numComitenteCorregido").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 0, digits: 14, groupSeparator: ' ', max: 999999999, theme: theme});
        $("#comitenteCorregido").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme, disabled: true});
//        $("#administradorCorregido").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme, disabled: true});


        $("#cantidadCorregido").jqxInput({ width: '300px', height: '25px', theme: theme}); 
        $("#arancelCorregido").jqxInput({ width: '300px', height: '25px', theme: theme}); 
        $("#observaciones").jqxTextArea({ height: 90, width: 300, theme: theme});
        
        $("#precioCorregido").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 2, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme});
        $("#tipoOperacionCorregido").jqxInput({ width: '300px', height: '25px', theme: theme});
        $("#especieCorregido").jqxInput({ width: '300px', height: '25px', theme: theme});


        
        
//        $("#autorizadores").jqxInput({ width: '300px', height: '25px', theme: theme}); 
//        $("#caja").jqxInput({ width: '300px', height: '25px', theme: theme}); 
//        $("#visual").jqxInput({ width: '300px', height: '25px', theme: theme}); 
//        $("#control").jqxInput({ width: '300px', height: '25px', theme: theme});
//        $("#fechaActualizacion").jqxDateTimeInput({ formatString: "yyyy-MM-dd", showTimeButton: true, width: '250px', height: '25px', theme: theme });

        var esCantidad = [
            { value: 'N', label: 'NO'},
            { value: 'S', label: 'SI'}
        ];
        $("#esCantidad").jqxDropDownList({ width: '300px', height: '25px', source: esCantidad, theme: theme, placeHolder: ''});
        
        var esArancel = [
            { value: 'N', label: 'NO'},
            { value: 'S', label: 'SI'}
        ];
        $("#esArancel").jqxDropDownList({ width: '300px', height: '25px', source: esArancel, theme: theme, placeHolder: ''});

        
        var esComitente = [
            { value: 'N', label: 'NO'},
            { value: 'S', label: 'SI'}
        ];
        $("#esComitente").jqxDropDownList({ width: '300px', height: '25px', source: esComitente, theme: theme, placeHolder: ''});
        
        
        
        
        
        
        
        var esPrecio = [
            { value: 'N', label: 'NO'},
            { value: 'S', label: 'SI'}
        ];
        $("#esPrecio").jqxDropDownList({ width: '300px', height: '25px', source: esPrecio, theme: theme, placeHolder: ''});
        
        var esTipoOperacion = [
            { value: 'N', label: 'NO'},
            { value: 'S', label: 'SI'}
        ];
        $("#esTipoOperacion").jqxDropDownList({ width: '300px', height: '25px', source: esTipoOperacion, theme: theme, placeHolder: ''});
        
        var esEspecie = [
            { value: 'N', label: 'NO'},
            { value: 'S', label: 'SI'}
        ];
        $("#esEspecie").jqxDropDownList({ width: '300px', height: '25px', source: esEspecie, theme: theme, placeHolder: ''});
        
        
        
        
        $('#esCantidad').on('change', function (event) {            
            if($('#esCantidad').val() == 'S'){
                $(".esCantidad").show();
            }else{
                $(".esCantidad").hide();
            }
        });
         
        $('#esArancel').on('change', function (event) {
            if($('#esArancel').val() == 'S'){
                $(".esArancel").show();
            }else{
                $(".esArancel").hide();
            }
        }); 
        
        $('#esComitente').on('change', function (event) {
            if($('#esComitente').val() == 'S'){
                $(".esComitente").show();
            }else{
                $(".esComitente").hide();
            }
        }); 
         
         
         
         
         
        $('#esPrecio').on('change', function (event) {            
            if($('#esPrecio').val() == 'S'){
                $(".esPrecioSi").show();
            }else{
                $(".esPrecioSi").hide();
            }
        }); 
        
        $('#esTipoOperacion').on('change', function (event) {            
            if($('#esTipoOperacion').val() == 'S'){
                $(".esTipoOperacionSi").show();
            }else{
                $(".esTipoOperacionSi").hide();
            }
        });
        
        $('#esEspecie').on('change', function (event) {            
            if($('#esEspecie').val() == 'S'){
                $(".esEspecieSi").show();
            }else{
                $(".esEspecieSi").hide();
            }
        });
        
        
         
         
         
        jQuery.ajaxSetup({async:false});
        $('#numBoleto').on('change', function (event) {
            var value = $("#numBoleto").val();
            $.post('/esco/getBoleto', {numBoleto: value}, function(pNumBoleto){
//                numBoleto = pNumBoleto;
                if (pNumBoleto){
                    
                    console.log(pNumBoleto);
                    
                    boletoAnulado = false;
                    
                    $(".esAnulado").hide();
                    
                    $("#operador").val(pNumBoleto.operador);
                    $("#numBoleto").val(pNumBoleto.NumBoleto);
                    $("#fechaConcertacion").val(pNumBoleto.FechaConcertacion);
                    $("#fechaLiquidacion").val(pNumBoleto.FechaLiquidacion);
                    $("#tpOperacionBurs").val(pNumBoleto.TPODescripcion);
                    $("#numComitente").val(pNumBoleto.NumComitente);
                    $("#comitente").val(pNumBoleto.ComitenteDescripcion);
                    $("#administrador").val(pNumBoleto.Administrador);
                    $("#instrumentoAbrev").val(pNumBoleto.EspAbreviatura);
                    $("#moneda").val(pNumBoleto.Moneda);
                    $("#precio").val(pNumBoleto.Precio);
                    $("#cantidad").val(pNumBoleto.Cantidad);
                    $("#arancel").val(pNumBoleto.PorcArancel);                    
                    $("#numComitenteCorregido").val(pNumBoleto.numComitenteCorregido);
                    $("#comitenteCorregido").val(pNumBoleto.comitenteCorregido);
                    
                    
                    if(pNumBoleto.tpOperacionBurs == "Compra Exterior" || pNumBoleto.tpOperacionBurs == "Venta Exterior"){
                        $(".esPrecio").show();
                        $(".esTipoOperacion").show();
                        $(".esEspecie").show();
                        
//                        $(".esPrecioSi").hide();
//                        $(".esTipoOperacionSi").hide();
//                        $(".esEspecieSi").hide();
                    }else{
                        $(".esPrecio").hide();
                        $(".esTipoOperacion").hide();
                        $(".esEspecie").hide();
                    }
                    
//                    $("#administradorCorregido").val(pNumBoleto.administradorCorregido);
//                    $("#cantidadCorregido").val(pNumBoleto.cantidadCorregido);
//                    $("#arancelCorregido").val(pNumBoleto.arancelCorregido);
//                    $("#observaciones").val(pNumBoleto.observaciones);
                    
//                    $("#precioCorregido").val(pNumBoleto.precioCorregido);
//                    $("#tipoOperacionCorregido").val(pNumBoleto.tipoOperacionCorregido);
//                    $("#especieCorregido").val(pNumBoleto.especieCorregido);

                    
//                    $("#fechaActualizacion").val(pNumBoleto.fechaActualizacion);
                    
//                    $("#autorizadores").val(pNumBoleto.autorizadores);
//                    if(pNumBoleto.CodPRI == 'RBNC' || pNumBoleto.CodPRI == 'RA' || pNumBoleto.CodPRI == 'CC' || pNumBoleto.CodPRI == 'RANC' || pNumBoleto.CodPRI == 'RMC' || pNumBoleto.CodPRI == 'RAC'){
//                        $("#riesgo").val("SI");
//                    }else{
//                        $("#riesgo").val("NO");
//                    }

                    $('#form').jqxValidator('hideHint', '#numBoleto');
                } else {
                    console.log('No lo encontró');
                    
                    console.log('numBoleto' + value);
                    
                    $.post('/esco/getBoletoAnulado', {numBoleto: value}, function(pNumBoletoAnulado){
//                        comitente = pNumBoletoAnulado;

                        console.log('Acá vá el ' + pNumBoletoAnulado);

                        if (pNumBoletoAnulado){
                            
                            $(".esAnulado").show();
                            
                            boletoAnulado = true;                            
                            
                            console.log('Entra acá');
                            
                            $("#fechaConcertacion").val(pNumBoletoAnulado.FechaConcertacion);
                            $("#fechaLiquidacion").val(pNumBoletoAnulado.FechaLiquidacion);
                            $("#tpOperacionBurs").val(pNumBoletoAnulado.CodTpOperacionBurs);                            

                            $("#numComitente").val(pNumBoletoAnulado.CodComitente);
                            $("#precio").val(pNumBoletoAnulado.Precio);
                            $("#cantidad").val(pNumBoletoAnulado.Cantidad);
                            
                            $('#form').jqxValidator('hideHint', '#numBoleto');
                        } else {
                            
                            boletoAnulado = false;
                            console.log('Por qué no entra acá???');
                            
                            $(".esAnulado").hide();
                            
                            console.log('No lo encontró en ningún lado');
                            
                            $("#fechaConcertacion").val('');
                            $("#fechaLiquidacion").val('');
                            $("#tpOperacionBurs").val('');
                            $("#numComitente").val('');
                            $("#comitente").val('');
                            $("#administrador").val('');
                            $("#instrumentoAbrev").val('');
                            $("#moneda").val('');
                            $("#precio").val('');
                            $("#cantidad").val('');
                            $("#arancel").val('');

                        }
                    }, 'json');
                }
            }, 'json');
        });
        jQuery.ajaxSetup({async:true});




//        $('#numComitente').on('change', function (event) {
//            var value = $("#numComitente").val();
//            $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
//                comitente = pComitente;
//                if (pComitente){
//                    $("#comitente").val(pComitente.comitente);
//                    $("#administrador").val(pComitente.administrador);
//                    $('#form').jqxValidator('hideHint', '#numComitente');
//                } else {
//                    $("#comitente").val('');
//                    $("#administrador").val('');
//                }
//            }, 'json');
//        });
        
        
        $('#numComitenteCorregido').on('change', function (event) {
            var value = $("#numComitenteCorregido").val();
            $.post('/esco/getComitente', {numComitente: value}, function(pComitenteCorregido){
                comitenteCorregido = pComitenteCorregido;
                if (pComitenteCorregido){
                    $("#comitenteCorregido").val(pComitenteCorregido.comitente);
//                    $("#administradorCorregido").val(pComitenteCorregido.administrador);
                    $('#form').jqxValidator('hideHint', '#numComitenteCorregido');
                } else {
                    $("#comitenteCorregido").val('');
                }
            }, 'json');
        });
        


    
        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Orden Correccion');
            $("#operador").val($("#origen").val());
        } else {
            $("#titulo").text('Editar Orden Correccion');
            var datos = {
                id: $("#id").val()
            };
            $.post('/correccion/getOrden', datos, function(data){
                cierre_id = data.cierrecorreccion_id;

                $("#operador").val(data.operador);
                $("#numBoleto").val(data.numBoleto);
                $("#fechaConcertacion").val(data.fechaConcertacion);
                $("#fechaLiquidacion").val(data.fechaLiquidacion);
                $("#tpOperacionBurs").val(data.tpOperacionBurs);
                $("#numComitente").val(data.numComitente);
                $("#comitente").val(data.comitente);
                $("#administrador").val(data.administrador);
                $("#instrumentoAbrev").val(data.instrumentoAbrev);
                $("#moneda").val(data.moneda);
                $("#precio").val(data.precio);
                $("#cantidad").val(data.cantidad);
                $("#arancel").val(data.arancel);
                
                $("#numComitenteCorregido").val(data.numComitenteCorregido);
                $("#cantidadCorregido").val(data.cantidadCorregido);
                $("#arancelCorregido").val(data.arancelCorregido);
                $("#observaciones").val(data.observaciones);
                $("#precioCorregido").val(data.precioCorregido);
                $("#tipoOperacionCorregido").val(data.tipoOperacionCorregido);
                $("#especieCorregido").val(data.especieCorregido);
            }
            , 'json');
        };
         $('#form').jqxValidator({ rules: [
                 
                { input: '#numBoleto', message: 'Debe ingresar el número de boleto!', action: 'keyup, blur',  rule: function(){
                    var result;
                    if ( !($("#numBoleto").val() > 0 ))  {
                        result = false;
                    }else{
                        result = true;
                    }  
                    return result;                    
                }},

                { input: '#numBoleto', message: 'Debe ingresar un boleto existente!', action: 'keyup, blur',  rule: function(){
                    var result;
                    var value = $("#numBoleto").val();
                    
                    jQuery.ajaxSetup({async:false});
                    $.post('/esco/getBoleto', {numBoleto: value}, function(pNumBoleto){
                        if(pNumBoleto){
                           if (pNumBoleto.NumBoleto > 0){
                                result = true;

                            }  else {
                                result = false;
                            } 
                        }else{
                            if(boletoAnulado == true){
                                result = true;
                            }else{
                                result = false;
                            }
                        }
                    }, 'json');
                    jQuery.ajaxSetup({async:true});
                    return result;                    
                }},


                { input: '#observaciones', message: 'Debe Seleccionar alguna opción o completar el campo observaciones!', action: 'keyup, blur',  rule: function(){
                    var result;
                                        
                    if (
                            (  
                            $("#esCantidad").jqxDropDownList('getSelectedIndex') != 1
                            && $("#esArancel").jqxDropDownList('getSelectedIndex') != 1
                            && $("#esComitente").jqxDropDownList('getSelectedIndex') != 1

                            && $("#esPrecio").jqxDropDownList('getSelectedIndex') != 1
                            && $("#esTipoOperacion").jqxDropDownList('getSelectedIndex') != 1
                            && $("#esEspecie").jqxDropDownList('getSelectedIndex') != 1

                            && ($("#observaciones").val()).length == 0
                            ) 
                        )
                    {
                        result = false;
                    }else{
                        result = true;
                    }
                    return result;
                }},

                { input: '#cantidadCorregido', message: 'Debe indicar una cantidad, si selecciona que modificará la cantidad!', action: 'keyup, blur',  rule: function(){
                    var result;                   
                    if ( $("#esCantidad").jqxDropDownList('getSelectedIndex') == 1 && ($("#cantidadCorregido").val()).length == 0){
                        result = false;
                    }else{
                        result = true;
                    }
                    return result;
                }},
                 
                { input: '#arancelCorregido', message: 'Debe indicar un arancel, si selecciona que modificará el arancel!', action: 'keyup, blur',  rule: function(){
                    var result;                   
                    if ( $("#esArancel").jqxDropDownList('getSelectedIndex') == 1 && ($("#arancelCorregido").val()).length == 0){
                        result = false;
                    }else{
                        result = true;
                    }
                    return result;
                }},                 
                 
                { input: '#numComitenteCorregido', message: 'Debe indicar un arancel, si selecciona que modificará el arancel!', action: 'keyup, blur',  rule: function(){
                    var result;                   
                    if ( $("#esComitente").jqxDropDownList('getSelectedIndex') == 1 && $("#numComitenteCorregido").val() == 0 )  {
                        result = false;
                    }else{
                        result = true;
                    } 
                    
                    return result;
                }},  
            
                { input: '#precioCorregido', message: 'Debe indicar un precio, si selecciona que modificará el precio!', action: 'keyup, blur',  rule: function(){
                    var result;                   
                    if ( $("#esPrecio").jqxDropDownList('getSelectedIndex') == 1 && ($("#precioCorregido").val()).length == 0){
                        result = false;
                    }else{
                        result = true;
                    } 
                    return result;
                }}, 
            
                { input: '#tipoOperacionCorregido', message: 'Debe indicar un tipo Operacion, si selecciona que lo modificará!', action: 'keyup, blur',  rule: function(){
                    var result;                   
                    if ( $("#esTipoOperacion").jqxDropDownList('getSelectedIndex') == 1 && ($("#tipoOperacionCorregido").val()).length == 0){
                        result = false;
                    }else{
                        result = true;
                    } 
                    return result;
                }}, 
            
                { input: '#especieCorregido', message: 'Debe indicar una especie, si selecciona que modificará la especie!', action: 'keyup, blur',  rule: function(){
                    var result;                   
                    if ( $("#esEspecie").jqxDropDownList('getSelectedIndex') == 1 && ($("#especieCorregido").val()).length == 0){
                        result = false;
                    }else{
                        result = true;
                    } 
                    return result;
                }}, 
            
                 
                { input: '#moneda', message: 'Modificación no autorizada! Enviar un correo a: autorizacionesME@allaria.com.ar, adjuntar boleto en formato PDF, detallando Motivos del cambio. Gracias.', action: 'keyup, blur',  rule: function(){
                    
                    // Validacion moneda DOLAR
                    jQuery.ajaxSetup({async:false});
                    $.post('/usuario/getUsuario', {id: $("#userId").val()}, function(result){
                        if (result){
                            rUsuario = result.grupos[0];
                        }
                    }, 'json');
                    jQuery.ajaxSetup({async:true});
                    
            
                    var result;
                    if ( $("#moneda").val() != 'Pesos' && $("#tpOperacionBurs").val() != 'Venta Exterior' && $("#tpOperacionBurs").val() != 'Compra Exterior' && $("#esComitente").jqxDropDownList('getSelectedIndex') == 1 && rUsuario != 1 && rUsuario != 4 && rUsuario != 6 ){
                        new Messi("Modificación no autorizada, solicite autorización: <a href='https://docs.google.com/forms/d/e/1FAIpQLSelOhE_Ezi4dMkIsYSH0N4yU76Q5r6yb6Q8XLDvXV2TtOzsIw/viewform?usp=sf_link'>Haga click aquí", {title: 'Error', buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        result = false;
                    } else {
                        result = true;
                    }
                    return result;
                }},

            ], 
            theme: theme
        });
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        
        $('#aceptarButton').jqxButton({ theme: theme, width: '65px' });
        $('#aceptarButton').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                $('#ventanaCorreccion').ajaxloader();
                var cable = 0;
                if ($("#cable").val()){
                    cable = 1;
                }
                
                var fechaConcertacion = moment($("#fechaConcertacion").jqxDateTimeInput('val'), 'DD/MM/YYYY').format('YYYY-MM-DD');
                var fechaLiquidacion = moment($("#fechaLiquidacion").jqxDateTimeInput('val'), 'DD/MM/YYYY').format('YYYY-MM-DD');
                
                
                if($("#esComitente").val() != 'S'){
                    numeroComitente = 0;
                }else{
                    numeroComitente = $("#numComitenteCorregido").val();
                }
                
                datos = {
                    id: $("#id").val(),
                    operador: $("#operador").val(),
//                    codBoleto: $("#codBoleto").val(),
                    numBoleto: $("#numBoleto").val(),
//                    fechaConcertacion: fechaConcertacion,
//                    fechaLiquidacion: fechaLiquidacion,
                    
                    fechaConcertacion: $("#fechaConcertacion").val(),
                    fechaLiquidacion: $("#fechaLiquidacion").val(),
                    
                    tpOperacionBurs: $("#tpOperacionBurs").val(),
                    numComitente: $("#numComitente").val(),
                    comitente: $("#comitente").val(),
                    administrador: $("#administrador").val(),
                    instrumentoAbrev: $("#instrumentoAbrev").val(),
                    moneda: $("#moneda").val(),
                    precio: $("#precio").val(),
                    cantidad: $("#cantidad").val(),
                    arancel: $("#arancel").val(),
                    numComitenteCorregido: numeroComitente,
                    comitenteCorregido: $("#comitenteCorregido").val(),
//                    administradorCorregido: $("#administradorCorregido").val(),
                    
                    cantidadCorregido: $("#cantidadCorregido").val(),
                    arancelCorregido: $("#arancelCorregido").val(),
                    observaciones: $("#observaciones").val(),

                    precioCorregido: $("#precioCorregido").val(),
                    tipoOperacionCorregido: $("#tipoOperacionCorregido").val(),
                    especieCorregido: $("#especieCorregido").val(),

                    autorizadores: $("#autorizadores").val(),
                    caja: $("#caja").val(),
                    visual: $("#visual").val(),
                    control: $("#control").val(),
//                    fechaActualizacion: $("#fechaActualizacion").val(),
                    

                };
                $.post('/correccion/saveOrden', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/correccion');
                    } else {
                        new Messi('Hubo un error guardando la orden', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaCorreccion').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
        
        
    });
    
</script>


<style>


td.border1 {
  border-collapse: collapse;
  border-bottom: 1px solid black;
}

/*p.dotted {border-bottom-style: solid;}*/


/*td.border2 {
  border-bottom-style: dotted;
}*/



</style>