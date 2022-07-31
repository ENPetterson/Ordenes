<?php
$timestamp = time();
?>

<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
            
        var timestamp = <?= $timestamp; ?>;
        var token = '<?= md5('unique_salt' . $timestamp); ?>'; 
        
        var theme = getTheme();
        var id = 0;
        var enviar = [];
        var cierre_id = 0;
        var cierreFecha;
        
        var srcCierre = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'fechahora' }
            ],
            id: 'id',
            url: '/canjelocal/getCierres',
            async: false
        };
        var DACierre = new $.jqx.dataAdapter(srcCierre);

        $("#sistema").jqxMenu({width: 200, height: 25, theme: theme});
        
        $("#cierre").on('bindingComplete', function(event){
            $.post('/canjelocal/getCierreActual', function(cierre){
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
            source.data = {cierrecanjelocal_id: event.args.item.value};
            $("#grilla").jqxGrid('updatebounddata');
            $("#excelButton").jqxButton({disabled: false });
            cierreFecha = event.args.item.label;
        });

        var source = {
                datatype: "json",
                datafields: [
                { name: 'id', type: 'int'},
                { name: 'numComitente', type: 'int'},
//                { name: 'comision', type: 'float'},
                { name: 'cantidad', type: 'number'},
                { name: 'arancel', type: 'float'},
                { name: 'especie'},
                { name: 'plazo'},
                { name: 'tipo'},
                { name: 'bonoNombre'},
//                { name: 'bono'},
//                { name: 'cantidadACrecer', type: 'number'},
//                { name: 'precio', type: 'float'},
//                { name: 'segundaParte', type: 'bool'},
//                { name: 'cantidadAcrecerSegunda', type: 'number'},
                { name: 'comitente'},
                { name: 'tipoPersona'},
                { name: 'oficial'},
                { name: 'usuario'},
                { name: 'cuit', type: 'number'},
                { name: 'posicion'},
                { name: 'fhmodificacion', type: 'date', format: 'yyyy-MM-dd'},
                { name: 'estado'},
                { name: 'estado_id'},
//                { name: 'envio'},
                { name: 'fhenvio', type: 'date', format: 'yyyy-MM-dd'},
                { name: 'estaConfirmado'}
            ],
            cache: false,
            url: '/canjelocal/procesarGrilla',
            data: {cierre_id: cierre_id},
            type: 'post',
            async: false,
            beforeprocessing: function(data)
            {		
                    if (data != null)
                    {
//                        source.totalrecords = data[0].TotalRows;					
                        source.totalrecords = data.length;					
                    }
            }
        };
        
        var dataadapter = new $.jqx.dataAdapter(source, {
                loadError: function(xhr, status, error)
                {
                        alert(xhr.responseText);
                }
            }
        );

        var cellclassname = function (row, column, value, data) {
            switch (data.estado_id){
                case '2':
                case '7':
                    return "greenClass";
                    break;
                case '3':
                    return "yellowClass";
                    break;
                case '6':
                case '8':
                    return "redClass";
                    break;
            }
            if (data.esRetail == 'S'){
                return "grayClass";
            }
            
            if (data.esAdblick == 'S'){
                return "orangeClass";
            }
            
        };

        // initialize jqxGrid
        $("#grilla").jqxGrid(
        {		
                source: dataadapter,
                theme: theme,
                filterable: true,
                showfilterrow: true,
                showfiltermenuitems: true,
                filtermode: 'excel',
                sortable: true,
                pageable: false,
                virtualmode: false,
                selectionmode: 'checkbox',
                columnsresize: true,
                showstatusbar: true,
                statusbarheight: 25,
                showaggregates: true,
                autosavestate: true,
                autoloadstate: false,
                rendergridrows: function(obj)
                {
                        return obj.data;    
                },
                width: 1810,
                height: 400,
                columns: [
                        { text: 'Id', datafield: 'id', width: 70, cellsalign: 'right', cellsformat: 'd', aggregates: ['count'] },
                        { text: 'Nro Comitente', datafield: 'numComitente', width: 70},
//                        { text: 'Comis', datafield: 'comision', width: 60, cellsalign: 'right', cellsformat: 'd4'},
                        { text: 'Cantidad', datafield: 'cantidad', width: 90, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum'] },
                        { text: 'Arancel', datafield: 'arancel', width: 70, cellsalign: 'right', cellsformat: 'd4'},
                        { text: 'Especie', datafield: 'especie', width: 90},
                        { text: 'Código', datafield: 'plazo', width: 90},
                        { text: 'CVSA', datafield: 'tipo', width: 60},
                        { text: 'Bono', datafield: 'bonoNombre', width: 90},
//                        { text: 'Bono', datafield: 'bono', width: 60},
//                        { text: 'Cant A Crecer', datafield: 'cantidadACrecer', width: 140, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum'] },
//                        { text: 'Precio', datafield: 'precio', width: 100, cellsalign: 'right', cellsformat: 'd10'},
//                        { text: 'Segund', datafield: 'segundaParte', width: 30, columntype: 'checkbox'},
//                        { text: 'Cant A Crec 2da', datafield: 'cantidadAcrecerSegunda', width: 140, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum'] },
                        { text: 'Comitente', datafield: 'comitente', width: 200},
                        { text: 'Tipo Per', datafield: 'tipoPersona', width: 80},
                        { text: 'Oficial', datafield: 'oficial', width: 200},
                        { text: 'Usuario', datafield: 'usuario', width: 150},
                        { text: 'CUIT', datafield: 'cuit', width: 100},
                        { text: 'Posicion', datafield: 'posicion', width: 100},
                        { text: 'Generada', datafield: 'fhmodificacion', width: 80, cellsformat: 'dd/MM/yyyy HH:mm:ss'},
                        { text: 'Estado', datafield: 'estado', width: 90},
                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true},
                        { text: 'Inst', datafield: 'fhenvio', width: 80, cellsformat: 'dd/MM/yyyy HH:mm:ss'},
                        { text: 'WEB', datafield: 'estaConfirmado', width: 30, columntype: 'checkbox'}
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
        $("#enviarArchivoButton").jqxButton({ width: '160', theme: theme, disabled: true });
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
            $.post('/canjelocal/previewMercado', datos, function(data){
                
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
                            $.post('/canjelocal/enviarMercado', datos, function(data){
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
        
        
        $("#enviarArchivoButton").click(function(){
            $("#grilla").ajaxloader();
            var datos = {ordenes: enviar};
            $("#grilla").ajaxloader();
            $.post('/canjelocal/previewArchivo', datos, function(data){                
                var resultado = data;
                if(resultado){
                    $.redirect('/canjelocal/getDescargarAchivo', {archivo: resultado});
//                    $.redirect('/flujo/getDescargarLog', {'logName': logName});                    
                }

                new Messi('Ha enviado los datos al mercado ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                    buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                        if (val == 's'){
                            $.post('/canjelocal/enviarArchivo', datos, function(data){
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
                
        
////////////////////////////////////////////////////////////////////////////////   
        $("#procesarExcel").jqxButton({ width: '300', theme: theme, disabled: false });

        $('#procesarExcel').uploadifive({
            'uploadScript': '/uploadifive.php',
            'formData': {
                'timestamp': timestamp,
                'token': token
            },
            'buttonText': 'Importar Excel...',
            'multi': false,
            'queueSizeLimit': 1,
            'uploadLimit': 0,
            'height': 20,
            'width': 200,
            'removeCompleted': true,
            'onUploadComplete': function(file) {
                $('#grilla').ajaxloader();
                $.post('/canjelocal/procesarExcel', { file: file.name, cierre: $("#cierre").jqxDropDownList('getSelectedItem').value}, function(msg){
                    var titleClass;
                    var mensaje;
                    var title;
                    if(msg.resultado == 'OK'){
                        
                        console.log(msg);
                        var datos = {ordenes: msg.ordenes};


                         $.post('/canjelocal/previewArchivo', datos, function(data){                
                            var resultado = data;
                            if(resultado){
                                $.redirect('/canjelocal/getDescargarAchivo', {archivo: resultado});
            //                    $.redirect('/flujo/getDescargarLog', {'logName': logName});                    
                            }

//                            new Messi('Ha enviado los datos al mercado ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
//                                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
//                                    if (val == 's'){
//                                        $.post('/canjelocal/enviarArchivo', datos, function(data){
//                                            var titleClass;
//                                            if (data.exito == 0){
//                                                titleClass = 'error';
//                                            } else {
//                                                titleClass = 'success';
//                                            }
//                                            $("#grilla").ajaxloader('hide');
//                                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
//                                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass});
//                                            $('#grilla').jqxGrid('updatebounddata');
//                                            $('#grilla').jqxGrid('clearselection');
//                                        }, 'json');                            
//                                    } else {
//                                        $("#grilla").ajaxloader('hide');
//                                    }
//                                }
//                            });                
                        }, 'json');           
                                    
                                    
                                    
//                        titleClass = 'success';
//                        title = 'Correcto';
//                        mensaje = 'Se han procesado las ordenes';
                    } else {
                        titleClass = 'error';
                        title = 'No se procesaron las ordenes';
                        mensaje = msg.mensaje;
                    }
                    $('#grilla').ajaxloader('hide');
                    new Messi(mensaje, {title: title, modal: true,
                        buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass, callback: function(val) { 
                            if (val == 'X'){
                                $("#grilla").jqxGrid('updatebounddata');
                            } 
                        }
                    });                    
                }, 'json');
            }
        });
////////////////////////////////////////////////////////////////////////////////                   
        
        
        $("#anularButton").click(function(){
            new Messi('Desea anular las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        $("#grilla").ajaxloader();
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/canjelocal/anularOrdenes', datos, function(data){
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
            $.redirect('/canjelocal/editar', {'id': id, origen: 'procesar'});
        });
        

        
        
        $('#grilla').on('rowselect rowunselect', function (event) {
            enviar = [];
            var rowindexes = $('#grilla').jqxGrid('getselectedrowindexes');
            $('#enviarSantanderButton').jqxButton({disabled: true });
            $('#enviarMercadoButton').jqxButton({disabled: true });
            $('#enviarArchivoButton').jqxButton({disabled: true });
            $('#editarButton').jqxButton({disabled: true });
            $('#anularButton').jqxButton({disabled: true });
            if (rowindexes.length > 0){
                $.each(rowindexes, function(index, value){
                    var row = $('#grilla').jqxGrid('getrowdata', value);
                    var estado_id = row.estado_id;
                    id = row.id;
                    if (estado_id != 2 && estado_id != 4 && estado_id != 10){
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
                    $('#enviarArchivoButton').jqxButton({disabled: false });
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
                {name: 'cantidadOrdenes', type: 'integer'},
                {name: 'sumaCantidad', type: 'number'}
            ],
            url: '/canjelocal/grillaResumen',
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
              { text: 'Cant Ord', datafield: 'cantidadOrdenes', width: 100, cellsalign: 'right', cellsformat: 'n', aggregates: ['sum']  },
              { text: 'Total VN', datafield: 'sumaCantidad', width: 180, cellsalign: 'right', cellsformat: 'd2', aggregates: ['sum'] }
            ],
            theme: theme
        });





        $("#ventanaPreviewSantander").jqxWindow({autoOpen: false, keyboardCloseKey: -1, showCloseButton: true, height: '270px', width: '590px', theme: theme});

        
        var srcGrillaPreviewSantander = {
            datatype: "json",
            datafields: [
                {name: 'precio', type: 'number'},
                {name: 'sumaCantidad', type: 'number'}
            ],
            url: '/canjelocal/previewSantander',
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
              { text: 'Precio', datafield: 'precio', width: 180, cellsalign: 'right', cellsformat: 'd6' },
              { text: 'Total VN', datafield: 'sumaCantidad', width: 180, cellsalign: 'right', cellsformat: 'd2', aggregates: ['sum'] }
            ],
            theme: theme
        });        
        
        $("#aceptarSantander").jqxButton({ width: '160', theme: theme, disabled: false });

        $("#aceptarSantander").click(function(){
            var datos = {ordenes: enviar};
            $.post('/canjelocal/enviarSantander', datos, function(data){
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
                $('#enviarArchivoButton').jqxButton({disabled: true });
                $('#grilla').jqxGrid('clearselection');
                $('#ventanaPreviewSantander').jqxWindow('close');
            }, 'json');
        });

    });
</script>


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

<div id="cierre"></div>
<br>
<div id="sistema" style='float: left; vertical-align: text-bottom; text-align: left;'><ul>Procesar Canjelocales</ul></div>
<br>
<br>
<div id="grilla"></div>
<div id="botonera">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
            <td><input type="button" value="Enviar a Santander" id="enviarSantanderButton"></td>
            <td><input type="button" value="Enviar a Mercado" id="enviarMercadoButton"></td>
            <td><input type="button" value="Enviar a Archivo" id="enviarArchivoButton"></td>
            <td id='procesarExcelFila'><input type="file" value="A Procesar" id="procesarExcel"></td>
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
