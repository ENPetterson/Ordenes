<input type="hidden" id="id" value="<?php echo $id;?>" >
<input type="hidden" id="origen" value="<?php echo $origen;?>" >
<input type="hidden" id="usuario" value="<?php echo $usuario;?>" >
<div id="ventanaFondo">
    <div id="titulo">
        Editar Orden Fondo
    </div>
    <div>
        <form id="form">
            <table>
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">operador:</td>
                    <td><input type="text" id="operador" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 3px">fechaConcertacion: </td>
                    <td><div id="fechaConcertacion"></div></td>
                </tr>                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Número Comitente: (*)</td>
                    <td><div id="numComitente" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Comitente:</td>
                    <td><input type="text" id="comitente" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">operacion: (*)</td>
                    <td><div id="operacion"></div></td>
                </tr>
                
                
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">fondo: (*)</td>
                    <td><div id="fondo" ></div></td>
                </tr>               
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">rescate:</td>
                    <td><div id="rescate" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">importe:</td>
                    <td><div id="importe" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">esAcdi: (*)</td>
                    <td><div id="esAcdi"></div></td>
                </tr>
                <tr>
                    <td class="noEsAcdi" style="padding-right: 10px; padding-bottom: 25px">Garantia:</td>
                    <td class="noEsAcdi" ><div id="noEsAcdiTipo"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">destinoRescate: (*)</td>
                    <td><div id="destinoRescate"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">cantidadCuotapartes:</td>
                    <td><div id="cantidadCuotapartes" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">ValorCuota:</td>
                    <td><div id="totalCuotapartes" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">saldoMonetario $:</td>
                    <td><div id="saldoMonetario" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">saldoMonetario u$s:</td>
                    <td><div id="saldoMonetarioDolar" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">saldoMonetario MEP:</td>
                    <td><div id="saldoMonetarioMep" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">origenFondos: (*)</td>
                    <td><div id="origenFondos"></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">moneda:</td>
                    <td><input type="text" id="moneda" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">observaciones:</td>
                    <td><input type="text" id="observaciones" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">saldoAcdi:</td>
                    <td><div id="saldoAcdi" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">saldoColocadorSimple:</td>
                    <td><div id="saldoColocadorSimple" ></div></td>
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
        var cierre_id = 0;
        var saldoAcdi = null;
        var saldoColocadorSimple = null;
        var numComitente = 0;
        var esFisico = 0;
        var esFisicoFondo = 0;
        var esFisicoJuridicoFondo = 0;
        var cantidadCuotapartes = 0;
        var importe = 0;
        var moneda = '';
        
        
        
        
        $("#ventanaFondo").jqxWindow({showCollapseButton: false, height: 800, width: 600, theme: theme, resizable: false, keyboardCloseKey: -1, maxHeight: 1000});        

        $("#operador").jqxInput({ width: '300px', height: '25px', theme: theme, disabled: true}); 
        $("#fechaConcertacion").jqxDateTimeInput({ formatString: "yyyy-MM-dd", showTimeButton: true, width: '250px', height: '25px', theme: theme, disabled: true });
        $("#operacion").jqxDropDownList({ width: '300px', height: '25px', source: ['SUSC', 'RESC'], theme: theme, selectedIndex: 0, disabled: false});

        var srcFondo =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'CodFondo'},
                    { name: 'Abreviatura'}
                ],
                id: 'id',
                url: '/esco/getFondos',
                async: false
            };
        var DAFondo = new $.jqx.dataAdapter(srcFondo);
        $("#fondo").jqxDropDownList({ selectedIndex: -1, source: DAFondo, displayMember: "Abreviatura", valueMember: "CodFondo", width: 300, height: 25, theme: theme, placeHolder: "Elija un fondo:" });
        DAFondo.dataBind();  

        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999});
        $("#comitente").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        
        var rescate = [
            { value: '', label: ''},
            { value: 'TOTAL', label: 'TOTAL'}
        ];
        $("#rescate").jqxDropDownList({ width: '300px', height: '25px', source: rescate, theme: theme, placeHolder: 'elija', selectedIndex: 0});
        $("#importe").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 4, digits: 10, groupSeparator: ' ', max: 9999999999, disabled: false});

        var esAcdi = [
            { value: '', label: ''},
            { value: 'SI', label: 'SI'}
        ];
        $("#esAcdi").jqxDropDownList({selectedIndex: 0, width: '300px', height: '25px', source: esAcdi, theme: theme, placeHolder: "Elija si es Acdi:"});
