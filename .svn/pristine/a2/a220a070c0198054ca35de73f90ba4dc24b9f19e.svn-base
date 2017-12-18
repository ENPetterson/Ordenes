<input type="hidden" value="<?php $dominio;?>" id="dominioPrevio"/>
<div id="ventanaLogin">
    <div>
        <h4>Ingreso al Sistema</h4>
    </div>
    <div>
        <div>
            <form id="formLogin">
                <table>
                    <tr>
                        <td style="padding: 0px 10px 12px 0px"><label for="nombreUsuario">Usuario:</label></td>
                        <td><input type="text" id="nombreUsuario" /></td>
                    </tr>
                    <tr>
                        <td style="padding: 0px 10px 12px 0px"><label for="clave">Clave:</label></td>
                        <td><input type="password" id="clave"></td>
                    </tr>
                    <tr>
                        <td style="padding: 0px 10px 12px 0px; vertical-align: middle">Dominio:</td>
                        <td><div id="dominio" ></div></td>
                    </tr>
                </table>
            </form>
        </div>
        <div style="float: right; margin-top: 15px;">
            <input type="submit" id="aceptar" value="Ingresar" style="margin-right: 10px" />
        </div>
    </div>
</div>
<script>
    
    $(function(){
        $("body").data('theme', 'darkblue');
        
        var theme = getTheme();
        var formOK = false;
            
        $('#ventanaLogin').jqxWindow({ height: 170, width: 250,
            theme: theme, resizable: false, isModal: true, modalOpacity: 0.3,
            autoOpen: true,
            initContent: function () {
                $('#aceptar').jqxButton({ theme: theme, width: '65px' });
                $('#formLogin').jqxValidator({
                    rules: [
                        { input: '#nombreUsuario', message: 'Debe ingresar el nombre de usuario!', rule: 'required' },
                        { input: '#clave', message: 'Debe ingresar la clave!',  rule: 'required' }
                    ], theme: theme
                });
            }
        });
        
        $("#nombreUsuario").jqxInput({placeHolder: "Usuario", height: '20px', width: '170px', minLength: 1, theme: theme });
        $("#clave").jqxPasswordInput({  width: '170px', height: '20px', theme: theme});
        var sourceDominios = [<?php echo DOMINIOS;?>];
        $("#dominio").jqxDropDownList({ source: sourceDominios, selectedIndex: 0, width: '170', height: '20px', theme: theme});
        if ($("#dominioPrevio").val()){
            $("#dominio").jqxDropDownList('selectItem', $("#dominioPrevio").val());
        }
        
        $('#aceptar').bind('click', function () {
            $('#formLogin').jqxValidator('validate');
            if (formOK){
                $('#ventanaLogin').ajaxloader();
                datos = {
                    nombreUsuario: $('#nombreUsuario').val(),
                    clave: $('#clave').val(),
                    dominio: $("#dominio").val()
                };
                $.post('usuario/validarUsuario', datos, function(data){
                    if (data.resultado == 'OK'){
                        $(location).attr('href','/');
                    } else {
                        new Messi(data.resultado, {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaLogin').ajaxloader('hide');
                    }
                }, 'json');
            }
        });        
        $('#formLogin').bind('validationSuccess', function (event) { formOK = true; });
        $('#formLogin').bind('validationError', function (event) { formOK = false; }); 
        
        $('input').keypress(function (e) {
        if(e.which === 13) {
            
            $('#aceptar').click();
        }
    });
    });
</script>    