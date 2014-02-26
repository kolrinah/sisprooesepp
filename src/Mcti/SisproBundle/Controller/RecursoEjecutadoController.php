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
use Mcti\SisproBundle\Entity\RecursoEjecutado;
use Symfony\Component\HttpFoundation\Request;

class RecursoEjecutadoController extends Controller
{
    /*
     * GET  Prepara Formulario para Registrar Recurso Ejecutado
     * POST Guarda el Registro
     */
    public function registrarRecursoEjecutadoAction(Request $request)
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
           
           if ($this->_testFormRecursoEjecutado($data))
           {  
               $error='';
               $this->_registrarRecursoEjecutado($data);
               
               $panel = $this->_panelRecursoEjecutado($data);
         
               $badge = $this->getDoctrine()
                             ->getRepository('SisproBundle:Proyecto')
                             ->getRecursosEjecutadosProyecto($data['proyecto']);
               
               // REGISTRA LA ACCION EN LA BITACORA
               $data['registro']='Registro de Ejecución de Recursos en el proyecto: '.
                                  $proyecto->getCodigo().' - '.$proyecto->getNombre().            
                                  '. Realizado por: '.$this->getUser()->__toString(); 
               $data['entidad']='RecursoEjecutado';
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
       
       return $this->render('SisproBundle:Ejecucion:registrarRecursoEjecutado.html.twig',       
                      array('data' => $data));              
    }
    
   /*
    * GET Prepara el Formulario para editar el Recurso Ejecutado
    * POST Actualiza los cambios en el Recurso
    */
    public function editarRecursoEjecutadoAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {        
           $idRecurso = $request->query->get('id');           
           
           $recurso = $this->getDoctrine()
                           ->getRepository('SisproBundle:RecursoEjecutado')
                           ->find($idRecurso);
           if (!$recurso) die(json_encode(array('error'=>'Recurso no encontrado.')));
           
           $data['recurso'] = $recurso;
           $data['idProyecto'] = $recurso->getActividad()
                                         ->getObjetivoEspecifico()
                                         ->getProyecto()->getId();
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           
           $recurso = $this->getDoctrine()
                           ->getRepository('SisproBundle:RecursoEjecutado')
                           ->find($data['idRecurso']);
           
           if (!$recurso) die(json_encode(array('error'=>'Recurso no encontrado.')));
           
           $data['recurso'] = $data;
           
           if ($this->_testFormRecursoEjecutado($data))
           {  
             $data['recurso'] = $recurso; 
             $proyecto = $recurso->getActividad()->getObjetivoEspecifico()->getProyecto();
             $data['proyecto'] = $proyecto;
             
             $error='';
             $this->_actualizarRecursoEjecutado($data);
                      
             //Información para el registro en bitácora
             $data['registro'] ="Actualización de Recurso Ejecutado id: ".$recurso->getId().
                    ", en el Proyecto: ".$proyecto->getCodigo()." - ".$proyecto->getNombre().
                    ". Realizado por: ".$this->getUser()->__toString().".";
       
             $data['entidad']="RecursoEjecutado";
             $data['operacion']="UPDATE";             
             $this->_registrarBitacora($data);
             
             die(json_encode(array('error'=>$error,                                   
                                   'panel' => $this->_panelRecursoEjecutado($data) )));
           }
       } 
       // Hidratamos los campos del formulario
       $data = $this->_hidratarForm($data);
       
