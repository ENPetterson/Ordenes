<div id="ventanaConsulta">
    <div id="titulo">
        Consultar Datos Comitente
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Numero Comitente: </td>
                    <td><div id="numComitente" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Comitente:</td>
                    <td><input type="text" id="comitente" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Tipo Persona:</td>
                    <td><input type="text" id="tipoPersona" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 10px">Oficial:</td>
                    <td><input type="text" id="oficial" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right: 10px; padding-bottom: 25px">CUIT:</td>
                    <td><input type="text" id="cuit" style="width: 250px"></td>
                </tr>
            </table>
        </form>
    </div> 
</div>
<script>
    $(function(){
        var theme = getTheme();
        $("#ventanaConsulta").jqxWindow({showCollapseButton: false, height: 230, width: 470, theme: theme, resizable: false, keyboardCloseKey: -1});
        
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999});
        $("#comitente").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        $("#tipoPersona").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        $("#oficial").jqxInput({ width: '300px', height: '25px', disabled: true, theme: theme});
        $("#cuit").jqxInput({ width: '110px', height: '25px', disabled: true, theme: theme});
        
        $('#numComitente').on('valueChanged', function (event) {
            var value = $("#numComitente").val();
            $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
                comitente = pComitente;
                if (pComitente){
                    $("#comitente").val(pComitente.comitente);
                    if (pComitente.esFisico == -1){
                        $("#tipoPersona").val('FISICA'); 
                    } else {
                        $("#tipoPersona").val('JURIDICA'); 
                    }
                    $("#oficial").val(pComitente.oficial);
                    $("#cuit").val(pComitente.cuit);
                    $('#form').jqxValidator('hideHint', '#numComitente');
                } else {
                    $("#comitente").val('');
                    $("#tipoPersona").val(''); 
                    $("#oficial").val('');
                    $("#cuit").val('');
                }
            }, 'json');
        });
        
    });
    
    //Aca va el codigo de la calculadora de lebacs
</script>