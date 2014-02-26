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
use Mcti\SisproBundle\Entity\Bitacora;
use Mcti\SisproBundle\Entity\MetaAlcanzada;
use Symfony\Component\HttpFoundation\Request;

class MetaAlcanzadaController extends Controller
{
    /*
     * GET  Prepara Formulario para Registrar Meta Alcanzada
     * POST Guarda el Registro
     */
    public function registrarMetaAlcanzadaAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {          
           $idProyecto = $request->query->get('id');           
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           
           $proyecto = $this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->find($data['idProyecto']);           
           if (!$proyecto) die(json_encode(array('error'=>'Proyecto no encontrado.')));
           
           $data['proyecto']=$proyecto;
           
           if ($this->_testFormMetaAlcanzada($data))
           {  
               $error='';
               $this->_registrarMetaAlcanzada($data);
               
               $panel = $this->_panelMetaAlcanzada($data);
               $badge = $this->getDoctrine()
                             ->getRepository('SisproBundle:Proyecto')
                             ->getMetasAlcanzadasProyecto($data['proyecto']);
         
               // REGISTRA LA ACCION EN LA BITACORA
               $data['registro']='Registro de Ejecución de Metas en el proyecto: '.
                                  $proyecto->getCodigo().' - '.$proyecto->getNombre().            
                                  '. Realizado por: '.$this->getUser()->__toString(); 
               $data['entidad']='MetaAlcanzada';
               $data['operacion']='INSERT';               
               
               $this->_registrarBitacora($data);
               die(json_encode(array('error'=>$error,
                                     'badge'=>count($badge),
                                     'panel'=>$panel)));
           }
       }       
       //die(json_encode(array('error'=>'')));
       
       $data['idProyecto'] = $idProyecto;
       
       // Hidratamos los campos del formulario
       $data = $this->_hidratarForm($data);
       
