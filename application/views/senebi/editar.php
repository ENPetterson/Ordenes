<input type="hidden" id="id" value="<?php echo $id;?>" >
<input type="hidden" id="origen" value="<?php echo $origen;?>" >
<input type="hidden" id="usuario" value="<?php echo $usuario;?>" >
<div id="ventanaSenebi">
    <div id="titulo">
        Editar Orden Senebi
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Operador:</td>
                    <td><input type="text" id="operador" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Tipo de Operación:</td>
                    <td><div id="tipoOperacion"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Precio Comitente:</td>
                    <td><div id="precio" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Precio Operado:</td>
                    <td><div id="precioContraparte" ></div></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-right: 10px; padding-bottom: 10px"> <font color="#ff0000">Atención, el tipo de cambio de senebi es el de banco nacion vendedor.</font></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Número Comitente:</td>
                    <td><div id="numComitente" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Comitente:</td>
                    <td><input type="text" id="comitente" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Especie:</td>
                    <td><input type="text" id="especie" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Plazo:</td>
                    <td><div id="plazo"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Moneda:</td>
                    <td><div id="moneda"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Cantidad:</td>
                    <td><div id="cantidad"></div></td>
                </tr>
<!--                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">CTTE:</td>
                    <td><input type="text" id="ctte" style="width: 250px"></td>
                </tr>-->
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Bruto Cliente:</td>
                    <td><div id="brutoCliente"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">Origen Fondos:</td>
                    <td><input type="text" id="origenFondos" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">Deriva:</td>
                    <td><input type="text" id="deriva" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">Observaciones:</td>
                    <td><input type="text" id="observaciones" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">CTTE Contraparte:</td>
                    <td><input type="text" id="ctteContraparte" style="width: 250px"></td>
                </tr>
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">Riesgo Comitente:</td>
                    <td><input type="text" id="riesgoComitente" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">Riesgo:</td>
                    <td><input type="text" id="riesgo" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">Rango precios:</td>
                    <td><input type="text" id="rangoPrecios" style="width: 250px"></td>
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
        var riesgoComitente = '';
        var plazoCargado = 0;
        var cierre_id = 0;
        var minimos = [];
        var minimo = 0;
        var precio = 0;
        var cantidad = 0;
        var brutoCliente = 0;
        
        
        var p = 0;
        var pc = 0;
        
        var pm = 0;
        var pcm = 0;

        
       
        $("#ventanaSenebi").jqxWindow({showCollapseButton: false, height: 800, width: 500, theme: theme, resizable: false, keyboardCloseKey: -1, maxHeight: 1000});
        
        $("#operador").jqxInput({ width: '300px', height: '25px', theme: theme});
        $("#tipoOperacion").jqxDropDownList({ width: '300px', height: '25px', source: ['Compra', 'Venta'], theme: theme, selectedIndex: 0, disabled: false});
        $("#precio").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 3, digits: 4, groupSeparator: ' ', max: 9999.99, theme: theme});
        $("#precioContraparte").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 3, digits: 4, groupSeparator: '', max: 9999.99, theme: theme});
        

        
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999});
        $("#comitente").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        
                $("#especie").jqxInput({ width: '300px', height: '25px', theme: theme}); 
        $("#plazo").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 3, groupSeparator: ' ', max: 999999999, theme: theme});
//        $("#moneda").jqxDropDownList({ width: '300px', height: '25px', source: ['Peso', 'Dolar'], theme: theme, selectedIndex: 0, disabled: false});
        var monedas = [
            { value: '$', label: 'Peso'},
            { value: 'u$s', label: 'Dolar'}
        ];
        $("#moneda").jqxDropDownList({ width: '300px', height: '25px', source: monedas, theme: theme, placeHolder: 'elija la moneda'});


        $("#cantidad").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: '.', max: 999999999, theme: theme, value: 0});
