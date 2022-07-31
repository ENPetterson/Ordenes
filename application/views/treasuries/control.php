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
            url: '/treasuries/getCierres',
            async: false
        };
        var DACierre = new $.jqx.dataAdapter(srcCierre);
        
        $("#cierre").on('bindingComplete', function(event){
            $.post('/treasuries/getCierreActual', function(cierre){
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
            source.data = {cierretreasuries_id: event.args.item.value};
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
                { name: 'tipoOperacion'},
                { name: 'precioComitente'},
                { name: 'precioCartera'},
                { name: 'numComitente'},
                { name: 'especie'},
                { name: 'arancel'},
                { name: 'garantia'},
                { name: 'plazo'},
                { name: 'moneda'},
                { name: 'cantidad'},
                { name: 'codigo'},
                { name: 'brutoCliente'},
                { name: 'observaciones'},
                { name: 'numComitenteContraparte'},
                
                { name: 'autorizadores'},
                { name: 'procesadores'},
                { name: 'controladores'},
                
                { name: 'estado'},
                { name: 'estado_id'},
                { name: 'fechaActualizacion'},
                { name: 'envio'},
                { name: 'fhenvio', type: 'date', format: 'yyyy-MM-dd HH:mm:ss'}
            ],
            cache: false,
            url: '/treasuries/controlGrilla',
            data: {cierretreasuries_id: cierre_id},
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
//                editable: true,
//                editmode: 'click',
                columnsresize: true,
                showstatusbar: true,
                statusbarheight: 25,
                showaggregates: true,
                width: 1800,
                height: 600,
                columns: [
                        { text: 'Id', datafield: 'id', width: 40, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'operador', datafield: 'operador', width: 70 },
                        { text: 'tipoOperacion', datafield: 'tipoOperacion', width: 100},
                        { text: 'precioComitente', datafield: 'precioComitente', width: 80},
                        { text: 'precioCartera', datafield: 'precioCartera', width: 200 },
                        { text: 'numComitente', datafield: 'numComitente', width: 125},
                        { text: 'especie', datafield: 'especie', width: 70},
                        { text: 'arancel', datafield: 'arancel', width: 41},
                        { text: 'garantia', datafield: 'garantia', width: 41},
                        { text: 'plazo', datafield: 'plazo', width: 41},
                        { text: 'moneda', datafield: 'moneda', width: 60},
                        { text: 'cantidad', datafield: 'cantidad', width: 70},
                        { text: 'codigo', datafield: 'codigo', width: 100},
                        { text: 'brutoCliente', datafield: 'brutoCliente', width: 95},
                        { text: 'Observaciones', datafield: 'observaciones', width: 100},
                        { text: 'numComitenteContraparte', datafield: 'numComitenteContraparte', width: 90},
                        { text: 'autorizadores', datafield: 'autorizadores', width: 120},
                        { text: 'procesadores', datafield: 'procesadores', width: 120},
                        { text: 'controladores', datafield: 'controladores', width: 120},

                        { text: 'Estado', datafield: 'estado', width: 80},
                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true},
                        { text: 'Fecha Actualizacion', datafield: 'fechaActualizacion', width: 120},
//                        { text: 'Inst', datafield: 'envio', width: 30},
//                        { text: 'Envio', datafield: 'fhenvio', width: 150, cellsformat: 'dd/MM/yyyy HH:mm:ss'}



//                        { text: 'caja', datafield: 'caja', width: 57},
//                        { text: 'visual', datafield: 'visual', width: 122},
//                        { text: 'control', datafield: 'control', width: 100},
                        { text: 'cierretreasuries_id', datafield: 'cierretreasuries_id', width: 100, hidden:true},
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
        
        
        
//        $("#enviarMercadoButton").jqxButton({ width: '160', theme: theme, disabled: true });
        $("#controlarButton").jqxButton({ width: '160', theme: theme, disabled: true });
//        $("#editarButton").jqxButton({ width: '160', theme: theme, disabled: true });
//        $("#anularButton").jqxButton({ width: '160', theme: theme, disabled: true });
        
        /*
        $("#enviarMercadoButton").click(function(){
            $("#grilla").ajaxloader();
            var datos = {ordenes: enviar};
            $("#grilla").ajaxloader();
            $.post('/treasuries/previewMercado', datos, function(data){
                $.each(data.uris, function(indice, uri){
                    $.fileDownload(uri);
                });
                new Messi('Ha enviado los datos al mercado ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                    buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                        if (val == 's'){
                            $.post('/treasuries/enviarMercado', datos, function(data){
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
       
        $("#controlarButton").click(function(){
            new Messi('Desea controlar las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        $("#grilla").ajaxloader();
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/treasuries/controlarOrdenes', datos, function(data){
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
       
       

//        $("#anularButton").click(function(){
//            new Messi('Desea anular las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
//                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
//                    if (val == 's'){
//                        $("#grilla").ajaxloader();
//                        datos = {
//                            ordenes: enviar
//                        };
//                        $.post('/treasuries/anularOrdenes', datos, function(data){
//                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
//                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'error'});
//                            $('#grilla').jqxGrid('updatebounddata');
//                            $('#grilla').jqxGrid('clearselection');
//                            $("#grilla").ajaxloader('hide');
//                        }
//                        , 'json');
//                    } 
//                }
//            });
//        });
        

        
        $('#grilla').on('rowselect rowunselect', function (event) {
            enviar = [];
            var rowindexes = $('#grilla').jqxGrid('getselectedrowindexes');
//            $('#enviarMercadoButton').jqxButton({disabled: true });
            $('#controlarButton').jqxButton({disabled: true });
//            $('#editarButton').jqxButton({disabled: true });
//            $('#anularButton').jqxButton({disabled: true });
            if (rowindexes.length > 0){
                $.each(rowindexes, function(index, value){
                    var row = $('#grilla').jqxGrid('getrowdata', value);
                    var estado_id = row.estado_id;
                    id = row.id;
                    if (estado_id != 3){
                        $("#grilla").jqxGrid('unselectrow', value);
                    } else {
                        enviar.push(id);
                    }
                });
                if (enviar.length > 0){
                    if (enviar.length == 1){
//                        $('#editarButton').jqxButton({disabled: false });
                    }
//                    $('#enviarMercadoButton').jqxButton({disabled: false });
                    $('#controlarButton').jqxButton({disabled: false });
//                    $('#anularButton').jqxButton({disabled: false });
//                    $("#ventanaResumen").jqxWindow('open');
                }
            }
//            srcGrillaResumen.data = {ordenes: enviar};
//            $("#grillaResumen").jqxGrid('updatebounddata');
        });

/*
        $("#grilla").bind('checked', function (event) {
            console.log('Cliasdasdasdck');
            console.log(event);
            console.log(event.args);            
            var rowindexes = $('#grilla').jqxGrid('getselectedrowindexes');
            console.log(rowindexes);
        });
        */

//        $('#grilla').on('click', function (event) {
//                console.log('Click en la grilla en general');
//                console.log(event);
//            });

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
//            url: '/treasuries/grillaResumen',
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
<div id="sistema" style='float: left; vertical-align: text-bottom; text-align: left;'><ul>Grilla Control Treasuries</ul></div>
<br>
<br>
<div id="grilla"></div>
<div id="botonera">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
<!--            <td><input type="button" value="Enviar a Mercado" id="enviarMercadoButton"></td>-->
            <td><input type="button" value="Controlar" id="controlarButton">
<!--            <td><input type="button" value="Anular" id="anularButton"></td>
            <td><input type="button" value="Editar" id="editarButton"></td>-->
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