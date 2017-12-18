<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
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
            url: '/bono/getCierres',
            async: false
        };
        var DACierre = new $.jqx.dataAdapter(srcCierre);
        
        $("#cierre").on('bindingComplete', function(event){
            $.post('/bono/getCierreActual', function(cierre){
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
            source.data = {cierrebono_id: event.args.item.value};
            $("#grilla").jqxGrid('updatebounddata');
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
                { name: 'precio', type: 'float'},
                { name: 'comitente'},
                { name: 'tipoPersona'},
                { name: 'oficial'},
                { name: 'cuit', type: 'number'},
                { name: 'estado'},
                { name: 'estado_id'},
                { name: 'fhmodificacion', type: 'date', format: 'yyyy-MM-dd'}
            ],
            cache: false,
            url: '/bono/grilla',
            data: {cierrebono_id: cierre_id},
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
                width: 1510,
                height: 600,
                columns: [
                        { text: 'Id', datafield: 'id', width: 80, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'Tramo', datafield: 'tramo', width: 110 },
                        { text: 'Nro Comitente', datafield: 'numComitente', width: 70},
                        { text: 'Mone', datafield: 'moneda', width: 30},
                        { text: 'Cable', datafield: 'cable', width: 30, columntype: 'checkbox'},
                        { text: 'Plazo', datafield: 'plazo', width: 40, cellsalign: 'right'},
                        { text: 'Comis', datafield: 'comision', width: 60, cellsalign: 'right', cellsformat: 'd4'},
                        { text: 'Cantidad', datafield: 'cantidad', width: 140, cellsalign: 'right', cellsformat: 'd', aggregates: ['sum']},
                        { text: 'Precio', datafield: 'precio', width: 100, cellsalign: 'right', cellsformat: 'd10'},
                        { text: 'Comitente', datafield: 'comitente', width: 200},
                        { text: 'Tipo Per', datafield: 'tipoPersona', width: 100},
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
        
        $("#nuevoButton").click(function(){
            $.redirect('/bono/editar', {'id': 0, origen: 'bono'});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/bono/editar', {'id': id, origen: 'bono'});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/bono/delOrden', datos, function(data){
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
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/bono/enviarOrdenes', datos, function(data){
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
        
        
        
    });
</script>
<div id="cierre"></div>
<br>
<div id="grilla"></div>
<div id="botonera">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
            <td><input type="button" value="Nuevo" id="nuevoButton"></td>
            <td><input type="button" value="Editar" id="editarButton"></td>
            <td><input type="button" value="Borrar" id="borrarButton"></td>
            <td><input type="button" value="Enviar a Backoffice" id="enviarButton"></td>
        </tr>
    </table>
</div>