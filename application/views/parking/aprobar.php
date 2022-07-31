<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
        var theme = getTheme();
        var id = 0;
        var numComit = 0;
        var enviar = [];
        var enviarOrdenes = [];
        var enviarComitentes = [];
        var cierre_id = 0;
        var cierreFecha;
        
        $("#sistema").jqxMenu({width: 200, height: 25, theme: theme});
        
        var srcCierre = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'fechahora' }
            ],
            id: 'id',
            url: '/parking/getCierres',
            async: false
        };
        var DACierre = new $.jqx.dataAdapter(srcCierre);
        
        $("#cierre").on('bindingComplete', function(event){
            $.post('/parking/getCierreActual', function(cierre){
                if (!cierre.cerrado){
                    $("#cierre").jqxDropDownList('val', cierre.id);
                }
            },'json');
        });
        
        $("#cierre").jqxDropDownList({ selectedIndex: -1, source: DACierre, displayMember: "fechahora", 
            valueMember: "id", width: 200, height: 25, theme: theme, placeHolder: "Elija el cierre:", renderer: function (index, label, value){
                return moment(label).format('DD/MM/YYYY HH:mm');
            }  
        });
        
        $('#cierre').on('change', function (event)        {  
            source.data = {cierreparking_id: event.args.item.value};
            $("#grilla").jqxGrid('updatebounddata');
            $("#excelButton").jqxButton({disabled: false });
            cierreFecha = event.args.item.label;
        });


        var cellclassname = function (row, column, value, data) {
                return "orangeClass";            
        };
               


        var source = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'operador'},
                { name: 'numComitente'},
                { name: 'descripcionComitente'},
                { name: 'codigo'},
                { name: 'especie'},
                { name: 'especieDescripcion'},
                { name: 'cantidad'},
                { name: 'parking'},
                { name: 'sumaAprobado'},
                { name: 'cantidadTotal'},
                { name: 'moneda'},
                { name: 'esMismaMoneda'},
                { name: 'esCableMep'},
                { name: 'observaciones'},
                
                { name: 'autorizadores'},
