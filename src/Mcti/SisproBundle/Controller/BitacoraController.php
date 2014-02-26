<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: JUNIO DE 2013                                             *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Mcti\SisproBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BitacoraController extends Controller
{
    public function listarAction()
    {                         
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery('SELECT b,u 
                                 FROM SisproBundle:Bitacora b
                                 JOIN b.usuario u
                                 ORDER BY b.fecha DESC');

      $bitacora = $query->getResult();
      
      if (!$bitacora) return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
                  
      $i=0;            
      foreach ($bitacora as $b)
      {
          $eventos[$i]['id']=$b->getId();
          $eventos[$i]['fecha']=$b->getFecha();
          $eventos[$i]['entidad']=$b->getEntidad();
          $eventos[$i]['accion']=$b->getAccion();
          $eventos[$i]['registro']=$b->getRegistro();
          $eventos[$i]['ip']=$b->getIp();
          $eventos[$i]['userAgent']=$b->getUserAgent();
          $eventos[$i]['usuario']=$b->getUsuario();
          
          switch ($b->getUserAgent())
          {
             case (stristr(strtoupper($b->getUserAgent()), 'MSIE')!=false): // Internet Explorer
                  $imagen='bundles/sispro/img/msie.png';
                  break;
             case (stristr(strtoupper($b->getUserAgent()), 'FIREFOX')!=false): // Firefox
                  $imagen='bundles/sispro/img/firefox.png';
                  break;
             case (stristr(strtoupper($b->getUserAgent()), 'CHROME')!=false): // Chrome
                  $imagen='bundles/sispro/img/chrome.png';
                  break;
             case (stristr(strtoupper($b->getUserAgent()), 'SAFARI')!=false): // Chrome
                  $imagen='bundles/sispro/img/safari.png';
                  break;
             case (stristr(strtoupper($b->getUserAgent()), 'OPERA')!=false): // Opera
                   $imagen='bundles/sispro/img/opera.png';
                   break;
             case (stristr(strtoupper($b->getUserAgent()), 'ANDROID')!=false): // Android
                  $imagen='bundles/sispro/img/android.png';
                  break;
             default:           
                  $imagen='bundles/sispro/img/desconocido.png';
          }
          $eventos[$i]['browser']=$imagen;
          $i++;
      }    
      
      return $this->render('SisproBundle:Bitacora:listar.html.twig', 
                array('eventos'=>$eventos));
   }
    
}
