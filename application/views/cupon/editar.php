<input type="hidden" id="id" value="<?php echo $id;?>" >
<input type="hidden" id="origen" value="<?php echo $origen;?>" >
<div id="ventanaCupon">
    <div id="titulo">
        Editar Orden Cupon
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Numero Comitente: </td>
                    <td><div id="numComitente" ></div></td>
                </tr>
<!--                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Comisión %: </td>
                    <td><div id="comision"></div></td>
                </tr>-->
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Cantidad: </td>
                    <td><div id="cantidad"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Arancel: </td>
                    <td><div id="arancel"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Plazo: </td>
                    <td><div id="plazo"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Opcion: </td>
                    <td><div id="cmbBono"></div></td>
                </tr>
                
<!--                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Acciones acrecer: </td>
                    <td><div id="cantidadACrecer"></div></td>
                </tr>-->
<!--                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Precio: </td>
                    <td><div id="precio" ></div></td>
                </tr>-->
<!--                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Segunda Parte:</td>
                    <td><div id="segundaParte"></div></td>
                </tr>-->
<!--                <tr class="cantidadAcrecerSegunda">
                    <td style="padding-right:10px; padding-bottom: 10px">Acciones Acrecer 2da: </td>
                    <td><div id="cantidadAcrecerSegunda"></div></td>
                </tr>-->
                
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
                    <td style="padding-right: 10px; padding-bottom: 25px">Posición:</td>
                    <td><input type="text" id="posicion" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">WEB:</td>
                    <td><div id="estaConfirmado"></div></td>
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
        var cierrecupon_id = 0;
        var plazoCargado = 0;
        var posicion = false;
            var especie = '';
        var item = '';
        var tipo = '';

        $("#ventanaCupon").jqxWindow({showCollapseButton: false, height: 500, width: 500, theme: theme, resizable: false, keyboardCloseKey: -1});
        
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme});
        
//        $("#comision").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 1, groupSeparator: ' ', max: 99, theme: theme});
        $("#cantidad").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme});

        $("#arancel").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 1, groupSeparator: ' ', max: 99, theme: theme});


//$.post(url, datos, function(plazos){
//                    $("#plazo").jqxDropDownList('clear'); 
//                    $.each(plazos, function(index,value){
//                        
//                        
//                        $("#plazo").jqxDropDownList('addItem', value.plazo + ' ' + value.especie ); 
//                    });
//                    setDropDown("#plazo", plazoCargado);
//                }, 'json');


        var srcPlazos = {
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'plazo'},
                {name: 'especie'}
            ],
            data: {cierrecupon_id: cierrecupon_id},
            async: false,
            type: 'POST',
            id: 'id',
            url: '/cupon/getPlazosEspecies'
        }
        var DAPlazos = new $.jqx.dataAdapter(srcPlazos);       
//        $("#plazo").jqxDropDownList({ width: '110px', height: '25px', source: DAMonedas, theme: theme, placeHolder: 'elija plazo'});
        $("#plazo").jqxDropDownList({ width: '300px', height: '25px', source: DAPlazos, theme: theme, placeHolder: 'elija Plazo', displayMember: 'especie', valueMember: 'id'});
        
        var srcBonos = {
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'descripcion'},
                {name: 'tipo'}
            ],
            data: {
                    plazocupon_id: $("#plazo").val()
                },
            async: false,
            type: 'POST',
            id: 'id',
            url: '/cupon/getPlazosBono'
        }
        var DABonos = new $.jqx.dataAdapter(srcBonos);
        $("#cmbBono").jqxDropDownList({ width: '300px', height: '25px', source: DABonos, theme: theme, placeHolder: 'Elija bono', displayMember: 'descripcion', valueMember: 'id'});
 
//        $('#cmbBono').on('change', function (event){     
//            
//            var item = $('#cmbBono').jqxDropDownList('val');
//            var item2 = $('#cmbBono').jqxDropDownList('getSelectedItem');
////            var vista_id = item.value;
//            
//            console.log( item );
//            console.log( item2.originalItem.tipo );
//        });
 
        $('#plazo').on('change', function (event){     
            cargarComboPlazoBono(event);
            calcularPosicion(event);
        });
        
        function cargarComboPlazoBono(event){
            var args = event.args;
            if (args) {
                // index represents the item's index.                      
                var item = args.item;
                var value = item.value;
                srcBonos.data = {
                    plazocupon_id: $("#plazo").val()
                };
                DABonos.dataBind();
                var items = $("#cmbBono").jqxDropDownList('getItems'); 
                if(items.length > 0){
                    $("#cmbBono").jqxDropDownList('selectIndex', 0 ); 
                }
            }
        }
        
