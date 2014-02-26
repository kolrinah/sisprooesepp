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
use Mcti\SisproBundle\Entity\RegistroProblema;
use Symfony\Component\HttpFoundation\Request;

class RegistroProblemaController extends Controller
{
    /*
     * GET  Prepara Formulario para Registrar Registro de Problema
     * POST Guarda el Registro
     */
    public function registrarRegistroProblemaAction(Request $request)
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
           
           if ($this->_testFormRegistroProblema($data))
           {  
               $error='';
               $this->_registrarRegistroProblema($data);
               
               $panel = $this->_panelRegistroProblema($data);
               $badge = $this->getDoctrine()
                             ->getRepository('SisproBundle:Proyecto')
                             ->getRegistrosProblemasProyecto($data['proyecto']);
         
               // REGISTRA LA ACCION EN LA BITACORA
               $data['registro']='Registro de Problema en el proyecto: '.
                                  $proyecto->getCodigo().' - '.$proyecto->getNombre().            
                                  '. Realizado por: '.
                                  $this->getUser()->__toString().
                                  ' ('.$this->getUser()->getCorreo().')'; 
               $data['entidad']='RegistroProblema';
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
       
       return $this->render('SisproBundle:Ejecucion:registrarRegistroProblema.html.twig',
                      array('data' => $data));              
    }
    
   /*
    * GET Prepara el Formulario para editar el Registro de Problema
    * POST Actualiza los cambios en el Registro
    */
    public function editarRegistroProblemaAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {        
           $idRegistro = $request->query->get('id');           
           
           $registro = $this->getDoctrine()
                            ->getRepository('SisproBundle:RegistroProblema')
                            ->find($idRegistro);
           if (!$registro) die(json_encode(array('error'=>'Registro no encontrado.')));
           
           $data['registro'] = $registro;
           $data['idProyecto'] = $registro->getProyecto()->getId();
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           
           $registro = $this->getDoctrine()
                            ->getRepository('SisproBundle:RegistroProblema')
                            ->find($data['idRegistro']);
           
           if (!$registro) die(json_encode(array('error'=>'Registro no encontrado.')));
           
           $data['registro'] = $data;
           
           if ($this->_testFormRegistroProblema($data))
           {  
             $data['registro'] = $registro; 
             $proyecto = $registro->getProyecto();
             $data['proyecto'] = $proyecto;
             
             $error='';
             $this->_actualizarRegistroProblema($data);
                      
             //Información para el registro en bitácora
             $data['registro'] ="Actualización de Registro de Problema id: ".$registro->getId().
                    ", en el Proyecto: ".$proyecto->getCodigo()." - ".$proyecto->getNombre().
                    ". Realizado por: ".$this->getUser()->__toString().".";
       
             $data['entidad']="RegistroProblema";
             $data['operacion']="UPDATE";             
             $this->_registrarBitacora($data);
             
             die(json_encode(array('error'=>$error,                                   
                                   'panel' => $this->_panelRegistroProblema($data) )));
           }
       }                    
       
       // Hidratamos los campos del formulario
       $data = $this->_hidratarForm($data);
       
       return $this->render('SisproBundle:Ejecucion:editarRegistroProblema.html.twig',      
                      array('data' => $data));       
    }
    
    /*
     * ELIMINA REGISTRO DE PROBLEMA
     */
    public function eliminarRegistroProblemaAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }       
       
       $idRegistro = $request->request->get('id');  
       
       $em = $this->getDoctrine()->getManager();
       
       $registro = $em->getRepository('SisproBundle:RegistroProblema')
                      ->find($idRegistro);    
       if (!$registro) die(json_encode(array('error'=>'Registro no encontrado')));
       
       $data['proyecto'] = $registro->getProyecto();
               
        //Información para el registro en bitácora
        $data['registro'] ="Eliminación de Registro de Problema id: $idRegistro, en el Proyecto: ".
                          $registro->getProyecto()->getCodigo()." - ".
                          $registro->getProyecto()->getNombre().". Problema: '".
                          $registro->getObservaciones().
                          "'. Realizado por: ".$this->getUser()->__toString().".";
       
       $data['entidad']="RegistroProblema";
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
       
       $badge = $this->getDoctrine()
                     ->getRepository('SisproBundle:Proyecto')
                     ->getRegistrosProblemasProyecto($data['proyecto']);
       
       $this->_registrarBitacora($data);
       
       die(json_encode(array('error'=>$error,
                             'badge'=>count($badge),
                             'panel'=>$this->_panelRegistroProblema($data))) );
    }     
    
    /*
     * ACTUALIZA REGISTRO DE PROBLEMA
     */
    private function _actualizarRegistroProblema($data)
    {
        $registro = $data['registro'];                
        
        $problema = $this->getDoctrine()
                         ->getRepository('SisproBundle:TipoProblema')
                         ->find($data['problema']);         
        if (!$problema) die(json_encode(array('error'=>'Tipo de Problema no encontrado.')));         
                
        $registro->setTipoProblema($problema);        
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
       // BUSCAMOS LOS TIPOS DE PROBLEMAS
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery('SELECT tp
                                  FROM SisproBundle:TipoProblema tp                             
                                  ORDER BY tp.problema');
       $data['problemas'] = $query->getResult();     
       
       return $data;
    }

    /*
     * Actualiza Panel de Registros Recibidos
     */
    private function _panelRegistroProblema($data)
    {        
       $data['registroProblema'] = $this->getDoctrine()
                                        ->getRepository('SisproBundle:Proyecto')
                                        ->getRegistrosProblemasProyecto($data['proyecto']);
       
       return $this->renderView('SisproBundle:Ejecucion:registrosProblemas.html.twig',
                      array('data' => $data));
    }
    
    /*
     * Guardamos el Registro de Registros Recibidos
     */
    private function _registrarRegistroProblema($data)
    {             
        $registro = new RegistroProblema();
                
        $problema = $this->getDoctrine()
                         ->getRepository('SisproBundle:TipoProblema')
                         ->find($data['problema']);        
        if (!$problema) die(json_encode(array('error'=>'Tipo de Problema no encontrado.')));
                
        $registro->setTipoProblema($problema);
        $registro->setProyecto($data['proyecto']);                        
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

        // ACTUALIZAMOS EL ESTATUS DEL PROYECTO A 'PARALIZADO'
        $proyecto = $data['proyecto'];
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
    
    /*
     * Validación de campos de formulario
     */
    private function _testFormRegistroProblema($data)
    {
        $test = true;
                
        if(intval(trim($data['problema'])) == 0 ) $test = false;        
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