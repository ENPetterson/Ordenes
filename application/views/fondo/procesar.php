<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
        var theme = getTheme();
        var id = 0;
        var enviar = [];
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
            url: '/fondo/getCierres',
            async: false
        };
        var DACierre = new $.jqx.dataAdapter(srcCierre);
        
        $("#cierre").on('bindingComplete', function(event){
            $.post('/fondo/getCierreActual', function(cierre){
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
            source.data = {cierrefondo_id: event.args.item.value};
            $("#grilla").jqxGrid('updatebounddata');
            $("#excelButton").jqxButton({disabled: false });
            cierreFecha = event.args.item.label;
        });

        var source = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'fechaConcertacion'},
                { name: 'operacion'},
                { name: 'operador'},
                { name: 'nombreFondo'},
                { name: 'numComitente'},
                { name: 'nombreComitente'},
                { name: 'rescate'},
                { name: 'importe'},
                { name: 'esAcdi'},
                { name: 'esNoAcdiTipo'},
                { name: 'destinoRescate'},
                { name: 'origenFondos'},
                { name: 'moneda'},
                { name: 'observaciones'},
                { name: 'saldoAcdi'},
                { name: 'saldoColocadorSimple'},
                { name: 'estado'},
                { name: 'estado_id'}
//                { name: 'estado'},
//                { name: 'estado_id'},
//                { name: 'envio'},
//                { name: 'fhenvio', type: 'date', format: 'yyyy-MM-dd HH:mm:ss'}
            ],
            cache: false,
            url: '/fondo/procesarGrilla',
            data: {cierrefondo_id: cierre_id},
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
                width: 1800,
                height: 600,
                columns: [                   
                        { text: 'Id', datafield: 'id', width: 40, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'fechaConcertacion', datafield: 'fechaConcertacion', width: 100 },
                        { text: 'operacion', datafield: 'operacion', width: 70},
                        { text: 'operador', datafield: 'operador', width: 120},
                        { text: 'nombreFondo', datafield: 'nombreFondo', width: 120},
                        { text: 'Num CTTE', datafield: 'numComitente', width: 80},
                        { text: 'Comitente', datafield: 'nombreComitente', width: 200},
                        { text: 'rescate', datafield: 'rescate', width: 70},
                        { text: 'importe', datafield: 'importe', width: 100},
                        { text: 'esAcdi', datafield: 'esAcdi', width: 60},
                        { text: 'Garantia', datafield: 'esNoAcdiTipo', width: 60},
                        { text: 'destinoRescate', datafield: 'destinoRescate', width: 70},
                        { text: 'origenFondos', datafield: 'origenFondos', width: 95},
                        { text: 'moneda', datafield: 'moneda', width: 90},
                        { text: 'observaciones', datafield: 'observaciones', width: 150},
                        { text: 'saldoAcdi', datafield: 'saldoAcdi', width: 150},
                        { text: 'saldoColocadorSimple', datafield: 'saldoColocadorSimple', width: 150},
                        { text: 'Estado', datafield: 'estado', width: 80},
                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true}
//                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true},
//                        { text: 'fechaActualizacion', datafield: 'fechaActualizacion'},
//                        { text: 'Inst', datafield: 'envio', width: 30},
//                        { text: 'Envio', datafield: 'fhenvio', width: 150, cellsformat: 'dd/MM/yyyy HH:mm:ss'}
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
        
        
        
//        $("#enviarMercadoButton").jqxButton({ width: '160', theme: theme, disabled: true });
        $("#procesarButton").jqxButton({ width: '160', theme: theme, disabled: true });
        $("#editarButton").jqxButton({ width: '160', theme: theme, disabled: true });
        $("#anularButton").jqxButton({ width: '160', theme: theme, disabled: true });
        
        /*
        $("#enviarMercadoButton").click(function(){
            $("#grilla").ajaxloader();
            var datos = {ordenes: enviar};
            $("#grilla").ajaxloader();
            $.post('/fondo/previewMercado', datos, function(data){
                $.each(data.uris, function(indice, uri){
                    $.fileDownload(uri);
                });
                new Messi('Ha enviado los datos al mercado ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                    buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                        if (val == 's'){
                            $.post('/fondo/enviarMercado', datos, function(data){
                                var titleClass;
                                if (data.exito == 0){
                                    titleClass = 'error';
                                } else {
                                    titleClass = 'success';
                                }
                                $("#grilla").ajaxloader('hide');
                                new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                    buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass});
                                $('#grilla').jqxGrid('updatebounddata');
                                $('#grilla').jqxGrid('clearselection');
                            }, 'json');                            
                        } else {
                            $("#grilla").ajaxloader('hide');
                        }
                    }
                });
            },'json');
        });
        */
       
        $("#procesarButton").click(function(){
            new Messi('Desea procesar las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        $("#grilla").ajaxloader();
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/fondo/procesarOrdenes', datos, function(data){
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
       
       

        $("#anularButton").click(function(){
            new Messi('Desea anular las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,

                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) {
                    if (val == 's'){
                        $("#grilla").ajaxloader();
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/fondo/anularOrdenes', datos, function(data){
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
            $.redirect('/fondo/editar', {'id': id, origen: 'procesar'});
        });
        
        $('#grilla').on('rowselect rowunselect', function (event) {
            enviar = [];
            var rowindexes = $('#grilla').jqxGrid('getselectedrowindexes');
//            $('#enviarMercadoButton').jqxButton({disabled: true });
            $('#procesarButton').jqxButton({disabled: true });
            $('#editarButton').jqxButton({disabled: true });            
            $('#anularButton').jqxButton({disabled: true });
            if (rowindexes.length > 0){
                $.each(rowindexes, function(index, value){
                    var row = $('#grilla').jqxGrid('getrowdata', value);
                    var estado_id = row.estado_id;
                    id = row.id;
                    if (estado_id != 2 && estado_id != 3 ){
                        $("#grilla").jqxGrid('unselectrow', value);
                    } else {
                        enviar.push(id);
                    }
                });
                if (enviar.length > 0){
                    if (enviar.length == 1){
                        $('#editarButton').jqxButton({disabled: false });
                    }
//                    $('#enviarMercadoButton').jqxButton({disabled: false });
                    $('#procesarButton').jqxButton({disabled: false });
                    $('#anularButton').jqxButton({disabled: false });
//                    $("#ventanaResumen").jqxWindow('open');
                }
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
//            url: '/fondo/grillaResumen',
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
<div id="sistema" style='float: left; margin-left: 0px; margin-bottom: 10px; text-align: left; vertical-align: text-bottom;'><ul>Procesar Fondos</ul></div>
<br>
<div id="grilla"></div>
<div id="botonera">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
<!--            <td><input type="button" value="Enviar a Mercado" id="enviarMercadoButton"></td>-->
            <td><input type="button" value="Procesar" id="procesarButton">
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
