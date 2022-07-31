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
        });

        var source = {
                datatype: "json",
                datafields: [
                { name: 'id'},
                { name: 'numComitente', type: 'number'},
//                { name: 'comision', type: 'float'},
                { name: 'cantidad', type: 'number'},
                { name: 'arancel', type: 'arancel'},
                { name: 'especie'},
                { name: 'plazo'},
                { name: 'tipo'},
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
                { name: 'estado'},
                { name: 'estado_id'},
                { name: 'fhmodificacion', type: 'date', format: 'yyyy-MM-dd'},
                { name: 'estaConfirmado'}
            ],
            cache: false,
            url: '/canjelocal/grilla',
            data: {cierrecanjelocal_id: cierre_id},
            type: 'post'
        };

        dataadapter = new $.jqx.dataAdapter(source);

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
                width: 1610,
                height: 600,
                columns: [
                        { text: 'Id', datafield: 'id', width: 80, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'Nro Comitente', datafield: 'numComitente', width: 70},
//                        { text: 'Comis', datafield: 'comision', width: 60, cellsalign: 'right', cellsformat: 'd4'},
                        { text: 'Cantidad', datafield: 'cantidad', width: 90, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum']},
                        { text: 'Arancel', datafield: 'arancel', width: 70, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum']},
                        { text: 'Especie', datafield: 'especie', width: 90},
                        { text: 'Código', datafield: 'plazo', width: 60},
                        { text: 'CVSA', datafield: 'tipo', width: 60},
//                        { text: 'Bono', datafield: 'bono', width: 60},
//                        { text: 'Cant a Crecer', datafield: 'cantidadACrecer', width: 140, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum']},
//                        { text: 'Precio', datafield: 'precio', width: 100, cellsalign: 'right', cellsformat: 'd10'},
//                        { text: 'Segunda', datafield: 'segundaParte', width: 30, columntype: 'checkbox'},
//                        { text: 'Cant a Crec 2da', datafield: 'cantidadAcrecerSegunda', width: 140, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum']},
                        { text: 'Comitente', datafield: 'comitente', width: 200},
                        { text: 'Tipo Per', datafield: 'tipoPersona', width: 100},
                        { text: 'Oficial', datafield: 'oficial', width: 200},
                        { text: 'Usuario', datafield: 'usuario', hidden: true, width: 200},
                        { text: 'CUIT', datafield: 'cuit', width: 100},
                        { text: 'Posicion', datafield: 'posicion', width: 60},
                        { text: 'Estado', datafield: 'estado', width: 100},
                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true},
                        { text: 'Carga', datafield: 'fhmodificacion', width: 100, cellsformat: 'dd/MM/yyyy'},
                        { text: 'WEB', datafield: 'estaConfirmado', width: 50, columntype: 'checkbox'}
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
        
        $("#nuevoButton").click(function(){
            $.redirect('/canjelocal/editar', {'id': 0, origen: 'canjelocal'});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/canjelocal/editar', {'id': id, origen: 'canjelocal'});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/canjelocal/delOrden', datos, function(data){
                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'error'});
                            $('#grilla').jqxGrid('updatebounddata');
                            $('#editarButton').jqxButton({disabled: true });
                            $('#borrarButton').jqxButton({disabled: true });
                            $('#enviarButton').jqxButton({disabled: true });
                            $('#grilla').jqxGrid('clearselection'); 
                        }
                        , 'json');
                    } 
                }
            });
        });
        
        $("#enviarButton").click(function(){
            
//            var titleClass;
//            titleClass = 'success';
//            new Messi('Estamos realizando procesos en el sistema. Aguarde unos minutos e intente nuevamente', {title: 'Mensaje', modal: true,
//            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass});
            
            
            datos = {
                cierre: $("#cierre").val()
            };  
            
            $.post('/canjelocal/comprobarEstadoCierre', datos, function(data){
                if(data == 'true'){
                    var titleClass;
                    titleClass = 'success';
                    new Messi('Estamos realizando procesos en el sistema. Aguarde unos minutos e intente nuevamente', {title: 'Mensaje', modal: true,
                    buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass});
                }else{
                    new Messi('Desea enviar las ordenes seleccionadas ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                        buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                            if (val == 's'){
                                datos = {
                                    ordenes: enviar
                                };
                                $.post('/canjelocal/enviarOrdenes', datos, function(data){
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
                                    $('#grilla').jqxGrid('clearselection');
                                }
                                , 'json');
                            } 
                        }
                    });
                }
            }
            , 'json');
            
        });
        
        
        $('#grilla').on('rowselect rowunselect', function (event) {
            enviar = [];
            var rowindexes = $('#grilla').jqxGrid('getselectedrowindexes');
            $('#editarButton').jqxButton({disabled: true });
            $('#borrarButton').jqxButton({disabled: true });
            $('#enviarButton').jqxButton({disabled: true });
            if (rowindexes.length > 0){
                $.each(rowindexes, function(index, value){
                    var row = $('#grilla').jqxGrid('getrowdata', value);
                    var estado_id = row.estado_id;
                    id = row.id;
                    if (estado_id != 1){
                        $("#grilla").jqxGrid('unselectrow', value);
                    } else {
                        enviar.push(id);
                    }
                });
                if (enviar.length > 0){
                    $('#enviarButton').jqxButton({disabled: false });
                    $('#borrarButton').jqxButton({disabled: false });
                    if (enviar.length == 1){
                        $('#editarButton').jqxButton({disabled: false });
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
                $.post('/canjelocal/grabarExcel', { file: file.name, cierre: $("#cierre").jqxDropDownList('getSelectedItem').value}, function(msg){
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

        
    });
</script>
<div id="cierre"></div>
<br>
<div id="sistema" style='float: left; vertical-align: text-bottom; text-align: left;'><ul>Grilla Canjelocales</ul></div>
<br>
<br>
<div id="grilla"></div>
<div id="botonera">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
            <td><input type="button" value="Nuevo" id="nuevoButton"></td>
            <td><input type="button" value="Editar" id="editarButton"></td>
            <td><input type="button" value="Borrar" id="borrarButton"></td>
            <td><input type="button" value="Enviar a Backoffice" id="enviarButton"></td>
            <td id='archivoExcelFila'><input type="file" value="Archivo" id="archivoExcel"></td>
        </tr>
    </table>
</div>