<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: DICIEMBRE DE 2013                                             *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Mcti\SisproBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EjecucionController extends Controller
{
    /*
     * LISTA LOS PROYECTOS DE LAS UNIDADES INFERIORES AL USUARIO AUTENTICADO
     */    
    public function listarProyectosAction()
    {      
      $session = $this->get('Session');
      if (time() - $session->getMetadataBag()->getLastUsed()>6000)
      {
          $session->invalidate();
          return $this->redirect($this->generateUrl('logout'), 301);
      }
      
      // SI EL ACTOR ES ROLE_USER SOLO MOSTRAMOS LOS PROYECTOS DONDE ES EL RESPONSABLE
      // EN CASO CONTRARIO LISTAMOS TODOS LOS PROYECTOS DE LAS ESTRUCTURAS INFERIORES      
      
      if ( ($this->get('security.context')->isGranted('ROLE_ADMIN')) || 
           ($this->get('security.context')->isGranted('ROLE_ENLACE')) )
      {      
      
          $proyectos = $this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->getProyectosUnidadesInferiores($this->getUser()->getEstructura());
      }     
      else
      {
          $proyectos = $this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->getProyectosUsuario($this->getUser());
      }
      
      return $this->render('SisproBundle:Ejecucion:listar.html.twig', 
                                       array('proyectos'=>$proyectos));     
    }
    
    /*
     * Revisamos los detalles de ejeción del proyecto solicitado
     */
    public function revisarProyectoAction(Request $request)
    {          
       if ($request->isMethod('GET')) 
       {
           if (!$request->query->has('id'))
           return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
           
           $id = $request->query->get('id');
       }
       else $id = $request->request->get('id');
       
       $proyecto = $this->getDoctrine()
                        ->getRepository('SisproBundle:Proyecto')
                        ->find($id);
       if (!$proyecto) return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       
       $data['proyecto']=$proyecto;
       
       // REVISAMOS PERMISOLOGÍA PARA VER EL PROYECTO
       if (!$this->_revisarPermisologia($data))
       return $this->render('SisproBundle:Plantillas:prohibido.html.twig');          
       
       // Obtenemos todos los Registros de ejecución del Proyecto              
       $data['objetivos'] = $this->getDoctrine()
                                 ->getRepository('SisproBundle:Proyecto')
                                 ->getObjetivosProyecto($proyecto);       
       
       $data['actividades'] = $this->getDoctrine()
                                   ->getRepository('SisproBundle:Proyecto')
                                   ->getActividadesProyecto($proyecto);  
       
       $data['recursoRecibido'] = $this->getDoctrine()
                                       ->getRepository('SisproBundle:Proyecto')
                                       ->getRecursosRecibidosProyecto($proyecto);
       
       $data['recursoEjecutado'] = $this->getDoctrine()
                                        ->getRepository('SisproBundle:Proyecto')
                                        ->getRecursosEjecutadosProyecto($proyecto);

       $data['registroProblema'] = $this->getDoctrine()
                                        ->getRepository('SisproBundle:Proyecto')
                                        ->getRegistrosProblemasProyecto($proyecto);
       
       $data['metaAlcanzada'] = $this->getDoctrine()
                                     ->getRepository('SisproBundle:Proyecto')
                                     ->getMetasAlcanzadasProyecto($proyecto);       
       
       $data['analisis'] = $this->getDoctrine()
                                ->getRepository('SisproBundle:Proyecto')
                                ->getAnalisisEjecucion($proyecto);
       
       $data['fotos'] = $this->getDoctrine()
                             ->getRepository('SisproBundle:Proyecto')
                             ->getFotografiasProyecto($proyecto); 
       
       $data['years'] = array();
       
       if (count($data['actividades']) != 0)
       {
           foreach ($data['actividades'] as $a)
            {               
               if (!in_array($a->getFechaIni()->format('Y'),$data['years']))
                  array_push($data['years'], $a->getFechaIni()->format('Y'));
               
               if (!in_array($a->getFechaFin()->format('Y'),$data['years']))
                  array_push($data['years'], $a->getFechaFin()->format('Y'));
            }            
            sort($data['years']);
       }
       
       $data['cols'] = (count($data['years']) >= 3)? 3:count($data['years']);
       
       return $this->render('SisproBundle:Ejecucion:revisarProyecto.html.twig',
                      array('data' => $data));       
    }  
    
    /*
     * ANÁLISIS DE PERMISOLOGÍA
     */
    private function _revisarPermisologia($data)
    {           
       $proyecto = $data['proyecto'];       
       
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