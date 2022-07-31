<input type="hidden" id="id" value="<?php echo $id;?>" >
<input type="hidden" id="origen" value="<?php echo $origen;?>" >
<input type="hidden" id="usuario" value="<?php echo $usuario;?>" >
<input type="hidden" id="userId" value="<?php echo $userId;?>" >

<div id="ventanaMinuta">
    <div id="titulo">
        Editar Orden Minuta
    </div>
    <div>
        <form id="form">


            <table>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">Operador:</td>
                    <td><input type="text" id="operador" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">numRegistro:</td>
                    <td><div id="numRegistro" ></div></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px">numComitente:</td>
                    <td><div id="numComitente" ></div></td>
                </tr>
                <tr id="agrego">
                <td class="border1" style="padding-left: 10px; padding-right: 10px; padding-bottom: 10px; border-bottom-style: dotted;"><p colspan="2"></p></td>
                </tr>

                <tr>
                <td colspan='2' align='center' style='padding-left: 10px; padding-right: 10px; padding-top: 20px; padding-bottom: 20px'></td>
                </tr>
                
                <?php if ($id == 0): ?>
                <tr>
                    <td colspan="4">
                        <table boder="0" cellpadding="2" cellspacing="2">
                            <tr colspan="4">
                                <td class="a">
                                Tramos
                                </td>
                            </tr>
                            <tr colspan="4">
                                <td>
                                <div id="grdTramos" style="margin-bottom: 0.9em">Tramos</div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="button" value="Agregar" id="agregarButtonTramo">
                                    <input type="button" value="Borrar" id="borrarButtonTramo">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>    
                <?php endif; ?>
                
                
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
        var comitenteDesc = '';
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

        var datosCompletos = [];

        var p = 0;
        var pc = 0;

        var pm = 0;
        var pcm = 0;

        var instrumento_id = $("#id").val();
        
        
        var numRegistro = 0;
        var numComitente = 0;
        
        var id = 0;
        var arrayDatosGuardar = [];
        
        var rUsuario = '';

        $("#ventanaMinuta").jqxWindow({showCollapseButton: false, height: 800, width: 1500, theme: theme, resizable: false, keyboardCloseKey: -1, maxHeight: 1500, maxWidth: 1500});


        ///////////////////////

        $("#operador").jqxInput({ width: '300px', height: '25px', theme: theme});

        $("#numRegistro").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 10, groupSeparator: ' ', max: 9999999999, theme: theme});
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 10, groupSeparator: ' ', max: 9999999999, theme: theme});


        $('#numRegistro').on('change', function (event) {
            ////////// Validar que la minuta no tenga boleto ///////////////////
            var value = $("#numRegistro").val();
            $.post('/esco/validarMinutaRegistroBoleto', {numRegistro: value}, function(result){
                if (result){                    
                    new Messi("Registro: " + value + " Posée un boleto.", {title: 'Error', buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});                    
                    $('#form').jqxValidator('hideHint', '#numComitente');
                } 
            }, 'json');
            ////////////////////////////////////////////////////////////////////
            
            ///// Agregado por la grilla /////
            srcTramos.data = {
                    numRegistro: $("#numRegistro").val(),
                    numComitente: $("#numComitente").val(),
                };                     

            $("#grdTramos").jqxGrid('updatebounddata');

            jQuery.ajaxSetup({async:true});
            //////////////////////////////////
        });
       
        
        $('#numComitente').on('change', function (event) {
            var resultadosValidacion = [];
            ////////////////// Valido Comitente ///////////////////////////////
            var value = $("#numComitente").val();
            jQuery.ajaxSetup({async:false});
            $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
                comitente = pComitente;
                if (pComitente){
                    comitenteDesc = pComitente.comitente;
                    $('#form').jqxValidator('hideHint', '#numComitente');
                    ////////// Validar que la minuta no tenga boleto ///////////
                    $.post('/esco/validarMinutaComitenteBoleto', {numComitente: value}, function(resultado){
                        if (resultado != false){
//                            $.each(resultado, function(index, val){
//                                resultadosValidacion.push(val.NumRegistro + "\n");
//                            });
                            for (var h=0; h<5; h++){
                                if(resultado[h]){
                                    resultadosValidacion.push(resultado[h].NumRegistro + "\n");
                                }
                            }
                            new Messi("El registro: " + resultadosValidacion + " ya posée un boleto generado.", {title: 'Error', buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});                    
                            $('#form').jqxValidator('hideHint', '#numComitente');
                        } 
                    }, 'json'); 
                    ////////////////////////////////////////////////////////////
                } 
            }, 'json');    
            ////////////////////////////////////////////////////////////////////
            
            ///// Agregado por la grilla /////
            srcTramos.data = {
                    numRegistro: $("#numRegistro").val(),
                    numComitente: $("#numComitente").val()
                };                     

            $("#grdTramos").jqxGrid('updatebounddata');

            jQuery.ajaxSetup({async:true});
            //////////////////////////////////
        });
            
        jQuery.ajaxSetup({async:false});
    
        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Orden Minuta');
            $("#operador").val($("#origen").val());
            
            //Botones de la grilla//
            $('#agregarButtonTramo').jqxButton({ theme: theme, width: '65px' });
            $('#borrarButtonTramo').jqxButton({ theme: theme, width: '65px' });
            
        } else {
            $("#titulo").text('Editar Orden Minuta');
            
            
            
            var datos = {
                id: $("#id").val()
            };

            $("#numRegistro").jqxNumberInput({ disabled: true });
            $("#numComitente").jqxNumberInput({ disabled: true });

            $.post('/minuta/getOrden', datos, function(data){
                cierre_id = data.cierreminuta_id;

                var index = 0;

                $("#operador").val(data.operador);
                $("#numRegistro").val(data.numRegistro);
                $("#numComitente").val(data.numComitente);

                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>observaciones"+index.toString()+":</td><td><input class='remove' type='text' id='observaciones"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#observaciones"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: false});
                $("#observaciones"+index.toString()).val(data.observaciones);


                $("<tr class='remove estoArancel"+index.toString()+"'><td class='remove estoArancel"+index.toString()+"' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>arancelCorregido"+index.toString()+":</td><td><input class='remove estoArancel"+index.toString()+"' type='text' id='arancelCorregido"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#arancelCorregido"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: false});
                $("#arancelCorregido"+index.toString()).val(data.arancelCorregido);

                var esArancelCorregido = [
                  { value: '0', label: 'NO'},
                  { value: '1', label: 'SI'}
                ];
                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px;'>Modificará Arancel?:</td><td><div class='remove' id='esArancelCorregido"+index.toString()+"'></div></td></tr>").insertAfter("#agrego");
                $("#esArancelCorregido"+index.toString()+"").jqxDropDownList({ width: '300px', height: '25px', source: esArancelCorregido, theme: theme, placeHolder: ''});
                $("#esArancelCorregido"+index.toString()).val(data.esArancelCorregido);

                $("<tr class='remove estoCantidad"+index.toString()+"'><td class='remove estoCantidad"+index.toString()+"' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>cantidadCorregido"+index.toString()+":</td><td><input class='remove estoCantidad"+index.toString()+"' type='text' id='cantidadCorregido"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#cantidadCorregido"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: false});
                $("#cantidadCorregido"+index.toString()).val(data.cantidadCorregido);

                var esCantidadCorregido = [
                  { value: '0', label: 'NO'},
                  { value: '1', label: 'SI'}
                ];
                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px;'>Modificará Cantidad?:</td><td><div class='remove' id='esCantidadCorregido"+index.toString()+"'></div></td></tr>").insertAfter("#agrego");

    
                $("#esCantidadCorregido"+index.toString()+"").jqxDropDownList({ width: '300px', height: '25px', source: esCantidadCorregido, theme: theme, placeHolder: ''});
                $("#esCantidadCorregido"+index.toString()).val(data.esCantidadCorregido);

                $("<tr class='remove estoComitente"+index.toString()+"'><td class='remove estoComitente"+index.toString()+"' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>comitenteCorregido"+index.toString()+":</td><td><input class='remove estoComitente"+index.toString()+"' type='text' id='comitenteCorregido"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#comitenteCorregido"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#comitenteCorregido"+index.toString()).val(data.comitenteCorregido);

                $("<tr class='remove estoNumComitente"+index.toString()+"'><td class='remove estoNumComitente"+index.toString()+"' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>numComitenteCorregido"+index.toString()+":</td><td><div class='remove estoNumComitente"+index.toString()+"' id='numComitenteCorregido"+index.toString()+"' style='width: 250px'></div></td></tr>").insertAfter("#agrego");
                $("#numComitenteCorregido"+index.toString()).jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 10, groupSeparator: ' ', max: 9999999999, theme: theme});
                $("#numComitenteCorregido"+index.toString()).val(data.numComitenteCorregido);

                var esComitenteCorregido = [
                  { value: '0', label: 'NO'},
                  { value: '1', label: 'SI'}
                ];
                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px;'>Modificará Comitente?:</td><td><div class='remove' id='esComitenteCorregido"+index.toString()+"'></div></td></tr>").insertAfter("#agrego");
                $("#esComitenteCorregido"+index.toString()+"").jqxDropDownList({ width: '300px', height: '25px', source: esComitenteCorregido, theme: theme, placeHolder: '' });
                $("#esComitenteCorregido"+index.toString()).val(data.esComitenteCorregido);

                ////////////

                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>cantidad"+index.toString()+":</td><td><input class='remove' type='text' id='cantidad"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#cantidad"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#cantidad"+index.toString()).val(data.cantidad);


                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>monedaDescripcion"+index.toString()+":</td><td><input class='remove' type='text' id='monedaDescripcion"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#monedaDescripcion"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#monedaDescripcion"+index.toString()).val(data.monedaDescripcion);

                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>codMoneda"+index.toString()+":</td><td><input class='remove' type='text' id='codMoneda"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#codMoneda"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#codMoneda"+index.toString()).val(data.codMoneda);
                
                
                

                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>especieAbreviatura"+index.toString()+":</td><td><input class='remove' type='text' id='especieAbreviatura"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#especieAbreviatura"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#especieAbreviatura"+index.toString()).val(data.especieAbreviatura);

                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>codEspecie"+index.toString()+":</td><td><input class='remove' type='text' id='codEspecie"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#codEspecie"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#codEspecie"+index.toString()).val(data.codEspecie);

                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>tipoOperacionBursDesc"+index.toString()+":</td><td><input class='remove' type='text' id='tipoOperacionBursDesc"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#tipoOperacionBursDesc"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#tipoOperacionBursDesc"+index.toString()).val(data.tipoOperacionBursDesc);

                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>tipoOperacionBurs"+index.toString()+":</td><td><input class='remove' type='text' id='tipoOperacionBurs"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#tipoOperacionBurs"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#tipoOperacionBurs"+index.toString()).val(data.tipoOperacionBurs);
                
                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>fechaLiquidacion"+index.toString()+":</td><td><input class='remove' type='text' id='fechaLiquidacion"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#fechaLiquidacion"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#fechaLiquidacion"+index.toString()).val(data.fechaLiquidacion);

                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>comitente"+index.toString()+":</td><td><input class='remove' type='text' id='comitente"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#comitente"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#comitente"+index.toString()).val(data.comitente);

                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>numComitente"+index.toString()+":</td><td><div id='numComitente"+index.toString()+"' style='width: 250px'></div></td></tr>").insertAfter("#agrego");
                $("#numComitente"+index.toString()).jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 10, groupSeparator: ' ', max: 9999999999, theme: theme, disabled: true});
                $("#numComitente"+index.toString()).val(data.numComitente);

                $("<tr class='remove'><td class='remove' style='padding-left: 10px; padding-right: 10px; padding-bottom: 10px'>numRegistro"+index.toString()+":</td><td><input class='remove' type='text' id='numRegistro"+index.toString()+"' style='width: 250px'></td></tr>").insertAfter("#agrego");
                $("#numRegistro"+index.toString()).jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true});
                $("#numRegistro"+index.toString()).val(data.numRegistro);


                $("#numComitenteCorregido"+ index.toString()).on('change', function(event){
                    var value = $("#numComitenteCorregido"+index.toString()).val();
                    $.post('/esco/getComitente', {numComitente: value}, function(pComitenteCorregido){
                        if (pComitenteCorregido){
                            $("#comitenteCorregido"+index.toString()).val(pComitenteCorregido.comitente);
                            $('#form').jqxValidator('hideHint', '#numComitenteCorregido');
                        } else {
                            $("#comitenteCorregido"+index).val('');
                        }
                    }, 'json');

                });

                $(".estoArancel"+index.toString()).hide();
                $(".estoCantidad"+index.toString()).hide();
                $(".estoComitente"+index.toString()).hide();
                $(".estoNumComitente"+index.toString()).hide();

                if($("#esCantidadCorregido"+index.toString()).val() == '1'){
                     $(".estoCantidad"+index.toString()).show();
                }else{
                    $(".estoCantidad"+index.toString()).hide();
                }
                                    
                $("#esCantidadCorregido"+index.toString()).on('change', function (event) {            
                    if($("#esCantidadCorregido"+index.toString()).val() == '1'){
                        $(".estoCantidad"+index.toString()).show();
                    }else{
                        $(".estoCantidad"+index.toString()).hide();
                    }
                });

                if($("#esComitenteCorregido"+index.toString()).val() == '1'){
                    $(".estoComitente"+index.toString()+"").show();
                    $(".estoNumComitente"+index.toString()+"").show();
                }else{
                    $(".estoComitente"+index.toString()+"").hide();
                    $(".estoNumComitente"+index.toString()+"").hide();
                }

                $("#esComitenteCorregido"+index.toString()).on('change', function (event) {
                    if($("#esComitenteCorregido"+index.toString()+"").val() == '1'){
                       $(".estoComitente"+index.toString()+"").show();
                       $(".estoNumComitente"+index.toString()+"").show();
                   }else{
                       $(".estoComitente"+index.toString()+"").hide();
                       $(".estoNumComitente"+index.toString()+"").hide();
                   }
                });

                if($("#esArancelCorregido"+index.toString()).val() == '1'){
                     $(".estoArancel"+index.toString()).show();
                }else{
                    $(".estoArancel"+index.toString()).hide();
                }

                $("#esArancelCorregido"+index.toString()).on('change', function (event) {            
                    if($("#esArancelCorregido"+index.toString()+"").val() == '1'){
                       $(".estoArancel"+index.toString()+"").show();
                   }else{
                       $(".estoArancel"+index.toString()+"").hide();
                   }
                });
            }
            , 'json');
        };

        jQuery.ajaxSetup({async:true});


        if ($("#id").val() == 0){
        //////     Grilla     //////////
            var srcTramos =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'id'},
                        { name: 'NumRegistro'},
                        { name: 'NumComitente' },
                        { name: 'Descripcion' },
                        { name: 'FechaLiquidacion'},
                        { name: 'CodTpOperacionBurs'},
                        { name: 'TipoOperacionBursDesc'},
                        { name: 'CodEspecie'},
                        { name: 'Abreviatura'},
                        { name: 'CodMoneda' },
                        { name: 'MonedaDescripcion'},
                        { name: 'Cantidad'},
                        { name: 'esComitenteCorregido'},
                        { name: 'numComitenteCorregido'},
                        { name: 'comitenteCorregido'},
                        { name: 'esArancelCorregido' },
                        { name: 'arancelCorregido'},
                        { name: 'esCantidadCorregido' },
                        { name: 'cantidadCorregido'},
                        { name: 'observaciones'},
                        { name: 'NumBoleto'},
                        { name: 'datoNuevo', type: 'number' },
                    ],
                    cache: false,
                    url: '/esco/getMinuta',
                    data: {
                        numRegistro: 0,
                        numComitente: 0
                    },
                    //id: 'id',
                    type: 'post',
                };
            var DATramos = new $.jqx.dataAdapter(srcTramos);

            var isEditable = function (row) {
                return false;
            }




            $("#grdTramos").jqxGrid({
                source: DATramos,
                editable: true,
                theme: theme,
                selectionmode: 'checkbox',
                width: 1450,
                height: 500,
                rendergridrows: function(obj)
                {
                        return obj.data;
                },
                autoheight: false,
                columns: [
                    { text: 'Id', datafield: 'id', width: 0, 'hidden': true },
                    { text: 'Registro', datafield: 'NumRegistro', width: 70},
                    { text: 'Comitente', datafield: 'NumComitente', width: 80},
                    { text: 'Comitente', datafield: 'Descripcion', width: 200, cellbeginedit: isEditable },
                    { text: 'tipoOperacionBurs', datafield: 'CodTpOperacionBurs', 'hidden': true },
                    { text: 'fechaLiquidacion', datafield: 'FechaLiquidacion', 'hidden': true },
                    { text: 'Tipo Operacion', datafield: 'TipoOperacionBursDesc', width: 135, cellbeginedit: isEditable },
                    { text: 'especie', datafield: 'CodEspecie',  'hidden': true, cellbeginedit: isEditable },
                    { text: 'Especie', datafield: 'Abreviatura', width: 70, cellbeginedit: isEditable },
                    { text: 'moneda', datafield: 'CodMoneda', 'hidden': true, cellbeginedit: isEditable },
                    { text: 'Moneda', datafield: 'MonedaDescripcion', width: 70, cellbeginedit: isEditable },
                    { text: 'Cantidad', datafield: 'Cantidad', width: 70},
                    { text: 'esComitenteCorregido', datafield: 'esComitenteCorregido', width: 70, 'hidden': true },
                    { text: 'Comitente', datafield: 'numComitenteCorregido', width: 80 },
                    { text: 'comitente', datafield: 'comitenteCorregido', width: 200, cellbeginedit: isEditable },
                    { text: 'esArancelCorregido', datafield: 'esArancelCorregido', width: 70, 'hidden': true },
                    { text: 'arancel', datafield: 'arancelCorregido', width: 100 },
                    { text: 'esCantidadCorregido', datafield: 'esCantidadCorregido', width: 70, 'hidden': true },
                    { text: 'cantidad', datafield: 'cantidadCorregido', width: 100 },
                    { text: 'observaciones', datafield: 'observaciones', width: 150 },
                    { text: 'Boleto', datafield: 'NumBoleto', width: 70 },
                    { text: 'datoNuevo', datafield: 'datoNuevo', width: 70, value: 0, 'hidden': true}
                    
                ]
            }); 
            $('#grdTramos').jqxGrid('updatebounddata');
    //            $("#grdTramos").on("bindingcomplete", function (event){
    //                var localizationobj = getLocalization();
    //                $("#grdTramos").jqxGrid('localizestrings', localizationobj);
    //            }); 


            $("#agregarButtonTramo").bind('click', function () {
                var datarow = {
                    NumRegistro: '',
                    NumComitente: '',
                    Descripcion: '',
                    fechaLiquidacion: '',
                    CodTpOperacionBurs: '',
                    TipoOperacionBursDesc: '',
                    CodEspecie: '',
                    Abreviatura: '',
                    CodMoneda: '',
                    MonedaDescripcion: '',
                    Cantidad: '',
                    FechaLiquidacion: '1970-01-01',
                    esComitenteCorregido: 0,
                    numComitenteCorregido: '',
                    comitenteCorregido: '',
                    esArancelCorregido: 0,
                    arancelCorregido: '',
                    esCantidadCorregido: 0,
                    cantidadCorregido: '',
                    observaciones: '',
                    NumBoleto: '',
                    datoNuevo: 1
                };
                $("#grdTramos").jqxGrid('addrow', null, datarow);
            });



