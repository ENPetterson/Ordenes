<script>
    $(function(){
        
        var theme = getTheme();
        var formOK = false;
        
        var srcComitente =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'numComitente'},
                        { name: 'nombre' }
                    ],
                    id: 'numComitente',
                    url: '/consultaordenes/getComitentes',
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
        
//        $("#numComitente").jqxNumberInput({theme: theme, width: 80, height: 25, allowNull: true, decimalDigits: 0, groupSeparator: '.' });
        $("#numComitente").jqxNumberInput({theme: theme, width: 80, height: 25, decimalDigits: 0, groupSeparator: '.' });
//        $("#numComitente").jqxInput({theme: theme, width: 80, height: 25 });
        
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
        
        
        var url = "/consultaordenes/getConsultaOrdenes";
            // prepare the data
        var srcOperaciones =
        {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'int'},
                { name: 'numComitente', type: 'int' },
                { name: 'cantidad', type: 'int' },
                { name: 'plazo' },
                { name: 'especie' },
                { name: 'bonoNombre' },
                { name: 'tipo' },
                { name: 'fhmodificacion'},
                { name: 'estado'},
                { name: 'usuario'},
                { name: 'orden', type: 'int' },

            ],
            id: 'id',
            url: url,
            data: {
                   numComitente: 0
               },
            type: 'post'
        };
        var DAOperaciones = new $.jqx.dataAdapter(srcOperaciones);
        
        $("#grdOperaciones").jqxGrid(
        {
            width: '100%',
            height: 450,
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
                { text: 'Id', dataField: 'id', width: 20},
                { text: 'Comitente', dataField: 'numComitente', width: 15 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'Cantidad', dataField: 'cantidad', width: 15 , cellsalign: 'right', cellsformat: 'n' },
                { text: 'Plazo', dataField: 'plazo', width: 90 , cellsalign: 'right' },
                { text: 'Especie', dataField: 'especie', width: 15 , cellsalign: 'right' },
                { text: 'Bono', dataField: 'bonoNombre', width: 15 , cellsalign: 'right' },
                { text: 'Tipo Bono', dataField: 'tipo', width: 15 , cellsalign: 'right' },
                { text: 'Fecha Modificacion', dataField: 'fhmodificacion', width: 15 , cellsalign: 'right' },
                { text: 'Estado', dataField: 'estado', width: 15 , cellsalign: 'right' },
                { text: 'Usuario', dataField: 'usuario', width: 15 , cellsalign: 'right' },
                { text: 'Orden', dataField: 'orden', width: 15 , cellsalign: 'right' }
            ]
        });
        
        
        
//        $("#mensajeImportacion").jqxNotification({
//                width: 250, position: "bottom-left", opacity: 0.9,
//                autoOpen: false, animationOpenDelay: 800, autoClose: false, template: "warning", closeOnClick: false, showCloseButton: false
//        });

        $("#grdOperaciones").on("bindingcomplete", function (event) {
            $("#grdOperaciones").jqxGrid('autoresizecolumns');
            $("#grdOperaciones").jqxGrid('setcolumnproperty','descComitente', 'width', 180);
            $("#grdOperaciones").jqxGrid('setcolumnproperty','plazo', 'width', 90);
        });
        
   
        $("#actualizarButton").jqxButton({ width: '80', theme: theme });
        
        $("#actualizarButton").click(function(){
            var numComitente = $("#comitentes").val();

            srcOperaciones.data = {
                                    numComitente: numComitente
                                };
            $("#grdOperaciones").jqxGrid('updatebounddata');
        });
        
//        $("#enviarButton").jqxButton({ width: '160', theme: theme, disabled: true });
//        
//        $('#grdOperaciones').on('rowselect rowunselect', function (event) {
//            var rowindexes = $('#grdOperaciones').jqxGrid('getselectedrowindexes');
//            if (rowindexes.length == 0){
//                $("#enviarButton").jqxButton({disabled: true});
//            } else {
//                $("#enviarButton").jqxButton({disabled: false});
//            };
//        });
//        
//        $("#enviarButton").on('click', function () {
//            var getselectedrowindexes = $('#grdOperaciones').jqxGrid('getselectedrowindexes');
//            if (getselectedrowindexes.length > 0){
//                $("#container").ajaxloader();
//                var data = [];
//                $.each(getselectedrowindexes, function(indice, dd){
//                    data.push($('#grdOperaciones').jqxGrid('getrowdata', dd).id);
//                });
//                $.post('/operacion/enviarMails', {idBoletos: data}, function(resultado){
//                    $("#grdOperaciones").jqxGrid('updatebounddata');
//                    $("#container").ajaxloader('hide');
//                    new Messi('Se han enviado los correos', {title: 'Aviso', 
//                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
//                }, 'json');
//                
//            }
//         });
        
        

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
                <td>Comitente (0 = Todos):</td>            
                <!--<td><input type="text" id="numComitente" value='0'></td>-->
                <td><div id="numComitente"></div></td>
                <td><div id='comitentes'></div></td>
                <td rowspan="2"><input type="button" value="Actualizar" id="actualizarButton"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                
            </tr>
        </table>
    </div>
    <div id="loader">

    </div>
    <div>
        <div>
            <div id="grdOperaciones"></div>
        </div>
        <div>
        <table boder="0" cellpadding="2" cellspacing="2">
            <tr>
<!--                <td><input type="button" value="Enviar Mails" id="enviarButton"></td>
                <td><input type="button" value="Importar Confirmaciones" id="importarConfirmaciones"></td>
                <td><input type="button" value="Excel" id="excelButton"></td>-->
            </tr>
        </table>
        </div>
    </div>
</div>
<!--<div id="mensajeImportacion">
    <div>
        Importando Confirmaciones
    </div>
</div>-->
<!--
<div id="mensajeImportacionFondos">
    <div>
        Importando Movimientos Fondos
    </div>
</div>-->