//        $("#esAcdi").jqxDropDownList('selectIndex', 1);
//        $("#esAcdi").jqxDropDownList({disabled: true});
        
        var noEsAcdiTipo = [
            { value: '', label: ''},
            { value: 'ROFEX', label: 'ROFEX'},
            { value: 'CAUCION', label: 'CAUCION'}
        ];
        $("#noEsAcdiTipo").jqxDropDownList({selectedIndex: 0, width: '300px', height: '25px', source: noEsAcdiTipo, theme: theme, placeHolder: 'elija opción'});
               
        var destinoRescate = [
            { value: '', label: ''},
            { value: 'CUBRE', label: 'CUBRE'},
            { value: 'TRANSFIERE', label: 'TRANSFIERE'}
        ];
        $("#destinoRescate").jqxDropDownList({selectedIndex: 0, width: '300px', height: '25px', source: destinoRescate, theme: theme, placeHolder: 'elija el destino de Rescate'});
       
        $("#cantidadCuotapartes").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 4, digits: 13, groupSeparator: '', max: 99999999999999, disabled: true});
       
        $("#totalCuotapartes").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 6, digits: 13, groupSeparator: '', max: 99999999999999, disabled: true});        
        
        $("#saldoMonetario").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 4, digits: 13, groupSeparator: '', max: 99999999999999, disabled: true});
        $("#saldoMonetarioDolar").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 4, digits: 13, groupSeparator: '', max: 99999999999999, disabled: true});
        $("#saldoMonetarioMep").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 4, digits: 13, groupSeparator: '', max: 99999999999999, disabled: true});

        var origenFondos = [
            { value: '', label: ''},
            { value: 'CI', label: 'CI'},
            { value: 'TRANSFERENCIA', label: 'TRANSFERENCIA'},
            { value: 'SALDO EN CUENTA', label: 'SALDO EN CUENTA'}
        ];
        $("#origenFondos").jqxDropDownList({selectedIndex: 0, width: '300px', height: '25px', source: origenFondos, theme: theme, placeHolder: 'elija el origen de Fondos'});

        $("#moneda").jqxInput({ width: '300px', height: '25px', theme: theme}); 
        $("#observaciones").jqxInput({ width: '300px', height: '25px', theme: theme}); 
        $("#saldoAcdi").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 4, digits: 13, groupSeparator: '', max: 99999999999999, disabled: true});
        $("#saldoColocadorSimple").jqxNumberInput({ width: '300px', height: '25px', decimalDigits: 4, digits: 13, groupSeparator: '', max: 99999999999999, disabled: true});

        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Orden Fondo');
            $("#operador").val($("#origen").val());
        } else {
            $("#titulo").text('Editar Orden Fondo');
            var datos = {
                id: $("#id").val()
            };
            $.post('/fondo/getOrden', datos, function(data){
                cierre_id = data.cierrefondo_id;
                $("#operador").val(data.operador);
                $("#fechaConcertacion").val(data.fechaConcertacion);
                $("#operacion").val(data.operacion);
                
                if($("#esAcdi").val() == '' && $("#operacion").val() == 'SUSC'){
                    $(".noEsAcdi").show();
                }else{
                    $(".noEsAcdi").hide();
                }
                
                $("#fondo").val(data.fondo);
                $("#numComitente").val(data.numComitente);
                $("#comitente").val(data.nombreComitente);
                $("#rescate").val(data.rescate);
                $("#importe").val(data.importe);
                $("#esAcdi").val(data.brutoCliente);
                $("#noEsAcdiTipo").val(data.noAcdiTipo);
                $("#destinoRescate").val(data.origenFondos);
                $("#cantidadCuotapartes").val(data.cantidadCuotapartes);
                $("#totalCuotapartes").val(data.totalCuotapartes);
                $("#saldoMonetario").val(data.saldoMonetario);
                $("#saldoMonetarioDolar").val(data.saldoMonetarioDolar);
                $("#saldoMonetarioMep").val(data.saldoMonetarioMep);
                $("#origenFondos").val(data.origenFondos);
                $("#usuario").val(data.operador);
                $("#moneda").val(data.ctteContraparte);
                $("#observaciones").val(data.observaciones);
                $("#saldoAcdi").val(data.saldoAcdi);
                $("#saldoColocadorSimple").val(data.saldoColocadorSimple);                
            }
            , 'json');
        };


        $('#numComitente').on('change', function (event) {
            var value = $("#numComitente").val();
            $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
                comitente = pComitente;
                
                esFisico = (pComitente.esFisico);

                if (pComitente){
                    $("#comitente").val(pComitente.comitente);
                    $('#form').jqxValidator('hideHint', '#numComitente');
                    
                    if (!bowser.msie){
                        $("#ventanaResumen").jqxWindow('open');
                        srcOrdenes.data = {cierre_id: cierre_id, numComitente: $('#numComitente').val()};
                        $("#grillaOrdenes").jqxGrid('updatebounddata');
                    }
                                        
                    $.post('/esco/getDisponible', {numComitente: value}, function(pComitente){
                        if (pComitente){

                            var saldoMonetarioPesos = false;
                            var saldoMonetarioDolar = false;
                            var saldoMonetarioMep = false;

                            $.each(pComitente, function(index,value){
                                if(value.SimbMoneda == '$'){
                                    saldoMonetarioPesos = true;
                                    $("#saldoMonetario").val(value.SaldoDisponible);
                                }
                                if(value.SimbMoneda == 'U$S'){
                                    saldoMonetarioDolar = true;
                                    $("#saldoMonetarioDolar").val(value.SaldoDisponible);
                                }
                                if(value.SimbMoneda == 'M'){
                                    saldoMonetarioMep = true;
                                    $("#saldoMonetarioMep").val(value.SaldoDisponible);
                                }
                            });

                            if(saldoMonetarioPesos == false){
                                $("#saldoMonetario").val('');                    
                            }
                            if(saldoMonetarioDolar == false){
                                $("#saldoMonetarioDolar").val('');
                            }
                            if(saldoMonetarioMep == false){
                                $("#saldoMonetarioMep").val('');
                            }

                        } else {
                            $("#saldoMonetario").val('');
                            $("#saldoMonetarioDolar").val('');
                            $("#saldoMonetarioMep").val('');
                        }
                    }, 'json');
                } else {
                    $("#comitente").val('');
                    $("#ventanaResumen").jqxWindow('close');
                }
            }, 'json');
            $("#fondo").jqxDropDownList({ selectedIndex: -1, placeHolder: "Elija un fondo:" });
            cargarComboFondo(event);
            IndicarSaldos(event);
