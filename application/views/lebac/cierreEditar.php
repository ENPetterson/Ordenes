<style>
    .tabla-formulario td {
        padding: 0.2em 0.2em 0.4em 0.2em;
    }
</style>
<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaCierre">
    <div id="titulo">
        Editar Cierre
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 3px">Fecha y hora: </td>
                    <td><div id="fechaHora"></div></td>
                </tr>
                <tr>
                    <td rowspan="2" style="padding-right: 10px; padding-bottom: 3px; vertical-align: top">Plazos:</td>
                    <td><div id="grillaPlazos"></div></td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="padding: 10px 5px 0px 0px"><input type="button" id="nuevoPlazo" value="Nuevo"></td>
                                <td style="padding-right: 5px"><input type="button" id="editarPlazo" value="Editar"></td>
                                <td><input type="button" id="borrarPlazo" value="Borrar"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 10px 3px 0px">Instrumento: </td>
                    <td><input type="text" id="instrumentoLebac"></td>
                </tr>
                
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">pausarCierre:</td>
                    <td><div id="pausarCierre"></div></td>
                </tr>
                
                <tr>
                    <td colspan="2" style="text-align: center; padding-top: 20px">
                        <input type="button" id="aceptarButton" value="Aceptar">
                    </td>
                </tr>
            </table>
        </form>
    </div> 
</div>
<div id='ventanaPlazo'>
    <div>Alta de Plazo</div>
    <div>
        <form id="formPlazo">
            <table class="tabla-formulario">
                <tr>
                    <td>Moneda:</td>
                    <td><div id="moneda"></div></td>
                </tr>
                <tr>
                    <td>Plazo:</td>
                    <td><div id="plazo"></div></td>
                </tr>
                <tr>
                    <td>Especie:</td>
                    <td><input id="especie"></td>
                </tr>
                <tr>
                    <td>Colocacion:</td>
                    <td><div id="colocacion"></div></td>
                </tr>
                <tr>
                    <td>Precio Minimo Competitivo:</td>
                    <td><div id="precioMinimoC"></div></td>
                </tr>
                <tr>
                    <td>Titulo Competitivo:</td>
                    <td><input id="tituloC"7></td>
                </tr>
                <tr>
                    <td>Titulo No Competitivo Persona Juridica:</td>
                    <td><input id="tituloNCJ"></td>
                </tr>
                <tr>
                    <td>Titulo No Competitivo Persona Fisica:</td>
                    <td><input id="tituloNCF"></td>
                </tr>
                <tr id="filaSegmento">
                    <td>Segmento:</td>
                    <td><div id="segmento"></div></td>
                </tr>
            </table>
            <div style="margin-top: 1em; width: '100%'; text-align: center">
                <input type="button" id="aceptarPlazo" value="Aceptar" />
            </div>            
        </form>
    </div>
