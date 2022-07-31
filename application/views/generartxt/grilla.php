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
        
        $("#sistema").jqxMenu({width: 200, height: 25, theme: theme});

        var source = {
                datatype: "json",
                datafields: [
                { name: 'id'},
                { name: 'tramo'},
                { name: 'numeroComitente', type: 'number'},
                { name: 'especie'},
                { name: 'moneda'},
                { name: 'cable', type: 'bool'},
                { name: 'comision', type: 'float'},
                { name: 'cantidad', type: 'number'},
                { name: 'precio', type: 'float'},
                { name: 'comitente'},
//                { name: 'tipoPersona'},
                { name: 'oficial'},
                { name: 'cuit', type: 'number'},
                { name: 'estado'},
                { name: 'estado_id'},
                { name: 'fhmodificacion', type: 'date', format: 'yyyy-MM-dd'}
            ],
            cache: false,
            url: '/generartxt/grilla',
            type: 'post'
        };

        var dataadapter = new $.jqx.dataAdapter(source);

        // initialize jqxGrid
        $("#grilla").jqxGrid(
        {		
                source: dataadapter,
                theme: theme,
                filterable: true,
                filtermode: 'excel',
                sortable: true,
                autoheight: false,
                pageable: false,
                virtualmode: false,
                selectionmode: 'checkbox',
                columnsresize: true,
                showstatusbar: true,
                statusbarheight: 25,
                showaggregates: true,
                width: 1510,
                height: 600,
                columns: [
                        { text: 'Id', datafield: 'id', width: 80, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'Tramo', datafield: 'tramo', width: 110 },
                        { text: 'Nro Comitente', datafield: 'numeroComitente', width: 70},
                        { text: 'Especie', datafield: 'especie', width: 70},
                        { text: 'Mone', datafield: 'moneda', width: 30},
                        { text: 'Cable', datafield: 'cable', width: 30, columntype: 'checkbox'},
                        /*
                        { text: 'Plazo', datafield: 'plazo', width: 40, cellsalign: 'right'},
                        */
                        { text: 'Comis', datafield: 'comision', width: 60, cellsalign: 'right', cellsformat: 'd4'},
                        { text: 'Cantidad', datafield: 'cantidad', width: 140, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum']},
                        { text: 'Tasa', datafield: 'precio', width: 100, cellsalign: 'right', cellsformat: 'd10'},
                        { text: 'Comitente', datafield: 'comitente', width: 200},
//                        { text: 'Tipo Per', datafield: 'tipoPersona', width: 100},
                        { text: 'Oficial', datafield: 'oficial', width: 200},
                        { text: 'CUIT', datafield: 'cuit', width: 100},
                        { text: 'Estado', datafield: 'estado', width: 100},
                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true},
                        { text: 'Carga', datafield: 'fhmodificacion', width: 100, cellsformat: 'dd/MM/yyyy'}
                ]
        });
        $("#grilla").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grilla").jqxGrid('localizestrings', localizationobj);
        }); 
        
        $("#nuevoButton").jqxButton({ width: '80', theme: theme });
        $("#editarButton").jqxButton({ width: '80', theme: theme, disabled: true });
        $("#borrarButton").jqxButton({ width: '80', theme: theme, disabled: true });
        $("#enviarButton").jqxButton({ width: '160', theme: theme, disabled: true });
        //$("#retirarButton").jqxButton({ width: '160', theme: theme, disabled: true });
        
        $("#nuevoButton").click(function(){
            $.redirect('/generartxt/editar', {'id': 0, origen: 'generartxt'});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/generartxt/editar', {'id': id, origen: 'generartxt'});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/generartxt/delOrden', datos, function(data){
                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'error'});
                            $('#grilla').jqxGrid('updatebounddata');
                            $('#editarButton').jqxButton({disabled: true });
                            $('#borrarButton').jqxButton({disabled: true });
                            $('#enviarButton').jqxButton({disabled: true });
                            //$('#retirarButton').jqxButton({disabled: true });
                            $('#grilla').jqxGrid('clearselection'); 
                        }
                        , 'json');
                    } 
                }
            });
        });
        
        $("#enviarButton").click(function(){
            new Messi('Desea enviar las ordenes seleccionadas ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/generartxt/enviarOrdenes', datos, function(data){
                            var titleClass;
                            if (data.exito == 0){
                                titleClass = 'error';
                            } else {
                                titleClass = 'success';
                            }
                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass});
                            $('#grilla').jqxGrid('updatebounddata');
                            $('#editarButton').jqxButton({disabled: true });
                            $('#borrarButton').jqxButton({disabled: true });
                            $('#enviarButton').jqxButton({disabled: true });
                            //$('#retirarButton').jqxButton({disabled: true });
                            $('#grilla').jqxGrid('clearselection');
                        }
                        , 'json');
                    } 
                }
            });
        });
        
        /*
        $("#retirarButton").click(function(){
            new Messi('Desea retirar las ordenes seleccionadas ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/licitacion/retirarOrdenes', datos, function(data){
                            var titleClass;
                            if (data.exito == 0){
                                titleClass = 'error';
                            } else {
                                titleClass = 'success';
                            }
                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass});
                            $('#grilla').jqxGrid('updatebounddata');
                            $('#editarButton').jqxButton({disabled: true });
                            $('#borrarButton').jqxButton({disabled: true });
                            $('#enviarButton').jqxButton({disabled: true });
                            $('#retirarButton').jqxButton({disabled: true });
                            $('#grilla').jqxGrid('clearselection');
                        }
                        , 'json');
                    } 
                }
            });
        });
        */
        
        $('#grilla').on('rowselect rowunselect', function (event) {
            enviar = [];
            var rowindexes = $('#grilla').jqxGrid('getselectedrowindexes');
            $('#editarButton').jqxButton({disabled: true });
            $('#borrarButton').jqxButton({disabled: true });
            $('#enviarButton').jqxButton({disabled: true });
            //$('#retirarButton').jqxButton({disabled: true });
            var primerEstado = 0;
            if (rowindexes.length > 0){
                $.each(rowindexes, function(index, value){
                    var row = $('#grilla').jqxGrid('getrowdata', value);
                    var estado_id = row.estado_id;
                    id = row.id;
                    //if (estado_id == 2 || estado_id == 4 || estado_id == 5 || estado_id !== primerEstado){
                    if (estado_id != 1){
                        $("#grilla").jqxGrid('unselectrow', value);
                    } else {
                        if (primerEstado == 0){
                            primerEstado = estado_id;
                        }
                        enviar.push(id);
                    }
                });                
                if (enviar.length > 0){
                    if (primerEstado == 1){
                        $('#enviarButton').jqxButton({disabled: false });
                        $('#borrarButton').jqxButton({disabled: false });
                        if (enviar.length == 1){
                            $('#editarButton').jqxButton({disabled: false });
                        }
                    }
                    if (primerEstado == 3){
                        $('#retirarButton').jqxButton({disabled: false });
                    }
                }
            }
        });
        
