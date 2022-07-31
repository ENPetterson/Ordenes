<input type="hidden" id="id" value="<?php echo $id;?>" >
<input type="hidden" id="origen" value="<?php echo $origen;?>" >
<input type="hidden" id="usuario" value="<?php echo $usuario;?>" >
<div id="ventanaParking">
    <div id="titulo">
        Editar Orden Parking
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Operador:</td>
                    <td><input type="text" id="operador" style="width: 250px"></td>
                </tr>
<!--                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Tipo de Operación:</td>
                    <td><div id="tipoOperacion"></div></td>
                </tr>-->
                
<!--                <tr>
                    <td colspan="2" style="padding-right: 10px; padding-bottom: 10px"> <font color="#ff0000">Atención, el tipo de cambio de parking es el de banco nacion vendedor.</font></td>
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
                    <td><input type="hidden" id="personaJuridica" style="width: 250px"></td>
                </tr>
                
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Código Especie:</td>
                    <td><div id="codigo"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Abreviatura:</td>
                    <td><input type="text" style="text-transform: uppercase" id="especie" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Descripción:</td>
                    <td><input type="text" id="especieDescripcion" style="width: 250px"></td>
                </tr>
                
                
                <tr>
                    <!--<td style="padding-right: 10px; padding-bottom: 10px">Parking:</td>-->
                    <td><input type="hidden" id="parking"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Cantidad:</td>
                    <td><div id="cantidad"></div></td>
                </tr>
                
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Moneda:</td>
                    <td><div id="moneda"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Es Misma Moneda:</td>
                    <td><div id="esMismaMoneda"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">PJ cable a mep:</td>
                    <td><div id="esCableMep"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">Observaciones:</td>
                    <td><input type="text" id="observaciones" style="width: 250px"></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center">
                    </td>
                </tr>

                
                
                <tr>
                    <td colspan="2" style="text-align: center">
                        <input type="button" id="aceptarButton" value="Aceptar">
                        <!--<input type="button" id="obtenerParkingButton" value="Parking">-->
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
        var riesgoComitente = '';
        var plazoCargado = 0;
        var cierre_id = 0;
        var minimos = [];
        var minimo = 0;
        var precio = 0;
        var cantidad = 0;
        var brutoCliente = 0;
        var codEspecie = false;
        var especie = false;
        var numComitente = 0;
        
        var p = 0;
        var pc = 0;
        
        var pm = 0;
        var pcm = 0;

        
       
        $("#ventanaParking").jqxWindow({showCollapseButton: false, height: 800, width: 500, theme: theme, resizable: false, keyboardCloseKey: -1, maxHeight: 1000});

        $("#ventanaResumen").jqxWindow({autoOpen: false, height: 500, width:470, position: {x: 5, y: 50}, theme: theme });

        
        $("#operador").jqxInput({ width: '300px', height: '25px', theme: theme});
//        $("#tipoOperacion").jqxDropDownList({ width: '300px', height: '25px', source: ['Compra', 'Venta'], theme: theme, selectedIndex: 0, disabled: false});
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999});
        $("#comitente").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        $("#personaJuridica").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        
        $("#codigo").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999});
        $("#especie").jqxInput({ width: '300px', height: '25px', theme: theme}); 
        $("#especieDescripcion").jqxInput({ width: '300px', height: '25px', theme: theme}); 
 
        $("#parking").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 2, digits: 9, groupSeparator: ' ', max: 999999999, disabled: true});
        $("#cantidad").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: '.', max: 999999999, theme: theme, value: 0});
        $("#observaciones").jqxInput({ width: '300px', height: '25px', theme: theme}); 



