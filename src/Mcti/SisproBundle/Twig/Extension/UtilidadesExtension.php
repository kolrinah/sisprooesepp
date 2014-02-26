<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: AGOSTO DE 2013                                            *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Mcti\SisproBundle\Twig\Extension;

class UtilidadesExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
        'miniGantt' => new \Twig_Function_Method($this, 'miniGantt'),
        );
    }   
    
    // ESTA FUNCION OBTIENE EL DIV DE LA BARRA DEL GANTT
    public function miniGantt($years=array(), $fechaIni, $fechaFin) 
    {
       $cols = (count($years) >= 3)? 3:count($years);
              
       $yearI =  array_search($fechaIni->format('Y'), $years);
       $yearF =  array_search($fechaFin->format('Y'), $years);
       
       // Día del año en Fecha de Inicio
       $fechaI = $fechaIni->format('z') + $yearI*365; 
       
       // Día del año en Fecha Final
       $fechaF = $fechaFin->format('z') + $yearF*365;
       
       $duracion = $fechaF - $fechaI;
      
       // LLEVAMOS LAS FECHAS A LA ESCALA DEL LIENZO DE TRABAJO        
       if ( ($fechaI > ($cols*365)) || ($fechaF > ($cols*365)) )
          return '<div>Actividad Planificada para el Año '.$fechaFin->format('Y').'</div>';
       
       $bloque = intval(round($fechaI*100 / ($cols*365)));
       $anchoGantt = intval(round($duracion*100 / ($cols*365)));
       
       $anchoGantt = ($anchoGantt<1)?1:$anchoGantt;
       
     /*  return '<div>'.$fechaI.' - '.$fechaF.' Duracion '.$duracion.' Bloque '.$bloque.
               ' anchoGantt '.$anchoGantt.'</div>';*/
      
       $alto='15px';
         
       $html='<div style="float:left; width:'.$bloque.'%; height:'.$alto.'; display:inline">&nbsp';
       $html.='</div>';    
       $html.='<div class="gantt" style="float:left; width:'.$anchoGantt.'%; height:'.$alto.'; display:inline">&nbsp';
       $html.='</div>';
       $html.='<div class="clear"></div>';  
       
       return $html;
    } 

    public function getName()
    {
        return 'utilidades';
    }        
}
?>