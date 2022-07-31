<input type="hidden" id="id" value="<?php echo $id;?>" >
<input type="hidden" id="origen" value="<?php echo $origen;?>" >
<input type="hidden" id="usuario" value="<?php echo $usuario;?>" >
<div id="ventanaTreasuries">
    <div id="titulo">
        Editar Orden Treasuries
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
                    <td style="padding-right: 10px; padding-bottom: 10px">Mark-up:</td>
                    <td><div id="esPrecioComitente"></div></td>
                </tr>
                
                <tr>
                    <td class="esPrecioComitente" style="padding-right: 10px; padding-bottom: 10px">Precio Comitente:</td>
                    <td class="esPrecioComitente"><div id="precioComitente" ></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Arancel:</td>
                    <td><div id="esArancel"></div></td>
                </tr>
                
                <tr>
                    <td class="esArancel" style="padding-right: 10px; padding-bottom: 10px">Arancel:</td>
                    <td class="esArancel"><div id="arancel"></div></td>
                </tr>    
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Garantía:</td>
                    <td><div id="garantia" ></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px ">Precio Cartera:</td>
                    <td><div id="precioCartera" ></div></td>
                </tr>
                
<!--                <tr>
                    <td colspan="2" style="padding-right: 10px; padding-bottom: 10px"> <font color="#ff0000">Atención, el tipo de cambio de treasuries es el de banco nacion vendedor.</font></td>
                    <td></td>
                </tr>-->
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Número Comitente:</td>
                    <td><div id="numComitente"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Comitente:</td>
                    <td><input type="text" id="comitente" style="width: 250px"></td>
                </tr>
                
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Código Especie:</td>
                    <td><div id="codigo"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Especie:</td>
                    <td><input type="text" id="especie" style="width: 250px"></td>
                </tr>
                
                
                
                             
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Plazo (T+):</td>
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
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Bruto Cliente:</td>
                    <td><div id="brutoCliente"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">Observaciones:</td>
                    <td><input type="text" id="observaciones" style="width: 250px"></td>
                </tr>
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">CTTE Contraparte:</td>
                    <td><input type="text" id="numComitenteContraparte" style="width: 250px"></td>
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
        var codEspecie = false;
        
        var p = 0;
        var pc = 0;
        
        var pm = 0;
        var pcm = 0;

        
       
        $("#ventanaTreasuries").jqxWindow({showCollapseButton: false, height: 800, width: 500, theme: theme, resizable: false, keyboardCloseKey: -1, maxHeight: 1000});

        $(".esPrecioComitente").hide();
        $(".esArancel").hide();

        $("#operador").jqxInput({ width: '300px', height: '25px', theme: theme});
        $("#tipoOperacion").jqxDropDownList({ width: '300px', height: '25px', source: ['Compra', 'Venta'], theme: theme, selectedIndex: 0, disabled: false});
        $("#esPrecioComitente").jqxCheckBox({ width: 200, height: 20, theme: theme });
        $("#esArancel").jqxCheckBox({ width: 200, height: 20, theme: theme });

        $("#garantia").jqxCheckBox({ width: 200, height: 20, theme: theme });

        $("#precioComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 3, digits: 4, groupSeparator: ' ', max: 9999.99, theme: theme});
        $("#precioCartera").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 3, digits: 4, groupSeparator: '', max: 9999.99, theme: theme});        
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999});
        $("#comitente").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
                
        $("#codigo").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999});
        $("#especie").jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true}); 
        $("#arancel").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 3, groupSeparator: ' ', max: 999999999, theme: theme});
        $("#plazo").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 3, groupSeparator: ' ', max: 999999999, theme: theme});
//        $("#moneda").jqxDropDownList({ width: '300px', height: '25px', source: ['Peso', 'Dolar'], theme: theme, selectedIndex: 0, disabled: false});
        var monedas = [
//            { value: '1', label: 'Peso'},
            { value: '2', label: 'Dolar'}
        ];
        $("#moneda").jqxDropDownList({ width: '300px', height: '25px', source: monedas, theme: theme, disabled: true, selectedIndex: 0});


        $("#cantidad").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: '.', max: 999999999, theme: theme, value: 0});
        $("#brutoCliente").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 2, digits: 14, groupSeparator: '.', max: 99999999999999, theme: theme, value: precio, disabled: true});
        $("#observaciones").jqxInput({ width: '300px', height: '25px', theme: theme}); 

        $("#numComitenteContraparte").jqxInput({ width: '110px', height: '25px', theme: theme, value: '70002', disabled: true});


        $("#precioComitente").on('change', function(event){
            calcularBrutoCliente(event)
        });

        $("#cantidad").on('change', function(event){
            calcularBrutoCliente(event)
        });
        
        
        function calcularBrutoCliente(event){
            brutoCliente = 0;
            var args = event.args;      
            if (args){
                precio = $("#precioComitente").val();
                cantidad = $("#cantidad").val();

                brutoCliente = (parseFloat(precio) * parseFloat(cantidad)) / 100;
                brutoCliente = parseFloat(brutoCliente);          

                $("#brutoCliente").jqxNumberInput({value: brutoCliente}); 
                if( precio == 0 || cantidad == 0){
                    $("#brutoCliente").jqxNumberInput({value: 0}); 
                }
            }
        }


        /*
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
        */
        
        /*
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
        */
        
        
        
        
        
        
        $('#numComitente').on('change', function (event) {
            var value = $("#numComitente").val();
            $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
                comitente = pComitente;
                if (pComitente){
                    $("#comitente").val(pComitente.comitente);
                    $('#form').jqxValidator('hideHint', '#numComitente');
                } else {
                    $("#comitente").val('');
                }
            }, 'json');
        });
        
        
        $('#esPrecioComitente').on('change', function (event) {
                if ($('#esPrecioComitente').val() == true){
                    $(".esPrecioComitente").show();                
                } else {
                    $(".esPrecioComitente").hide();
                    $("#precioComitente").val('');
                }
            calcularBrutoCliente(event)
        });
        
        $('#esArancel').on('change', function (event) {
                if ($('#esArancel').val() == true){
                    $(".esArancel").show();                
                } else {
                    $(".esArancel").hide();
                    $("#arancel").val('');
                }
        });
        
        $('#codigo').on('change', function (event) {
            var value = $("#codigo").val();
            $.post('/esco/getEspecie', {codEspecie: value}, function(pCodEspecie){
                codEspecie = pCodEspecie;
                if (pCodEspecie){
                    $("#especie").val(pCodEspecie.Abreviatura);
                    $('#form').jqxValidator('hideHint', '#numComitente');
                } else {
                    $("#especie").val('');
                }
            }, 'json');
        });


    
        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Orden Treasuries');
            $("#operador").val($("#origen").val());