//        var srcMonedas =
//            {
//                datatype: "json",
//                datafields: [
//                    { name: 'id'},
//                    { name: 'simbolo'},
//                    { name: 'nombre'}
//                ],
//                id: 'id',
//                url: '/parking/getMonedas',
//                async: false
//            };
//        var DAMonedas = new $.jqx.dataAdapter(srcMonedas);

        var srcMonedas = [
            { id: 1, simbolo: '$', nombre: 'Mep'},
            { id: 2, simbolo: 'u$s', nombre: 'Cable'}
        ];
        var DAMonedas = new $.jqx.dataAdapter(srcMonedas);

        $("#moneda").jqxDropDownList({ selectedIndex: -1, source: DAMonedas, displayMember: "nombre", valueMember: "id", width: 300, height: 25, theme: theme, placeHolder: "Elija moneda:" });

        $("#esMismaMoneda").jqxCheckBox({ width: 200, height: 20, theme: theme });
        $("#esCableMep").jqxCheckBox({ width: 200, height: 20, theme: theme });

       

        $('#numComitente').on('change', function (event) {
            var value = $("#numComitente").val();
            $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
                comitente = pComitente;
                if (pComitente){
                    $("#comitente").val(pComitente.comitente);
                    $("#personaJuridica").val(pComitente.esFisico);
                    $('#form').jqxValidator('hideHint', '#numComitente');
                    
                    if (!bowser.msie){
                        $("#ventanaResumen").jqxWindow('open');
                        srcOrdenes.data = {cierre_id: cierre_id, numComitente: $('#numComitente').val()};
                        $("#grillaOrdenes").jqxGrid('updatebounddata');
                    }
                    
                } else {
                    $("#comitente").val('');
                    $("#personaJuridica").val('');
                    $("#ventanaResumen").jqxWindow('close');                
                }
            }, 'json');
        });
        
        
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
        
        jQuery.ajaxSetup({async:false});
        
        $('#especie').on('change', function (event) {

            var value = $("#especie").val();
            $.post('/esco/getEspecieDescripcion', {especie: value}, function(pEspecie){
                especie = pEspecie;
                if (pEspecie){
                    $("#codigo").val(pEspecie.CodigoEspecie);
                    $("#especieDescripcion").val(pEspecie.Descripcion);
                    $('#form').jqxValidator('hideHint', '#especie');
                } else {
                    $("#codigo").val('');
                    $("#especieDescripcion").val('');
                }
            }, 'json');
        });


        $('#codigo').on('change', function (event) {
            var value = $("#codigo").val();
            $.post('/esco/getEspecie', {codEspecie: value}, function(pCodEspecie){
                codEspecie = pCodEspecie;
                if (pCodEspecie){
                    $("#especie").val(pCodEspecie.Abreviatura);
                    $("#especieDescripcion").val(pCodEspecie.Descripcion);
                    $('#form').jqxValidator('hideHint', '#codigo');
                } else {
                    $("#especie").val('');
                    $("#especieDescripcion").val('');
                }
            }, 'json');
        });
        
        jQuery.ajaxSetup({async:true});
    
        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Orden Parking');
            $("#operador").val($("#origen").val());
        } else {
            $("#titulo").text('Editar Orden Parking');
            var datos = {
                id: $("#id").val()
            };
            $.post('/parking/getOrden', datos, function(data){
                cierre_id = data.cierreparking_id;
                
                $("#operador").val(data.operador);
//                $("#tipoOperacion").val(data.tipoOperacion);
//                $("#precioComitente").val(data.precioComitente);
//                $("#precioCartera").val(data.precioCartera);
                $("#numComitente").val(data.numComitente);
                $("#comitente").val(data.descripcionComitente);
                $("#especie").val(data.especie);
                $("#especieDescripcion").val(data.especieDescripcion);
//                $("#arancel").val(data.arancel);
//                $("#plazo").val(data.plazo);
//                $("#moneda").val(data.moneda);
//                switch((data.moneda_id)){
//                    case '1':
//                        $("#moneda").jqxDropDownList('selectIndex', 0);
//                        break;
//                    case '2':
//                        $("#moneda").jqxDropDownList('selectIndex', 1);
//                        break;
//                }
                $("#parking").val(data.parking);
                $("#cantidad").val(data.cantidad);
                $("#codigo").val(data.codigo);
//                $("#brutoCliente").val(data.brutoCliente);
                $("#observaciones").val(data.observaciones);
                
                $("#moneda").val(data.moneda);
                
                $("#esMismaMoneda").val(data.esMismaMoneda);
                $("#esCableMep").val(data.esCableMep);

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
            
                { input: '#cantidad', message: 'Debe ingresar cantidad!', action: 'keyup, blur',  rule: function(){
                    if ( $("#cantidad").val() == 0 )  {
                        return false;
                    } else {
                        return true;
                    }
                }},

                { input: '#especie', message: 'Debe ingresar Especie!', action: 'keyup, blur',  rule: function(){
                    if ( $("#especie").val() != '' )  {
                        return true;
                    } else {
                        return false;
                    }
                }},
                { input: '#moneda', message: 'Debe Seleccionar la moneda!', action: 'keyup, blur',  rule: function(){
                    return ($("#moneda").jqxDropDownList('getSelectedIndex') != -1);
                }},
            
            
                { input: '#esCableMep', message: 'Debe ser Persona Juridica!', action: 'keyup, blur',  rule: function(){
//                    console.log( $("#esMismaMoneda").val() );
                    if ( $("#esCableMep").val() == true && $("#personaJuridica").val() == -1 )  {
                        return false;
                    } else {
                        return true;
                    }
                }},
            
                { input: '#esMismaMoneda', message: 'Debe elegir solo una opcion!', action: 'keyup, blur',  rule: function(){
//                    console.log( $("#esMismaMoneda").val() );
                    if ( $("#esMismaMoneda").val() == true && $("#esCableMep").val() == true )  {
                        return false;
                    } else {
                        return true;
                    }
                }},
            ], 
            theme: theme
        });
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        
        