//                { name: 'procesadores'},
                
                { name: 'estado'},
                { name: 'estado_id'},
                { name: 'fechaEstado'},
                { name: 'fechaActualizacion'},
                { name: 'envio'},
                { name: 'fhenvio', type: 'date', format: 'yyyy-MM-dd HH:mm:ss'}
            ],
            cache: false,
            url: '/parking/aprobarGrilla',
            data: {cierreparking_id: cierre_id},
            type: 'post'
        };
        
        var dataadapter = new $.jqx.dataAdapter(source);

        // initialize jqxGrid
        $("#grilla").jqxGrid(
        {		
                source: dataadapter,
                theme: theme,
                filterable: true,
                sortable: true,
                pageable: false,
                virtualmode: false,
                selectionmode: 'checkbox',
                columnsresize: true,
                showstatusbar: true,
                statusbarheight: 25,
                showaggregates: true,
                width: '95%',
                height: 460,
                columns: [
                        { text: 'Id', datafield: 'id', width: 40, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'operador', datafield: 'operador', width: 145 },
//                        { text: 'tipoOperacion', datafield: 'tipoOperacion', width: 100},
                        { text: 'Nro Comitente', datafield: 'numComitente', width: 80},
                        { text: 'Comitente', datafield: 'descripcionComitente', width: 100},
                        { text: 'Código', datafield: 'codigo', width: 50},
                        { text: 'Abreviatura', datafield: 'especie', width: 60},
                        { text: 'Descripción', datafield: 'especieDescripcion', width: 180},
                        { text: 'cantidad', datafield: 'cantidad', width: 85},
                        { text: 'parking', datafield: 'parking', width: 85},
                        { text: 'suma Aprobado', datafield: 'sumaAprobado', width: 85},
                        { text: 'cantidadTotal', datafield: 'cantidadTotal', width: 90},
//                        { text: 'qsuma', datafield: 'cantidadsuma', width: 90},
                        { text: 'moneda', datafield: 'moneda', width: 60},
                        { text: 'esMismaMoneda', datafield: 'esMismaMoneda', width: 50},
                        { text: 'PJ cable a MEP', datafield: 'esCableMep', width: 50},
                        { text: 'Observaciones', datafield: 'observaciones', width: 90},
                        { text: 'autorizadores', datafield: 'autorizadores', width: 150},
//                        { text: 'procesadores', datafield: 'procesadores', width: 120},
                        { text: 'Estado', datafield: 'estado', width: 80},
                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true},
                        { text: 'Fecha Estado', datafield: 'fechaEstado', width: 150},
                        { text: 'Fecha Orden', datafield: 'fechaActualizacion', width: 150},
//                        { text: 'Inst', datafield: 'envio', width: 30},
//                        { text: 'Envio', datafield: 'fhenvio', width: 150, cellsformat: 'dd/MM/yyyy HH:mm:ss'}



//                        { text: 'caja', datafield: 'caja', width: 57},
//                        { text: 'visual', datafield: 'visual', width: 122},
//                        { text: 'control', datafield: 'control', width: 100},
                        { text: 'cierreparking_id', datafield: 'cierreparking_id', width: 100, hidden:true},
                        { text: 'usuario_id', datafield: 'usuario_id', width: 100, hidden:true},

                        
                ]
        });
        $("#grilla").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grilla").jqxGrid('localizestrings', localizationobj);
        }); 
        
        $("#excelButton").jqxButton({ width: '160', theme: theme, disabled: true });
        
        $("#excelButton").click(function(){
            grid2excel('#grilla', 'Operaciones Cierre - ' + cierreFecha, false);
        });
        
        
        $("#aprobarButton").jqxButton({ width: '160', theme: theme, disabled: true });
        $("#editarButton").jqxButton({ width: '160', theme: theme, disabled: true });
        $("#anularButton").jqxButton({ width: '160', theme: theme, disabled: true });
        
        
        $("#aprobarButton").click(function(){
            new Messi('Desea aprobar las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        $("#grilla").ajaxloader();
                                                
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/parking/aprobarOrdenes', datos, function(data){
                                                        
                            enviarOrdenes = enviar;
                            
                            if(data.exito == 1){
                                new Messi(data.msj, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'success'});
                                datos = {
                                    ordenes: enviarComitentes,
                                    tipo: 'aprobar',
                                    cierre: $("#cierre").val()
                                };
                                $.post('/envioautorizacion/enviarAOMS', datos, function(data){
                                    
                                    $.each(data.resultado, function(index, value){
                                        if(value.response == 'confirmado'){
                                        
                                            datosOMS = {
                                                ordenes: enviarOrdenes
                                            };
                                            $.post('/parking/aprobarOrdenesOMS', datosOMS, function(dataOMS){

                                            new Messi('Envío a OMS exitoso. Parking aprobado y '+value.message , {title: 'Mensaje', modal: true,
                                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'success'});
                                $('#grilla').jqxGrid('updatebounddata');

                                            }
                                            , 'json');                                            
                                        
                                        }else{
                                            new Messi('Parking aprobado. '+value.response+' '+value.message, {title: 'Mensaje', modal: true,
                                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'error'});
                                        }
                                    });
                                }
                                , 'json');
                            
                                $('#grilla').jqxGrid('updatebounddata');
                                $('#grilla').jqxGrid('clearselection');
                                $("#grilla").ajaxloader('hide');
                            }else{
                                new Messi(data.resultado, {title: 'No se aprobó', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'error'});
                                $('#grilla').jqxGrid('updatebounddata');
                                $('#grilla').jqxGrid('clearselection');
                                $("#grilla").ajaxloader('hide');
                            }                            
                        }
                        , 'json');
                    } 
                }
            });
        });
       
       

        $("#anularButton").click(function(){
            new Messi('Desea anular las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        $("#grilla").ajaxloader();
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/parking/anularOrdenes', datos, function(data){
                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'error'});
                            $('#grilla').jqxGrid('updatebounddata');
                            $('#grilla').jqxGrid('clearselection');
                            $("#grilla").ajaxloader('hide');
                        }
                        , 'json');
                    } 
                }
            });
        });
        
        $("#editarButton").click(function(){
            $.redirect('/parking/editar', {'id': id, origen: 'aprobar'});
        });
        
        $('#grilla').on('rowselect rowunselect', function (event) {
            enviar = [];
            enviarComitentes = [];
            
            var rowindexes = $('#grilla').jqxGrid('getselectedrowindexes');
            $('#aprobarButton').jqxButton({disabled: true });
            $('#editarButton').jqxButton({disabled: true });
            $('#anularButton').jqxButton({disabled: true });
            if (rowindexes.length > 0){
                $.each(rowindexes, function(index, value){
                    var row = $('#grilla').jqxGrid('getrowdata', value);
                    var estado_id = row.estado_id;
                    id = row.id;
                    numComit = row.numComitente;
                    if (estado_id != 2 && estado_id != 6 && estado_id != 9){
                        $("#grilla").jqxGrid('unselectrow', value);
                    } else {
                        enviar.push(id);
                        enviarComitentes.push(numComit);
                    }
                    
                    
                    if (enviar.length > 0){
                        if (enviar.length == 1 && estado_id == 2){
                            $('#anularButton').jqxButton({disabled: false });
                            $('#aprobarButton').jqxButton({disabled: false });
                            $('#editarButton').jqxButton({disabled: true });
                        }else if ( (enviar.length == 1 && estado_id == 6) || (enviar.length == 1 && estado_id == 9) ){
                            $('#anularButton').jqxButton({disabled: false });
                            $('#aprobarButton').jqxButton({disabled: false });
                        } 
                        
                        if (enviar.length > 1 && estado_id != 2){
                            $('#aprobarButton').jqxButton({disabled: false });
                            $("#grilla").jqxGrid('unselectrow', value);
                        }
                        
                    }
                    
                });

            }
//            srcGrillaResumen.data = {ordenes: enviar};
//            $("#grillaResumen").jqxGrid('updatebounddata');
        });
        
        
        
