<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: FEBRERO DE 2013                                           *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Mcti\SisproBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mcti\SisproBundle\Librerias\FichaPDF;

use Symfony\Component\HttpFoundation\Request;

class FichaProyectoController extends Controller
{    
    public function cargarFichaAction(Request $request)
    { 
       $idProyecto =  $request->query->get('id');

       $proyecto = $this->getDoctrine()
                        ->getRepository('SisproBundle:Proyecto')
                        ->find($idProyecto);
       if(!$proyecto) die('Proyecto no existe.');
       
       $actividades = $this->getDoctrine()
                           ->getRepository('SisproBundle:Proyecto')
                           ->getActividadesProyecto($proyecto);       
       
       $data['proyecto'] = $proyecto;
       $data['actividades'] = $actividades;
       
       // REVISAMOS PERMISOLOGÍA PARA VER EL PROYECTO
       if (!$this->_revisarPermisologia($proyecto))
       return $this->render('SisproBundle:Plantillas:prohibido.html.twig');        
            
       $pdf = new FichaPDF('L', 'mm', 'LETTER', true, 'UTF-8', false); 
            
       $pdf->cargarData($data);
      
       $pdf->Output('FichaProyecto.pdf', 'I');
    }
    
    /*
     * ANÁLISIS DE PERMISOLOGÍA
     */
    private function _revisarPermisologia($proyecto)
    {         
       if ( ($this->get('security.context')->isGranted('ROLE_ADMIN'))  // ADMINISTRADOR
             ||
            ($proyecto->getUsuario() == $this->getUser())   //RESPONSABLE DE PROYECTO
             ||  
            ($this->get('security.context')->isGranted('ROLE_ENLACE') ) //ROL ENLACE
          ) 
       
       return true;
       else return false;
    }    
}

?>