//        $('#obtenerParkingButton').jqxButton({ theme: theme, width: '65px' });
//        $('#obtenerParkingButton').bind('click', function () {
//            $('#form').jqxValidator('validate');
//            if (formOK){
//                $('#ventanaParking').ajaxloader();
//                
//                datos = {
//                    id: $("#id").val(),
//                    numComitente: $("#numComitente").val(),
//                    especie: $("#especie").val(),
//                };
//                $.post('/parking/getParkingEsco', datos, function(data){
//                    if (data){
//                        $("#parking").val(data.resultado);
//                        new Messi('Parking: '+data.resultado, {title: 'Ok', 
//                        buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'success'});
//                        $('#ventanaParking').ajaxloader('hide');                    
//                    } else {
//                        new Messi('Hubo un error obteniendo el Parking', {title: 'Error', 
//                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
//                        $('#ventanaParking').ajaxloader('hide');
//                    }
//                }, 'json');
//            }
//        });
        
        
        $('#aceptarButton').jqxButton({ theme: theme, width: '65px' });
        $('#aceptarButton').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                $('#ventanaParking').ajaxloader();
                
                jQuery.ajaxSetup({async:false});
                
//                datos = {
//                    id: $("#id").val(),
//                    numComitente: $("#numComitente").val(),
//                    especie: $("#especie").val(),
//                };
                //Esto es viejo, el parking ya no se va a calcular en el editar, sino cuando se envia la orden
                /*
                $.post('/parking/getParkingEsco', datos, function(data){
                    if (data){
                        $("#parking").val(data.resultado);
//                        new Messi('Parking: '+data.resultado, {title: 'Ok', 
//                        buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'success'});
//                        $('#ventanaParking').ajaxloader('hide');                    
                    } 
                    else {
                        new Messi('Hubo un error guardando Orden Parking', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaParking').ajaxloader('hide');
                    }
                }, 'json');
                */
                
                
                datos = {
                    id: $("#id").val(),
                    operador: $("#operador").val(),
//                    tipoOperacion: $("#tipoOperacion").val(),
//                    precioComitente: $("#precioComitente").val(),
//                    precioCartera: $("#precioCartera").val(),
                    numComitente: $("#numComitente").val(),
                    descripcionComitente: $("#comitente").val(),
                    especie: $("#especie").val(),
                    especieDescripcion: $("#especieDescripcion").val(),
//                    arancel: $("#arancel").val(),
//                    plazo: $("#plazo").val(),
//                    moneda: $("#moneda").val(),
                    parking: $("#parking").val(),
                    cantidad: $("#cantidad").val(),
                    codigo: $("#codigo").val(),
//                    brutoCliente: $("#brutoCliente").val(),
                    observaciones: $("#observaciones").val(),
                    
                    moneda: $("#moneda").val(),
                    esMismaMoneda: $("#esMismaMoneda").val(),
                    esCableMep: $("#esCableMep").val(),
//                    numComitenteContraparte: $("#numComitenteContraparte").val(),

//                    deriva: $("#deriva").val(),
//                    riesgoComitente: $("#riesgoComitente").val(),
//                    riesgo: $("#riesgo").val(),
//                    rangoPrecios: $("#rangoPrecios").val(),
                    
                };
                $.post('/parking/saveOrden', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/parking');
                    } else {
                        new Messi('Hubo un error guardando la orden', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaParking').ajaxloader('hide');
                    }
                }, 'json');
                
                jQuery.ajaxSetup({async:false});

            }
        });      
        
        
        
        
        
        var srcOrdenes = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'numComitente'},
                { name: 'especie'},
                { name: 'cantidad'},
                { name: 'estado'}
            ],
            cache: false,
            url: '/parking/getOrdenesParking',
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
                width: 450,
                height: 400,
                columns: [
                        { text: 'Id', datafield: 'id', width: 60, cellsalign: 'right', cellsformat: 'd', aggregates: ['count'] },
                        { text: 'numComitente', datafield: 'numComitente', width: 80},
                        { text: 'especie', datafield: 'especie', width: 80},
                        { text: 'cantidad', datafield: 'cantidad', width: 60, cellsalign: 'right'},
                        { text: 'estado', datafield: 'estado', width: 140, cellsalign: 'right' }
                ]
        });
        $("#grilla").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grilla").jqxGrid('localizestrings', localizationobj);
            $("#numComitente").focus();
        }); 
        
        
        
    });
    
</script>
