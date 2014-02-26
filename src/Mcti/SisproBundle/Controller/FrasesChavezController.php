<?php
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
namespace Mcti\SisproBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mcti\SisproBundle\Entity\FrasesChavez;

class FrasesChavezController extends Controller
{
    const _MIN_ = 1;
    const _MAX_ = 575; // ESTABLECE EL MAXIMO NUMERO DE FRASES DISPONIBLES
    const _MAX_FOTO =143; // ESTABLECE EL MAXIMO DE FOTOS DE CHAVEZ
    
    public function fraseAction()
    {  
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
      // CIERRA LA SESION LUEGO DE 10 MINUTOS DE INACTIVIDAD  
      $session = $this->get('Session');
      
      if (time() - $session->getMetadataBag()->getLastUsed()>600)
      {
          $session->invalidate();
          return $this->redirect($this->generateUrl('logout'), 301);          
      }           
        
       $id = rand ( self::_MIN_ , self::_MAX_ ); // OBTENGO LA FRASE
            
       //$id=96;
       $frase = $this->getDoctrine()
                     ->getRepository('SisproBundle:FrasesChavez')
                     ->find($id);
       
       if(!$frase)die('Error: índice de frase incorrecto');
       
       $data=array('frase'=>$frase->getFrase(),
                    'cita'=>$frase->getCita(),
                      'id'=>$id
                   );
       
       /* Si no hay imagen disponible para la frase, buscamos una de manera aleatoria */
       $data['ruta'] = $frase->getRuta();
       if ($data['ruta'] == null)
       {
           $id = rand ( self::_MIN_ , self::_MAX_FOTO ); // OBTENGO LA FOTO
           $ruta = $this->getDoctrine()
                        ->getRepository('SisproBundle:FrasesChavez')
                        ->find($id);
           if (!$ruta)die('Error: índice de foto incorrecto');
       
           $data['ruta'] = $ruta->getRuta();
       }       
       
       die(json_encode($data));
    }
}

?>
