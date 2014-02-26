/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: JULIO DE 2013                                             *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$(document).ready(function()
{  
    getFrase();
    setInterval(function(){getFrase();},300000);
});

function getFrase()
{    
   $.ajax({
        type:'POST',
        url: $('#baseURL').val()+'login/frase/',
        //data: ,
       // beforeSend:function(){$("#cargandoModal").show();},        
        dataType:'json'})
   
    .done(function(data) { 
        $('#foto').attr('src',$('#baseImg').val()+data.ruta);        
        
        $("#frase").attr('title', data.id);   
        
        $("#frase").effect("fold",{}, "slow",
               function(){
           $(this).html(data.frase+'<cite>'+data.cita+'</cite>');
           if (data.frase.length>=400)$('#frase').css('font-size','1.2em');
           else if ((data.frase.length>270) && (data.frase.length<400))$('#frase').css('font-size','1.3em');
           else $('#frase').css('font-size','1.5em');
         //  if (data.cita.length>150) $('#frase cite').css('font-size','0.7em');
         //  else $('#frase cite').css('font-size','0.8em');
           $(this).hide().fadeIn('slow');                 
               });
      });
/*      
   .fail(function(jqXHR, textStatus, errorThrown) {
      var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
      cajaDialogo(traductor(textStatus), mensaje);                
      });      */
      
    return true;
}