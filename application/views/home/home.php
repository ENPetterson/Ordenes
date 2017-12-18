<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    .btn {
      background: #3498db;
      background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
      background-image: -moz-linear-gradient(top, #3498db, #2980b9);
      background-image: -ms-linear-gradient(top, #3498db, #2980b9);
      background-image: -o-linear-gradient(top, #3498db, #2980b9);
      background-image: linear-gradient(to bottom, #3498db, #2980b9);
      -webkit-border-radius: 28;
      -moz-border-radius: 28;
      border-radius: 28px;
      font-family: Arial;
      color: #ffffff;
      font-size: 50px;
      padding: 10px 20px 10px 20px;
      text-decoration: none;
      min-width: 400px;
    }

    .btn:hover {
      background: #3cb0fd;
      background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
      background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
      background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
      background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
      background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
      text-decoration: none;
    }    
    
    table#botonera {
        width: 50%;
        margin-left: 25%;
        margin-right: 25%;
        margin-top: 10%;
    }
    
    .texto-cierre {
        font-size: 10pt;
        text-align: center;
        padding-top: 10px;
    }
</style>
<table id="botonera">
    <tr>
        <td><button class="btn" id="btnLebacs">Lebacs<div id="cierreLebacs" class="texto-cierre"></div></button></td>
        <td><button class="btn" id="btnLetes">Letes<div id="cierreLetes" class="texto-cierre"></div></button></td>
    </tr>
    <tr>
        <td style="padding-top: 5em"><button class="btn" id="btnBono">RICHMOND<div id="cierreBono" class="texto-cierre"></div></button></td>
        <td style="padding-top: 5em"><button class="btn" id="btnCupon">INVJ Cupones<div id="cierreCupon" class="texto-cierre"></div></button></td>
    </tr>
</table>
<script>
    $(function(){

        $.post('lebac/getCierreActual', function(cierre){
             if (cierre.cerrado){
                 periodoCerrado();
             } else {
                 var fechaCierre = moment(cierre.fechahora).format('YYYY/MM/DD HH:mm:ss');
                 $('#cierreLebacs').countdown(fechaCierre, function(event) {
                 var $this = $(this).html(event.strftime(''
                    + 'Cierre en <span>%w</span> semanas <span>%d</span> días '
                    + '<span>%H</span> horas '
                    + '<span>%M</span> minutos '
                    + '<span>%S</span> segundos'));
                  });
                  $('#cierreLebacs').on('finish.countdown', function(){
                      periodoCerrado();
                  });
             }
        }, 'json');

        function periodoCerrado(){
            $('#cierreLebacs').html('No hay licitaciones abiertas');
        }

        $.post('letes/getCierreActual', function(cierre){
             if (cierre.cerrado){
                 periodoCerradoLetes();
             } else {
                 var fechaCierre = moment(cierre.fechahora).format('YYYY/MM/DD HH:mm:ss');
                 $('#cierreLetes').countdown(fechaCierre, function(event) {
                 var $this = $(this).html(event.strftime(''
                    + 'Cierre en <span>%w</span> semanas <span>%d</span> días '
                    + '<span>%H</span> horas '
                    + '<span>%M</span> minutos '
                    + '<span>%S</span> segundos'));
                  });
                  $('#cierreLetes').on('finish.countdown', function(){
                      periodoCerradoLetes();
                  });
             }
        }, 'json');

        function periodoCerradoLetes(){
            $('#cierreLetes').html('No hay licitaciones abiertas');
        }
        
        $.post('bono/getCierreActual/15', function(cierre){
             if (cierre.cerrado){
                 periodoCerradoBono();
             } else {
                 var fechaCierre = moment(cierre.fechahora).format('YYYY/MM/DD HH:mm:ss');
                 $('#cierreBono').countdown(fechaCierre, function(event) {
                 var $this = $(this).html(event.strftime(''
                    + 'Cierre en <span>%w</span> semanas <span>%d</span> días '
                    + '<span>%H</span> horas '
                    + '<span>%M</span> minutos '
                    + '<span>%S</span> segundos'));
                  });
                  $('#cierreBono').on('finish.countdown', function(){
                      periodoCerradoBono();
                  });
             }
        }, 'json');

        function periodoCerradoBono(){
            $('#cierreBono').html('No hay licitaciones abiertas');
        }

        $.post('bono/getCierreActual', function(cierre){
             if (cierre.cerrado){
                 periodoCerradoBonar();
             } else {
                 var fechaCierre = moment(cierre.fechahora).format('YYYY/MM/DD HH:mm:ss');
                 $('#cierreBonar').countdown(fechaCierre, function(event) {
                 var $this = $(this).html(event.strftime(''
                    + 'Cierre en <span>%w</span> semanas <span>%d</span> días '
                    + '<span>%H</span> horas '
                    + '<span>%M</span> minutos '
                    + '<span>%S</span> segundos'));
                  });
                  $('#cierreBonar').on('finish.countdown', function(){
                      periodoCerradoBonar();
                  });
             }
        }, 'json');

        function periodoCerradoBonar(){
            $('#cierreBonar').html('No hay licitaciones abiertas');
        }


        $.post('cupon/getCierreActual', function(cierre){
             if (cierre.cerrado){
                 periodoCerradoCupon();
             } else {
                 var fechaCierre = moment(cierre.fechahora).format('YYYY/MM/DD HH:mm:ss');
                 $('#cierreCupon').countdown(fechaCierre, function(event) {
                 var $this = $(this).html(event.strftime(''
                    + 'Cierre en <span>%w</span> semanas <span>%d</span> días '
                    + '<span>%H</span> horas '
                    + '<span>%M</span> minutos '
                    + '<span>%S</span> segundos'));
                  });
                  $('#cierreBono').on('finish.countdown', function(){
                      periodoCerradoCupon();
                  });
             }
        }, 'json');
        
        function periodoCerradoCupon(){
            $('#cierreCupon').html('No hay licitaciones abiertas');
        }
        
        $("#btnLebacs").click(function(){
            $.redirect('/lebac');
        });
        
        $("#btnLetes").click(function(){
            $.redirect('/letes');
        });
        
        $("#btnBono").click(function(){
            $.redirect('/bono');
        });
        
        $("#btnCupon").click(function(){
            $.redirect('/cupon');
        });
        
    });

</script>