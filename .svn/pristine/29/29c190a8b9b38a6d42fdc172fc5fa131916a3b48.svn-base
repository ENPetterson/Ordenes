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
                    <td colspan="2" style="text-align: center; padding-top: 20px">
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
        
        $("#ventanaCierre").jqxWindow({showCollapseButton: false, height: '200px', width: '320px', theme: theme,
            resizable: false, keyboardCloseKey: -1});
        $("#fechaHora").jqxDateTimeInput({ formatString: "dd/MM/yyyy HH:mm", showTimeButton: true, width: '190px', height: '25px', theme: theme });

        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Cierre');
        } else {
            $("#titulo").text('Editar Cierre');
            datos = {
                cierrecupon_id: $("#id").val()
            };
            $.post('/cupon/getCierre', datos, function(data){
                $("#fechaHora").val(data.fechahora);
            }, 'json');
        };
        
        $('#form').jqxValidator({ rules: [
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

                var datos = {
                    cierrecupon_id: $("#id").val(),
                    fechahora: fechaHora.format("YYYY-MM-DD HH:mm") + ":00"
                }
                $.post('/cupon/saveCierre', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/cupon/cierre');
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