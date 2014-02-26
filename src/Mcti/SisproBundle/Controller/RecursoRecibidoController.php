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
use Mcti\SisproBundle\Entity\RecursoRecibido;
use Symfony\Component\HttpFoundation\Request;

class RecursoRecibidoController extends Controller
{
    /*
     * GET  Prepara Formulario para Registrar Recurso Recibido
     * POST Guarda el Registro
     */
    public function registrarRecursoRecibidoAction(Request $request)
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
           
           if ($this->_testFormRecursoRecibido($data))
           {  
               $error='';
               $this->_registrarRecursoRecibido($data);
               
               $panel = $this->_panelRecursoRecibido($data);
               $badge = $this->getDoctrine()
                             ->getRepository('SisproBundle:Proyecto')
                             ->getRecursosRecibidosProyecto($data['proyecto']);
         
               // REGISTRA LA ACCION EN LA BITACORA
               $data['registro']='Registro de Recepción de Recursos en el proyecto: '.
                                  $proyecto->getCodigo().' - '.$proyecto->getNombre().            
                                  '. Realizado por: '.
                                  $this->getUser()->__toString().
                                  ' ('.$this->getUser()->getCorreo().')'; 
               $data['entidad']='RecursoRecibido';
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
       
       return $this->render('SisproBundle:Ejecucion:registrarRecursoRecibido.html.twig',
                      array('data' => $data));              
    }
    
   /*
    * GET Prepara el Formulario para editar el Recurso Recibido
    * POST Actualiza los cambios en el Recurso
    */
    public function editarRecursoRecibidoAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {        
           $idRecurso = $request->query->get('id');           
           
           $recurso = $this->getDoctrine()
                           ->getRepository('SisproBundle:RecursoRecibido')
                           ->find($idRecurso);
           if (!$recurso) die(json_encode(array('error'=>'Recurso no encontrado.')));
           
           $data['recurso'] = $recurso;
           $data['idProyecto'] = $recurso->getProyecto()->getId();
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           
           $recurso = $this->getDoctrine()
                           ->getRepository('SisproBundle:RecursoRecibido')
                           ->find($data['idRecurso']);
           
           if (!$recurso) die(json_encode(array('error'=>'Recurso no encontrado.')));
           
           $data['recurso'] = $data;
           
           if ($this->_testFormRecursoRecibido($data))
           {  
             $data['recurso'] = $recurso; 
             $proyecto = $recurso->getProyecto();
             $data['proyecto'] = $proyecto;
             
             $error='';
             $this->_actualizarRecursoRecibido($data);
                      
             //Información para el registro en bitácora
             $data['registro'] ="Actualización de Recurso Recibido id: ".$recurso->getId().
                    ", en el Proyecto: ".$proyecto->getCodigo()." - ".$proyecto->getNombre().
                    ". Realizado por: ".$this->getUser()->__toString().".";
       
             $data['entidad']="RecursoRecibido";
             $data['operacion']="UPDATE";             
             $this->_registrarBitacora($data);
             
             die(json_encode(array('error'=>$error,                                   
                                   'panel' => $this->_panelRecursoRecibido($data) )));
           }
       }                    
       
       // Hidratamos los campos del formulario
       $data = $this->_hidratarForm($data);
       
       return $this->render('SisproBundle:Ejecucion:editarRecursoRecibido.html.twig',      
                      array('data' => $data));       
    }
    
    /*
     * ELIMINA REGISTRO DE RECURSO RECIBIDO
     */
    public function eliminarRecursoRecibidoAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }       
       
       $idRecurso = $request->request->get('id');  
       
       $em = $this->getDoctrine()->getManager();
       
       $recurso = $em->getRepository('SisproBundle:RecursoRecibido')
                     ->find($idRecurso);
    
       if (!$recurso) die(json_encode(array('error'=>'Registro no encontrado')));
       
       $data['proyecto'] = $recurso->getProyecto();
               
        //Información para el registro en bitácora
        $data['registro'] ="Eliminación de Recurso Recibido id: $idRecurso, en el Proyecto: ".
                          $recurso->getProyecto()->getCodigo()." - ".$recurso->getProyecto()->getNombre().
                          ". Por ".$recurso->getMoneda()->getSimbolo()." ".$recurso->getMonto()." (".
                          $recurso->getMoneda()->getIso().") provenientes de: ".
                          $recurso->getFuenteFinanciamiento()->getFuente().". Realizado por: ".
                          $this->getUser()->__toString().".";
       
       $data['entidad']="RecursoRecibido";
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
                     ->getRecursosRecibidosProyecto($data['proyecto']);
       
       $this->_registrarBitacora($data);
       
       die(json_encode(array('error'=>$error,
                             'badge'=>count($badge),
                             'panel'=>$this->_panelRecursoRecibido($data))) );
    }     
    
    /*
     * ACTUALIZA REGISTRO DE RECURSO RECIBIDO
     */
    private function _actualizarRecursoRecibido($data)
    {
        $recurso = $data['recurso'];        
        
        $moneda = $this->getDoctrine()
                       ->getRepository('SisproBundle:Moneda')
                       ->find($data['moneda']);
        if (!$moneda) die(json_encode(array('error'=>'Moneda no encontrada.')));
        
        $fuente = $this->getDoctrine()
                       ->getRepository('SisproBundle:FuenteFinanciamiento')
                       ->find($data['fuente']);         
        if (!$fuente) die(json_encode(array('error'=>'Fuente no encontrada.')));         
        
        $recurso->setMoneda($moneda);       
        $recurso->setFuenteFinanciamiento($fuente);               
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
       
       // BUSCAMOS LOS TIPOS DE FUENTES DE FINANCIAMIENTO
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery('SELECT f
                                  FROM SisproBundle:FuenteFinanciamiento f                             
                                  ORDER BY f.fuente');
       $data['fuentes'] = $query->getResult();     
       
       return $data;
    }

    /*
     * Actualiza Panel de Recursos Recibidos
     */
    private function _panelRecursoRecibido($data)
    {        
       $data['recursoRecibido'] = $this->getDoctrine()
                                       ->getRepository('SisproBundle:Proyecto')
                                       ->getRecursosRecibidosProyecto($data['proyecto']);
       
       return $this->renderView('SisproBundle:Ejecucion:recursosRecibidos.html.twig',
                      array('data' => $data));
    }
    
    /*
     * Guardamos el Registro de Recursos Recibidos
     */
    private function _registrarRecursoRecibido($data)
    {             
        $recurso = new RecursoRecibido();
        $moneda = $this->getDoctrine()
                       ->getRepository('SisproBundle:Moneda')
                       ->find($data['moneda']);        
        if (!$moneda) die(json_encode(array('error'=>'Moneda no encontrada.')));   
        
        $fuente = $this->getDoctrine()
                       ->getRepository('SisproBundle:FuenteFinanciamiento')
                       ->find($data['fuente']);        
        if (!$fuente) die(json_encode(array('error'=>'Fuente de Financiamiento no encontrada.')));
        
        $recurso->setMoneda($moneda);
        $recurso->setFuenteFinanciamiento($fuente);
        $recurso->setProyecto($data['proyecto']);                
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
            
        // ACTUALIZAMOS EL ESTATUS DEL PROYECTO
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
     * Validación de campos de recursos recibidos
     */
    private function _testFormRecursoRecibido($data)
    {
        $test = true;
        
        if(intval(trim($data['moneda'])) == 0 ) $test = false;        
        if(intval(trim($data['fuente'])) == 0 ) $test = false;        
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