//        $("#cantidadACrecer").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme});
//        $("#precio").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 7, groupSeparator: ' ', max: 999999999.9, theme: theme});
//        $("#segundaParte").jqxCheckBox({height: '20px', theme: theme});
//        $("#cantidadAcrecerSegunda").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme});
        $("#comitente").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        $("#tipoPersona").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        $("#oficial").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        $("#cuit").jqxInput({ width: '110px', height: '25px', disabled: true, theme: theme});
        $("#posicion").jqxInput({ width: '110px', height: '25px', disabled: true, theme: theme});
//        $(".cantidadAcrecerSegunda").hide();
        $("#estaConfirmado").jqxCheckBox({ width: 200, height: 20, theme: theme });
        
        
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
        
        
        $('#numComitente').on('valueChanged', function (event) {
            calcularPosicion(event);
        });
        
        
        function calcularPosicion(event){
            
            var value = $("#numComitente").val();
            var plazo = $("#plazo").val();

            $.post('/cupon/getEspecie', { plazo: $("#plazo").val() }, function(data){
                especie = data;
                $.post('/esco/getPosicion', {numComitente: value, especie: especie}, function(pPosicion){

                    console.log(pPosicion);
                    posicion = pPosicion;
                    if (pPosicion){
                        $("#posicion").val(pPosicion.Cantidad);
                        $('#form').jqxValidator('hideHint', '#numComitente');
                    } else {
                        $("#posicion").val(0);
                        $('#form').jqxValidator('hideHint', '#numComitente');
                    }
                }, 'json');
            
            }, 'json');
        }
        
        
        
        
        
        
        
        
        
        
        
        