////////////////////////////////////////////////////////////////////////////////   
        $("#archivoExcel").jqxButton({ width: '300', theme: theme, disabled: false });

        $('#archivoExcel').uploadifive({
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
                $.post('/generartxt/grabarExcel', { file: file.name}, function(msg){
                    var titleClass;
                    var mensaje;
                    var title;
                    if(msg.resultado == 'OK'){
                        titleClass = 'success';
                        title = 'Correcto';
                        mensaje = 'Se han importado las ordenes';
                    } else {
                        titleClass = 'error';
                        title = 'No se importaron las ordenes';
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
        $("#generarArchivo").jqxButton({ width: '300', theme: theme, disabled: false });

        $('#generarArchivo').uploadifive({
            'uploadScript': '/uploadifive.php',
            'formData': {
                'timestamp': timestamp,
                'token': token
            },
            'buttonText': 'Generar Archivo...',
            'multi': false,
            'queueSizeLimit': 1,
            'uploadLimit': 0,
            'height': 20,
            'width': 200,
            'removeCompleted': true,
            'onUploadComplete': function(file) {
                $('#grilla').ajaxloader();
                $.post('/generartxt/generarArchivo', { file: file.name}, function(resultado){

                    // Hablaba de este resultado y este redirect
                    if(resultado){
                        $.redirect('/generartxt/getDescargarAchivo', {archivo: resultado});
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
        
    });
</script>
<div id="sistema" style='float: left; vertical-align: text-bottom; text-align: left;'><ul>Grilla Generar Txt</ul></div>
<br>
<br>
<div id="grilla"></div>
<div id="asd">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
            <td><input type="button" value="Nuevo" id="nuevoButton"></td>
            <td><input type="button" value="Editar" id="editarButton"></td>
            <td><input type="button" value="Borrar" id="borrarButton"></td>
            <td><input type="button" value="Enviar a Backoffice" id="enviarButton"></td>
            <td id='archivoExcelFila'><input type="file" value="Archivo" id="archivoExcel"></td>
            <td id='generarArchivoFila'><input type="file" value="Archivo" id="generarArchivo"></td>
        </tr>
    </table>
</div>
<!--
<div>
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
            <td><input type="button" value="Retirar Oferta" id="retirarButton"></td>
        </tr>
    </table>
</div>
-->