       return $this->render('SisproBundle:Ejecucion:editarRecursoEjecutado.html.twig',      
                      array('data' => $data));       
    }
    
    /*
     * ELIMINA REGISTRO DE RECURSO EJECUTADO
     */
    public function eliminarRecursoEjecutadoAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }       
       
       $idRecurso = $request->request->get('id');  
       
       $em = $this->getDoctrine()->getManager();
       
       $recurso = $em->getRepository('SisproBundle:RecursoEjecutado')
                     ->find($idRecurso);
    
       if (!$recurso) die(json_encode(array('error'=>'Registro no encontrado')));
       
       $proyecto = $recurso->getActividad()->getObjetivoEspecifico()->getProyecto();
       $data['proyecto'] = $proyecto;
       //Información para el registro en bitácora
       $data['registro'] ="Eliminación de Recurso Ejecutado id: $idRecurso, en el Proyecto: ".
                          $proyecto->getCodigo()." - ".$proyecto->getNombre().
                          ". Por ".$recurso->getMoneda()->getSimbolo()." ".$recurso->getMonto()." (".
                          $recurso->getMoneda()->getIso()."). Realizado por: ".
                          $this->getUser()->__toString().".";
       
       $data['entidad']="RecursoEjecutado";
       $data['operacion']="DELETE";
       
       $error='';
       $em->remove($recurso);            
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
       $badge = $this->getDoctrine()
                     ->getRepository('SisproBundle:Proyecto')
                     ->getRecursosEjecutadosProyecto($data['proyecto']); 
       
       $this->_registrarBitacora($data);
       
       die(json_encode(array('error'=>$error,
                             'badge'=>count($badge),
                             'panel'=>$this->_panelRecursoEjecutado($data))) );
    }     
    
    /*
     * ACTUALIZA REGISTRO DE RECURSO EJECUTADO
     */
    private function _actualizarRecursoEjecutado($data)
    {
        $recurso = $data['recurso'];        
        
        $moneda = $this->getDoctrine()
                       ->getRepository('SisproBundle:Moneda')
                       ->find($data['moneda']);
        if (!$moneda) die(json_encode(array('error'=>'Moneda no encontrada.')));  
        
        $actividad = $this->getDoctrine()
                          ->getRepository('SisproBundle:Actividad')
                          ->find($data['actividad']);         
        if (!$actividad) die(json_encode(array('error'=>'Actividad no encontrada.')));         
        
        $recurso->setMoneda($moneda);       
        $recurso->setActividad($actividad);               
        $recurso->setMonto(str_ireplace(",",".",str_ireplace(".","",trim($data['monto']))));
        $recurso->setObservaciones(trim($data['observaciones']));        
        $recurso->setFecha(\DateTime::createFromFormat('d/m/Y',trim($data['fecha'])));        
        
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
       // BUSCAMOS LOS TIPOS DE MONEDAS DISPONIBLES       
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery('SELECT m
                                  FROM SisproBundle:Moneda m                             
                                  ORDER BY m.moneda');
       $data['monedas'] = $query->getResult();
       
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
     * Actualiza Panel de Recursos Ejecutado
     */
    private function _panelRecursoEjecutado($data)
    {        
       $data['recursoEjecutado'] = $this->getDoctrine()
                                        ->getRepository('SisproBundle:Proyecto')
                                        ->getRecursosEjecutadosProyecto($data['proyecto']);
       
       return $this->renderView('SisproBundle:Ejecucion:recursosEjecutados.html.twig',
                      array('data' => $data));
    }
    
    /*
     * Guardamos el Registro de Recursos Ejecutados
     */
    private function _registrarRecursoEjecutado($data)
    {             
        $recurso = new RecursoEjecutado();
        $moneda = $this->getDoctrine()
                       ->getRepository('SisproBundle:Moneda')
                       ->find($data['moneda']);        
        if (!$moneda) die(json_encode(array('error'=>'Moneda no encontrada.')));   
        
        $actividad = $this->getDoctrine()
                       ->getRepository('SisproBundle:Actividad')
                       ->find($data['actividad']);
        if (!$actividad) die(json_encode(array('error'=>'Actividad no encontrada.')));
        
        $recurso->setMoneda($moneda);
        $recurso->setActividad($actividad);               
        $recurso->setMonto(str_ireplace(",",".",str_ireplace(".","",trim($data['monto']))));
        $recurso->setObservaciones(trim($data['observaciones']));                
        $recurso->setFecha(\DateTime::createFromFormat('d/m/Y',trim($data['fecha'])));        
        
        $em = $this->getDoctrine()->getManager();       
        $em->persist($recurso);
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
     * Validación de campos de recursos Ejecutados
     */
    private function _testFormRecursoEjecutado($data)
    {
        $test = true;
        
        if(intval(trim($data['moneda'])) == 0 ) $test = false;        
        if(intval(trim($data['actividad'])) == 0 ) $test = false;        
        if(intval(trim($data['monto'])) == 0 ) $test = false;        
        if(trim($data['observaciones']) == '' ) $test = false;                
        if(trim($data['fecha']) == '' ) $test = false; 
        
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