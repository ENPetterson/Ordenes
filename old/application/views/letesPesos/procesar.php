<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
        var theme = getTheme();
        var id = 0;
        var enviar = [];
        var cierreletespesos_id = 0;
        var cierreFecha;
        
        var srcCierre = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'fechahora' }
            ],
            id: 'id',
            url: '/letesPesos/getCierres',
            async: false
        };
        var DACierre = new $.jqx.dataAdapter(srcCierre);
        
        $("#cierre").on('bindingComplete', function(event){
            $.post('/letesPesos/getCierreActual', function(cierre){
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
            source.data = {cierreletespesos_id: event.args.item.value};
            $("#grilla").jqxGrid('updatebounddata');
            $("#excelButton").jqxButton({disabled: false });
            cierreFecha = event.args.item.label;
        });

        var source = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'tramo'},
                { name: 'numComitente', type: 'number'},
                { name: 'moneda'},
                { name: 'cable', type: 'bool'},
                { name: 'plazo', type: 'number'},
                { name: 'comision', type: 'float'},
                { name: 'cantidad', type: 'number'},
                { name: 'tenencia', type: 'number'},
                { name: 'posicion', type: 'number'},
                { name: 'precio', type: 'float'},
                { name: 'comitente'},
                { name: 'tipoPersona'},
                { name: 'oficial'},
                { name: 'usuario'},
                { name: 'cuit', type: 'number'},
                { name: 'estado'},
                { name: 'estado_id'},
                { name: 'envio'},
                { name: 'fhenvio', type: 'date', format: 'yyyy-MM-dd HH:mm:ss'}
            ],
            cache: false,
            url: '/letesPesos/procesarGrilla',
            data: {cierreletespesos_id: cierreletespesos_id},
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
                width: 1680,
                height: 400,
                columns: [
                        { text: 'Id', datafield: 'id', width: 80, cellsalign: 'right', cellsformat: 'd', aggregates: ['count'] },
                        { text: 'Tramo', datafield: 'tramo', width: 110 },
                        { text: 'Nro Comitente', datafield: 'numComitente', width: 70},
                        { text: 'Mone', datafield: 'moneda', width: 30},
                        { text: 'Cable', datafield: 'cable', width: 30, columntype: 'checkbox'},
                        { text: 'Plazo', datafield: 'plazo', width: 40, cellsalign: 'right'},
                        { text: 'Comis', datafield: 'comision', width: 60, cellsalign: 'right', cellsformat: 'd4'},
                        { text: 'Cantidad', datafield: 'cantidad', width: 140, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum'] },
                        { text: 'Tenencia', datafield: 'tenencia', width: 140, cellsalign: 'right', cellsformat: 'd' },
                        { text: 'Posicion', datafield: 'posicion', width: 140, cellsalign: 'right', cellsformat: 'd' , hidden: true},
                        { text: 'Precio', datafield: 'precio', width: 100, cellsalign: 'right', cellsformat: 'd10'},
                        { text: 'Comitente', datafield: 'comitente', width: 200},
                        { text: 'Tipo Per', datafield: 'tipoPersona', width: 80},
                        { text: 'Oficial', datafield: 'oficial', width: 200},
                        { text: 'Usuario', datafield: 'usuario', width: 150},
                        { text: 'CUIT', datafield: 'cuit', width: 100},
                        { text: 'Estado', datafield: 'estado', width: 90},
                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true},
                        { text: 'Inst', datafield: 'envio', width: 30},
                        { text: 'Envio', datafield: 'fhenvio', width: 150, cellsformat: 'dd/MM/yyyy HH:mm:ss'}
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
        
        
        
        $("#enviarSantanderButton").jqxButton({ width: '160', theme: theme, disabled: true });
        $("#enviarMercadoButton").jqxButton({ width: '160', theme: theme, disabled: true });
        $("#editarButton").jqxButton({ width: '160', theme: theme, disabled: true });
        $("#anularButton").jqxButton({ width: '160', theme: theme, disabled: true });
        

        $("#enviarSantanderButton").click(function(){
            srcGrillaPreviewSantander.data = {ordenes: enviar};
            $("#grillaPreviewSantander").jqxGrid('updatebounddata');
            $("#ventanaPreviewSantander").jqxWindow('open');
        });
        
        $("#enviarMercadoButton").click(function(){
            $("#grilla").ajaxloader();
            var datos = {ordenes: enviar};
            $("#grilla").ajaxloader();
            $.post('/letesPesos/previewMercado', datos, function(data){
                
                $.each(data.uris, function(indice, uri){
                    $.fileDownload(uri, {  
                   successCallback: function (url) {  
                       alert('You just got a file download dialog or ribbon for this URL :' + url); 
                       },  
                       failCallback: function (html, url) {    
                        alert('Your file download just failed for this URL:' + url + '\r\n' +     
                                              'Here was the resulting error HTML: \r\n' + html);    
                        }
                    }                      );
                });
                new Messi('Ha enviado los datos al mercado ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                    buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                        if (val == 's'){
                            $.post('/letesPesos/enviarMercado', datos, function(data){
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
            }, 'json');
        });
        
        $("#anularButton").click(function(){
            new Messi('Desea anular las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        $("#grilla").ajaxloader();
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/letesPesos/anularOrdenes', datos, function(data){
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
            $.redirect('/letesPesos/editar', {'id': id, origen: 'procesar'});
        });
        

        
        
        $('#grilla').on('rowselect rowunselect', function (event) {
            enviar = [];
            var rowindexes = $('#grilla').jqxGrid('getselectedrowindexes');
            $('#enviarSantanderButton').jqxButton({disabled: true });
            $('#enviarMercadoButton').jqxButton({disabled: true });
            $('#editarButton').jqxButton({disabled: true });
            $('#anularButton').jqxButton({disabled: true });
            if (rowindexes.length > 0){
                $.each(rowindexes, function(index, value){
                    var row = $('#grilla').jqxGrid('getrowdata', value);
                    var estado_id = row.estado_id;
                    id = row.id;
                    if (estado_id != 2){
                        $("#grilla").jqxGrid('unselectrow', value);
                    } else {
                        enviar.push(id);
                    }
                });
                if (enviar.length > 0){
                    if (enviar.length == 1){
                        $('#editarButton').jqxButton({disabled: false });
                    }
                    $('#enviarSantanderButton').jqxButton({disabled: false });
                    $('#enviarMercadoButton').jqxButton({disabled: false });
                    $('#anularButton').jqxButton({disabled: false });
                    $("#ventanaResumen").jqxWindow('open');
                }
            }
            srcGrillaResumen.data = {ordenes: enviar};
            $("#grillaResumen").jqxGrid('updatebounddata');
        });
        
        
        
        $("#ventanaResumen").jqxWindow({autoOpen: false, keyboardCloseKey: -1, showCloseButton: false, height: '230px', width: '410px', theme: theme, position: 'bottom, left' });
        
        var srcGrillaResumen = {
            datatype: "json",
            datafields: [
                {name: 'plazo'},
                {name: 'moneda'},
                {name: 'cantidadOrdenes', type: 'integer'},
                {name: 'sumaCantidad', type: 'number'}
            ],
            url: '/letesPesos/grillaResumen',
            data: {ordenes: enviar},
            type: 'post'
        };
        
        var daGrillaResumen = new $.jqx.dataAdapter(srcGrillaResumen);
        
        $("#grillaResumen").jqxGrid(
        {
            width: 380,
            height: 190,
            source: daGrillaResumen,
            columnsresize: true,
            showstatusbar: true,
            statusbarheight: 25,
            showaggregates: true,
            columns: [
              { text: 'Plazo', datafield: 'plazo', width: 50 },
              { text: 'Mone', datafield: 'moneda', width: 30 },
              { text: 'Cant Ord', datafield: 'cantidadOrdenes', width: 100, cellsalign: 'right', cellsformat: 'n', aggregates: ['sum']  },
              { text: 'Total VN', datafield: 'sumaCantidad', width: 180, cellsalign: 'right', cellsformat: 'd2', aggregates: ['sum'] }
            ],
            theme: theme
        });





        $("#ventanaPreviewSantander").jqxWindow({autoOpen: false, keyboardCloseKey: -1, showCloseButton: true, height: '270px', width: '590px', theme: theme});

        
        var srcGrillaPreviewSantander = {
            datatype: "json",
            datafields: [
                {name: 'plazo'},
                {name: 'moneda'},
                {name: 'especie'},
                {name: 'precio', type: 'number'},
                {name: 'sumaCantidad', type: 'number'}
            ],
            url: '/letesPesos/previewSantander',
            data: {ordenes: enviar},
            type: 'post'
        };
        
        var daGrillaPreviewSantander = new $.jqx.dataAdapter(srcGrillaPreviewSantander);
        
        $("#grillaPreviewSantander").jqxGrid(
        {
            width: 560,
            height: 190,
            source: daGrillaPreviewSantander,
            columnsresize: true,
            showstatusbar: true,
            statusbarheight: 25,
            showaggregates: true,
            columns: [
              { text: 'Plazo', datafield: 'plazo', width: 50 },
              { text: 'Mone', datafield: 'moneda', width: 30 },
              { text: 'Especie', datafield: 'especie', width: 100 },
              { text: 'Precio', datafield: 'precio', width: 180, cellsalign: 'right', cellsformat: 'd6' },
              { text: 'Total VN', datafield: 'sumaCantidad', width: 180, cellsalign: 'right', cellsformat: 'd2', aggregates: ['sum'] }
            ],
            theme: theme
        });        
        
        $("#aceptarSantander").jqxButton({ width: '160', theme: theme, disabled: false });

        $("#aceptarSantander").click(function(){
            var datos = {ordenes: enviar};
            $.post('/letesPesos/enviarSantander', datos, function(data){
                var titleClass;
                if (data.exito == 0){
                    titleClass = 'error';
                } else {
                    titleClass = 'success';
                }
                new Messi(data.resultado, {title: 'Mensaje', modal: true,
                    buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass});
                $('#grilla').jqxGrid('updatebounddata');
                $('#enviarSantanderButton').jqxButton({disabled: true });
                $('#enviarMercadoButton').jqxButton({disabled: true });
                $('#grilla').jqxGrid('clearselection');
                $('#ventanaPreviewSantander').jqxWindow('close');
            }, 'json');
        });

    });
</script>
<div id="cierre"></div>
<br>
<div id="grilla"></div>
<div id="botonera">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
            <td><input type="button" value="Enviar a Santander" id="enviarSantanderButton"></td>
            <td><input type="button" value="Enviar a Mercado" id="enviarMercadoButton"></td>
            <td><input type="button" value="Anular" id="anularButton"></td>
            <td><input type="button" value="Editar" id="editarButton"></td>
            <td><input type="button" value="Generar Excel" id="excelButton"></td>
        </tr>
    </table>
</div>
<div id="ventanaResumen">
    <div>Resumen de seleccion</div>
    <div>
        <div id="grillaResumen"></div>
    </div>
</div>
<div id="ventanaPreviewSantander">
    <div>
        Envio al Santander
    </div>
    <div>
        <div id="grillaPreviewSantander"></div>
        <div style="padding-top: 10px; text-align: right">
            <input type="button" id="aceptarSantander" value="Marcar como enviado">
        </div>        
    </div>
</div>