//            $("#borrarButtonTramo").bind('click', function () {
//                var celda = $('#grdTramos').jqxGrid('getselectedcell');
//                var selectedrowindex = celda.rowindex;
//                var rowscount = $("#grdTramos").jqxGrid('getdatainformation').rowscount;
//                if (selectedrowindex >= 0 && selectedrowindex < rowscount) {
//                    var id = $("#grdTramos").jqxGrid('getrowid', selectedrowindex);
//                    $("#grdTramos").jqxGrid('deleterow', id);
//                }
//            });
            
            
            
            $("#borrarButtonTramo").bind('click', function (event) {
                var rowIDs = [];                
                var rowindexes = $('#grdTramos').jqxGrid('getselectedrowindexes');

                if (rowindexes.length > 0){
                    $.each(rowindexes, function(index, value){
                        var row = $('#grdTramos').jqxGrid('getrowdata', value);
                        rowIDs.push(row.uid);
                    });
                }     
                $("#grdTramos").jqxGrid('deleterow', rowIDs);                
                $('#grdTramos').jqxGrid('clearselection');
            });
            



            // Edición de celdas. Esto ya funcionaba ///////////////////////////
            /*
            $('#grdTramos').on('cellbeginedit', function (event) {
                                
                var args = event.args; 
                var value = event.args.value;   
                var rowindex = event.args.rowindex;
                jQuery.ajaxSetup({async:false});
                
                var theColumnName = event.args.datafield;

                console.log(typeof(value));
                console.log(value);
                console.log(rowindex);
                console.log(theColumnName);

                var yaTieneDatos = 0;

                if (value == ""){
                    yaTieneDatos = 0;
                }else{
                    yaTieneDatos = 1;
                }

                if(theColumnName == "NumRegistro"){
                    
                    if(yaTieneDatos == 0){
                        console.log("Es vacio");                        
//                        $("#grdTramos").jqxGrid('setcellvalue', rowindex, "NumRegistro", "40");
//                        
//                        console.log("Mayor a 0");
//                        $("#grdTramos").jqxGrid('endcelledit', rowindex, "NumRegistro", false);
                    }else{
                        $("#grdTramos").jqxGrid('endcelledit', rowindex, "NumRegistro", false);
                        console.log("NO Es vacio");
                        $("#grdTramos").jqxGrid('setcellvalue', rowindex, "NumRegistro", value);
//                        $("#grdTramos").jqxGrid('endcelledit', rowindex, "NumRegistro", true);
                    }
                    
//                    $("#grdTramos").jqxGrid('setcellvalue', rowindex, "NumRegistro", "40");
//                    $("#grdTramos").jqxGrid('setcellvalue', rowindex, "NumRegistro", 40);
                }
                
                
            });
            */
            ////////////////////////////////////////////////////////////////////
            
            
            //Edición de celdas.
            ////////////////////////////////////////////////////////////////////
            
            $('#grdTramos').on('cellbeginedit', function (event) {
                                
                var args = event.args; 
                var value = event.args.value;   
                var rowindex = event.args.rowindex;
                jQuery.ajaxSetup({async:false});
                var theColumnName = event.args.datafield;
                var row = $('#grdTramos').jqxGrid('getrowdata', rowindex);               

//                        datoDeMoneda != '' && rUsuario != 1 && rUsuario != 4 && rUsuario != 6 ){
//                jQuery.ajaxSetup({async:false});
                        $.post('/usuario/getUsuario', {id: $("#userId").val()}, function(result){
                            if (result){
                                rUsuario = result.grupos[0];
                            } 
                        }, 'json');   
//                jQuery.ajaxSetup({async:true});

                if(theColumnName == "numComitenteCorregido" ){                    
                    if(row.MonedaDescripcion != 'Pesos' && row.MonedaDescripcion != 'pesos' && row.MonedaDescripcion != '' && rUsuario != 1 && rUsuario != 4 && rUsuario != 6){
                        new Messi("Las modificaciones de Comitente no están autorizadas por el tipo de moneda, puede modificar arancel, cantidad y observaciones! Si quiere cambiar comitente solicite autorización: <a href='https://docs.google.com/forms/d/e/1FAIpQLSelOhE_Ezi4dMkIsYSH0N4yU76Q5r6yb6Q8XLDvXV2TtOzsIw/viewform?usp=sf_link'>Haga click aquí", {title: 'Error', buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});                    
                        $("#grdTramos").jqxGrid('endcelledit', rowindex, theColumnName, false);
                    }
                }                

                if( (theColumnName == "NumRegistro") || (theColumnName == "NumComitente") || (theColumnName == "Cantidad") ){                    
                    if(args.row.datoNuevo != 1){
                        $("#grdTramos").jqxGrid('endcelledit', rowindex, theColumnName, false);
                        $("#grdTramos").jqxGrid('setcellvalue', rowindex, theColumnName, value);                    
                    }
                }
            });
            
            ////////////////////////////////////////////////////////////////////
            
            

            ////////////////////////////////
            // Esta parte es si seleccionas o des-seleccionas ////////////////////////////////////////
            $('#grdTramos').on('rowselect rowunselect', function (event) {
                $('#form').jqxValidator('hideHint', '#grdTramos');

                arrayDatosGuardar = [];
                var rowindexes = $('#grdTramos').jqxGrid('getselectedrowindexes');

                if (rowindexes.length > 0){
                    $.each(rowindexes, function(index, value){
                        var row = $('#grdTramos').jqxGrid('getrowdata', value);

                        
                        
                        // Validacion moneda DOLAR
                        jQuery.ajaxSetup({async:false});
                        $.post('/usuario/getUsuario', {id: $("#userId").val()}, function(result){
                            if (result){
                                rUsuario = result.grupos[0];
                            } 
                        }, 'json');   
                        jQuery.ajaxSetup({async:true});
                        
                        
                        var datoDeLaRow = row.NumBoleto;
                        if (datoDeLaRow > 0 && rUsuario != 1 && rUsuario != 4){
                            $("#grdTramos").jqxGrid('unselectrow', value);
                        }
                        
                        var datoDeMoneda = row.CodMoneda;
                        if (datoDeMoneda != 1 && datoDeMoneda != 'Pesos' && datoDeMoneda != 'pesos' && datoDeMoneda != '' && rUsuario != 1 && rUsuario != 4 && rUsuario != 6 ){
                            new Messi("Las modificaciones de Comitente no están autorizadas por el tipo de moneda, puede modificar arancel, cantidad y observaciones! Si quiere cambiar comitente solicite autorización: <a href='https://docs.google.com/forms/d/e/1FAIpQLSelOhE_Ezi4dMkIsYSH0N4yU76Q5r6yb6Q8XLDvXV2TtOzsIw/viewform?usp=sf_link'>Haga click aquí", {title: 'Error', buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});                    
//                            $("#grdTramos").jqxGrid('unselectrow', value);
                        }
                        // Fin validacion moneda DOLAR
                        
                        arrayDatosGuardar.push(row);
                    });
                }
            });
            ////////////////////////////////////////////////////////////////////
            
            ////////////////////////////////
            // Esta parte es si editás el Número de comitente///////////////////
            $("#grdTramos").on('cellendedit', function (event) {
                var args = event.args; 
                var value = event.args.value;   
                var rowindex = event.args.rowindex;
                jQuery.ajaxSetup({async:false});
                
                
                var theColumnName = event.args.datafield;
                
                
                
                if(theColumnName == "numComitenteCorregido"){
                    $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
                        if (pComitente){
                            $("#grdTramos").jqxGrid('setcellvalue', rowindex, "comitenteCorregido", pComitente.comitente);
                        } 
                    }, 'json');   
                }
                
                
                
                
                
                
                
            });
            ////////////////////////////////////////////////////////////////////
            
        }

        $('#form').jqxValidator({ rules: [
                { input: '#numRegistro', message: 'Debe ingresar un numRegistro correcto!', action: 'keyup, blur',  rule: function(){
                    var result;
                    
                    console.log(arrayDatosGuardar);
                    
                    if ( !($("#numRegistro").val() > 0 ) && !($("#numComitente").val() > 0 ) && !(arrayDatosGuardar.length > 0) )  {
                        result = false;
                    }else{
                        result = true;
                    }

                    return result;
                }},
            
                { input: '#numComitente', message: 'Debe Seleccionar un comitente existente!', action: 'keyup, blur',  rule: function(){
                        
                     console.log(arrayDatosGuardar);    
                        
                    var result;
                    if (!comitente && ( !($("#numRegistro").val() > 0 ) && !($("#numComitente").val() > 0 ) && !(arrayDatosGuardar.length > 0) )  ){
                        result = false;
                    } else {
                        result = true;
                    }
                    return result;
                }},
            
                { input: '#grdTramos', message: 'Debe Seleccionar algún dato para guardar!', action: 'keyup, blur',  rule: function(){
                    var result;
                    if ( $("#id").val() == 0 && arrayDatosGuardar.length == 0){                
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
                $('#ventanaMinuta').ajaxloader();

                var datos = Array();

                var length = 1;

                if(datosCompletos.length > 0){
                    length = datosCompletos.length;
                }
                
                if ($("#id").val() == 0){
                    $.each(arrayDatosGuardar, function(index, value){

                        if (value.numComitenteCorregido > 0) {
                            value.esComitenteCorregido = 1;
                        }
                        if (value.cantidadCorregido > 0) {
                            value.esCantidadCorregido = 1;
                        }
                        if (value.arancelCorregido > 0) {
                            value.esArancelCorregido = 1;
                        }

                        var dato = {
                            id: $('#id').val(),
                            operador: $('#operador').val(),
                            numRegistro: value.NumRegistro,
                            numComitente: value.NumComitente,
                            comitente: value.Descripcion,
                            fechaLiquidacion: value.FechaLiquidacion,
                            tipoOperacionBurs: value.CodTpOperacionBurs,
                            tipoOperacionBursDesc: value.TipoOperacionBursDesc,
                            codEspecie: value.CodEspecie,
                            especieAbreviatura: value.Abreviatura,
                            codMoneda: value.CodMoneda,
                            monedaDescripcion: value.MonedaDescripcion,
                            
                            cantidad: value.Cantidad,

                            esComitenteCorregido: value.esComitenteCorregido,
                            numComitenteCorregido: value.numComitenteCorregido,
                            comitenteCorregido: value.comitenteCorregido,
                            
                            esCantidadCorregido: value.esCantidadCorregido,
                            cantidadCorregido: value.cantidadCorregido,
                            
                            esArancelCorregido: value.esArancelCorregido,
                            arancelCorregido: value.arancelCorregido,

                            observaciones: value.observaciones
                        };
                        datos.push(dato);
                    });
                }else{
                    for (var h=0; h<length; h++){
                        var dato = {
                            id: $('#id').val(),
                            operador: $('#operador').val(),
                            numRegistro: $('#numRegistro' + h.toString()).val(),
                            numComitente: $('#numComitente' + h.toString()).val(),
                            comitente: $('#comitente' + h.toString()).val(),
                            fechaLiquidacion: $('#fechaLiquidacion' + h.toString()).val(),
                            tipoOperacionBurs: $('#tipoOperacionBurs' + h.toString()).val(),
                            tipoOperacionBursDesc: $('#tipoOperacionBursDesc' + h.toString()).val(),
                            codEspecie: $('#codEspecie' + h.toString()).val(),
                            especieAbreviatura: $('#especieAbreviatura' + h.toString()).val(),
                            codMoneda: $('#codMoneda' + h.toString()).val(),
                            monedaDescripcion: $('#monedaDescripcion' + h.toString()).val(),
                            cantidad: $('#cantidad' + h.toString()).val(),
                            numComitenteCorregido: $('#numComitenteCorregido' + h.toString()).val(),
                            comitenteCorregido: $('#comitenteCorregido' + h.toString()).val(),
                            esComitenteCorregido: $('#esComitenteCorregido' + h.toString()).val(),
                            cantidadCorregido: $('#cantidadCorregido' + h.toString()).val(),
                            esCantidadCorregido: $('#esCantidadCorregido' + h.toString()).val(),
                            arancelCorregido: $('#arancelCorregido' + h.toString()).val(),
                            esArancelCorregido: $('#esArancelCorregido' + h.toString()).val(),
                            observaciones: $("#observaciones" + h.toString()).val(),
                        };
                        datos.push(dato);
                    }
                }

                var datosAEnviar = {
                    datos: datos
                };

                $.post('/minuta/saveOrden', datosAEnviar, function(data){
                    if (data.id > 0){
                        $.redirect('/minuta');
                    } else {
                        new Messi('Hubo un error guardando la orden', {title: 'Error',
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaMinuta').ajaxloader('hide');
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