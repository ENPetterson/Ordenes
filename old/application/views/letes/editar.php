<input type="hidden" id="id" value="<?php echo $id;?>" >
<input type="hidden" id="origen" value="<?php echo $origen;?>" >
<div id="ventanaLetes">
    <div id="titulo">
        Editar Orden Letes
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
                <tr id="filaCable">
                    <td style="padding-right: 10px; padding-bottom: 10px">Es Dolar Cable:</td>
                    <td><div id="cable"></div></td>
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
                    <td style="padding-right:10px; padding-bottom: 10px">Cantidad V/N (u$s): </td>
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
<script>
    $(function(){
        var theme = getTheme();
        var formOK = false;
        var comitente = false;
        var plazoCargado = 0;
        var cierre_id = 0;
        var minimos = [];
        var minimo = 0;
        
        $("#filaPrecio").hide();
        
        $("#ventanaLetes").jqxWindow({showCollapseButton: false, height: 450, width: 470, theme: theme, resizable: false, keyboardCloseKey: -1});
        
        $("#tramo").jqxDropDownList({ width: '300px', height: '25px', source: ['No Competitiva', 'Competitiva'], theme: theme, selectedIndex: 0, disabled: false});
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999});
        var monedas = [
            { value: '$', label: 'Peso'},
            { value: 'u$s', label: 'Dolar'}
        ];
        $("#moneda").jqxDropDownList({ width: '300px', height: '25px', source: monedas, theme: theme, placeHolder: 'elija la moneda'});
        $("#cable").jqxCheckBox({height: '20px', theme: theme});
        $("#plazo").jqxDropDownList({ width: '110px', height: '25px', theme: theme, placeHolder: 'elija plazo'});
        $("#comision").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 1, groupSeparator: ' ', max: 99, theme: theme});
        $("#cantidad").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme});
        $("#precio").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 3, groupSeparator: ' ', max: 999.99, theme: theme});
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
                } else {
                    $("#comitente").val('');
                    $("#tipoPersona").val(''); 
                    $("#oficial").val('');
                    $("#cuit").val('');
                }
            }, 'json');
        });
        
        $("#moneda").on('change', function(event){
            var args = event.args;
            if (args){
                if (args.item.value == '$'){
                    $("#filaCable").hide();
                    $("#cable").jqxCheckBox('uncheck');
                } else {
                    $("#filaCable").show();
                }
            }
        });
        
        
        $("#tramo").on('change', function(event){
            var args = event.args;
            if (args){
                if (args.index == 0) { //No Competitiva
                    $("#precio").val(0);
                    $("#filaPrecio").hide();
                    $('#form').jqxValidator('hideHint', '#precio');
                } else { //Competitiva
                    $("#filaPrecio").show();
                }
            }
        });
        
        
        
        
        
        $("#plazo").on('change', function(event){
            var args = event.args;
            if (args){
                plazoCargado = args.item.value;
                minimo = minimos[args.index];
            }
        });
        
    
        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Orden Letes');
            $("#filaCable").hide();
            var url = '/letes/getCierreActual';
            $.post(url, {}, function(cierre){
                var comboPlazos;
                comboPlazos = cierre.plazos.split(",");
                minimos = cierre.minimos.split(",");
                $("#plazo").jqxDropDownList('clear'); 
                $.each(comboPlazos, function(index,value){
                    $("#plazo").jqxDropDownList('addItem', value ); 
                });
            }, 'json');
        } else {
            $("#titulo").text('Editar Orden Letes');
            var datos = {
                id: $("#id").val()
            };
            $.post('/letes/getOrden', datos, function(data){
                cierre_id = data.cierreletes_id;
                $("#numComitente").val(data.numcomitente);
                
                $("#comision").val(data.comision);
                $("#cantidad").val(data.cantidad);
                $("#precio").val(data.precio);
                $("#cable").jqxCheckBox('uncheck');
                if(data.moneda == '$'){
                    $("#moneda").jqxDropDownList('selectIndex', 0);
                } else {
                    $("#moneda").jqxDropDownList('selectIndex', 1);
                    if (data.cable == 1){
                        $("#cable").jqxCheckBox('check');
                    }
                }
                plazoCargado = data.plazo;
                $("#tramo").val(data.tramo);
                $("#numComitente").focus();
                var datos = {cierreletes_id: cierre_id};
                url = '/letes/getCierre';
                $.post(url, datos, function(cierre){
                    var comboPlazos = cierre.plazos.split(",");
                    minimos = cierre.minimos.split(",");
                    $("#plazo").jqxDropDownList('clear'); 
                    $.each(comboPlazos, function(index,value){
                        $("#plazo").jqxDropDownList('addItem', value ); 
                    });
                    setDropDown("#plazo", plazoCargado);
                }, 'json');

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
                    return ($("#plazo").val() >= 30);
                }},
                { input: '#cantidad', message: 'Cantidad incorrecta!', action: 'keyup, blur',  rule: function(){
                    var result = true;
                    if (minimo == 0){
                        minimo = 1000;
                    }
                    if ($("#tipoPersona").val() == 'JURIDICA'){
                        minimo = 1000;
                    }
                    var cantidad = $("#cantidad").val();
                    $('#form').jqxValidator('hideHint', '#cantidad');
                    if (cantidad < minimo){
                        $('#form').jqxValidator('rules')[3].message = "La cantidad debe ser mayor o igual que " + minimo.toString() + " !";
                        result = false;
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
                }}
            ], 
            theme: theme
        });
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        
        $('#aceptarButton').jqxButton({ theme: theme, width: '65px' });
        $('#aceptarButton').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                $('#ventanaLetes').ajaxloader();
                var cable = 0;
                if ($("#cable").val()){
                    cable = 1;
                }
                datos = {
                    id: $("#id").val(),
                    tramo: $("#tramo").val(),
                    numComitente: $("#numComitente").val(),
                    moneda: $("#moneda").val(),
                    cable: cable,
                    plazo: $("#plazo").val(),
                    comision: $("#comision").val(),
                    cantidad: $("#cantidad").val(),
                    precio: $("#precio").val(),
                    comitente: $("#comitente").val(),
                    tipoPersona: $("#tipoPersona").val(),
                    oficial: $("#oficial").val(),
                    cuit: $("#cuit").val()
                };
                $.post('/letes/saveOrden', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/letes');
                    } else {
                        new Messi('Hubo un error guardando la orden', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaLetes').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
        
        
    });
    
</script>