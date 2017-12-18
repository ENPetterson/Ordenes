<input type="hidden" id="id" value="<?php echo $id;?>" >
<input type="hidden" id="origen" value="<?php echo $origen;?>" >
<div id="ventanaBono">
    <div id="titulo">
        Editar Orden Richmond
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
                    <td style="padding-right: 10px; padding-bottom: 10px">Tipo de Inversor:</td>
                    <td><div id="tipoInversor"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Moneda:</td>
                    <td><div id="moneda"></div></td>
                </tr>
                <tr id="filaCable">
                    <td style="padding-right: 10px; padding-bottom: 10px">Es Dolar Cable:</td>
                    <td><div id="cable"></div></td>
                </tr>
                <!--
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Plazo Días: </td>
                    <td><div id="plazo"></div></td>
                </tr>
                -->
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Comisión %: </td>
                    <td><div id="comision"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Cantidad (Acciones): </td>
                    <td><div id="cantidad"></div></td>
                </tr>
                
                <tr id="filaPrecio">
                    <!--<td style="padding-right:10px; padding-bottom: 10px">Tasa (%): </td>-->
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
        
        $("#ventanaBono").jqxWindow({showCollapseButton: false, height: 500, width: 470, theme: theme, resizable: false, keyboardCloseKey: -1});
        
        $("#tramo").jqxDropDownList({ width: '300px', height: '25px', source: ['No Competitiva', 'Competitiva'], theme: theme, selectedIndex: 0, disabled: false});
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999});
        var tipoInversor = [
            {value: 'I', label: 'Institucional'},
            {value: 'M', label: 'Minorista'},
            {value: 'P', label: 'Cartera Propia'},
            {value: 'R', label: 'No Residente'},
            {value: 'C', label: 'Corporativo'}
        ];
        $("#tipoInversor").jqxDropDownList({ width: '300px', height: '25px', source: tipoInversor, theme: theme, placeHolder: 'elija el tipo de inversor'});
        var monedas = [
            { value: '$', label: 'Peso'},
            { value: 'u$s', label: 'Dolar'}
        ];
        $("#moneda").jqxDropDownList({ width: '300px', height: '25px', source: monedas, theme: theme, placeHolder: 'elija la moneda', disabled: false});
        
        $("#moneda").jqxDropDownList('selectIndex', 0);
        $("#moneda").jqxDropDownList({disabled: true});
        
        //$("#cable").jqxCheckBox({height: '20px', theme: theme});
        /*
        $("#plazo").jqxDropDownList({ width: '110px', height: '25px', theme: theme, placeHolder: 'elija plazo'});
        */
        $("#comision").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 1, groupSeparator: ' ', max: 99, theme: theme});
        $("#cantidad").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme});
        $("#precio").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 2, groupSeparator: ' ', max: 99.99, theme: theme});
        //$("#precio").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 6, digits: 4, groupSeparator: ' ', max: 9999.999999, theme: theme});
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
        
        $("#filaCable").hide();
        
        $("#moneda").on('change', function(event){
            var args = event.args;
            if (args){
                if (args.item.value == '$'){
                    $("#filaCable").hide();
                    $("#cable").jqxCheckBox('uncheck');
                } else {
                    //$("#filaCable").show();
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
        
        /*
        $("#cantidad").on('textchanged', function(event){
            var args = event.args;
            if (args){
                var cantidad = args.value;
                if (cantidad <= 1000){ //Solo no competitivo
                    $("#tramo").jqxDropDownList({disabled: false});
                    $("#tramo").jqxDropDownList('selectIndex', 0);
                    $("#tramo").jqxDropDownList({disabled: true});
                } else { //Solo competitivo
                    $("#tramo").jqxDropDownList({disabled: false});
                    $("#tramo").jqxDropDownList('selectIndex', 1);
                    $("#tramo").jqxDropDownList({disabled: true});
                }
            }
        });
        */
    
        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Orden Richmond');
            $("#filaCable").hide();
            /*
             * 
            var url = '/bono/getCierreActual';
            $.post(url, {}, function(cierre){
                var comboPlazos;
                comboPlazos = cierre.plazos.split(",");
                minimos = cierre.minimos.split(",");
                $("#plazo").jqxDropDownList('clear'); 
                $.each(comboPlazos, function(index,value){
                    $("#plazo").jqxDropDownList('addItem', value ); 
                });
            }, 'json');
            */
        } else {
            $("#titulo").text('Editar Orden Richmond');
            var datos = {
                id: $("#id").val()
            };
            $.post('/bono/getOrden', datos, function(data){
                cierre_id = data.cierrebono_id;
                $("#numComitente").val(data.numcomitente);
                
                $("#comision").val(data.comision);
                $("#cantidad").val(data.cantidad);
                $("#precio").val(data.precio);
                $("#cable").jqxCheckBox('uncheck');
                $("#tipoInversor").val(data.tipoInversor);
                if(data.moneda == '$'){
                    $("#moneda").jqxDropDownList('selectIndex', 0);
                } else {
                    $("#moneda").jqxDropDownList('selectIndex', 1);
                    if (data.cable == 1){
                        $("#cable").jqxCheckBox('check');
                    }
                }
                /*
                plazoCargado = data.plazo;
                */
                $("#tramo").val(data.tramo);
                $("#numComitente").focus();
                /*
                var datos = {cierrebono_id: cierre_id};
                url = '/bono/getCierre';
                $.post(url, datos, function(cierre){
                    var comboPlazos = cierre.plazos.split(",");
                    minimos = cierre.minimos.split(",");
                    $("#plazo").jqxDropDownList('clear'); 
                    $.each(comboPlazos, function(index,value){
                        $("#plazo").jqxDropDownList('addItem', value ); 
                    });
                    setDropDown("#plazo", plazoCargado);
                }, 'json');
                */
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
                { input: '#tipoInversor', message: 'Debe Seleccionar el tipo de inversor!', action: 'keyup, blur',  rule: function(){
                    return ($("#tipoInversor").jqxDropDownList('getSelectedIndex') != -1);
                }},
                { input: '#moneda', message: 'Debe Seleccionar la moneda!', action: 'keyup, blur',  rule: function(){
                    return ($("#moneda").jqxDropDownList('getSelectedIndex') != -1);
                }},
                /*
                { input: '#plazo', message: 'Debe elegir el plazo!', action: 'change',  rule: function(){
                    return ($("#plazo").val() >= 30);
                }},
                */
                { input: '#cantidad', message: 'Cantidad incorrecta!', action: 'keyup, blur',  rule: function(){
                    var result = true;
                    if (minimo == 0){
                        minimo = 1;
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
                /*
                { input: '#precio', message: 'La tasa debe estar entre 15% y 30% y ser multiplo de 0.25%!', action: 'keyup, blur',  rule: function(){
                    if ($('#tramo').jqxDropDownList('getSelectedIndex') == 1 && ($("#precio").val() < 15 || $("#precio").val() > 30 || ($("#precio").val() % 0.25 ) !== 0)) {
                        return false;
                    } else {
                        return true;
                    }
                }}
                */
                { input: '#precio', message: 'El precio debe ser mayor o igual que 20!', action: 'keyup, blur',  rule: function(){
                    if ($('#tramo').jqxDropDownList('getSelectedIndex') == 1 && ($("#precio").val() < 20)) {
                        return false;
                    } else {
                        return true;
                    }
                }},
                { input: '#precio', message: 'El precio debe ser menor o igual que 70!', action: 'keyup, blur',  rule: function(){
                    if ($('#tramo').jqxDropDownList('getSelectedIndex') == 1 && ($("#precio").val() > 70)) {
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
                $('#ventanaBono').ajaxloader();
                var cable = 0;
                if ($("#cable").val()){
                    cable = 1;
                }
                datos = {
                    id: $("#id").val(),
                    tramo: $("#tramo").val(),
                    numComitente: $("#numComitente").val(),
                    tipoInversor: $("#tipoInversor").val(),
                    moneda: $("#moneda").val(),
                    cable: cable,
                    /*
                    plazo: $("#plazo").val(),
                    */
                    comision: $("#comision").val(),
                    cantidad: $("#cantidad").val(),
                    precio: $("#precio").val(),
                    comitente: $("#comitente").val(),
                    tipoPersona: $("#tipoPersona").val(),
                    oficial: $("#oficial").val(),
                    cuit: $("#cuit").val()
                };
                $.post('/bono/saveOrden', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/bono');
                    } else {
                        new Messi('Hubo un error guardando la orden', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaBono').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
        
        
    });
    
</script>