<input type="hidden" id="id" value="<?php echo $id;?>" >
<input type="hidden" id="origen" value="<?php echo $origen;?>" >
<div id="ventanaLebac">
    <div id="titulo">
        Editar Orden Lebac
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Tramo:</td>
                    <td><div id="tramo"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Numero Comitente: </td>
                    <td><div id="numComitente" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Moneda:</td>
                    <td><div id="moneda"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Plazo Días: </td>
                    <td><div id="plazo"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Comisión %: </td>
                    <td><div id="comision"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Cantidad V/N: </td>
                    <td><div id="cantidad"></div></td>
                </tr>
                <tr id="filaPrecio">
                    <td style="padding-right:10px; padding-bottom: 10px">Precio: </td>
                    <td><div id="precio" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Comitente:</td>
                    <td><input type="text" id="comitente" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Tipo Persona:</td>
                    <td><input type="text" id="tipoPersona" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Oficial:</td>
                    <td><input type="text" id="oficial" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">CUIT:</td>
                    <td><input type="text" id="cuit" style="width: 250px"></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center">
                        <input type="button" id="aceptarButton" value="Aceptar">
                    </td>
                </tr>
            </table>
        </form>
    </div> 
</div>
<div id="ventanaResumen">
    <div>Ordenes Cargadas del Comitente</div>
    <div>
        <div id="grillaOrdenes"></div>
    </div>