//            importeTotal(event);
        });

        $('#fondo').on('change', function (event) {
            var value = $("#fondo").val(); 

            $.post('/esco/getFondo', {fondo: value}, function(pFondo){
                
                esFisicoFondo = (pFondo.EsFisico);    
                esFisicoJuridicoFondo = (pFondo.EsFisicoJuridico); 
                
                if (pFondo){
                    $("#moneda").val(pFondo.Simbolo);
                } else {
                    $("#moneda").val('');
                }
            }, 'json');
            $.post('/esco/getDisponible', {numComitente: $("#numComitente").val()}, function(pComitente){
                        if (pComitente){

                            var saldoMonetarioPesos = false;
                            var saldoMonetarioDolar = false;
                            var saldoMonetarioMep = false;

                            $.each(pComitente, function(index,value){
                                if(value.SimbMoneda == '$'){
                                    saldoMonetarioPesos = true;
                                    $("#saldoMonetario").val(value.SaldoDisponible);
                                }
                                if(value.SimbMoneda == 'U$S'){
                                    saldoMonetarioDolar = true;
                                    $("#saldoMonetarioDolar").val(value.SaldoDisponible);
                                }
                                if(value.SimbMoneda == 'M'){
                                    saldoMonetarioMep = true;
                                    $("#saldoMonetarioMep").val(value.SaldoDisponible);
                                }
                            });

                            if(saldoMonetarioPesos == false){
                                $("#saldoMonetario").val('');                    
                            }
                            if(saldoMonetarioDolar == false){
                                $("#saldoMonetarioDolar").val('');
                            }
                            if(saldoMonetarioMep == false){
                                $("#saldoMonetarioMep").val('');
                            }

                        } else {
                            $("#saldoMonetario").val('');
                            $("#saldoMonetarioDolar").val('');
                            $("#saldoMonetarioMep").val('');
                        }
            }, 'json');
            
//            jQuery.ajaxSetup({async:true});
            
            $.post('/esco/getValorCuota', {fondo: value}, function(pFondo){
                if (pFondo){
                    $("#totalCuotapartes").val(pFondo.Cotizacion);
                    
                    if(pFondo.Cotizacion){                        
                        cantidadCuotapartes = parseFloat($("#importe").val()) / parseFloat(pFondo.Cotizacion);                        
                        $("#cantidadCuotapartes").val(cantidadCuotapartes);
                    }
                    
                } else {
                    $("#totalCuotapartes").val('');
                }
            }, 'json');
            IndicarSaldos(event);
            
            
//            importeTotal(event);
        });


        $('#importe').on('change', function (event){
            if($("#totalCuotapartes").val()){
                cantidadCuotapartes = parseFloat($("#importe").val()) / parseFloat($("#totalCuotapartes").val());                        
                 $("#cantidadCuotapartes").val(cantidadCuotapartes);
            }
        });
        
        $('#rescate').on('change', function (event){
//            importeTotal(event);
            if($("#operacion").val() == 'RESC'){
                if( $("#rescate").jqxDropDownList('getSelectedIndex') == 1 && $("#saldoColocadorSimple").val() != null && $("#saldoColocadorSimple").val() != 0 ){
//                    $("#esAcdi").jqxDropDownList({ selectedIndex: 0});
//                    $("#esAcdi").jqxDropDownList({disabled: true});                    
                }else{
//                    $("#esAcdi").jqxDropDownList({ selectedIndex: 0});
//                    $("#esAcdi").jqxDropDownList({disabled: false});
                }
            }
            
            $('#form').jqxValidator('hideHint', '#rescate');

        });
        


        $('#operacion').on('select', function (event){
            $("#fondo").jqxDropDownList({ selectedIndex: -1, placeHolder: "Elija un fondo:" });
            cargarComboFondo(event);
            if($("#esAcdi").val() == '' && $("#operacion").val() == 'SUSC'){
                $(".noEsAcdi").show();
            }else{
                $(".noEsAcdi").hide();
            }
//            importeTotal(event);
        });
        
        $('#esAcdi').on('change', function (event){
            if($('#esAcdi').val() == '' && $('#operacion').val() == 'SUSC'){
                console.log(" no esAcdi");
                $('.noEsAcdi').show();
            }else{
                $('.noEsAcdi').hide();
                console.log("esAcdi");
            }
        });
        
        $('#fondo').on('click', function (event){
            $("#fondo").jqxDropDownList({ selectedIndex: -1, placeHolder: "Elija un fondo:" });
            cargarComboFondo(event);
        });
        
        
        $('#saldoMonetario').on('valueChanged', function (event){
            cambiarColor(event);
        });
        
        
