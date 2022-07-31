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
            url: '/correccion/getCierres',
            async: false
        };
        var DACierre = new $.jqx.dataAdapter(srcCierre);
        
        $("#cierre").on('bindingComplete', function(event){
            $.post('/correccion/getCierreActual', function(cierre){
                
                console.log(cierre);
                
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
            source.data = {cierrecorreccion_id: event.args.item.value};
            $("#grilla").jqxGrid('updatebounddata');
        });


        var cellclassname = function (row, column, value, data) {

                return "orangeClass";
            
        };

        var source = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'operador'},
//                { name: 'codBoleto'},
                { name: 'numBoleto'},
                { name: 'fechaConcertacion', type: 'date', format: 'yyyy-MM-dd'},
                { name: 'fechaLiquidacion', type: 'date', format: 'yyyy-MM-dd'},
                { name: 'tpOperacionBurs'},
                { name: 'numComitente'},
                { name: 'comitente'},
                { name: 'administrador'},
                { name: 'instrumentoAbrev'},
                { name: 'moneda'},
                { name: 'precio'},
                { name: 'cantidad'},
                { name: 'arancel'},
//                { name: 'administradorCorregido'},
//                { name: 'precioCorregido'},
                { name: 'cantidadCorregido'},                
                { name: 'arancelCorregido'},
                { name: 'numComitenteCorregido'},
                { name: 'comitenteCorregido'},                
                { name: 'observaciones'},
                { name: 'precioCorregido'},
                { name: 'tipoOperacionCorregido'}, 
                { name: 'especieCorregido'}, 
                
                
//                { name: 'autorizadores'},
//                { name: 'caja'},
//                { name: 'visual'},
//                { name: 'control'},

                { name: 'cierrecorreccion_id'},
                { name: 'usuario_id'},
                { name: 'fechaActualizacion', type: 'date', format: 'yyyy-MM-dd'},
                { name: 'estado'},
                { name: 'estado_id'}
            ],
            cache: false,
            url: '/correccion/grilla',
            data: {cierrecorreccion_id: cierre_id},
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
                width: 1800,
                height: 600,
                rendergridrows: function(obj)
                {
                        return obj.data;    
                },
                columns: [
                        { text: 'Id', datafield: 'id', width: 40, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'operador', datafield: 'operador', width: 130 },
//                        { text: 'codBoleto', datafield: 'codBoleto', width: 80},
                        { text: 'Boleto', datafield: 'numBoleto', width: 70},
                        { text: 'Concertacion', datafield: 'fechaConcertacion', width: 85, cellsformat: 'dd/MM/yyyy'},
                        { text: 'Liquidacion', datafield: 'fechaLiquidacion', width: 85, cellsformat: 'dd/MM/yyyy'},
                        
                        { text: 'OperacionBursatil', datafield: 'tpOperacionBurs', width: 150},
                        { text: 'Comitente', datafield: 'numComitente', width: 80},
                        { text: 'comitente', datafield: 'comitente', width: 148, hidden:true},
                        { text: 'administrador', datafield: 'administrador', width: 100, hidden:true},

                        { text: 'Instrumento', datafield: 'instrumentoAbrev', width: 90},
                        { text: 'Moneda', datafield: 'moneda', width: 90},
                        { text: 'precio', datafield: 'precio', width: 70},
                        { text: 'cantidad', datafield: 'cantidad', width: 80},
                        { text: 'arancel', datafield: 'arancel', width: 60},

//                        { text: 'precioCorregido', datafield: 'precioCorregido', width: 110},
                        { text: 'cantidad', datafield: 'cantidadCorregido', width: 80, cellclassname: cellclassname},
                        { text: 'arancel', datafield: 'arancelCorregido', width: 60, cellclassname: cellclassname},
                        { text: 'Comitente', datafield: 'numComitenteCorregido', width: 80, cellclassname: cellclassname},
                        { text: 'comitente', datafield: 'comitenteCorregido', width: 148, hidden:true, cellclassname: cellclassname},
                        { text: 'observaciones', datafield: 'observaciones', width: 100, cellclassname: cellclassname},
                        
                        { text: 'Precio', datafield: 'precioCorregido', width: 80, cellclassname: cellclassname},
                        { text: 'Tipo Operacion', datafield: 'tipoOperacionCorregido', width: 80, cellclassname: cellclassname},
                        { text: 'Especie', datafield: 'especieCorregido', width: 80, cellclassname: cellclassname},
                        
//                        { text: 'autorizadores', datafield: 'autorizadores', width: 120},
//                        { text: 'caja', datafield: 'caja', width: 57},
//                        { text: 'visual', datafield: 'visual', width: 122},
//                        { text: 'control', datafield: 'control', width: 100},
                        { text: 'cierrecorreccion_id', datafield: 'cierrecorreccion_id', width: 100, hidden:true},
                        { text: 'usuario_id', datafield: 'usuario_id', width: 100, hidden:true},
                        { text: 'fechaActualizacion', datafield: 'fechaActualizacion', width: 85, cellsformat: 'dd/MM/yyyy'},                     
                        { text: 'estado', datafield: 'estado', width: 90},
                        { text: 'estado_id', datafield: 'estado_id', width: 100, hidden:true}
                        
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
            $.redirect('/correccion/editar', {'id': 0, origen: 'correccion'});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/correccion/editar', {'id': id, origen: 'correccion'});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/correccion/delOrden', datos, function(data){
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
                        $.post('/correccion/enviarOrdenes', datos, function(data){
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
<div id="sistema" style='float: left; margin-left: 0px; margin-bottom: 10px; text-align: left; vertical-align: text-bottom;'><ul>Grilla Arreglo Boletos</ul></div>
<br>
<div id="grilla"></div>
<div id="botonera">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
            <td><input type="button" value="Nuevo" id="nuevoButton"></td>
            <td><input type="button" value="Editar" id="editarButton"></td>
            <td><input type="button" value="Borrar" id="borrarButton"></td>
            <td><input type="button" value="Enviar para Aprobación" id="enviarButton"></td>
        </tr>
    </table>
</div>
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
