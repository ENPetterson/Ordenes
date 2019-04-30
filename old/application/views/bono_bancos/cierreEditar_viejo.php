<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaCierre">
    <div id="titulo">
        Editar Cierre Bono
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 3px">Fecha y hora cierre: </td>
                    <td><div id="fechaHora"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Colocacion: </td>
                    <td><div id="colocacion"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Titulo Competitivo: </td>
                    <td><input id="tituloC"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Titulo No Competitivo: </td>
                    <td><input id="tituloNC"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Especie: </td>
                    <td><input id="especie"></td>
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
        $("#colocacion").jqxNumberInput({ width: '250px', height: '25px', theme:theme, digits:6, decimalDigits: 0});
        $("#tituloC").jqxInput({ width: '250px', height: '25px', theme:theme});
        $("#tituloNC").jqxInput({ width: '250px', height: '25px', theme:theme});
        $("#especie").jqxInput({ width: '250px', height: '25px', theme:theme});
    
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Cierre Bono');
        } else {
            $("#titulo").text('Editar Cierre Bono');
            datos = {
                cierrebono_id: $("#id").val()
            };
            $.post('/bono/getCierre', datos, function(data){
                $("#fechaHora").val(data.fechahora);
                $("#colocacion").val(data.colocacion);
                $("#tituloC").val(data.tituloC);
                $("#tituloNC").val(data.tituloNC);
                $("#especie").val(data.especie);
            }
            , 'json');
        };
         $('#form').jqxValidator({ rules: [
                    { input: '#colocacion', message: 'Debe ingresar el numero de colocación!',  rule: function(){
                        return $("#colocacion").val() > 30;
                    } },
                    { input: '#tituloC', message: 'Debe ingresar el titulo competitivo!',  rule: 'required'},
                    { input: '#tituloNC', message: 'Debe ingresar el titulo no competitivo!',  rule: 'required'},
                    { input: '#especie', message: 'Debe ingresar la especie!',  rule: 'required'},
                    { input: '#fechaHora', message: 'Ya existe un cierre con esa fecha y hora!',  rule: function(){
                            var fechaHora = moment($("#fechaHora").jqxDateTimeInput('val','date'));
                            datos = {
                                tabla: 'cierrebono',
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
                    cierrebono_id: $("#id").val(),
                    fechahora: fechaHora.format("YYYY-MM-DD HH:mm") + ":00",
                    colocacion: $("#colocacion").val(),
                    tituloC: $("#tituloC").val(),
                    tituloNC: $("#tituloNC").val(),
                    especie: $("#especie").val()
                }
                $.post('/bono/saveCierre', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/bono/cierre');
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