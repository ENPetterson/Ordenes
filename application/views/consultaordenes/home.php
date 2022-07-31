<?php
$timestamp = time();
?>

<script>
    $(function(){
        
        var theme = getTheme();
        var formOK = false;
        
        var timestamp = <?= $timestamp; ?>;
        var token = '<?= md5('unique_salt' . $timestamp); ?>'; 
        
        var fechaDesde = new Date().toISOString().slice(0, 10);
        var fechaHasta = new Date().toISOString().slice(0, 10);
        var incluir = 'T'; //Todos        
        
        var srcComitente =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'numComitente'},
                        { name: 'nombre' }
                    ],
                    id: 'numComitente',
                    url: '/operacion/getComitentes',
                    async: false
                };
        var DAComitente = new $.jqx.dataAdapter(srcComitente);

        $("#comitentes").on('bindingComplete', function (event) {
            var item = { label: '<<<<<<<<<<<<<< TODOS >>>>>>>>>>>>>>', value: '0'};
            $("#comitentes").jqxDropDownList('addItem', item );
            $("#comitentes").jqxDropDownList('selectItem', item ); 
        });

        $("#comitentes").jqxDropDownList({ selectedIndex: -1, source: DAComitente, displayMember: "nombre", 
            valueMember: "numComitente", width: 300, height: 25, theme: theme, placeHolder: "No Encontrado", disabled: true });
        
        $("#numComitente").jqxNumberInput({theme: theme, width: 80, height: 25, allowNull: false, decimalDigits: 0, groupSeparator: '.' });
        
        $('#numComitente').on('valueChanged', function (event) {
            var value = event.args.value;
            var item = $("#comitentes").jqxDropDownList('getItemByValue', value);
            $("#comitentes").jqxDropDownList('selectItem', item ); 
            if(item){
                $('#actualizarButton').jqxButton({disabled: false });
            } else {
                $('#actualizarButton').jqxButton({disabled: true });
            };
        }); 
        
        var url = "/controlCarteraPropia/getControlCarteraPropia";
        
        //////////////////////   GRID   ////////////////////////////////////////
        // prepare the data
        var srcOperaciones =
        {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'int'},
                { name: 'numComitente', type: 'int' },
                { name: 'especie' },
                { name: 'cantidad' },
                { name: 'vars' },
                { name: 'cmep' },
                { name: 'vmep' },
                { name: 'ccable' },
                { name: 'vcable' },
                { name: 'antiguedadOnline' },
                { name: 'estado' },
                { name: 'fechaActualizacion' }
            ],
            id: 'id',
            url: url,
            data: {fechaDesde:   fechaDesde,
                   fechaHasta:   fechaHasta,
                   hoja: 1,
                   numComitente: 0,
                   incluir:      incluir},
            type: 'post'
        };
        var DAOperaciones = new $.jqx.dataAdapter(srcOperaciones);
        
        $("#grdOperaciones").jqxGrid(
        {
            width: '94%',
            height: 440,
            source: DAOperaciones,
            theme: theme,
            filterable: true,
            selectionmode: 'checkbox',
            sortable: true,
            autoheight: false,
            pageable: false,
            virtualmode: false,
            columnsresize: true,
            columns: [
                { text: 'Id', dataField: 'id', width: 20, hidden: true},
                { text: 'Comitente', dataField: 'numComitente', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'especie', dataField: 'especie', width: 100, cellsalign: 'right' },
                { text: 'cantidad', dataField: 'cantidad', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'vars', dataField: 'vars', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'cmep', dataField: 'cmep', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'vmep', dataField: 'vmep', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'ccable', dataField: 'ccable', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'vcable', dataField: 'vcable', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'antiguedadOnline', dataField: 'antiguedadOnline', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'estado', dataField: 'estado', width: 100 , cellsalign: 'right' },
                { text: 'Fecha Actualizacion', dataField: 'fechaActualizacion', width: 100 }
            ]
        });
        
        /////////////////      FIN GRID       //////////////////////////////////
        
        
        
        
        //////////////////////   GRID2   ////////////////////////////////////////
        // prepare the data
        var srcOperacionesT0 =
        {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'int'},
                { name: 'numComitente', type: 'int' },
                { name: 'especie' },
                { name: 'cantidad' },
                { name: 'vars' },
                { name: 'cmep' },
                { name: 'vmep' },
                { name: 'ccable' },
                { name: 'vcable' },
                { name: 'antiguedadOnline' },
                { name: 'estado' },
                { name: 'fechaActualizacion' }
            ],
            id: 'id',
            url: url,
            data: {fechaDesde:   fechaDesde,
                   fechaHasta:   fechaHasta,
                   hoja: 2,
                   numComitente: 0,
                   incluir:      incluir},
            type: 'post'
        };
        var DAOperacionesT0 = new $.jqx.dataAdapter(srcOperacionesT0);
        
        $("#grdOperacionesT0").jqxGrid(
        {
            width: '94%',
            height: 440,
            source: DAOperacionesT0,
            theme: theme,
            filterable: true,
            selectionmode: 'checkbox',
            sortable: true,
            autoheight: false,
            pageable: false,
            virtualmode: false,
            columnsresize: true,
            columns: [
                { text: 'Id', dataField: 'id', width: 20, hidden: true},
                { text: 'Comitente', dataField: 'numComitente', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'especie', dataField: 'especie', width: 100, cellsalign: 'right' },
                { text: 'cantidad', dataField: 'cantidad', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'varsT0', dataField: 'vars', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'cmepT0', dataField: 'cmep', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'vmepT0', dataField: 'vmep', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'ccableT0', dataField: 'ccable', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'vcableT0', dataField: 'vcable', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'antiguedadOnlineT0', dataField: 'antiguedadOnline', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'estado', dataField: 'estado', width: 100 , cellsalign: 'right' },
                { text: 'Fecha Actualizacion', dataField: 'fechaActualizacion', width: 100 }
            ]
        });
        

        /////////////////      FIN GRID 2      //////////////////////////////////
        
        
        //////////////////////   GRID3   ////////////////////////////////////////
        // prepare the data
        var srcOperacionesT1 =
        {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'int'},
                { name: 'numComitente', type: 'int' },
                { name: 'especie' },
                { name: 'cantidad' },
                { name: 'vars' },
                { name: 'cmep' },
                { name: 'vmep' },
                { name: 'ccable' },
                { name: 'vcable' },
                { name: 'antiguedadOnline' },
                { name: 'estado' },
                { name: 'fechaActualizacion' }
            ],
            id: 'id',
            url: url,
            data: {fechaDesde:   fechaDesde,
                   fechaHasta:   fechaHasta,
                   hoja: 3,
                   numComitente: 0,
                   incluir:      incluir},
            type: 'post'
        };
        var DAOperacionesT1 = new $.jqx.dataAdapter(srcOperacionesT1);
        
        $("#grdOperacionesT1").jqxGrid(
        {
            width: '94%',
            height: 440,
            source: DAOperacionesT1,
            theme: theme,
            filterable: true,
            selectionmode: 'checkbox',
            sortable: true,
            autoheight: false,
            pageable: false,
            virtualmode: false,
            columnsresize: true,
            columns: [
                { text: 'Id', dataField: 'id', width: 20, hidden: true},
                { text: 'Comitente', dataField: 'numComitente', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'especie', dataField: 'especie', width: 100, cellsalign: 'right' },
                { text: 'cantidad', dataField: 'cantidad', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'varsT1', dataField: 'vars', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'cmepT1', dataField: 'cmep', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'vmepT1', dataField: 'vmep', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'ccableT1', dataField: 'ccable', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'vcableT1', dataField: 'vcable', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'antiguedadOnlineT1', dataField: 'antiguedadOnline', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'estado', dataField: 'estado', width: 100 , cellsalign: 'right' },
                { text: 'Fecha Actualizacion', dataField: 'fechaActualizacion', width: 100 }
            ]
        });
        
        
        /////////////////      FIN GRID 3      //////////////////////////////////
        
        
        //////////////////////   GRID4   ////////////////////////////////////////
        // prepare the data
        var srcOperaciones4 =
        {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'int'},
                { name: 'numComitente', type: 'int' },
