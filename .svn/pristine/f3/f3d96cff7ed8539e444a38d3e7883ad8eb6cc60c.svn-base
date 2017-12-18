<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaCierre">
    <div id="titulo">
        Editar Cierre Letes
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 3px">Fecha y hora cierre: </td>
                    <td><div id="fechaHora"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Plazos (separados por coma): </td>
                    <td><input type="text" id="plazos" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Minimos (separados por coma): </td>
                    <td><input type="text" id="minimos" style="width: 250px; text-transform: uppercase"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Colocacion $: </td>
                    <td><div id="colocacionPesos"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Colocacion u$s: </td>
                    <td><div id="colocacionDolares"></div></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; padding-top: 1em">
                        <input type="button" id="aceptarButton" value="Aceptar">
                    </td>
                </tr>
            </table>
        </form>
    </div> 
</div>
<script>
    $(function(){
        var theme = getTheme();
        var formOK = false;
        
        $("#ventanaCierre").jqxWindow({showCollapseButton: false, height: '240px', width: '500px', theme: theme,
        resizable: false, keyboardCloseKey: -1});
        $("#fechaHora").jqxDateTimeInput({ formatString: "dd/MM/yyyy HH:mm", showTimeButton: true, width: '250px', height: '25px', theme: theme });
        $("#plazos").jqxInput({theme: theme, height: '25px', width: '250px' });
        $("#minimos").jqxInput({theme: theme, height: '25px', width: '250px' });
        $("#colocacionPesos").jqxNumberInput({ width: '250px', height: '25px', theme:theme, digits:6, decimalDigits: 0});
        $("#colocacionDolares").jqxNumberInput({ width: '250px', height: '25px', theme:theme, digits:6, decimalDigits: 0});
    
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Cierre Letes');
        } else {
            $("#titulo").text('Editar Cierre Letes');
            datos = {
                cierreletes_id: $("#id").val()
            };
            $.post('/letes/getCierre', datos, function(data){
                $("#fechaHora").val(data.fechahora);
                $("#plazos").val(data.plazos);
                $("#minimos").val(data.minimos);
                $("#colocacionPesos").val(data.colocacionPesos);
                $("#colocacionDolares").val(data.colocacionDolares);
            }
            , 'json');
        };
         $('#form').jqxValidator({ rules: [
                    { input: '#plazos', message: 'Debe ingresar los plazos!',  rule: 'required' },
                    { input: '#plazos', message: 'Debe ingresar solos numeros separados por coma!',  rule: function(){
                        var string = $("#plazos").val();
                        var result = string.match(/^(([0-9]+)(,(?!$))?)+$/);
                        return(!(!result));
                    }},
                    { input: '#minimos', message: 'Debe ingresar los minimos!',  rule: 'required' },
                    { input: '#minimos', message: 'Debe ingresar solo numeros separados por coma!',  rule: function(){
                        var string = $("#minimos").val();
                        var result = string.match(/^(([0-9]+)(,(?!$))?)+$/);
                        return(!(!result));
                    }},
                    { input: '#minimos', message: 'No coinciden la cantidad de plazos y de minimos!',  rule: function(){
                        return ($("#plazos").val().split(",").length == $("#minimos").val().split(",").length);
                    }},
                    { input: '#colocacionPesos', message: 'Debe ingresar el numero de colocación en pesos!',  rule: function(){
                        return $("#colocacionPesos").val() > 30;
                    } },
                    { input: '#colocacionDolares', message: 'Debe ingresar el numero de colocación en dolares!',  rule: function(){
                        return $("#colocacionDolares").val() > 30;
                    } },
                    { input: '#fechaHora', message: 'Ya existe un cierre con esa fecha y hora!',  rule: function(){
                            var fechaHora = moment($("#fechaHora").jqxDateTimeInput('val','date'));
                            datos = {
                                tabla: 'cierreletes',
                                campo: 'fechahora',
                                valor: fechaHora.format("YYYY-MM-DD HH:mm") + ":00",
                                id: $('#id').val()
                            };
                            var resultado;
                            jQuery.ajaxSetup({async:false});
                            $.post('/util/buscarDuplicado', datos, function(data){
                                if (data.resultado){
                                    resultado = false;
                                } else {
                                    resultado = true;
                                }
                            }
                            , 'json');
                            jQuery.ajaxSetup({async:true});
                            return resultado;
                    }}
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
                datos = {
                    cierreletes_id: $("#id").val(),
                    fechahora: fechaHora.format("YYYY-MM-DD HH:mm") + ":00",
                    plazos: $('#plazos').val(),
                    minimos: $("#minimos").val(),
                    colocacionPesos: $("#colocacionPesos").val(),
                    colocacionDolares: $("#colocacionDolares").val()
                }
                $.post('/letes/saveCierre', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/letes/cierre');
                    } else {
                        new Messi('Hubo un error guardando el cierre', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaCierre').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
    });
</script>