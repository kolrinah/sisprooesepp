<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: SEPTIEMBRE DE 2013                                        *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Mcti\SisproBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MapaVenezuelaController extends Controller
{    
    public function cargarMapeoAction(Request $request)
    { 
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }

       $entidad =  $request->request->get('entidad');

       $entidades = $this->getDoctrine()
                         ->getRepository('SisproBundle:Municipio')
                         ->getEntidades($entidad);
       $html='';
       if ($entidad=='E00')
       {
         foreach ($entidades as $e)
         {         
           $html.='<area id="'.$e->getCodigoOnapre().'" href="#" title="'.
                               $e->getEstado().'" '.
                               $e->getMapArea().' />'; 
         }unset($e);
       }
       else
       {
         foreach ($entidades as $m)
         {         
           $html.='<area id="'.$m->getCodigoOnapre().'" href="#" title="'.
                               $m->getMunicipio().'" '.
                               $m->getMapArea().' />'; 
         }unset($m);
       }
       die($html);
    }
    
}

?>
