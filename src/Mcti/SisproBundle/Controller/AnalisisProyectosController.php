<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: ENERO DE 2014                                             *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Mcti\SisproBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mcti\SisproBundle\Entity\RegistroNotificacion;

use Symfony\Component\HttpFoundation\Request;

class AnalisisProyectosController extends Controller
{
    /*
     * Analizamos las actividades de los proyectos para conocer su estatus
     * y enviar Notificaciones correspondientes
     */
    public function analizarProyectosAction()
    {
       // Buscamos los Proyectos Retrasados     
       $proyectos = $this->getDoctrine()
                         ->getRepository('SisproBundle:Proyecto')
                         ->getProyectosRetrasados();
       
       // PROCEDEMOS A OBTENER LOS OBJETOS CORRESPONDIENTES A LOS PROYECTOS
       if (count($proyectos)== 0) die('Listo');
       $donde='';
       foreach($proyectos as $p) $donde.= ' p.id = '.$p['id'].' OR';
       
       $donde = substr($donde, 0, -3);
       
       // OBTENEMOS LOS PROYECTOS HIDRATADOS 
       $proyectos = $this->getDoctrine()
                         ->getRepository('SisproBundle:Proyecto') 
                         ->getProyectosHidratados($donde);

       foreach($proyectos as $p)
       {
           $this->_emailNotificacion($p);
           $this->_registrarNotificacion($p);
       }
       
       die('LISTO');       

       // \Doctrine\Common\Util\Debug::dump($proyectos);
    }
    
    /*
     * CORREO DE NOTIFICACION DE RETRASO EN REGISTRO DE ACTIVIDADES CUMPLIDAS
     */
    private function _emailNotificacion($p)
    {  
       $data['usuario'] =  $p->getUsuario()->__toString();
       $data['codigo'] = $p->getCodigo();
       $data['proyecto'] = $p->getNombre();
       $data['ente'] = $p->getEstructura()->getEstructura();
       $data['siglas'] = $p->getEstructura()->getSiglas();
       
       $titulo ='Retraso detectado en proyecto: '.$data['codigo'];
       $destino = $p->getUsuario()->getCorreo();        
       $grupoOesepp=$this->get('utilidades')->getCorreoOesepp(); // CORREO DE GRUPO OESEPP
       $correo=$this->get('utilidades')->getCorreoSispro(); // CORREO DE NO REPLAY               
       $from = array(
           $correo =>'Oficina Estratégica de Seguimiento y Evaluación de Políticas Públicas');       
       
       $message = \Swift_Message::newInstance()
                        ->setSubject($titulo)
                        ->setFrom($from)
                        ->setTo($destino)
                        ->setBcc($grupoOesepp)
                        ->setBody($this->renderView(
                'SisproBundle:Correos:correoNotificacionRetraso.html.twig', $data),
                                'text/html');
       
       // Preparar CC para enlaces       
       $enlace = $p->getEstructura()->getUsuarios();
       if (count($enlace)>0)
       {
           foreach($enlace as $cc) $message->addCc($cc->getCorreo());
       }       
       return $this->get('mailer')->send($message);
    }  
    
    /*
     * REGISTRO DE NOTIFICACIONES
     */
    private function _registrarNotificacion($p)
    {        
        $registro = new RegistroNotificacion();
                
        $proyecto = $this->getDoctrine()
                         ->getRepository('SisproBundle:Proyecto')
                         ->find($p->getId());        
        if (!$proyecto) die(json_encode(array('error'=>'Proyecto no encontrado.')));
        
        $registro->setProyecto($proyecto);
        $registro->setFecha(new \DateTime('now'));        
        
        $em = $this->getDoctrine()->getManager();       
        $em->persist($registro);
        try {
              $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor
                if(stristr($e->getMessage(), 'Not null violation')) 
                {
                  $error ='No ha llenado todos los campos.';
                }else  $error=$e->getMessage();
                
                die(json_encode(array('error'=>$error)));
            }

        // ACTUALIZAMOS EL ESTATUS DEL PROYECTO A 'PARALIZADO'       
        $estatus = $this->getDoctrine()
                        ->getRepository('SisproBundle:Estatus')
                        ->find(4);// Proyecto Paralizado
        if (!$estatus) die(json_encode(array('error'=>'Estatus no encontrado.')));
        
        $proyecto->setEstatus($estatus);
        $em = $this->getDoctrine()->getManager();       
        try {
              $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor                
                die(json_encode(array('error'=>$error)));
            } 
            
        return true;        
    }
}