//        $("#ventanaResumen").jqxWindow({autoOpen: false, keyboardCloseKey: -1, showCloseButton: false, height: '230px', width: '410px', theme: theme, position: 'bottom, left' });
//        
//        var srcGrillaResumen = {
//            datatype: "json",
//            datafields: [
//                {name: 'plazo'},
//                {name: 'moneda'},
//                {name: 'cantidadOrdenes', type: 'integer'},
//                {name: 'sumaCantidad', type: 'number'}
//            ],
//            url: '/parking/grillaResumen',
//            data: {ordenes: enviar},
//            type: 'post'
//        };
//        
//        var daGrillaResumen = new $.jqx.dataAdapter(srcGrillaResumen);
        
//        $("#grillaResumen").jqxGrid(
//        {
//            width: 380,
//            height: 190,
//            source: daGrillaResumen,
//            columnsresize: true,
//            showstatusbar: true,
//            statusbarheight: 25,
//            showaggregates: true,
//            columns: [
//              { text: 'Plazo', datafield: 'plazo', width: 50 },
//              { text: 'Mone', datafield: 'moneda', width: 30 },
//              { text: 'Cant Ord', datafield: 'cantidadOrdenes', width: 100, cellsalign: 'right', cellsformat: 'n', aggregates: ['sum']  },
//              { text: 'Total VN', datafield: 'sumaCantidad', width: 180, cellsalign: 'right', cellsformat: 'd2', aggregates: ['sum'] }
//            ],
//            theme: theme
//        });

    });
</script>
<div id="cierre"></div>
<br>
<div id="sistema" style='float: left; margin-left: 0px; margin-bottom: 10px; text-align: left; vertical-align: text-bottom;'><ul>Aprobar Parking</ul></div>
<br>
<div id="grilla"></div>
<div id="botonera">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
<!--            <td><input type="button" value="Enviar a Mercado" id="enviarMercadoButton"></td>-->
            <td><input type="button" value="Aprobar" id="aprobarButton">
            <td><input type="button" value="Anular" id="anularButton"></td>
            <td><input type="button" value="Editar" id="editarButton"></td>
            <td><input type="button" value="Generar Excel" id="excelButton"></td>
        </tr>
    </table>
</div>
<!--<div id="ventanaResumen">
    <div>Resumen de seleccion</div>
    <div>
        <div id="grillaResumen"></div>
    </div>
</div>-->
<style>
    .redClass:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .green:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
        background-color: #f4424e;
        color: white;
    }
    .greenClass:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .yellow:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
        color: white;
        background-color: darkgreen;
    }
    .yellowClass:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .red:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
        color: black;
        background-color: #fffd99;
    }
    .grayClass:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .red:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
        color: white;
        background-color: #999999
    }
	.orangeClass:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .red:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
        color: white;
        background-color: #ffa500
    }
</style>