       return $this->render('SisproBundle:Ejecucion:registrarMetaAlcanzada.html.twig',       
                      array('data' => $data));              
    }
    
   /*
    * GET Prepara el Formulario para editar el Meta Alcanzada
    * POST Actualiza los cambios en el Registro
    */
    public function editarMetaAlcanzadaAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {        
           $idRegistro = $request->query->get('id');           
           
           $registro = $this->getDoctrine()
                            ->getRepository('SisproBundle:MetaAlcanzada')
                            ->find($idRegistro);
           if (!$registro) die(json_encode(array('error'=>'Registro no encontrado.')));
           
           $data['registro'] = $registro;
           $data['idProyecto'] = $registro->getActividad()
                                          ->getObjetivoEspecifico()
                                          ->getProyecto()->getId();
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           
           $registro = $this->getDoctrine()
                            ->getRepository('SisproBundle:MetaAlcanzada')
                            ->find($data['idRegistro']);
           
           if (!$registro) die(json_encode(array('error'=>'Registro no encontrado.')));
           
           $data['registro'] = $data;
           
           if ($this->_testFormMetaAlcanzada($data))
           {  
             $data['registro'] = $registro; 
             $proyecto = $registro->getActividad()->getObjetivoEspecifico()->getProyecto();
             $data['proyecto'] = $proyecto;
             
             $error='';
             $this->_actualizarMetaAlcanzada($data);
                      
             //Información para el registro en bitácora
             $data['registro'] ="Actualización de Meta Alcanzada id: ".$registro->getId().
                    ", en el Proyecto: ".$proyecto->getCodigo()." - ".$proyecto->getNombre().
                    ". Realizado por: ".$this->getUser()->__toString().".";
       
             $data['entidad']="MetaAlcanzada";
             $data['operacion']="UPDATE";             
             $this->_registrarBitacora($data);
             
             die(json_encode(array('error'=>$error,                                   
                                   'panel' => $this->_panelMetaAlcanzada($data) )));
           }
       } 
       // Hidratamos los campos del formulario
       $data = $this->_hidratarForm($data);
       
       return $this->render('SisproBundle:Ejecucion:editarMetaAlcanzada.html.twig',      
                      array('data' => $data));       
    }
    
    /*
     * ELIMINA REGISTRO DE META ALCANZADA
     */
    public function eliminarMetaAlcanzadaAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }       
       
       $idRegistro = $request->request->get('id');  
       
       $em = $this->getDoctrine()->getManager();
       
       $registro = $em->getRepository('SisproBundle:MetaAlcanzada')
                     ->find($idRegistro);
    
       if (!$registro) die(json_encode(array('error'=>'Registro no encontrado')));
       
       $proyecto = $registro->getActividad()->getObjetivoEspecifico()->getProyecto();
       $data['proyecto'] = $proyecto;
       //Información para el registro en bitácora
       $data['registro'] ="Eliminación de Meta Alcanzada id: $idRegistro, en el Proyecto: ".
                          $proyecto->getCodigo()." - ".$proyecto->getNombre().
                          "). Realizado por: ".$this->getUser()->__toString().".";
       
       $data['entidad']="MetaAlcanzada";
       $data['operacion']="DELETE";
       
       $error='';
       $em->remove($registro);            
       try {       
             $em->flush();
           } catch (\Exception $e) { // Atrapa Error del servidor
               if(stristr($e->getMessage(), 'Foreign key violation'))
               {
                   $error= 'No se pudo eliminar el Registro.';                  
               }else
               {
                   $error = $e->getMessage();
               }
            die(json_encode(array('error'=>$error)));   
       } 
       // Logró ser eliminado
        
       $this->_registrarBitacora($data);
       
       $badge = $this->getDoctrine()
                     ->getRepository('SisproBundle:Proyecto')
                     ->getMetasAlcanzadasProyecto($data['proyecto']);
       
       die(json_encode(array('error'=>$error, 
                             'badge'=> count($badge),
                             'panel'=>$this->_panelMetaAlcanzada($data))) );
    }    
    
    /*
     * CULMINAR PROYECTO
     */
    public function culminarProyectoAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }       
       
       $idProyecto = $request->request->get('id');  
       
       $em = $this->getDoctrine()->getManager();
       
       $proyecto = $em->getRepository('SisproBundle:Proyecto')
                      ->find($idProyecto);
    
       if (!$proyecto) die(json_encode(array('error'=>'Proyecto no encontrado')));
     
       $data['proyecto'] = $proyecto;
              
        // ACTUALIZAMOS EL ESTATUS DEL PROYECTO A 'CULMINADO'        
        $estatus = $this->getDoctrine()
                        ->getRepository('SisproBundle:Estatus')
                        ->find(6);// Proyecto culminado
        if (!$estatus) die(json_encode(array('error'=>'Estatus no encontrado.')));
        
        $proyecto->setEstatus($estatus);
        
        $error='';
        $em = $this->getDoctrine()->getManager();       
        try {
              $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor                
                die(json_encode(array('error'=>$error)));
            }        
       $data['proyecto'] = $proyecto;
       
       //Información para el registro en bitácora
       $data['registro'] ="Culminación del Proyecto id: $idProyecto. ".
                          $proyecto->getCodigo()." - ".$proyecto->getNombre().
                          ". Realizado por: ".$this->getUser()->__toString().".";       
       $data['entidad']="Proyecto";
       $data['operacion']="UPDATE";      
        
       $this->_registrarBitacora($data);
       
       die(json_encode(array('error'=>$error,
                             'panel'=>$this->_panelMetaAlcanzada($data))) );
    }         
    
    /*
     * ACTUALIZA REGISTRO DE RECURSO EJECUTADO
     */
    private function _actualizarMetaAlcanzada($data)
    {
        $registro = $data['registro'];        
        
        $actividad = $this->getDoctrine()
                          ->getRepository('SisproBundle:Actividad')
                          ->find($data['actividad']);         
        if (!$actividad) die(json_encode(array('error'=>'Actividad no encontrada.')));         
                       
        $registro->setActividad($actividad);               
        $registro->setMeta(str_ireplace(",",".",str_ireplace(".","",trim($data['meta']))));
        $registro->setObservaciones(trim($data['observaciones']));        
        
        $em = $this->getDoctrine()->getManager();
        try {
              $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor
                if(stristr($e->getMessage(), 'Not null violation')) 
                {
                  $error ='No ha llenado todos los campos.';
                }else  $error=$e->getMessage();
                
                die(json_encode(array('error'=>$error)));
            }               
        return true;        
    }
    
    /*
     * Hidratamos los campos del formulario
     */
    private function _hidratarForm($data)
    {       
       // BUSCAMOS LAS ACTIVIDADES Y SUS OBJETIVOS ESPECIFICOS
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery('SELECT p, oe, a
                                  FROM SisproBundle:Actividad a
                                  JOIN a.objetivoEspecifico oe
                                  JOIN oe.proyecto p
                                  WHERE p.id = :proy
                                  ORDER BY oe.codigo, a.codigo')
                   ->setParameter('proy', $data['idProyecto'] );
       $data['actividades'] = $query->getResult();     
       
       return $data;
    }

    /*
     * Actualiza Panel de Metas Alcanzadas
     */
    private function _panelMetaAlcanzada($data)
    {        
       $data['metaAlcanzada'] = $this->getDoctrine()
                                     ->getRepository('SisproBundle:Proyecto')
                                     ->getMetasAlcanzadasProyecto($data['proyecto']);
       
       return $this->renderView('SisproBundle:Ejecucion:metasAlcanzadas.html.twig',
                      array('data' => $data));
    }
    
    /*
     * Guardamos el Registro de Metas Alcanzadas
     */
    private function _registrarMetaAlcanzada($data)
    {             
        $registro = new MetaAlcanzada();
        
        $actividad = $this->getDoctrine()
                          ->getRepository('SisproBundle:Actividad')
                          ->find($data['actividad']);
        if (!$actividad) die(json_encode(array('error'=>'Actividad no encontrada.')));
                
        $registro->setActividad($actividad);               
        $registro->setMeta(str_ireplace(",",".",str_ireplace(".","",trim($data['meta']))));
        $registro->setObservaciones(trim($data['observaciones']));                
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

        // ACTUALIZAMOS EL ESTATUS DEL PROYECTO A 'EN EJECUCIÓN'
        $proyecto = $data['proyecto'];
        $estatus = $this->getDoctrine()
                        ->getRepository('SisproBundle:Estatus')
                        ->find(3);// Proyecto en ejecución
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
    
    /*
     * Validación de campos de Metas Alcanzadas
     */
    private function _testFormMetaAlcanzada($data)
    {
        $test = true;
        
        if(intval(trim($data['meta'])) == 0 ) $test = false;        
        if(intval(trim($data['actividad'])) == 0 ) $test = false;        
        if(trim($data['observaciones']) == '' ) $test = false;   
        return $test;
    }      
    
    // REGISTRAMOS EN BITACORA
    private function _registrarBitacora($data)
    {
       $bitacora = new Bitacora();                 
             
       $bitacora->setRegistro($data['registro']);
       $bitacora->setEntidad($data['entidad']);
       $bitacora->setAccion($data['operacion']);
       $bitacora->setUsuario($this->getUser());
       //$bitacora->setIp($this->get('request')->getClientIp());
       $bitacora->setIp($_SERVER['REMOTE_ADDR']);
       $bitacora->setUserAgent($this->getRequest()->headers->get('user-agent'));
       $bitacora->setFecha(new \DateTime('now'));
                    
       $em = $this->getDoctrine()->getManager();
       $em->persist($bitacora);            
             try {       
                   $em->flush();
             } catch (\Exception $e) { // Atrapa Error del servidor
               $error='Ocurrió un error al registrar en la Bitácora.'.$e->getMessage();
               die(json_encode(array('error'=>$error)));
             }
       return true;
    }  
}