</div>
<script>
    $(function(){
        var theme = getTheme();
        var formOK = false;
        
        var plazo = {
            id: 0,
            fila: -1,
            nombre: '',
            formOK: false,
            operacion: '',
            contador: 0,
            borrar: []
        };
        
        $("#ventanaCierre").jqxWindow({showCollapseButton: false, height: '450px', width: '320px', theme: theme,
        resizable: false, keyboardCloseKey: -1});
        $("#fechaHora").jqxDateTimeInput({ formatString: "dd/MM/yyyy HH:mm", showTimeButton: true, width: '190px', height: '25px', theme: theme });
        
        $("#instrumentoLebac").jqxInput({height: '20px', width: '190px', theme: theme});
        
        $("#pausarCierre").jqxCheckBox({ width: 200, height: 20, theme: theme });
        
        $('#fechaHora').on('change', function (event)        {  
            var jsDate = moment(event.args.date).add(1, 'd'); 
            var fecha = jsDate.toISOString().slice(0, 10).replace(/-/g, '');
            if ($("#instrumentoLebac").val().trim().length == 0){
                $.post('/esco/getInstrumento', {fecha: fecha}, function(datos){
                    $("#instrumentoLebac").val(datos.Abreviatura);
                }, 'json');
            }
        });

        
        
        $("#grillaPlazos").jqxGrid( {		
                theme: theme,
                filterable: true,
                filtermode: 'excel',
                sortable: true,
                autoheight: false,
                pageable: false,
                virtualmode: false,
                columnsresize: true,
                width: 190,
                height: 240,
                columns: [
                    { text: 'Id', datafield: 'id', width: 0, hidden: true },
                    { text: 'Moneda', datafield: 'moneda', width: 30},
                    { text: 'Plazo', datafield: 'plazo', width: 50 },
                    { text: 'Especie', datafield: 'especie', width: 90},
                    { text: 'Colocacion', datafield: 'colocacion', width: 0, hidden: true },
                    { text: 'Precio Minimo C', datafield: 'precioMinimoC', width: 0, hidden: true },
                    { text: 'Titulo Competitivo', datafield: 'tituloC', width: 0, hidden: true},
                    { text: 'Titulo No Competitivo Persona Juridica', datafield: 'tituloNCJ', width: 0, hidden: true},
                    { text: 'Titulo No Competitivo Persona Fisica', datafield: 'tituloNCF', width: 0, hidden: true},
                    { text: 'Segmento', datafield: 'segmento', width: 0, hidden: true}
                ]
        });
        $("#grillaPlazos").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grillaPlazos").jqxGrid('localizestrings', localizationobj);
        });  
        
        $('#nuevoPlazo').jqxButton({ theme: theme, width: '60px', height: '25px' });
        $('#editarPlazo').jqxButton({ theme: theme, width: '60px', height: '25px', disabled: true });
        $('#borrarPlazo').jqxButton({ theme: theme, width: '60px', height: '25px', disabled: true });
        
        $('#grillaPlazos').on('rowselect', function (event) {
            var args = event.args; 
            plazo.fila = args.rowindex;
            if (plazo.fila >= 0){
                $('#editarPlazo').jqxButton({disabled: false });
                $('#borrarPlazo').jqxButton({disabled: false });
                plazo.id = args.row.id;
                plazo.nombre = args.row.moneda + ' ' + args.row.plazo;
            }
        });

        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Cierre');
        } else {
            $("#titulo").text('Editar Cierre');
            datos = {
                cierre_id: $("#id").val()
            };
            $.post('/lebac/getCierre', datos, function(data){
                $("#fechaHora").val(data.fechahora);
                $.each(data.plazos, function(key,plazoItem){
                    $("#grillaPlazos").jqxGrid('addrow', plazoItem.id, plazoItem);
                });
                $("#instrumentoLebac").val(data.instrumento);
                $("#pausarCierre").val(data.pausarCierre);
                
            }, 'json');
        };
        
        
        $('#form').jqxValidator({ rules: [
            { input: '#grillaPlazos', message: 'Debe ingresar al menos un plazo', action: 'blur', rule: function(){
                var rows = $("#grillaPlazos").jqxGrid('getrows');
                var rowcount = rows.length;
                return (rowcount > 0);
            }},
//            { input: '#instrumentoLebac', message: 'No existe un instrumento con ese nombre!',  rule: function(){
//                var resultado;
//                jQuery.ajaxSetup({async:false});
//                $.post('/esco/existeInstrumento', {instrumento: $('#instrumentoLebac').val()}, function(data){
//                    resultado = data.existe;
//                }
//                , 'json');
//                jQuery.ajaxSetup({async:true});
//                return resultado;
//            }}
            ], 
            theme: theme
        });
        
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        
        $('#aceptarButton').jqxButton({ theme: theme, width: '65px' });
        $('#aceptarButton').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                $('#ventanaCierre').ajaxloader();
                var fechaHora = moment($("#fechaHora").jqxDateTimeInput('val','date'));
                var plazos = [];
                
                var rowsPlazos = $("#grillaPlazos").jqxGrid('getrows');
                $.each(rowsPlazos, function(key, filaPlazo){
                    var id = 0;
                    if (filaPlazo.id > 0){
                        id = filaPlazo.id;
                    }
                    var itemPlazo = {
                        id: id,
                        moneda: filaPlazo.moneda,
                        plazo: filaPlazo.plazo,
                        especie: filaPlazo.especie,
                        colocacion: filaPlazo.colocacion,
                        precioMinimoC: filaPlazo.precioMinimoC,
                        tituloC: filaPlazo.tituloC,
                        tituloNCJ: filaPlazo.tituloNCJ,
                        tituloNCF: filaPlazo.tituloNCF,
                        segmento: filaPlazo.segmento
                    };
                    plazos.push(itemPlazo);
                });

                var datos = {
                    cierre_id: $("#id").val(),
                    fechahora: fechaHora.format("YYYY-MM-DD HH:mm") + ":00",
                    plazos: plazos,
                    plazosBorrar: plazo.borrar,
                    instrumento: $("#instrumentoLebac").val(),
                    pausarCierre: $("#pausarCierre").val()
                };
                $.post('/lebac/saveCierre', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/lebac/cierre');
                    } else {
                        new Messi('Hubo un error guardando el cierre', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaCierre').ajaxloader('hide');
                    }
                }, 'json');
            }
        });  
        
        
        
        /*******************************************************/
        /**                 VENTANA PLAZOS                    **/
        /*******************************************************/

        var monedas = [
            { value: '$', label: 'Peso'},
            { value: 'u$s', label: 'Dolar'}
        ];
        $("#moneda").jqxDropDownList({ width: '300px', height: '25px', source: monedas, theme: theme, placeHolder: 'elija la moneda'});

        $("#moneda").on('change', function(event){
            var args = event.args;
            if (args.item){
                if (args.item.value == '$'){
                    $("#formPlazo").jqxValidator('hideHint', '#segmento');
                    $("#filaSegmento").hide();
                } else {
                    $("#filaSegmento").show();
                }
            }
        });
        
        $("#plazo").jqxNumberInput({ width: '300px', height: '20px', theme:theme, digits:3, decimalDigits: 0});
        $("#especie").jqxInput({ width: '300px', height: '20px', theme: theme});
        $("#colocacion").jqxNumberInput({ width: '300px', height: '20px', theme:theme, digits:6, decimalDigits: 0});
        $("#precioMinimoC").jqxNumberInput({ width: '300px', height: '20px', theme:theme, digits:6, decimalDigits: 2});
        $("#tituloC").jqxInput({ width: '900px', height: '20px', theme: theme});
        $("#tituloNCJ").jqxInput({ width: '900px', height: '20px', theme: theme});
        $("#tituloNCF").jqxInput({ width: '900px', height: '20px', theme: theme});
        $("#segmento").jqxDropDownList({ width: '300px', height: '25px', source: ['I','E'], theme: theme, placeHolder: 'elija el segmento'});

        
        $('#aceptarPlazo').jqxButton({ theme: theme, width: '100px', height: '25px' });

        $("#ventanaPlazo").jqxWindow({showCollapseButton: false, maxHeight:800, maxWidth:1200, height: 340, width: 1200, theme: theme,
            resizable: false, keyboardCloseKey: -1, autoOpen: false, zIndex: 19000, isModal: true});
        
        $("#nuevoPlazo").bind('click',function(){
            $("#form").jqxValidator('hide');
            plazo.contador--;
            plazo.id = plazo.contador;
            plazo.operacion = 'I';
            inicializarPlazo();
            $("#ventanaPlazo").jqxWindow('open');
            $("#ventanaPlazo").jqxWindow('bringToFront');
        });
        
        $("#editarPlazo").bind('click', function(){
            $("#form").jqxValidator('hide');
            plazo.operacion = 'E';
            inicializarPlazo();
            var fila = $("#grillaPlazos").jqxGrid('getrowdata', plazo.fila);
            plazo.id = fila.id;
            
            setDropDown("#moneda", fila.moneda);
            $("#plazo").val(fila.plazo);
            $("#especie").val(fila.especie);
            $("#colocacion").val(fila.colocacion);
            $("#precioMinimoC").val(fila.precioMinimoC);
            $("#tituloC").val(fila.tituloC);
            $("#tituloNCJ").val(fila.tituloNCJ);
            $("#tituloNCF").val(fila.tituloNCF);
            if (fila.moneda == 'u$s'){
                if (fila.segmento == 'I'){
                    $("#segmento").jqxDropDownList('selectIndex', 0);
                } else if (fila.segmento == 'E'){
                    $("#segmento").jqxDropDownList('selectIndex', 1);
                }
            }
            
            $("#ventanaPlazo").jqxWindow('open');
            $("#ventanaPlazo").jqxWindow('bringToFront');
            
        });
        
        $("#borrarPlazo").bind('click', function(){
            new Messi('Desea borrar el plazo ' + plazo.nombre + ' ?' , {title: 'Confirmar', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        if (plazo.id > 0){
                            plazo.borrar.push(plazo.id);
                        }
                        $('#grillaPlazos').jqxGrid('deleterow', plazo.id);
                    } 
                }
            });
        });
        
        
        function inicializarPlazo(){
            $("#moneda").jqxDropDownList('selectIndex', -1);
            $("#plazo").val(0);
            $("#especie").val('');
            $("#colocacion").val(0);
            $("#precioMinimoC").val(0);
            $("#tituloC").val('');
            $("#tituloNCJ").val('');
            $("#tituloNCF").val('');
            $("#segmento").jqxDropDownList('selectIndex', -1);
            $("#filaSegmento").hide();
        }
        
        $('#formPlazo').jqxValidator({ rules: [
                { input: '#moneda', message: 'Debe seleccionar la moneda!',  rule: function(){
                    return ($("#moneda").jqxDropDownList('getSelectedIndex') !== -1)
                } },
                { input: '#plazo', message: 'El plazo debe ser mayor a 27!',  rule: function(){
                    return $("#plazo").val() > 27;
                } },
                { input: '#plazo', message: 'El plazo debe ser menor a 365!',  rule: function(){
                    return $("#plazo").val() < 365;
                } },            
                { input: '#especie', message: 'Debe ingresar el nombre de la especie!',  rule: 'required' },
                { input: '#colocacion', message: 'Debe ingresar el numero de colocación!',  rule: function(){
                    return $("#colocacion").val() > 30;
                } },
                { input: '#tituloC', message: 'Debe ingresar el nombre del titulo competitivo!',  rule: 'required' },
                { input: '#tituloNCJ', message: 'Debe ingresar el nombre del título no competitivo personas juridicas!',  rule: 'required' },
                { input: '#tituloNCF', message: 'Debe ingresar el nombre del titulo no competitivo personas físicas!',  rule: 'required' },
                { input: '#segmento', message: 'Debe seleccionar el segmento', rule: function(){
                    var result = true;
                    if (getDropDown('#moneda') == 'u$s'){
                        result = ($("#segmento").jqxDropDownList('getSelectedIndex') !== -1);
                    }
                    return result;
                }}
            ],
            theme: theme
        });
        $('#formPlazo').bind('validationSuccess', function (event) { 
            plazo.formOK = true; 
        });
        $('#formPlazo').bind('validationError', function (event) { 
            plazo.formOK = false; 
        }); 
        
        $('#aceptarPlazo').bind('click', function () {
            $('#formPlazo').jqxValidator('validate');
            if (plazo.formOK){
                $('#ventanaPlazo').ajaxloader();
                var segmento = null;
                if (getDropDown("#moneda") == 'u$s'){
                    segmento = getDropDown('#segmento');
                }
                var fila = {
                    id: plazo.id,
                    moneda: getDropDown("#moneda"),
                    plazo: $("#plazo").val(),
                    especie: $("#especie").val(),
                    colocacion: $("#colocacion").val(),
                    precioMinimoC: $("#precioMinimoC").val(),
                    tituloC: $("#tituloC").val(),
                    tituloNCJ: $("#tituloNCJ").val(),
                    tituloNCF: $("#tituloNCF").val(),
                    segmento: segmento
                };
                if (plazo.operacion == 'I'){
                    $("#grillaPlazos").jqxGrid('addrow', plazo.id, fila);
                } else {
                    $("#grillaPlazos").jqxGrid('updaterow', plazo.id, fila);
                }
                
                $('#ventanaPlazo').ajaxloader('hide');
                $('#ventanaPlazo').jqxWindow('close');
            }
        });                
       

    });
</script>