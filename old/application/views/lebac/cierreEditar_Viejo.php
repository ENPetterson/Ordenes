<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaCierre">
    <div id="titulo">
        Editar Cierre
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 3px">Fecha y hora cierre: </td>
                    <td><div id="fechaHora"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Plazos $ (separados por coma): </td>
                    <td><input type="text" id="plazosPesos" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Especies $ (separados por coma): </td>
                    <td><input type="text" id="especiesPesos" style="width: 250px; text-transform: uppercase"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Minimos (separados por coma): </td>
                    <td><input type="text" id="minimosPesos" style="width: 250px; text-transform: uppercase"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Colocaciones (separados por coma): </td>
                    <td><input type="text" id="colocacionesPesos" style="width: 250px; text-transform: uppercase"></td>
                </tr>
                 <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Plazos u$s (separados por coma): </td>
                    <td><input type="text" id="plazosDolares" style="width: 250px"></td>
                </tr>
                 <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Especies u$s (separados por coma): </td>
                    <td><input type="text" id="especiesDolares" style="width: 250px; text-transform: uppercase"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Minimos (separados por coma): </td>
                    <td><input type="text" id="minimosDolares" style="width: 250px; text-transform: uppercase"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Colocaciones (separados por coma): </td>
                    <td><input type="text" id="colocacionesDolares" style="width: 250px; text-transform: uppercase"></td>
                </tr>
                 <tr>
                    <td style="padding-right:10px; padding-bottom: 20px">Segmentos u$s (separados por coma): </td>
                    <td><input type="text" id="segmentosDolares" style="width: 250px; text-transform: uppercase"></td>
                </tr>
                 <tr>
                    <td style="padding-right:10px; padding-bottom: 20px">Instrumento Lebac que vence: </td>
                    <td><input type="text" id="instrumentoLebac" style="width: 250px; text-transform: uppercase"></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center">
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
        var instrumentoLebac;
        
        $("#ventanaCierre").jqxWindow({showCollapseButton: false, height: '400px', width: '550px', theme: theme,
        resizable: false, keyboardCloseKey: -1});
        $("#fechaHora").jqxDateTimeInput({ formatString: "dd/MM/yyyy HH:mm", showTimeButton: true, width: '250px', height: '25px', theme: theme });
        $("#plazosPesos").jqxInput({theme: theme, height: '25px', width: '250px' });
        $("#especiesPesos").jqxInput({theme: theme, height: '25px', width: '250px' });
        $("#minimosPesos").jqxInput({theme: theme, height: '25px', width: '250px' });
        $("#colocacionesPesos").jqxInput({theme: theme, height: '25px', width: '250px' });
        $("#plazosDolares").jqxInput({theme: theme, height: '25px', width: '250px' });
        $("#especiesDolares").jqxInput({theme: theme, height: '25px', width: '250px' });
        $("#minimosDolares").jqxInput({theme: theme, height: '25px', width: '250px' });
        $("#colocacionesDolares").jqxInput({theme: theme, height: '25px', width: '250px' });
        $("#segmentosDolares").jqxInput({theme: theme, height: '25px', width: '250px' });
        
    
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Cierre');
        } else {
            $("#titulo").text('Editar Cierre');
            datos = {
                cierre_id: $("#id").val()
            };
            $.post('/lebac/getCierre', datos, function(data){
                $("#fechaHora").val(data.fechahora);
                $("#plazosPesos").val(data.plazospesos);
                $("#especiesPesos").val(data.especiespesos);
                $("#minimosPesos").val(data.minimospesos);
                $("#colocacionesPesos").val(data.colocacionespesos);
                $("#plazosDolares").val(data.plazosdolares);
                $("#especiesDolares").val(data.especiesdolares);
                $("#minimosDolares").val(data.minimosdolares);
                $("#colocacionesDolares").val(data.colocacionesdolares);
                $("#segmentosDolares").val(data.segmentosdolares);
            }
            , 'json');
        };
         $('#form').jqxValidator({ rules: [
                    { input: '#plazosPesos', message: 'Debe ingresar los plazos en pesos!',  rule: 'required' },
                    { input: '#plazosPesos', message: 'Debe ingresar solos numeros separados por coma!',  rule: function(){
                        var string = $("#plazosPesos").val();
                        var result = string.match(/^(([0-9]+)(,(?!$))?)+$/);
                        return(!(!result));
                    }},
                    { input: '#especiesPesos', message: 'Debe ingresar las especies en pesos!',  rule: 'required' },
                    { input: '#especiesPesos', message: 'Debe ingresar solos numeros y letras separados por coma!',  rule: function(){
                        var string = $("#especiesPesos").val();
                        var result = string.match(/^(([a-zA-Z0-9]+)(,(?!$))?)+$/);
                        return(!(!result));
                    }},
                    { input: '#especiesPesos', message: 'No coinciden la cantidad de plazos y de especies!',  rule: function(){
                        return ($("#plazosPesos").val().split(",").length == $("#especiesPesos").val().split(",").length);
                    }},
                    { input: '#colocacionesPesos', message: 'Debe ingresar solos numeros separados por coma!',  rule: function(){
                        var string = $("#colocacionesPesos").val();
                        var result = string.match(/^(([0-9]+)(,(?!$))?)+$/);
                        return(!(!result));
                    }},
                    { input: '#colocacionesPesos', message: 'No coinciden la cantidad de plazos y de colocaciones!',  rule: function(){
                        return ($("#plazosPesos").val().split(",").length == $("#colocacionesPesos").val().split(",").length);
                    }},
                    //{ input: '#plazosDolares', message: 'Debe ingresar los plazos en dolares!',  rule: 'required' },
                    { input: '#plazosDolares', message: 'Debe ingresar solos numeros separados por coma!',  rule: function(){
                        var string = $("#plazosDolares").val();
                        if (string.length > 0){
                            var result = string.match(/^(([0-9]+)(,(?!$))?)+$/);
                            return(!(!result));
                        } else {
                            return true;
                        }
                    }},
                    //{ input: '#especiesDolares', message: 'Debe ingresar las especies en pesos!',  rule: 'required' },
                    { input: '#especiesDolares', message: 'Debe ingresar solos numeros y letras separados por coma!',  rule: function(){
                        var string = $("#especiesDolares").val();
                        if (string.length > 0){
                            var result = string.match(/^(([a-zA-Z0-9]+)(,(?!$))?)+$/);
                            return(!(!result));
                        } else {
                            return true;
                        }
                    }},
                    { input: '#especiesDolares', message: 'No coinciden la cantidad de plazos y de especies!',  rule: function(){
                        return ($("#plazosDolares").val().split(",").length == $("#especiesDolares").val().split(",").length);
                    }},
                    { input: '#colocacionesDolares', message: 'Debe ingresar solos numeros separados por coma!',  rule: function(){
                        var string = $("#colocacionesDolares").val();
                        if (string.length > 0){
                            var result = string.match(/^(([0-9]+)(,(?!$))?)+$/);
                            return(!(!result));
                        } else {
                            return true;
                        }
                    }},
                    { input: '#colocacionesDolares', message: 'No coinciden la cantidad de plazos y de especies!',  rule: function(){
                        return ($("#plazosDolares").val().split(",").length == $("#colocacionesDolares").val().split(",").length);
                    }},
                    { input: '#segmentosDolares', message: 'Debe ingresar solos numeros y letras separados por coma!',  rule: function(){
                        var string = $("#segmentosDolares").val();
                        if (string.length > 0){
                            var result = string.match(/^(([a-zA-Z0-9]+)(,(?!$))?)+$/);
                            return(!(!result));
                        } else {
                            return true;
                        }
                    }},
                    { input: '#segmentosDolares', message: 'No coinciden la cantidad de plazos y de segmentos!',  rule: function(){
                        return ($("#plazosDolares").val().split(",").length == $("#segmentosDolares").val().split(",").length);
                    }},
                    { input: '#minimosPesos', message: 'Debe ingresar los minimos en pesos!',  rule: 'required' },
                    { input: '#minimosPesos', message: 'Debe ingresar solo numeros separados por coma!',  rule: function(){
                        var string = $("#minimosPesos").val();
                        var result = string.match(/^(([0-9]+)(,(?!$))?)+$/);
                        return(!(!result));
                    }},
                    { input: '#minimosPesos', message: 'No coinciden la cantidad de plazos y de minimos!',  rule: function(){
                        return ($("#plazosPesos").val().split(",").length == $("#minimosPesos").val().split(",").length);
                    }},
                    //{ input: '#minimosDolares', message: 'Debe ingresar los minimos en dolares!',  rule: 'required' },
                    { input: '#minimosDolares', message: 'Debe ingresar solo numeros separados por coma!',  rule: function(){
                        var string = $("#minimosDolares").val();
                        if (string.length > 0){
                            var result = string.match(/^(([0-9]+)(,(?!$))?)+$/);
                            return(!(!result));
                        } else {
                            return true;
                        }
                    }},
                    { input: '#minimosDolares', message: 'No coinciden la cantidad de plazos y de minimos!',  rule: function(){
                        return ($("#plazosDolares").val().split(",").length == $("#minimosDolares").val().split(",").length);
                    }},
                    { input: '#fechaHora', message: 'Ya existe un cierre con esa fecha y hora!',  rule: function(){
                            var fechaHora = moment($("#fechaHora").jqxDateTimeInput('val','date'));
                            datos = {
                                tabla: 'cierre',
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
                    cierre_id: $("#id").val(),
                    fechahora: fechaHora.format("YYYY-MM-DD HH:mm") + ":00",
                    plazospesos: $('#plazosPesos').val(),
                    especiespesos: $('#especiesPesos').val().toUpperCase(),
                    minimospesos: $("#minimosPesos").val(),
                    colocacionespesos: $("#colocacionesPesos").val(),
                    plazosdolares: $('#plazosDolares').val(),
                    especiesdolares: $('#especiesDolares').val().toUpperCase(),
                    minimosdolares: $("#minimosDolares").val(),
                    colocacionesdolares: $("#colocacionesDolares").val(),
                    segmentosdolares: $('#segmentosDolares').val().toUpperCase()
                }
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
    });
</script>