//        $("#ctte").jqxInput({ width: '300px', height: '25px', theme: theme}); 
        $("#brutoCliente").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 2, digits: 14, groupSeparator: '.', max: 99999999999999, theme: theme, value: precio, disabled: true});
        $("#origenFondos").jqxInput({ width: '300px', height: '25px', theme: theme}); 
        $("#deriva").jqxInput({ width: '300px', height: '25px', theme: theme}); 
        $("#observaciones").jqxInput({ width: '300px', height: '25px', theme: theme}); 
        $("#ctteContraparte").jqxInput({ width: '300px', height: '25px', theme: theme}); 
    
        $("#riesgoComitente").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        
        $("#riesgo").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});

        $("#rangoPrecios").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});

        
        $("#precio").on('change', function(event){
            var args = event.args;
            if (args){
                precio = args.value;
                cantidad = $("#cantidad").val();
                brutoCliente = precio * cantidad / 100,
                $("#brutoCliente").jqxNumberInput({value: brutoCliente}); 
                if( precio == 0 || cantidad == 0){
                    $("#brutoCliente").jqxNumberInput({value: 0}); 
                }
                
                p = parseFloat($("#precio").val());
                pc = parseFloat($("#precioContraparte").val());
                
                pm = parseFloat( p * 1.015);
                pcm = parseFloat( pc * 1.015);
                
                if($("#tipoOperacion").val() == "Compra"){ 
                    if( p > pcm ){
                       $("#rangoPrecios").val('MAL'); 
                    }
                    else{                        
                        $("#rangoPrecios").val(''); 
                    }                    
                }else{
                    if( pc > pm ) {                      
                        $("#rangoPrecios").val('MAL'); 
                    }else{                        
                        $("#rangoPrecios").val(''); 
                    }
                }
                
            }
        });
        
        $("#precioContraparte").on('change', function(event){
            var args = event.args;
            if (args){
                
                p = parseFloat($("#precio").val());
                pc = parseFloat($("#precioContraparte").val());
                
                pm = parseFloat( p * 1.015);
                pcm = parseFloat( pc * 1.015);
                
                if($("#tipoOperacion").val() == "Compra"){ 
                    if( p > pcm ){
                       $("#rangoPrecios").val('MAL'); 
                    }
                    else{                        
                        $("#rangoPrecios").val(''); 
                    }                    
                }else{
                    if( pc > pm ) {                      
                        $("#rangoPrecios").val('MAL'); 
                    }else{                        
                        $("#rangoPrecios").val(''); 
                    }
                }
            }
        });
        
        $("#tipoOperacion").on('change', function(event){
            var args = event.args;
            if (args){
                
                p = parseFloat($("#precio").val());
                pc = parseFloat($("#precioContraparte").val());
                
                pm = parseFloat( p * 1.015);
                pcm = parseFloat( pc * 1.015);
                
                if($("#tipoOperacion").val() == "Compra"){ 
                    if( p > pcm ){
                       $("#rangoPrecios").val('MAL'); 
                    }
                    else{                        
                        $("#rangoPrecios").val(''); 
                    }                    
                }else{
                    if( pc > pm ) {                      
                        $("#rangoPrecios").val('MAL'); 
                    }else{                        
                        $("#rangoPrecios").val(''); 
                    }
                }
            }
        });
        
        
        
        $("#cantidad").on('change', function(event){
            var args = event.args;      
            if (args){
                precio = $("#precio").val();
                cantidad = args.value;

                brutoCliente = (parseFloat(precio) * parseFloat(cantidad)) / 100;
                brutoCliente = parseFloat(brutoCliente);          

                $("#brutoCliente").jqxNumberInput({value: brutoCliente}); 
                if( precio == 0 || cantidad == 0){
                    $("#brutoCliente").jqxNumberInput({value: 0}); 
                }
            }
        });
        
        $('#numComitente').on('change', function (event) {
            var value = $("#numComitente").val();
            $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
                comitente = pComitente;
                if (pComitente){
                    $("#comitente").val(pComitente.comitente);
                    
                    $("#riesgoComitente").val(pComitente.CodPRI);
                    
                    
                    
                    if(pComitente.CodPRI == 'RBNC' || pComitente.CodPRI == 'RA' || pComitente.CodPRI == 'CC' || pComitente.CodPRI == 'RANC' || pComitente.CodPRI == 'RMC' || pComitente.CodPRI == 'RAC'){
                        $("#riesgo").val("SI");
                    }else{
                        $("#riesgo").val("NO");
                    }
//                    if (pComitente.esFisico == -1){
//                        $("#tipoPersona").val('FISICA'); 
//                    } else {
//                        $("#tipoPersona").val('JURIDICA'); 
//                    }
//                    $("#oficial").val(pComitente.oficial);
//                    $("#cuit").val(pComitente.cuit);
                    $('#form').jqxValidator('hideHint', '#numComitente');
                } else {
                    $("#comitente").val('');
                    $("#riesgoComitente").val('');
                    $("#riesgo").val('');
                    
//                    $("#tipoPersona").val(''); 
//                    $("#oficial").val('');
//                    $("#cuit").val('');
                }
            }, 'json');
        });
        
        
        
        


    
        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Orden Senebi');
            $("#operador").val($("#origen").val());
