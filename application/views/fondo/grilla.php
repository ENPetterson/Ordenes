<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
        var theme = getTheme();
        var id = 0;
        var enviar = [];
        var cierre_id = 0;
        
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
            source.data = {cierrefondo_id: event.args.item.value};
            $("#grilla").jqxGrid('updatebounddata');
        });


        var source = {
                datatype: "json",
                datafields: [
                { name: 'id'},
                { name: 'operador'},
                { name: 'fechaConcertacion'},
                { name: 'operacion'},
                { name: 'fondo'},
                { name: 'nombreFondo'},
                { name: 'numComitente'},
                { name: 'nombreComitente'},
                { name: 'rescate'},
                { name: 'importe'},
                { name: 'esAcdi'},
                { name: 'esNoAcdiTipo'},
                { name: 'destinoRescate'},
                { name: 'totalCuotapartes'},
                { name: 'saldoMonetario'},
                { name: 'origenFondos'},
                { name: 'moneda'},
                { name: 'observaciones'},
                { name: 'saldoAcdi'},
                { name: 'saldoColocadorSimple'},
                { name: 'estado'},
                { name: 'estado_id'}
            ],
            cache: false,
            url: '/fondo/grilla',
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
                width: "98%",
                height: 600,
                columns: [
                        { text: 'Id', datafield: 'id', width: 40, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'operador', datafield: 'operador', width: 120 },
                        { text: 'fechaConcertacion', datafield: 'fechaConcertacion', width: 100 },
                        { text: 'operacion', datafield: 'operacion', width: 70},
                        { text: 'fondo', datafield: 'fondo', width: 65},
                        { text: 'nombreFondo', datafield: 'nombreFondo', width: 120},
                        { text: 'Num CTTE', datafield: 'numComitente', width: 80},
                        { text: 'Comitente', datafield: 'nombreComitente', width: 150},
                        { text: 'rescate', datafield: 'rescate', width: 70},
                        { text: 'importe', datafield: 'importe', width: 70},
                        { text: 'esAcdi', datafield: 'esAcdi', width: 50},
                        { text: 'Garantía', datafield: 'esNoAcdiTipo', width: 50},
                        { text: 'destinoRescate', datafield: 'destinoRescate', width: 70},
                        { text: 'ValorCuota', datafield: 'totalCuotapartes', width: 120},
                        { text: 'saldoMonetario', datafield: 'saldoMonetario', width: 110},
                        { text: 'origenFondos', datafield: 'origenFondos', width: 95},
                        { text: 'moneda', datafield: 'moneda', width: 60},
                        { text: 'observaciones', datafield: 'observaciones', width: 110},
                        { text: 'saldoAcdi', datafield: 'saldoAcdi', width: 110},
                        { text: 'saldoColocadorSimple', datafield: 'saldoColocadorSimple', width: 130},
                        { text: 'Estado', datafield: 'estado', width: 70},
                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true}
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
            $.redirect('/fondo/editar', {'id': 0, origen: 'fondo'});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/fondo/editar', {'id': id, origen: 'fondo'});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/fondo/delOrden', datos, function(data){
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
                        $.post('/fondo/enviarOrdenes', datos, function(data){
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
        
        
        
        
    });
</script>
<div id="cierre"></div>
<br>
<div id="sistema" style='float: left; margin-left: 0px; margin-bottom: 10px; text-align: left; vertical-align: text-bottom;'><ul>Grilla Fondos</ul></div>
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