//            $("#filaCable").hide();
//            var url = '/treasuries/getCierreActual';
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
            $("#titulo").text('Editar Orden Treasuries');
            var datos = {
                id: $("#id").val()
            };
            $.post('/treasuries/getOrden', datos, function(data){
                cierre_id = data.cierretreasuries_id;
                
                $("#operador").val(data.operador);
                $("#tipoOperacion").val(data.tipoOperacion);
                $("#esPrecioComitente").val(data.esPrecioComitente);
                $("#precioComitente").val(data.precioComitente);
                $("#esArancel").val(data.esArancel);
                $("#arancel").val(data.arancel);
                $("#garantia").val(data.garantia);
                $("#precioCartera").val(data.precioCartera);
                $("#numComitente").val(data.numComitente);
                $("#especie").val(data.especie);
                $("#plazo").val(data.plazo);
//                $("#moneda").val(data.moneda);
                switch((data.moneda_id)){
                    case '1':
                        $("#moneda").jqxDropDownList('selectIndex', 0);
                        break;
                    case '2':
                        $("#moneda").jqxDropDownList('selectIndex', 1);
                        break;
                }
                $("#cantidad").val(data.cantidad);
                $("#codigo").val(data.codigo);
                $("#brutoCliente").val(data.brutoCliente);
                $("#observaciones").val(data.observaciones);
                $("#numComitenteContraparte").val(data.numComitenteContraparte);


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
//                var datos = {cierretreasuries_id: cierre_id};
//                url = '/treasuries/getCierre';
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
            
                { input: '#cantidad', message: 'La cantidad debe ser múltiplo de 100!', action: 'keyup, blur',  rule: function(){
                    if ( ($("#cantidad").val() % 100 ) !== 0 )  {
                        return false;
                    } else {
                        return true;
                    }
                }},
            
                { input: '#codigo', message: 'Debe ingresar el código de Especie!', action: 'keyup, blur',  rule: function(){
                    if ( $("#codigo").val() != 0 )  {
                        return true;
                    } else {
                        return false;
                    }
                }},
            
//                { input: '#codigo', message: 'Debe ingresar el código de Especie!',  rule: 'required' },
//                { input: '#plazo', message: 'Debe elegir el plazo!', action: 'change',  rule: function(){
//                    return ($("#plazo").val() >= 30);
//                }},
//                
//                
//                
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
//                { input: '#precioComitente', message: 'El precio comitente debe ser menor a 99.999.', action: 'keyup, blur',  rule: function(){
//                    if ($("#precioComitente").val() > 99.999) {
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
                $('#ventanaTreasuries').ajaxloader();
                var cable = 0;
                if ($("#cable").val()){
                    cable = 1;
                }
                datos = {
                    id: $("#id").val(),
                    operador: $("#operador").val(),
                    tipoOperacion: $("#tipoOperacion").val(),
                    esPrecioComitente: $("#esPrecioComitente").val(),
                    precioComitente: $("#precioComitente").val(),
                    esArancel: $("#esArancel").val(),
                    arancel: $("#arancel").val(),
                    garantia: $("#garantia").val(),
                    precioCartera: $("#precioCartera").val(),
                    numComitente: $("#numComitente").val(),
                    especie: $("#especie").val(),
                    plazo: $("#plazo").val(),
                    moneda: $("#moneda").val(),
                    cantidad: $("#cantidad").val(),
                    codigo: $("#codigo").val(),
                    brutoCliente: $("#brutoCliente").val(),
                    observaciones: $("#observaciones").val(),
                    numComitenteContraparte: $("#numComitenteContraparte").val(),

                };
                
                
                
                $.post('/treasuries/saveOrden', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/treasuries');
                    } else {
                        new Messi('Hubo un error guardando la orden', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaTreasuries').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
        
        
    });
    
</script>