</div>
<script>
    $(function(){
        var theme = getTheme();
        var formOK = false;
        var comitente = false;
        var plazoCargado = 0;
        var cierre_id = 0;
        var numComitente = 0;
        
       
        $("#filaPrecio").hide();
        
        $("#ventanaLebac").jqxWindow({showCollapseButton: false, height: 500, width: 470, theme: theme, resizable: false, keyboardCloseKey: -1});
        $("#tramo").jqxDropDownList({ width: '300px', height: '25px', source: ['No Competitiva', 'Competitiva'], theme: theme, selectedIndex: 0, disabled: true});
        //$("#tramo").jqxDropDownList({ width: '300px', height: '25px', source: ['No Competitiva', 'Competitiva'], theme: theme, selectedIndex: 0, disabled: false});
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999});
        var srcMonedas = {
            datatype: 'json',
            datafields: [
                {name: 'simbolo'},
                {name: 'nombre'}
            ],
            data: {cierre_id: cierre_id},
            type: 'POST',
            id: 'simbolo',
            url: '/lebac/getMonedas'
        }
        var DAMonedas = new $.jqx.dataAdapter(srcMonedas);
        $("#moneda").jqxDropDownList({ width: '300px', height: '25px', source: DAMonedas, theme: theme, placeHolder: 'elija la moneda', displayMember: 'nombre', valueMember: 'simbolo'});
        $("#plazo").jqxDropDownList({ width: '110px', height: '25px', theme: theme, placeHolder: 'elija plazo'});
        $("#comision").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 1, groupSeparator: ' ', max: 99, theme: theme});
        $("#cantidad").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 12, groupSeparator: ' ', max: 999999999999, theme: theme});
        /*$("#precio").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 6, digits: 1, groupSeparator: ' ', max: 999999999.999999, theme: theme});*/
        $("#precio").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 4, groupSeparator: ' ', max: 9999.99, theme: theme});
        $("#comitente").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        $("#tipoPersona").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        $("#oficial").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        $("#cuit").jqxInput({ width: '110px', height: '25px', disabled: true, theme: theme});
        
        $('#numComitente').on('valueChanged', function (event) {
            var value = $("#numComitente").val();
            $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
                comitente = pComitente;
                if (pComitente){
                    $("#comitente").val(pComitente.comitente);
                    if (pComitente.esFisico == -1){
                        $("#tipoPersona").val('FISICA'); 
                    } else {
                        $("#tipoPersona").val('JURIDICA'); 
                    }
                    $("#oficial").val(pComitente.oficial);
                    $("#cuit").val(pComitente.cuit);
                    $('#form').jqxValidator('hideHint', '#numComitente');
                    if (!bowser.msie){
                        $("#ventanaResumen").jqxWindow('open');
                        srcOrdenes.data = {cierre_id: cierre_id, numComitente: $('#numComitente').val()};
                        $("#grillaOrdenes").jqxGrid('updatebounddata');
                    }
                } else {
                    $("#comitente").val('');
                    $("#tipoPersona").val(''); 
                    $("#oficial").val('');
                    $("#cuit").val('');
                    $("#ventanaResumen").jqxWindow('close');
                }
            }, 'json');
        });
        
        
        $('#cantidad').on('valueChanged', function (event) {
            var value = $("#cantidad").val();
            if (value < 2000000){
                $("#tramo").jqxDropDownList({selectedIndex: 0 });
                $("#tramo").jqxDropDownList({ disabled: true }); 
            } else {
                $("#tramo").val('Competitiva');
                $("#tramo").jqxDropDownList({selectedIndex: 1 });
                $("#tramo").jqxDropDownList({ disabled: true }); 
                /*
                if (value >= 10000000) {
                    $("#tramo").jqxDropDownList({ disabled: true }); 
                } else {
                    $("#tramo").jqxDropDownList({ disabled: false }); 
                }
                */
            }
        });
        
        
        $("#tramo").on('change', function(event){
            var args = event.args;
            if (args){
                $('#form').jqxValidator('hideHint', '#cantidad');
                if (args.index == 0) { //No Competitiva
                    $("#precio").val(0);
                    $("#filaPrecio").hide();
                    $('#form').jqxValidator('hideHint', '#precio');
                } else { //Competitiva
                    $("#filaPrecio").show();
                }
            }
        });
        
        $("#moneda").on('change', function(event){
            var args = event.args;
            if (args){
                var datos = {cierre_id: cierre_id, moneda: getDropDown("#moneda")};
                var url = '/lebac/getPlazos';
                $.post(url, datos, function(plazos){
                    $("#plazo").jqxDropDownList('clear'); 
                    $.each(plazos, function(index,value){
                        $("#plazo").jqxDropDownList('addItem', value ); 
                    });
                    setDropDown("#plazo", plazoCargado);
                }, 'json');
            }
        });
        
        $("#plazo").on('change', function(event){
            var args = event.args;
            if (args){
                plazoCargado = args.item.value;
            }
        });
        
    
        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Orden Lebac');
        } else {
            $("#titulo").text('Editar Orden Lebac');
            datos = {
                id: $("#id").val()
            };
            $.post('/lebac/getOrden', datos, function(data){
                cierre_id = data.cierre_id;
                DAMonedas.data = {cierre_id: cierre_id};
                DAMonedas.dataBind();
                $("#numComitente").val(data.numcomitente);
                
                $("#comision").val(data.comision);
                $("#cantidad").val(data.cantidad);
                $("#precio").val(data.precio);
                setDropDown("#moneda", data.moneda);
                plazoCargado = data.plazo;
                $("#tramo").val(data.tramo);
                $("#numComitente").focus();
            }
            , 'json');
        };
         $('#form').jqxValidator({ rules: [
                { input: '#numComitente', message: 'Debe Seleccionar un comitente existente!', action: 'keyup, blur',  rule: function(){
                    var result;
                    if (!comitente){
                        result = false;
                    } else {
                        result = true;
                    }
                    return result;
                }},
                { input: '#moneda', message: 'Debe Seleccionar la moneda!', action: 'keyup, blur',  rule: function(){
                    return ($("#moneda").jqxDropDownList('getSelectedIndex') != -1);
                }},
                { input: '#plazo', message: 'Debe elegir el plazo!', action: 'change',  rule: function(){
                    return ($("#plazo").val() >= 28);
                }},
                { input: '#cantidad', message: 'Cantidad incorrecta!', action: 'keyup, blur',  rule: function(){
                    var result = true;
                    var minimo;
                    var multiplo;
                    var maximo = 0;
                    if ($("#tramo").jqxDropDownList('getSelectedIndex') == 0){
                        if ($("#tipoPersona").val() == "FISICA"){
                            minimo = 10000;
                        } else {
                            minimo = 10000;
                        }
                        multiplo = 1;
                        maximo = 2000000;
                    } else {
                        minimo = 1000000;
                        multiplo = 1;
                        maximo = 0;
                    }
                    var cantidad = $("#cantidad").val();
                    $('#form').jqxValidator('hideHint', '#cantidad');
                    if (maximo > 0 && cantidad > maximo){
                        $('#form').jqxValidator('rules')[3].message = "La cantidad no puede ser mayor que " + maximo.toString() + "!";
                        result = false;
                    }
                    if (cantidad < minimo){
                        $('#form').jqxValidator('rules')[3].message = "La cantidad debe ser mayor o igual que " + minimo.toString() + "!";
                        result = false;
                    } else {
                        if (cantidad % multiplo > 0){
                            $('#form').jqxValidator('rules')[3].message = "La cantidad debe ser multiplo de " + multiplo.toString() +"!";
                            result = false;
                        }
                    }
                    return result;
                }},
                
                { input: '#comision', message: 'Valor incorrecto!', action: 'keyup, blur',  rule: function(){
                    if ($("#comision").val() > 3) {
                        return false;
                    } else {
                        return true;
                    }
                }},
                { input: '#precio', message: 'El precio debe ser mayor que cero!', action: 'keyup, blur',  rule: function(){
                    if ($('#tramo').jqxDropDownList('getSelectedIndex') == 1 && $("#precio").val() == 0) {
                        return false;
                    } else {
                        return true;
                    }
                }},
//                { input: '#precio', message: 'El precio debe expresarse como 0,XXXXXX !', action: 'keyup, blur',  rule: function(){
//                    if ($('#tramo').jqxDropDownList('getSelectedIndex') == 1  && $("#precio").val() >= 1) {
//                        return false;
//                    } else {
//                        return true;
//                    }
//                }}
            ], 
            theme: theme
        });
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        
        $('#aceptarButton').jqxButton({ theme: theme, width: '65px' });
        $('#aceptarButton').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                $('#ventanaLebac').ajaxloader();
                datos = {
                    id: $("#id").val(),
                    tramo: $("#tramo").val(),
                    numComitente: $("#numComitente").val(),
                    moneda: $("#moneda").val(),
                    plazo: $("#plazo").val(),
                    comision: $("#comision").val(),
                    cantidad: $("#cantidad").val(),
                    precio: $("#precio").val(),
                    comitente: $("#comitente").val(),
                    tipoPersona: $("#tipoPersona").val(),
                    oficial: $("#oficial").val(),
                    cuit: $("#cuit").val()
                };
                $.post('/lebac/saveOrden', datos, function(data){
                    if (data.id > 0){
                        //if ($('#origen').val('procesar')){
                        //    $.redirect('/lebac/procesar');
                        //} else {
                            $.redirect('/lebac');
                        //}
                    } else {
                        new Messi('Hubo un error guardando la orden', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaLebac').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
        
        $("#ventanaResumen").jqxWindow({autoOpen: false, height: 500, width:400, position: {x: 5, y: 50}, theme: theme });
        
        $('#numComitente').focusout(function(){
            if (bowser.msie){
                if (comitente){
                    $("#ventanaResumen").jqxWindow('open');
                    srcOrdenes.data = {cierre_id: cierre_id, numComitente: $('#numComitente').val()};
                    $("#grillaOrdenes").jqxGrid('updatebounddata');
                } else {
                    $("#ventanaResumen").jqxWindow('close');
                }
            }
        });
        
        var srcOrdenes = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'moneda'},
                { name: 'plazo', type: 'number'},
                { name: 'cantidad', type: 'number'},
                { name: 'precio', type: 'float'}
            ],
            cache: false,
            url: '/lebac/getOrdenes',
            data: {cierre_id: cierre_id,
                   numComitente: numComitente},
            type: 'post'
        };
        
        var DAOrdenes = new $.jqx.dataAdapter(srcOrdenes);

        // initialize jqxGrid
        $("#grillaOrdenes").jqxGrid(
        {		
                source: DAOrdenes,
                theme: theme,
                filterable: true,
                filtermode: 'excel',
                sortable: true,
                pageable: false,
                virtualmode: false,
                columnsresize: true,
                showstatusbar: true,
                statusbarheight: 25,
                showaggregates: true,
                width: 390,
                height: 400,
                columns: [
                        { text: 'Id', datafield: 'id', width: 60, cellsalign: 'right', cellsformat: 'd', aggregates: ['count'] },
                        { text: 'Mone', datafield: 'moneda', width: 30},
                        { text: 'Plazo', datafield: 'plazo', width: 40, cellsalign: 'right'},
                        { text: 'Cantidad', datafield: 'cantidad', width: 140, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum'] },
                        { text: 'Precio', datafield: 'precio', width: 100, cellsalign: 'right', cellsformat: 'd10'}
                ]
        });
        $("#grilla").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grilla").jqxGrid('localizestrings', localizationobj);
            $("#numComitente").focus();
        }); 
        
    });
    
    //Aca va el codigo de la calculadora de lebacs
    $(function(){
        
    });
</script>