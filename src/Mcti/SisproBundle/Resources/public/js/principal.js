/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: ENERO DE 2013                                             *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

$(document).ready(function()
{     
    if (parseInt($('#analizar').val()) === 0)
    {
       $('.menu').removeClass('oculto');
       $('#bienvenida').removeClass('oculto');
    }
    else
    {
       // INICIAMOS REVISION DE PROYECTOS 
       $('.menu').addClass('oculto');       
       $('#procesando').removeClass('oculto');
       
       analisisProyectos();
    }
    
} );
// FINAL DEL DOCUMENT READY

/*
 * ANALISIS DE ACTIVIDADES DE PROYECTOS
 */
function analisisProyectos()
{
    $.ajax({
            type:'POST',
            url:'principal/analizar',            
            dataType:'html'})
     .always(function(data) {
            $('#prompt').html(data);
            $('.menu').removeClass('oculto').effect("pulsate",{}, "slow");
            $('#procesando').addClass('oculto');
            $('#bienvenida').removeClass('oculto').effect("pulsate",{}, "slow");            
          })
     .fail(function(jqXHR, textStatus, errorThrown) {
            var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
            cajaDialogo(traductor(textStatus), mensaje);                
          });
    return true;
}