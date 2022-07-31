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
        var datosEnviar = [];
        var cierre_id = 0;
        
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
                    cierre_id = cierre.id;
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
        });

        var source = {
                datatype: "json",
                datafields: [
                { name: 'id'},
                { name: 'operador'},
//                { name: 'tipoOperacion'},
//                { name: 'precioComitente'},
//                { name: 'precioCartera'},
                { name: 'numComitente'},
                { name: 'descripcionComitente'},
                { name: 'codigo'},
                { name: 'especie'},
                { name: 'especieDescripcion'},
//                { name: 'arancel'},
//                { name: 'plazo'},
//                { name: 'moneda'},
                { name: 'cantidad'},
                
                { name: 'moneda'},
                { name: 'esMismaMoneda'},
                { name: 'esCableMep'},                
//                { name: 'brutoCliente'},
                { name: 'observaciones'},
//                { name: 'numComitenteContraparte'},
                { name: 'estado'},
                { name: 'estado_id'},
                { name: 'fechaActualizacion'}
//                { name: 'cierreparking_id'}
            ],
            cache: false,
            url: '/parking/grilla',
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
                width: '90%',
                height: 600,
                columns: [
                        { text: 'Id', datafield: 'id', width: 40, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'operador', datafield: 'operador', width: 150 },
//                        { text: 'tipoOperacion', datafield: 'tipoOperacion', width: 100},
//                        { text: 'precioComitente', datafield: 'precioComitente', width: 80},
//                        { text: 'precioCartera', datafield: 'precioCartera', width: 200 },
                        { text: 'Nro Comitente', datafield: 'numComitente', width: 100},
                        { text: 'Comitente', datafield: 'descripcionComitente', width: 100},
                        { text: 'Código', datafield: 'codigo', width: 80},
                        { text: 'Abreviatura', datafield: 'especie', width: 80},
                        { text: 'Descripción', datafield: 'especieDescripcion', width: 180},
//                        { text: 'arancel', datafield: 'arancel', width: 41},
//                        { text: 'plazo', datafield: 'plazo', width: 41},
//                        { text: 'moneda', datafield: 'moneda', width: 60},
                        { text: 'cantidad', datafield: 'cantidad', width: 70},
                        
                        { text: 'moneda', datafield: 'moneda', width: 70},
                        { text: 'esMismaMoneda', datafield: 'esMismaMoneda', width: 70},
                        { text: 'PJ cable a MEP', datafield: 'esCableMep', width: 70},
                        
//                        { text: 'brutoCliente', datafield: 'brutoCliente', width: 95},
                        { text: 'Observaciones', datafield: 'observaciones', width: 150},
//                        { text: 'numComitenteContraparte', datafield: 'numComitenteContraparte', width: 90},
                        { text: 'Estado', datafield: 'estado', width: 80},
                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true},
                        { text: 'Fecha Orden', datafield: 'fechaActualizacion', width: 150},
                        
//                        { text: 'cierreparking_id', datafield: 'cierreparking_id', width: 100}
                        
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
            $.redirect('/parking/editar', {'id': 0, origen: 'parking'});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/parking/editar', {'id': id, origen: 'parking'});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/parking/delOrden', datos, function(data){
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
            new Messi('Desea enviar las ordenes seleccionadas ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){                        
                        $('#grilla').ajaxloader();
                        
                        jQuery.ajaxSetup({async:false});
                        
                        datosEnviar = [];
                        var seleccionados = $('#grilla').jqxGrid('getselectedrowindexes');
            
                        $.each(seleccionados, function(index, value){
                            var row = $('#grilla').jqxGrid('getrowdata', value);
                            datosEnviar.push(row.id);
                        });
                        
                        $.each(datosEnviar, function(index, value){

                            datos = {
                                orden: value,
                            };
                            $.post('/parking/obtenerParkingEsco', datos , function(data){
                                if (data){                                   
                                    //Acá voy a tener que guardar este parking, lo obtengo y hago un save
                                    guardarDatos = {
                                        orden: value,
                                        parking: data.resultado,
                                        numComitente: data.numComitente,
                                        especie: data.especie
                                    };
                                    
                                    $.post('/parking/saveParkingEsco', guardarDatos , function(res){
                                        
                                        console.log('res');
                                        console.log(res);
                                        console.log(typeof(res));
                                                                                      
                                        var titleClass;
                                        if (res == 0){
                                            titleClass = 'error';
                                        } else {
                                            titleClass = 'success';
                                        }
                                        new Messi(res, {title: 'Mensaje', modal: true,
                                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass});
                                    });
//                                    $("#parking").val(data.resultado);                   
                                } 
                                
                            }, 'json');
                        });
                        
                        $('#grilla').jqxGrid('updatebounddata');
                        $('#grilla').jqxGrid('clearselection');                        
                        $('#grilla').ajaxloader('hide');
                        jQuery.ajaxSetup({async:true});
                        
//                        datos = {
//                            ordenes: enviar
//                        };
//                        $.post('/parking/enviarOrdenes', datos, function(data){
//                            var titleClass;
//                            if (data.exito == 0){
//                                titleClass = 'error';
//                            } else {
//                                titleClass = 'success';
//                            }
//                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
//                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass});
//                            $('#grilla').jqxGrid('updatebounddata');
//                            $('#editarButton').jqxButton({disabled: true });
//                            $('#borrarButton').jqxButton({disabled: true });
//                            $('#enviarButton').jqxButton({disabled: true });
//                            $('#grilla').jqxGrid('clearselection');
//                        }
//                        , 'json');
                    } 
                }
            });
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
                        enviar.push(row.id);
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
                $.post('/parking/grabarExcel', { file: file.name, cierre: $("#cierre").jqxDropDownList('getSelectedItem').value}, function(msg){
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
<div id="sistema" style='float: left; vertical-align: text-bottom; text-align: left;'><ul>Grilla Parking</ul></div>
<br>
<br>
<div id="grilla"></div>
<div id="botonera">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
            <td><input type="button" value="Nuevo" id="nuevoButton"></td>
            <td><input type="button" value="Editar" id="editarButton"></td>
            <td><input type="button" value="Borrar" id="borrarButton"></td>
            <td><input type="button" value="Enviar para Aprobación" id="enviarButton"></td>
            <td id='archivoExcelFila'><input type="file" value="Archivo" id="archivoExcel"></td>
        </tr>
    </table>
</div>