/* 
        function importeTotal(event){
            if($('#rescate').val() == 'TOTAL' && $('#operacion').val() == 'RESC' && $('#fondo').val() > 0 && $('#numComitente').val() > 0){

                if($("#esAcdi").jqxDropDownList('getSelectedIndex') == 1){
                    $('#importe').val($('#saldoAcdi').val());
                    $('#importe').jqxNumberInput({disabled: true});
                }else{
                    $('#importe').val($('#saldoColocadorSimple').val());
                    $('#importe').jqxNumberInput({disabled: true});
                }                
                
//                switch($('#moneda').val()){
//                    case '$':
//                        $('#importe').val($('#saldoMonetario').val());
//                        $('#importe').jqxNumberInput({disabled: true});
//                    break;
//                    case 'u$s':
//                        $('#importe').val($('#saldoMonetarioDolar').val());
//                        $('#importe').jqxNumberInput({disabled: true});
//                    break;
//                    case 'M':
//                        $('#importe').val($('#saldoMonetarioMep').val());
//                        $('#importe').jqxNumberInput({disabled: true});
//                    break;
//                }
            }else{
                $('#importe').val('');
                $('#importe').jqxNumberInput({disabled: false});
            }
        }
*/     
        
        
        function cambiarColor(event){
            if($("#saldoMonetario").val() < 0){                
                $('#saldoMonetario input').css('color', 'red');

            }else{
                $('#saldoMonetario input').css('color', 'gray');
            }
            
            if($("#saldoMonetarioDolar").val() < 0){                
                $('#saldoMonetarioDolar input').css('color', 'red');

            }else{
                $('#saldoMonetarioDolar input').css('color', 'gray');
            }
            
            if($("#saldoMonetarioMep").val() < 0){
                $('#saldoMonetarioMep input').css('color', 'red');
            }else{
                $('#saldoMonetarioMep input').css('color', 'gray');
            }
        }
        
        function IndicarSaldos(event){
            if($("#fondo").val()){
                if($("#numComitente").val() == ''){
                    $("#saldoAcdi").val(null);
                    $("#saldoColocadorSimple").val(null);
                }else{
                    var codigoFondo = $("#fondo").val(); 
                    var numeroComitente = $("#numComitente").val();            
                    $.post('/esco/getPosicionFondoNumeroComitente', {fondo: codigoFondo, numComitente: numeroComitente}, function(pResultado){
                        
                        
                        //Cambio esto 20200207
                        if($("#operacion").val() == 'RESC'){
                            if( pResultado.SaldoACDI != null && pResultado.SaldoACDI != 0 && ( pResultado.SaldoColocadorSimple == null || pResultado.SaldoColocadorSimple == 0 ) ){
                                $("#esAcdi").jqxDropDownList({ selectedIndex: 1 });
                                $("#esAcdi").jqxDropDownList({ disabled: true });
//                            $("#esAcdi").jqxDropDownList({ selectedItem: 'SI'});
                            }else if( pResultado.SaldoColocadorSimple != null && pResultado.SaldoColocadorSimple != 0 && ( pResultado.SaldoACDI == null || pResultado.SaldoACDI == 0 ) ){
                                $("#esAcdi").jqxDropDownList({ selectedIndex: 0 });
                                $("#esAcdi").jqxDropDownList({ disabled: true });
                            }
                        }

                        if($("#operacion").val() == 'RESC'){
                            if(pResultado.SaldoColocadorSimple != null && pResultado.SaldoColocadorSimple != 0 && $("#rescate").jqxDropDownList('getSelectedIndex') == 1){
                                $("#esAcdi").jqxDropDownList({ selectedIndex: 0});
                                $("#esAcdi").jqxDropDownList({disabled: true});
                            }else{
//                                $("#esAcdi").jqxDropDownList({ selectedIndex: 0});
//                                $("#esAcdi").jqxDropDownList('selectIndex', 0);
//                                $("#esAcdi").jqxDropDownList({disabled: true});
                            }
                        }


                        if (pResultado){
                            $("#saldoAcdi").val(pResultado.SaldoACDI);
                            $("#saldoColocadorSimple").val(pResultado.SaldoColocadorSimple);                            
                        } else {
                            $("#saldoAcdi").val(null);
                            $("#saldoColocadorSimple").val(null);
                        }
                    }, 'json');
                }
            }else if($("#numComitente").val()){
                if($("#fondo").val() == ''){
                        $("#saldoAcdi").val(null);
                        $("#saldoColocadorSimple").val(null);
                }else{
                    var codigoFondo = $("#fondo").val(); 
                    var numeroComitente = $("#numComitente").val();            
                    $.post('/esco/getPosicionFondoNumeroComitente', {fondo: codigoFondo, numComitente: numeroComitente}, function(pResultado){
                        if (pResultado){
                            $("#saldoAcdi").val(pResultado.SaldoACDI);
                            $("#SaldoColocadorSimple").val(pResultado.SaldoColocadorSimple);
                        } else {
                            $("#saldoAcdi").val(null);
                            $("#saldoColocadorSimple").val(null);
                        }
                    }, 'json');
                }
            }
        }
        
        function cargarComboFondo(event){
            if($("#operacion").val() == 'RESC'){
                
//                $("#esAcdi").jqxDropDownList({disabled: false});
                
                if($("#numComitente").val() == ''){
                    var srcFondo =
                        {
                            datatype: "json",
                            datafields: [
                                { name: 'id'},
                                { name: 'CodFondo'},
                                { name: 'Abreviatura'}
                            ],
                            id: 'id',
                            url: '/esco/getFondos',
                            async: false
                        };
                    var DAFondo = new $.jqx.dataAdapter(srcFondo);
                    $("#fondo").jqxDropDownList({ selectedIndex: -1, source: DAFondo, displayMember: "Abreviatura", valueMember: "CodFondo", width: 300, height: 25, theme: theme, placeHolder: "Elija un fondo:" });
                    DAFondo.dataBind();  
                }else{
                    var srcFondo =
                        {
                            datatype: "json",
                            datafields: [
                                { name: 'id'},
                                { name: 'CodFondo'},
                                { name: 'FondoAbreviatura'}
                            ],

                            id: 'id',
                            url: '/esco/getPosicionFondos',

                            data: {
                                numComitente: $("#numComitente").val()
                            },
                            type: 'POST',
                            async: false
                        };
                    if($("#fondo").val() == 0){
                        var DAFondo = new $.jqx.dataAdapter(srcFondo);
                        $("#fondo").jqxDropDownList({source: DAFondo, displayMember: "FondoAbreviatura", valueMember: "CodFondo", width: 300, height: 25, theme: theme, placeHolder: "Elija un fondo:" });
                        DAFondo.dataBind();
                    }else{
                        var DAFondo = new $.jqx.dataAdapter(srcFondo);
                        DAFondo.dataBind();
                    }
                }
            }else{
                var srcFondo =
                    {
                        datatype: "json",
                        datafields: [
                            { name: 'id'},
                            { name: 'CodFondo'},
                            { name: 'Abreviatura'}
                        ],
                        id: 'id',
                        url: '/esco/getFondos',
                        async: false
                    };        
                var DAFondo = new $.jqx.dataAdapter(srcFondo);
                $("#fondo").jqxDropDownList({ source: DAFondo, displayMember: "Abreviatura", valueMember: "CodFondo", width: 300, height: 25, theme: theme, placeHolder: "Elija un fondo:" });
                DAFondo.dataBind();  
                
//                $("#esAcdi").jqxDropDownList('val', 1);
                
//                $("#esAcdi").jqxDropDownList('selectIndex', 1);
//                $("#esAcdi").jqxDropDownList({disabled: true});

                
            }
        }

         $('#form').jqxValidator({ rules: [
//                { input: '#operacion', message: 'Debe Seleccionar una operacion existente!', action: 'keyup, blur',  rule: function(){
//                    var result;
//                    if (!comitente){
//                        result = false;
//                    } else {
//                        result = true;
//                    }
//                    return result;
//                }},
            
                { input: '#numComitente', message: 'Debe Seleccionar un comitente existente!', action: 'keyup, blur',  rule: function(){
                    var result;
                    if (!comitente){
                        result = false;
                    } else {
                        result = true;
                    }
                    return result;
                }},
            
                
            
            
                { input: '#operacion', message: 'Debe Seleccionar el tipo de operación!', action: 'keyup, blur',  rule: function(){
                    return ($("#operacion").jqxDropDownList('getSelectedIndex') != -1);
                }},
            
                { input: '#fondo', message: 'Debe Seleccionar el tipo de fondo!', action: 'keyup, blur',  rule: function(){
                    return ($("#fondo").jqxDropDownList('getSelectedIndex') != -1);
                }},
            
                
            
                { input: '#fondo', message: 'Chequée si el número de comitente es fisico o jurídico!', action: 'keyup, blur',  rule: function(){ 
                   
                    var fondoDescripcion = $("#fondo").jqxDropDownList('getSelectedItem');
                    //Si el comitente es fisico, y el fondo no es fisico. Y hace una excepción para dejar a los RTA DOLAR-C-MEP
                    if($("#operacion").val() == 'RESC' && fondoDescripcion.label != 'RTA DOLAR-C-MEP') {
//                    if($("#operacion").val() == 'RESC' ){
                        var result = false;

                        if(esFisico == -1 && esFisicoFondo == -1){
                            result = true;
                        }else if(esFisico == -1 && esFisicoJuridicoFondo == -1){
                            result =  true;
                        }else if(esFisico == 0 && esFisicoFondo == 0){
                            result =  true;
                        }                    
                        return result;
                    }else{
                        return true;
                    }
                }},
            
//                { input: '#fondo', message: 'No se puede operar con este fondo!', action: 'keyup, blur',  rule: function(){
//                    if($("#fondo").val() == 29 || $("#fondo").val() == 30 || $("#fondo").val() == 61 || $("#fondo").val() == 12 || $("#fondo").val() == 13 || $("#fondo").val() == 14 || $("#fondo").val() == 19 || $("#fondo").val() == 20 || $("#fondo").val() == 21 || $("#fondo").val() == 10 || $("#fondo").val() == 11 || $("#fondo").val() ==31 || $("#fondo").val() == 16 || $("#fondo").val() == 17 || $("#fondo").val() == 18 || $("#fondo").val() == 8 || $("#fondo").val() == 9 || $("#fondo").val() == 36 || $("#fondo").val() == 47 || $("#fondo").val() == 48 || $("#fondo").val() == 68 || $("#fondo").val() == 51 || $("#fondo").val() == 52 || $("#fondo").val() == 53 || $("#fondo").val() == 54 || $("#fondo").val() == 35 || $("#fondo").val() == 42 || $("#fondo").val() == 49 || $("#fondo").val() == 50){
//                        return false;
//                    }else{
//                        return true;
//                    }
//                }},
            
                { input: '#esAcdi', message: 'Debe Seleccionar si es ACDI!', action: 'keyup, blur',  rule: function(){
                        return ($("#esAcdi").jqxDropDownList('getSelectedIndex') != -1);
                    }},
                
//                { input: '#esAcdi', message: 'Debe Seleccionar si es ACDI o no!', action: 'keyup, blur',  rule: function(){
//                        var result = true;                        
//                        if($("#saldoAcdi").val() != null && $("#saldoAcdi").val() != 0 
//                            && $("#operacion").val() == 'RESC' 
//                            && $("#esAcdi").jqxDropDownList('getSelectedIndex') != 1 
//                            && (($("#saldoColocadorSimple").val() == null) || ($("#saldoColocadorSimple").val() == 0))){
//                            result = false;
//                        }
//                        return result;
//                    }},
                
//                { input: '#esAcdi', message: 'No posée saldo Acdi, seleccione la opción correcta.', action: 'keyup, blur',  rule: function(){
//                        var result = true;                        
//                        if( ($("#saldoAcdi").val() == null || $("#saldoAcdi").val() == 0) 
//                            && $("#operacion").val() == 'RESC' 
//                            && $("#esAcdi").jqxDropDownList('getSelectedIndex') != 0
//                            ){
//                            result = false;
//                        }
//                        return result;
//                    }},
                // Se agregó esto
//                { input: '#esAcdi', message: 'Las suscripciones deben ser ACDI', action: 'keyup, blur',  rule: function(){
//                        var result = true;                        
//                        if( $("#operacion").val() == 'SUSC' && $("#esAcdi").jqxDropDownList('getSelectedIndex') != 1 ){
//                            result = false;
//                        }
//                        return result;
//                    }},
                
                
                { input: '#destinoRescate', message: 'Debe Seleccionar el destino de Rescate!', action: 'keyup, blur',  rule: function(){
//                        return ($("#destinoRescate").jqxDropDownList('getSelectedIndex') != -1);
                        var result = true;                        
                        if( $("#operacion").val() == 'RESC' 
                                && $("#esAcdi").jqxDropDownList('getSelectedIndex') == 1 
                                && $("#destinoRescate").jqxDropDownList('getSelectedIndex') == 0 
                                ){
                            result = false;
                        }
                        return result;
                        
                        
                    }},
                { input: '#origenFondos', message: 'Debe Seleccionar el origen de Fondos!', action: 'keyup, blur',  rule: function(){
                        var result = true;                        
                        if( $("#operacion").val() == 'SUSC' 
                                && $("#origenFondos").jqxDropDownList('getSelectedIndex') == 0 
                                ){
                            result = false;
                        }
                        return result;
                    }},
                
                //Se agregó esto // Si es rescate, debe seleccionar origen de fondos.
//                { input: '#origenFondos', message: 'Debe Seleccionar el origen de Fondos!', action: 'keyup, blur',  rule: function(){
//                        var result = true;                        
//                        if( ($("#origenFondos").jqxDropDownList('getSelectedIndex') == 0) 
//                            && $("#operacion").val() == 'RESC' 
//                            ){
//                            result = false;
//                        }
//                        return result;                        
//                }},
                ////////////////

                { input: '#importe', message: 'Mínimo incorrecto!', action: 'keyup, blur',  rule: function(){
                        
                        if($("#operacion").val() == 'SUSC'){
                            importe = $("#importe").val();                        
                            if($("#moneda").val() == '$' && importe < 1000 ){
                                return false;
                            }else if ($("#moneda").val() == 'U$S' && importe < 100 ){
                                return false;
                            }else{
                                return true;
                            }
                        }else{
                            return true;
                        }
                    }},
                
                { input: '#importe', message: 'El máximo no puede superar el saldo', action: 'keyup, blur',  rule: function(){
                    
//                    simboloFondo;

                    if($("#moneda").val()){
                        
//                        switch($("#moneda").val()){
//                            case '$':
//                                var saldo = ($('#saldoMonetario').val());
//                            break;
//                            case 'u$s':
//                                var saldo = ($('#saldoMonetarioDolar').val());
//                            break;
//                            case 'M':
//                                var saldo = ($('#saldoMonetarioMep').val());
//                            break;
//                        }
                        
                        
                        if( $("#saldoAcdi").val() && $("#esAcdi").val() == 'SI'){                           
                            var saldo = parseFloat($("#saldoAcdi").val()) * parseFloat($("#totalCuotapartes").val()); 
                        }else{
                            var saldo = parseFloat($("#saldoColocadorSimple").val()) * parseFloat($("#totalCuotapartes").val()); 
                        }

                        if($("#importe").val() > saldo && ($("#operacion").val() == 'RESC') && $("#importe").val() != 0 ){
                            return false;
                        }else{
                            return true;
                        }
                        
                    }else{
                            return true;
                        }

                    /*
                    if($("#moneda").val()){
                        
                        switch($("#moneda").val()){
                            case '$':
                                var saldo = ($('#saldoMonetario').val());
                            break;
                            case 'u$s':
                                var saldo = ($('#saldoMonetarioDolar').val());
                            break;
                            case 'M':
                                var saldo = ($('#saldoMonetarioMep').val());
                            break;
                        }

                        if($("#importe").val() > saldo && ($("#operacion").val() == 'RESC') && $("#importe").val() != 0 ){
                            return false;
                        }else{
                            return true;
                        }
                        
                        
                    }
                    */
                }},
                
        
                { input: '#origenFondos', message: 'Seleccione el origen de fondos, ya que el importe es menor al saldo monetario correspondiente', action: 'keyup, blur',  rule: function(){
                        
                        if($("#operacion").val() == 'SUSC'){
                            moneda = $("#moneda").val();
                            importe = $("#importe").val();

                            if(moneda == '$' && $("#saldoMonetario").val() < importe && ($("#origenFondos").jqxDropDownList('getSelectedIndex') < 1)){
                                return false;
                            }
                            else if(moneda == 'U$S' && $("#saldoMonetarioDolar").val() < importe && ($("#origenFondos").jqxDropDownList('getSelectedIndex') < 1)){
                                return false;
                            }
                            else if(moneda == 'M' && $("#saldoMonetarioMep").val() < importe && ($("#origenFondos").jqxDropDownList('getSelectedIndex') < 1)){
                                return false;
                            }
                            else{
                                return true;
                            }
                        }else{
                            return true;
                        }
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
                $('#ventanaFondo').ajaxloader();
//                var cable = 0;
//                if ($("#cable").val()){
//                    cable = 1;
//                }                
                saldoAcdi = parseFloat($("#saldoAcdi").val());                  
                if (isNaN(saldoAcdi)) {
                    saldoAcdi = null;
                }
                
                saldoColocadorSimple = parseFloat($("#saldoColocadorSimple").val());                  
                if (isNaN(saldoColocadorSimple)) {
                    saldoColocadorSimple = null;
                }
                
                datos = {
                    id: $("#id").val(),
                    operador: $("#operador").val(),
                    fechaConcertacion: $("#fechaConcertacion").val(),
                    operacion: $("#operacion").val(),
                    fondo: $("#fondo").val(),
                    nombreFondo: $("#fondo").jqxDropDownList('getSelectedItem').label,
                    numComitente: $("#numComitente").val(),
                    nombreComitente: $("#comitente").val(),
                    rescate: $("#rescate").val(),
                    importe: $("#importe").val(),
                    esAcdi: $("#esAcdi").val(),
                    noEsAcdiTipo: $("#noEsAcdiTipo").val(),
                    destinoRescate: $("#destinoRescate").val(),
                    totalCuotapartes: $("#totalCuotapartes").val(),
                    saldoMonetario: $("#saldoMonetario").val(),
                    saldoMonetarioDolar: $("#saldoMonetarioDolar").val(),
                    saldoMonetarioMep: $("#saldoMonetarioMep").val(),
                    origenFondos: $("#origenFondos").val(),
                    usuario: $("#usuario").val(),
                    moneda: $("#moneda").val(),
                    observaciones: $("#observaciones").val(),                   
                    saldoAcdi: saldoAcdi,
                    saldoColocadorSimple: saldoColocadorSimple,                    
                };
                $.post('/fondo/saveOrden', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/fondo');
                    } else {
                        new Messi('Hubo un error guardando la orden', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true, titleClass: 'error'});
                        $('#ventanaFondo').ajaxloader('hide');
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
                { name: 'operacion'},
                { name: 'nombreFondo'},
                { name: 'importe'}
            ],
            cache: false,
            url: '/fondo/getOrdenesFondos',
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
                        { text: 'Operacion', datafield: 'operacion', width: 60, cellsalign: 'right'},
                        { text: 'Fondo', datafield: 'nombreFondo', width: 140, cellsalign: 'right' },
                        { text: 'importe', datafield: 'importe', width: 100, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum']}
                ]
        });
        $("#grilla").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grilla").jqxGrid('localizestrings', localizationobj);
            $("#numComitente").focus();
        }); 
        
        
    });
    
</script>