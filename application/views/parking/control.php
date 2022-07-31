<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
        var theme = getTheme();
        var id = 0;
        var numComit = 0;
        var enviar = [];
        var enviarOrdenes = [];
        var enviarComitentes = [];
        
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
            url: '/parking/getCierres',
            async: false
        };
        var DACierre = new $.jqx.dataAdapter(srcCierre);
        
        $("#cierre").on('bindingComplete', function(event){
            $.post('/parking/getCierreActual', function(cierre){
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
            source.data = {cierreparking_id: event.args.item.value};
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
                { name: 'numComitente'},
                { name: 'descripcionComitente'},
                
                { name: 'codigo'},
                { name: 'especie'},
                { name: 'especieDescripcion'},
                
                { name: 'cantidad'},
                { name: 'parking'},
                { name: 'cantidadTotal'},
                
                { name: 'moneda'},
                { name: 'esMismaMoneda'},
                { name: 'esCableMep'},
                { name: 'observaciones'},
                
                { name: 'autorizadores'},

                { name: 'estado'},
                { name: 'estado_id'},
                { name: 'fechaEstado'},
                { name: 'fechaActualizacion'},
                
                { name: 'envio'},
                { name: 'fhenvio', type: 'date', format: 'yyyy-MM-dd HH:mm:ss'},
                { name: 'fechaAceptacion'},

            ],
            cache: false,
            url: '/parking/controlGrilla',
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
                width: '90%',
                height: 600,
                columns: [
                        { text: 'Id', datafield: 'id', width: 40, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'operador', datafield: 'operador', width: 120 },
                        { text: 'Nro Comitente', datafield: 'numComitente', width: 70},
                        { text: 'Comitente', datafield: 'descripcionComitente', width: 100},
                        
                        { text: 'Código', datafield: 'codigo', width: 60},
                        { text: 'Abreviatura', datafield: 'especie', width: 60},
                        { text: 'Descripción', datafield: 'especieDescripcion', width: 180},
//                        
                        { text: 'cantidad', datafield: 'cantidad', width: 70},
                        { text: 'parking', datafield: 'parking', width: 70},
                        { text: 'cantidadTotal', datafield: 'cantidadTotal', width: 70},
                        { text: 'moneda', datafield: 'moneda', width: 70},
                        { text: 'esMismaMoneda', datafield: 'esMismaMoneda', width: 70},
                        { text: 'PJ cable a MEP', datafield: 'esCableMep', width: 70},
                        { text: 'Observaciones', datafield: 'observaciones', width: 90},
                        
                        { text: 'autorizadores', datafield: 'autorizadores', width: 150},

                        { text: 'Estado', datafield: 'estado', width: 80},
                        { text: 'estado_id', datafield: 'estado_id', width: 0, hidden: true},
                        { text: 'Fecha Estado', datafield: 'fechaEstado', width: 150},
                        { text: 'Fecha Orden', datafield: 'fechaActualizacion', width: 150},

                        { text: 'cierreparking_id', datafield: 'cierreparking_id', width: 100, hidden:true},
                        { text: 'usuario_id', datafield: 'usuario_id', width: 100, hidden:true},

                        { text: 'Fecha Aceptación', datafield: 'fechaAceptacion', width: 150},
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
        
        
        
        $("#comprobarOMSButton").jqxButton({ width: '160', theme: theme, disabled: false });
        $("#controlarButton").jqxButton({ width: '160', theme: theme, disabled: true });
//        $("#editarButton").jqxButton({ width: '160', theme: theme, disabled: true });
//        $("#anularButton").jqxButton({ width: '160', theme: theme, disabled: true });
        
        /*
        $("#enviarMercadoButton").click(function(){
            $("#grilla").ajaxloader();
            var datos = {ordenes: enviar};
            $("#grilla").ajaxloader();
            $.post('/parking/previewMercado', datos, function(data){
                $.each(data.uris, function(indice, uri){
                    $.fileDownload(uri);
                });
                new Messi('Ha enviado los datos al mercado ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                    buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                        if (val == 's'){
                            $.post('/parking/enviarMercado', datos, function(data){
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
            new Messi('Desea Enviar a OMS las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        $("#grilla").ajaxloader();
                        
                        enviarOrdenes = enviar;
                        
                        datos = {
                            ordenes: enviarComitentes,
                            tipo: 'control',
                            cierre: $("#cierre").val()
                        };
                        $.post('/envioautorizacion/enviarAOMS', datos, function(data){
                                    
                                    $.each(data.resultado, function(index, value){
                                        if(value.response == 'confirmado'){
                                        
                                            datosOMS = {
                                                    ordenes: enviarOrdenes
                                                };
                                            $.post('/parking/aprobarOrdenesOMS', datosOMS, function(dataOMS){

                                                new Messi('Envío a OMS exitoso '+value.message , {title: 'Mensaje', modal: true,
                                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'success'});
                                                $('#grilla').jqxGrid('updatebounddata');

                                            }
                                            , 'json');
                                        }else{
                                            new Messi('OMS Falló', {title: 'Mensaje', modal: true,
                                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'error'});
                                        }
                                    });
                                    
                                }
                                , 'json');
                                
                                $('#grilla').jqxGrid('updatebounddata');
                                $('#grilla').jqxGrid('clearselection');
                                $("#grilla").ajaxloader('hide');
                    } 
                }
            });
        });
       
       
        $("#comprobarOMSButton").click(function(){
            new Messi('Desea Enviar a OMS las ordenes ' + enviar.join(', ') + ' ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        $("#grilla").ajaxloader();
                                                
                        datos = {
                            cierre: $("#cierre").val()
                        };
                        $.post('/parking/comprobarOMS', datos, function(data){ 
                                
                            if (data) {
                                
                                //Revisar como paso los datos
                                datos = {
                                    ordenes: data,
                                    tipo: 'check',
                                    cierre: $("#cierre").val()
                                };
                                
                                $.post('/envioAutorizacion/index', datos, function(resultado){
                                                                        
                                    $.each(resultado.resultado, function(index, value){
                                        
                                        if(value.response == 'confirmado'){

                                            var idOMS = [];
                                            
                                            idOMS.push(value.id);

                                            datosOMS = {
                                                ordenes: idOMS
                                            };  

                                            $.post('/parking/aprobarOrdenesOMS', datosOMS, function(dataOMS){
                                                new Messi('OMS enviado '+value.response+' '+value.message , {title: 'Mensaje', modal: true,
                                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'success'});
                                            
                            $('#grilla').jqxGrid('updatebounddata');

    }
                                            , 'json');
                                        }else{
                                            new Messi('OMS Falló', {title: 'Mensaje', modal: true,
                                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: 'error'});
                                        }
                                        

                                    });  



                                }
                                , 'json');
                                
                                

                            }   
                            
                            
                            
                        }
                        , 'json');


                        $('#grilla').jqxGrid('updatebounddata');
                        $('#grilla').jqxGrid('clearselection');
                        $("#grilla").ajaxloader('hide'); 
                   
                    
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
//                        $.post('/parking/anularOrdenes', datos, function(data){
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
            enviarComitentes = [];
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
                    numComit = row.numComitente;
                    if (estado_id != 6){
                        $("#grilla").jqxGrid('unselectrow', value);
                    } else {
                        enviar.push(id);
                        enviarComitentes.push(numComit);
                    }
                    
                    if (enviar.length > 0){
                        if (enviar.length > 1){
                            $("#grilla").jqxGrid('unselectrow', value);
                        }
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
//            url: '/parking/grillaResumen',
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
<div id="sistema" style='float: left; vertical-align: text-bottom; text-align: left;'><ul>Grilla Control Parking</ul></div>
<br>
<br>
<div id="grilla"></div>
<div id="botonera">
    <table boder="0" cellpadding="2" cellspacing="2">
        <tr>
            <td><input type="hidden" value="Comprobar OMS" id="comprobarOMSButton"></td>
            <td><input type="button" value="Enviar a OMS" id="controlarButton">
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