//            $("#filaCable").hide();
//            var url = '/senebi/getCierreActual';
//            $.post(url, {}, function(cierre){
//                var comboPlazos;
//                comboPlazos = cierre.plazos.split(",");
//                minimos = cierre.minimos.split(",");
//                $("#plazo").jqxDropDownList('clear'); 
//                $.each(comboPlazos, function(index,value){
//                    $("#plazo").jqxDropDownList('addItem', value ); 
//                });
//            }, 'json');
        } else {
            $("#titulo").text('Editar Orden Senebi');
            var datos = {
                id: $("#id").val()
            };
            $.post('/senebi/getOrden', datos, function(data){
                cierre_id = data.cierresenebi_id;
                
                $("#operador").val(data.operador);
                $("#tipoOperacion").val(data.tipoOperacion);
                $("#precio").val(data.precio);
                $("#precioContraparte").val(data.precioContraparte);
                $("#numComitente").val(data.numeroComitente);
                $("#especie").val(data.especie);
                $("#plazo").val(data.plazo);
//                $("#moneda").val(data.moneda);
                switch((data.moneda)){
                    case '$':
                        $("#moneda").jqxDropDownList('selectIndex', 0);
                        break;
                    case 'u$s':
                        $("#moneda").jqxDropDownList('selectIndex', 1);
                        break;
                }
                $("#cantidad").val(data.cantidad);
//                $("#ctte").val(data.ctte);
                $("#brutoCliente").val(data.brutoCliente);
                $("#origenFondos").val(data.origenFondos);
                $("#deriva").val(data.deriva);
                $("#observaciones").val(data.observaciones);
                $("#ctteContraparte").val(data.ctteContraparte);
                
                $("#riesgoComitente").val(data.riesgoComitente);
//                $("#numComitente").val(data.numcomitente);
//                
//                $("#comision").val(data.comision);
//                $("#cantidad").val(data.cantidad);
//                $("#precio").val(data.precio);
//                $("#cable").jqxCheckBox('uncheck');
//                switch( (data.moneda)){
//                    case 'u$s':
//                        $("#moneda").jqxDropDownList('selectIndex', 1);
//                        if (data.cable == 1){
//                            $("#cable").jqxCheckBox('check');
//                        }
//                        break;
//                }
//                
//                plazoCargado = data.plazo;
//                $("#tramo").val(data.tramo);
//                $("#numComitente").focus();
//                var datos = {cierresenebi_id: cierre_id};
//                url = '/senebi/getCierre';
//                $.post(url, datos, function(cierre){
//                    var comboPlazos = cierre.plazos.split(",");
//                    minimos = cierre.minimos.split(",");
//                    $("#plazo").jqxDropDownList('clear'); 
//                    $.each(comboPlazos, function(index,value){
//                        $("#plazo").jqxDropDownList('addItem', value ); 
//                    });
//                    setDropDown("#plazo", plazoCargado);
//                }, 'json');

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
//                { input: '#plazo', message: 'Debe elegir el plazo!', action: 'change',  rule: function(){
//                    return ($("#plazo").val() >= 30);
//                }},
//                { input: '#cantidad', message: 'Cantidad incorrecta!', action: 'keyup, blur',  rule: function(){
//                    var result = true;
//                    if (minimo == 0){
//                        minimo = 1000;
//                    }
//                    var maximo = 0;
//                    
//                    if ($("#tramo").jqxDropDownList('getSelectedIndex') == 0){
//                        maximo = 2000001;
//                    } else {
//                        maximo = 0;
//                    }
//                    
//                    if ($("#tipoPersona").val() == 'JURIDICA'){
//                        //minimo = 10000;
//                    }
//                    var cantidad = $("#cantidad").val();
//                    $('#form').jqxValidator('hideHint', '#cantidad');
//                    
//                    if (maximo > 0 && cantidad > maximo){
//                        $('#form').jqxValidator('rules')[3].message = "La cantidad no puede ser mayor que " + maximo.toString() + "!";
//                        result = false;
//                    }
//                    
//                    if (cantidad < minimo){
//                        $('#form').jqxValidator('rules')[3].message = "La cantidad debe ser mayor o igual que " + minimo.toString() + " !";
//                        result = false;
//                    } 
//                    return result;
//                }},
//                { input: '#comision', message: 'Valor incorrecto!', action: 'keyup, blur',  rule: function(){
//                    if ($("#comision").val() > 3) {
//                        return false;
//                    } else {
//                        return true;
//                    }
//                }}, 
//                { input: '#precio', message: 'El precio debe ser mayor que cero!', action: 'keyup, blur',  rule: function(){
//                    if ($('#tramo').jqxDropDownList('getSelectedIndex') == 1 && $("#precio").val() == 0) {
//                        return false;
//                    } else {
//                        return true;
//                    }
//                }},
//                { input: '#precio', message: 'El precio debe ser menor o igual a 9999!', action: 'keyup, blur',  rule: function(){
//                    if ($('#tramo').jqxDropDownList('getSelectedIndex') == 1 && $("#precio").val() > (999999/100)) {
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
                $('#ventanaSenebi').ajaxloader();
                var cable = 0;
                if ($("#cable").val()){
                    cable = 1;
                }
                datos = {
                    id: $("#id").val(),
                    operador: $("#operador").val(),
                    tipoOperacion: $("#tipoOperacion").val(),
                    precio: $("#precio").val(),
                    precioContraparte: $("#precioContraparte").val(),
                    numeroComitente: $("#numComitente").val(),
                    especie: $("#especie").val(),
                    plazo: $("#plazo").val(),
                    moneda: $("#moneda").val(),
                    cantidad: $("#cantidad").val(),
//                    ctte: $("#ctte").val(),
                    brutoCliente: $("#brutoCliente").val(),
                    origenFondos: $("#origenFondos").val(),
                    deriva: $("#deriva").val(),
                    observaciones: $("#observaciones").val(),
                    ctteContraparte: $("#ctteContraparte").val(),
                    
                    riesgoComitente: $("#riesgoComitente").val(),
                    riesgo: $("#riesgo").val(),
                    rangoPrecios: $("#rangoPrecios").val(),
                    
                };
                $.post('/senebi/saveOrden', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/senebi');
                    } else {
                        new Messi('Hubo un error guardando la orden', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaSenebi').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
        
        
    });
    
</script>