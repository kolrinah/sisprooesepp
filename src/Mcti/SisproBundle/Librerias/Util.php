<?php 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: DICIEMBRE DE 2013                                         *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Mcti\SisproBundle\Librerias;

class Util
{
    // Declaramos las constantes para envío de emails    
    const CORREO_SISPRO = 'sisprooesepp@mcti.gob.ve'; // CORREO DEL SISTEMA (NO_REPLY)
    const CORREO_OESEPP = 'oesepp@mcti.gob.ve'; // CORREO DEL GRUPO OESEPP

    // CONSTRUYE OPCIONES DE CAJAS SELECT A PARTIR DE UNA MATRIZ
    public function getOpciones($opciones, $seleccionada=0)  
    {    
      $combo='';
      foreach ($opciones as $value => $text)
      {
        if ($value == $seleccionada)
        {
          $combo.='<option value="'.$value.'" selected="selected">'.$text.'</option>';
        }
        else
        {
          $combo.='<option value="'.$value.'">'.$text.'</option>';
        }
      }
      return $combo;
    } 
    
    // Método para obtener la constante CORREO_SISPRO
    public function getCorreoSispro()
    {
       return self::CORREO_SISPRO; 
    }   
    
    // Método para obtener la constante CORREO_OESEPP
    public function getCorreoOesepp()
    {
       return self::CORREO_OESEPP; 
    }   
}

?>