//                { name: 'especie' },
//                { name: 'cantidad' },
                { name: 'vars' },
                { name: 'cmep' },
                { name: 'vmep' },
                { name: 'ccable' },
                { name: 'vcable' },
//                { name: 'antiguedadOnline' },
                { name: 'estado' },
                { name: 'fechaActualizacion' }
            ],
            id: 'id',
            url: "/controlCarteraPropia/getControlCarteraPropiaOtrasAlertas",
            data: {fechaDesde:   fechaDesde,
                   fechaHasta:   fechaHasta,
                   hoja: 4,
                   numComitente: 0,
                   incluir:      incluir},
            type: 'post'
        };
        var DAOperaciones4 = new $.jqx.dataAdapter(srcOperaciones4);
        
        $("#grdOperaciones4").jqxGrid(
        {
            width: '94%',
            height: 440,
            source: DAOperaciones4,
            theme: theme,
            filterable: true,
            selectionmode: 'checkbox',
            sortable: true,
            autoheight: false,
            pageable: false,
            virtualmode: false,
            columnsresize: true,
            columns: [
                { text: 'Id', dataField: 'id', width: 20, hidden: true},
                { text: 'Comitente', dataField: 'numComitente', width: 100 , cellsalign: 'right', cellsformat: 'n' },
//                { text: 'especie', dataField: 'especie', width: 100, cellsalign: 'right' },
//                { text: 'cantidad', dataField: 'cantidad', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'vars', dataField: 'vars', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'cmep', dataField: 'cmep', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'vmep', dataField: 'vmep', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'ccable', dataField: 'ccable', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'vcable', dataField: 'vcable', width: 100 , cellsalign: 'right', cellsformat: 'n' },
//                { text: 'antiguedadOnline', dataField: 'antiguedadOnline', width: 100 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'estado', dataField: 'estado', width: 150 , cellsalign: 'right' },
                { text: 'Fecha Actualizacion', dataField: 'fechaActualizacion', width: 100 }
            ]
        });
        
        /////////////////      FIN GRID 4      //////////////////////////////////
        
        
        
        $("#mensajeImportacion").jqxNotification({
                width: 250, position: "bottom-left", opacity: 0.9,
                autoOpen: false, animationOpenDelay: 800, autoClose: false, template: "warning", closeOnClick: false, showCloseButton: false
        });
                
        $('#tabs').jqxTabs({ width: '95%', height: 480 });

        $("#importarCarteraPropiaEsco").jqxButton({theme: theme});
        
        $("#importarCarteraPropiaEsco").click(function(){
//            $.post('/operacion/checkAlDia', {}, function(result){
//                if (result.resultado === false){



                    $("#mensajeImportacion").jqxNotification('open'); 
                    $("#grdOperaciones").ajaxloader();
                    $("#grdOperacionesT0").ajaxloader();
                    $("#grdOperacionesT1").ajaxloader();
                    $("#grdOperaciones4").ajaxloader();
                    
                    var jsFechaDesde = $("#fechaDesde").jqxDateTimeInput('getDate');
                    var fechaDesde = jsFechaDesde.toISOString().slice(0, 10);
                    
                    $.post('/controlCarteraPropia/importarCarteraPropiaEsco', {fechaDesde: fechaDesde}, function(result){
                        if (result.resultado == 'OK'){
                            $("#mensajeImportacion").jqxNotification('closeAll'); 
                            
                            new Messi('Se importaron asignaciones', {titleClass: 'success', title: 'Aviso', 
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                            
                            $("#grdOperaciones").ajaxloader('hide');                            
                            $("#grdOperacionesT0").ajaxloader('hide');                            
                            $("#grdOperacionesT1").ajaxloader('hide');                            
                            $("#grdOperaciones4").ajaxloader('hide');                            
                            $("#grdOperaciones").jqxGrid('updatebounddata');
                            $("#grdOperacionesT0").jqxGrid('updatebounddata');
                            $("#grdOperacionesT1").jqxGrid('updatebounddata');
                            $("#grdOperaciones4").jqxGrid('updatebounddata');
                        } else{
                            $("#mensajeImportacion").jqxNotification('closeAll'); 
                            
                            new Messi(result.mensaje, {title: 'Aviso', 
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                            
                            $("#grdOperaciones").ajaxloader('hide');                            
                            $("#grdOperacionesT0").ajaxloader('hide');                            
                            $("#grdOperacionesT1").ajaxloader('hide');                            
                            $("#grdOperaciones4").ajaxloader('hide');                            
                            
                        }    
                    }, 'JSON');
//                } 
//            }, 'JSON');
            
        });
        
        
        $("#excelButton").jqxButton({ width: '80', theme: theme, disabled: false });
        
        $("#excelButton").click(function(){
            grid2excel('#grdOperaciones', 'Operaciones', false);
        });
        
        $("#fechaDesde").jqxDateTimeInput({formatString: 'dd/MM/yyyy', animationType: 'fade', width: '170px',
            height: '20px', dropDownHorizontalAlignment: 'right', theme: theme});
        $("#fechaHasta").jqxDateTimeInput({formatString: 'dd/MM/yyyy', animationType: 'fade', width: '170px',
            height: '20px', dropDownHorizontalAlignment: 'right', theme: theme});
       
       
        $.getScript('<?=base_url()?>js/jqwidgets/globalization/globalize.culture.es-AR.js', function () {
            $("#fechaDesde").jqxDateTimeInput({ culture: 'es-AR' });        
        }); 
        $.getScript('<?=base_url()?>js/jqwidgets/globalization/globalize.culture.es-AR.js', function () {
            $("#fechaHasta").jqxDateTimeInput({ culture: 'es-AR' });        
        }); 

        $("#actualizarButton").jqxButton({ width: '80', theme: theme });
        
        $("#actualizarButton").click(function(){
            var jsFechaDesde = $("#fechaDesde").jqxDateTimeInput('getDate');
            var jsFechaHasta = $("#fechaHasta").jqxDateTimeInput('getDate');
            var fechaDesde = jsFechaDesde.toISOString().slice(0, 10);
            var fechaHasta = jsFechaHasta.toISOString().slice(0, 10);
            var numComitente = $("#comitentes").val();
            srcOperaciones.data = {fechaDesde:   fechaDesde,
                                   fechaHasta:   fechaHasta,
                                   numComitente: numComitente,
                                   incluir:      incluir};
            $("#grdOperaciones").jqxGrid('updatebounddata');
            $("#grdOperacionesT0").jqxGrid('updatebounddata');
            $("#grdOperacionesT1").jqxGrid('updatebounddata');
            $("#grdOperaciones4").jqxGrid('updatebounddata');
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
                $('#grdOperaciones').ajaxloader();
                $('#grdOperacionesT0').ajaxloader();
                $('#grdOperacionesT1').ajaxloader();
                $('#grdOperaciones4').ajaxloader();
                $.post('/controlCarteraPropia/grabarExcel', { file: file.name }, function(msg){
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
                    $('#grdOperaciones').ajaxloader('hide');
                    $('#grdOperacionesT0').ajaxloader('hide');
                    $('#grdOperacionesT1').ajaxloader('hide');
                    $('#grdOperaciones4').ajaxloader('hide');
                    new Messi(mensaje, {title: title, modal: true,
                        buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass, callback: function(val) { 
                            if (val == 'X'){
                                $("#grdOperaciones").jqxGrid('updatebounddata');
                                $("#grdOperacionesT0").jqxGrid('updatebounddata');
                                $("#grdOperacionesT1").jqxGrid('updatebounddata');
                                $("#grdOperaciones4").jqxGrid('updatebounddata');
                            } 
                        }
                    });                    
                }, 'json');
            }
        });
////////////////////////////////////////////////////////////////////////////////  


});


</script>
<style>
    td{
        padding: 10px;
        vertical-align: middle
    }
</style>
<div id="container">
    <div>
        <table style="background-color: white; border-color: blue; border: 1px; padding: 0px">
            <tr>
                <td>Desde:</td>
                <td><div id="fechaDesde"></div></td>            
                <td>Hasta:</td>
                <td><div id="fechaHasta"></div></td>            
                <td>Comitente (0 = Todos):</td>            
                <td><input type="text" id="numComitente" value='0'></td>
                <td><div id='comitentes'></div></td>
                <td rowspan="2"><input type="button" value="Actualizar" id="actualizarButton"></td>
            </tr>
            <tr>
<!--                <td colspan="2"><div id="chkPrioridad">Comitententes con prioridad</div></td>
                <td colspan="2"><div id="chkResto">Resto de los comitentes</div></td>-->
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                
            </tr>
        </table>
    </div>
    
    <div id="loader">
    </div>
    
    <div>
        <div id='tabs'>
            <ul>
                <li style="margin-left: 20px">Titulo</li>
                <li>T0</li>
                <li>T1</li>
                <li>Otros</li>
            </ul>
            <div>
                <div id="grdOperaciones"></div>
            </div>
            <div>
                <div id="grdOperacionesT0"></div>
            </div>
            <div>
                <div id="grdOperacionesT1"></div>
            </div>
            <div>
                <div id="grdOperaciones4"></div>
            </div>
        </div>
        <div>
        <table boder="0" cellpadding="2" cellspacing="2">
            <tr>
<!--                <td><input type="button" value="Enviar Mails" id="enviarButton"></td>-->
                <td><input type="button" value="Importar CarteraPropiaEsco" id="importarCarteraPropiaEsco"></td>
                <td><input type="button" value="Excel" id="excelButton"></td>
                <td id='archivoExcelFila'><input type="file" value="Archivo" id="archivoExcel"></td>
            </tr>
        </table>
        </div>
    </div>
</div>
<div id="mensajeImportacion">
    <div>
        Importando Confirmaciones
    </div>
</div>
<!--
<div id="mensajeImportacionFondos">
    <div>
        Importando Movimientos Fondos
    </div>
</div>-->