//        $('#segundaParte').on('change', function (event) { 
//            var checked = event.args.checked; 
//            if (checked){
//                $(".cantidadAcrecerSegunda").show();
//            } else {
//                $(".cantidadAcrecerSegunda").hide();
//            }
//        }); 
        
        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Orden Cupones');
            $("#filaCable").hide();
        } else {
            $("#titulo").text('Editar Orden Cupones');
            datos = {
                id: $("#id").val()
            };
            $.post('/cupon/getOrden', datos, function(data){
                cierrecupon_id = data.cierrecupon_id;
                $("#numComitente").val(data.numcomitente);
//                $("#comision").val(data.comision);
                $("#cantidad").val(data.cantidad);
                $("#arancel").val(data.arancel);
////                $("#cantidadACrecer").val(data.cantidadACrecer);
//                $("#cmbBono").jqxDropDownList('val', data.bono);
//                $("#plazo").jqxDropDownList('val', data.plazo);
                setDropDown("#plazo", data.plazo);
                setDropDown("#cmbBono", data.bono);

                $("#estaConfirmado").val(data.estaConfirmado);
                
//                $("#segundaParte").jqxCheckBox('uncheck');
//                if (data.segundaParte == 1){
//                    $("#segundaParte").jqxCheckBox('check');
//                    $("#cantidadAcrecerSegunda").val(data.cantidadAcrecerSegunda);
//                } else {
//                    $("#cantidadAcrecerSegunda").val(0);
//                }
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
                { input: '#cantidad', message: 'Cantidad incorrecta!', action: 'keyup, blur',  rule: function(){
                    var result = true;
                    var minimo;
                    var multiplo;
                    minimo = 0;
                    multiplo = 1;
                    
                    
                    var cantidad = $("#cantidad").val();
                    $('#form').jqxValidator('hideHint', '#cantidad');
                    if (cantidad < minimo){
                        $('#form').jqxValidator('rules')[1].message = "La cantidad debe ser mayor o igual que " + minimo.toString() + "!";
                        result = false;
                    } else {
                        if (cantidad % multiplo > 0){
                            $('#form').jqxValidator('rules')[1].message = "La cantidad debe ser multiplo de " + multiplo.toString() +"!";
                            result = false;
                        }
                    }
                    return result;
                }},
            
            { input: '#plazo', message: 'Debe elegir el plazo!', action: 'change',  rule: function(){
                    return ($("#plazo").val() > 0);
                }},
            
//                { input: '#cantidadACrecer', message: 'Cantidad incorrecta!', action: 'keyup, blur',  rule: function(){
//                    var result = true;
//                    var minimo;
//                    var multiplo;
//                    minimo = 0;
//                    multiplo = 1;
//                    
//                    
//                    var cantidad = $("#cantidadACrecer").val();
//                    $('#form').jqxValidator('hideHint', '#cantidadACrecer');
//                    if (cantidad < minimo){
//                        $('#form').jqxValidator('rules')[2].message = "La cantidad debe ser mayor o igual que " + minimo.toString() + "!";
//                        result = false;
//                    } else {
//                        if (cantidad % multiplo > 0){
//                            $('#form').jqxValidator('rules')[2].message = "La cantidad debe ser multiplo de " + multiplo.toString() +"!";
//                            result = false;
//                        }
//                    }
//                    return result;
//                }},
//                { input: '#precio', message: 'El precio debe ser 0 (No Competitiva) o estar entre 7 y 16!', action: 'keyup, blur',  rule: function(){
//                        var result = true;
//                        if ($("#precio").val() < 7){
//                            result = false;
//                        }
//                        if ($("#precio").val() > 16){
//                            result = false;
//                        }
//                        if ($("#precio").val() == 0){
//                            result = true;
//                        }
//                        return result;
//                }},
//                { input: '#precio', message: 'El precio debe ser menor o igual que 16!', action: 'keyup, blur',  rule: function(){
//                        var result = true;
//                        if ($("#precio").val() > 16){
//                            result = false;
//                        }
//                        return result;
//                }},            
//                { input: '#comision', message: 'Valor incorrecto!', action: 'keyup, blur',  rule: function(){
//                    if ($("#comision").val() > 3) {
//                        return false;
//                    } else {
//                        return true;
//                    }
//                }},
//                { input: '#cantidadAcrecerSegunda', message: 'Cantidad incorrecta!', action: 'keyup, blur',  rule: function(){
//                    var result = true;
//                    var minimo;
//                    var multiplo;
//                    minimo = 0;
//                    multiplo = 1;
//                    
//                    
//                    var cantidad = $("#cantidadAcrecerSegunda").val();
//                    $('#form').jqxValidator('hideHint', '#cantidadACrecer');
//                    if (cantidad < minimo){
//                        $('#form').jqxValidator('rules')[4].message = "La cantidad debe ser mayor o igual que " + minimo.toString() + "!";
//                        result = false;
//                    } else {
//                        if (cantidad % multiplo > 0){
//                            $('#form').jqxValidator('rules')[4].message = "La cantidad debe ser multiplo de " + multiplo.toString() +"!";
//                            result = false;
//                        }
//                    }
//                    return result;
//                }},
            ], 
            theme: theme
        });
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        
        $('#aceptarButton').jqxButton({ theme: theme, width: '65px' });
        $('#aceptarButton').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){                
                $('#ventanaCupon').ajaxloader();
//                var segundaParte = 0;
//                var cantidadAcrecerSegunda = 0;
//                if ($("#segundaParte").val()){
//                    segundaParte = 1;
//                    cantidadAcrecerSegunda = $("#cantidadAcrecerSegunda").val();
//                }
                
                
                var item = $('#cmbBono').jqxDropDownList('getSelectedItem');
                var tipo = item.originalItem.tipo;
                
                var datos = {
                    id: $("#id").val(),
                    numComitente: $("#numComitente").val(),
//                    comision: $("#comision").val(),
                    cantidad: $("#cantidad").val(),
                    arancel: $("#arancel").val(),
                    plazo: $("#plazo").val(),
                    
                    bono: $("#cmbBono").val(),
                    bonoNombre: $("#cmbBono").jqxDropDownList('getSelectedItem').label,
                    tipo: tipo,
//                    cantidadACrecer: $("#cantidadACrecer").val(),
//                    precio: $("#precio").val(),
//                    segundaParte: segundaParte,
//                    cantidadAcrecerSegunda: cantidadAcrecerSegunda,
                    comitente: $("#comitente").val(),
                    tipoPersona: $("#tipoPersona").val(),
                    oficial: $("#oficial").val(),
                    cuit: $("#cuit").val(),
                    posicion: $("#posicion").val(),
                    estaConfirmado: $("#estaConfirmado").val(),
                };
                $.post('/cupon/saveOrden', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/cupon');
                    } else {
                        new Messi('Hubo un error guardando la orden', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaCupon').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
        
        
    });
    
    //Aca va el codigo de la calculadora de lebacs
    $(function(){
        
    });
</script>