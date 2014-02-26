<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: AGOSTO DE 2013                                             *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Mcti\SisproBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mcti\SisproBundle\Entity\Proyecto;
use Mcti\SisproBundle\Entity\Bitacora;
use Mcti\SisproBundle\Entity\Coordenadas;
use Mcti\SisproBundle\Entity\ProyectoMarco;
use Mcti\SisproBundle\Entity\ProyectoFuenteFinanciamiento;
use Mcti\SisproBundle\Entity\ObjetivoEspecifico;
use Mcti\SisproBundle\Entity\ObjetivoEspecificoOrg;
use Mcti\SisproBundle\Entity\Actividad;
use Mcti\SisproBundle\Entity\ActividadOrg;
use Mcti\SisproBundle\Form\ProyectoType;
use Symfony\Component\HttpFoundation\Request;

class ProyectosController extends Controller
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
      
      // Buscamos los Objetos Proyecto de las estructuras inferiores
      $proyectos = $this->getDoctrine()
                        ->getRepository('SisproBundle:Proyecto')
                        ->getProyectosUnidadesInferiores($this->getUser()->getEstructura());
            
      return $this->render('SisproBundle:Proyectos:listar.html.twig', 
                                       array('proyectos'=>$proyectos));     
    }
    
    /*
     * PREPARA FORMULARIO PARA REGISTRAR PROYECTO
     */
    public function nuevoProyectoAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       $proyecto = new Proyecto();
       
       $formulario = $this->createForm(new ProyectoType(), $proyecto);
       
       if ($request->isMethod('POST')) 
       {            
           $paquete = $request->request->get('proyecto');
           $data = $request->request->get('data');           

           $formulario->bind($request); 
          
           if ($formulario->isValid())
           {       
             // Buscamos la Parroquia            
             $parroquia = $this->getDoctrine()
                               ->getRepository('SisproBundle:Parroquia')
                               ->find($data['idParroquia']);  
             // Buscamos el Poblado
             $poblado = $this->getDoctrine()
                             ->getRepository('SisproBundle:Poblado')
                             ->find($data['idPoblado']);
            
             // Buscamos el estatus
             $estatus = $this->getDoctrine()
                             ->getRepository('SisproBundle:Estatus')
                             ->find(1); 
                         
             $proyecto->setEstatus($estatus);
             $proyecto->setParroquia($parroquia);
             $proyecto->setPoblado($poblado);
             $proyecto->setUsuario($this->getUser());
             $proyecto->setEstructura($this->getUser()->getEstructura());
             $proyecto->setMeta(0);
             $proyecto->setPobFemenina(0);
             $proyecto->setPobMasculina(0);
             $proyecto->setPobTotal(0);
             $proyecto->setEmpleosDirectosEjecucion(0);
             $proyecto->setEmpleosIndirectosEjecucion(0);
             $proyecto->setEmpleosDirectosOperacion(0);
             $proyecto->setEmpleosIndirectosOperacion(0);
             $proyecto->setNacional(false);             
             $proyecto->setFechaRegistro(new \DateTime('now'));
             
             // CODIGO AUTOGENERADO
             $ente = $this->getUser()->getEstructura()->getSiglas2();
             $estado = $parroquia->getMunicipio()->getEstado()->getSigla();             
             $ahora = getdate(time());
             $year= substr($ahora['year'],-2);
             $fecha1 = new \DateTime($ahora['year'].'-1-1');
             $fecha2 = new \DateTime($ahora['year'].'-12-31');
             
             $em = $this->getDoctrine()->getManager();
             $query = $em->createQuery('SELECT MAX(p.codigo)
                                        FROM SisproBundle:Proyecto p                                                              
                                        JOIN p.estructura e
                                        JOIN p.parroquia q
                                        JOIN q.municipio m
                                        JOIN m.estado s                                        
                                        WHERE p.estructura = :estructura
                                        AND m.estado = :estado
                                        AND p.fechaRegistro >= :fecha1
                                        AND p.fechaRegistro <= :fecha2')
                         ->setParameter('estructura', $this->getUser()->getEstructura())
                         ->setParameter('estado', $parroquia->getMunicipio()->getEstado())
                         ->setParameter('fecha1', $fecha1 )
                         ->setParameter('fecha2', $fecha2 );

             $codigoActual=$query->getResult(\Doctrine\ORM\Query::HYDRATE_SINGLE_SCALAR);             
             
             $codigoActual=($codigoActual == null)? array(0, 0, 0):split('-', $codigoActual);
             
             $nro = (intval($codigoActual[2])+1); 
             
             if ($nro > 999 or $nro < 1)
                die (json_encode(array('error'=>'HA SUPERADO EL LÍMITE DE PROYECTOS (999)')));
             elseif ($nro > 99) $nro;
             elseif ($nro > 9) $nro = '0'.$nro;
             else $nro = '00'.$nro;
             
             $codigo= $ente.'-'.$estado.$year.'-'.$nro;
                     
             $proyecto->setCodigo($codigo);
            
             $error='';
             
             $em = $this->getDoctrine()->getManager();
             $em->persist($proyecto);
             try {       
                 $em->flush();
             } catch (\Exception $e) { // Atrapa Error del servidor
                 if(stristr($e->getMessage(), 'Not null violation')) 
                 {
                    $error='Debe llenar todos los campos correctamente';
                 }
                 else if(stristr($e->getMessage(), 'Unique violation')) 
                 {
                    $error='Proyecto Duplicado en el Servidor';
                 }
                 else $error = $e->getMessage();
                 
                 die (json_encode(array('error'=>$error)));
             }
             
             $data['proyecto'] = $proyecto;
             
             if (!$this->_setCoordenadas($data)) 
                 die (json_encode(array('error'=>'Error Registrando Coordenadas')));
             
             // GUARDA LA ACCION EN LA BITACORA;
             $bitacora= new Bitacora();
             $registro='Registro de Proyecto: '.$proyecto->getCodigo().' - '.
                     $proyecto->getNombre().'. Realizado por: ';
             $registro.=$this->getUser()->__toString(). '('.$this->getUser()->getCorreo().')';
            
             $bitacora->setRegistro($registro);
             $bitacora->setEntidad('Proyecto');
             $bitacora->setAccion('INSERT');
             $bitacora->setUsuario($this->getUser());
             $bitacora->setIp($_SERVER['REMOTE_ADDR']);
             //$bitacora->setIp($this->get('request')->getClientIp());
             $bitacora->setUserAgent($this->getRequest()->headers->get('user-agent'));
             $bitacora->setFecha(new \DateTime('now'));
                    
             $em = $this->getDoctrine()->getManager();
             $em->persist($bitacora);            
             try {       
                   $em->flush();
             } catch (\Exception $e) { // Atrapa Error del servidor
                 $error='Ocurrió un error al registrar en la Bitácora.'.$e->getMessage(); 
                 die (json_encode(array('error'=>$error)));
             }             

             // REGISTRO EXITOSO: PASAMOS EL CONTROL A EDICION FORMULARIO 02
             die (json_encode(array('error'=>$error, 'idProyecto'=>$proyecto->getId())));
          }
        }         
       
        $data['estados'] = $this->_getComboEstados(isset($data['idEstado'])?$data['idEstado']:0);
        
        return $this->render('SisproBundle:Proyectos:nuevo.html.twig',
                      array('formulario' => $formulario->createView(),
                                  'data' => $data,)  ); 
    }
    
    /*
     * PREPARA FORMULARIO PARA AGREGAR OBJETIVO ESPECIFICO AL PROYECTO
     */
    public function agregarObjetivoEspecificoAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {          
           $id = $request->query->get('idProyecto');
           
           $proyecto = $this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->find($id);
           if (!$proyecto) die(json_encode(array('error'=>'Proyecto no encontrado.')));
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           
           $proyecto = $this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->find($data['idProyecto']);
           
           if (!$proyecto) die(json_encode(array('error'=>'Proyecto no encontrado.')));
           
           $data['proyecto']=$proyecto;
           
           if ($this->_testFormObjetivoEspecifico($data))
           {  
               $error='';
               $this->_agregarObjetivoEspecifico($data);
         
               // REGISTRA LA ACCION EN LA BITACORA
               $this->_bitacoraAgregarObjetivoEspecifico($data);
               die(json_encode(array('error'=>$error)));
           }
       }       
       //die(json_encode(array('error'=>'')));
       $data = $proyecto;
       
       return $this->render('SisproBundle:Proyectos:agregarObjetivoEspecifico.html.twig',
                      array('data' => $data));      
    }
    
    /*
     * PREPARA FORMULARIO PARA AGREGAR ACTIVIDAD
     */
    public function agregarActividadAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {          
           $id = $request->query->get('idOe');
           
           $objetivo = $this->getDoctrine()
                            ->getRepository('SisproBundle:ObjetivoEspecifico')
                            ->find($id);
           if (!$objetivo) die(json_encode(array('error'=>'Objetivo Específico no encontrado.')));
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           
           $objetivo = $this->getDoctrine()
                            ->getRepository('SisproBundle:ObjetivoEspecifico')
                            ->find($data['idOe']);           
           if (!$objetivo) die(json_encode(array('error'=>'Objetivo Específico no encontrado.')));
           
           $data['objetivo']=$objetivo;
           
           if ($this->_testFormActividad($data))
           {  
               $error='';
               $this->_agregarActividad($data);
         
               // REGISTRA LA ACCION EN LA BITACORA
               $this->_bitacoraAgregarActividad($data);
               die(json_encode(array('error'=>$error)));
           }
       }       
       //die(json_encode(array('error'=>'')));
       $data['objetivo'] = $objetivo;
       
       // BUSCAMOS LOS TIPOS DE MONEDAS DISPONIBLES       
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery('SELECT m
                                  FROM SisproBundle:Moneda m                             
                                  ORDER BY m.moneda');
       $data['monedas'] = $query->getResult();
       
       return $this->render('SisproBundle:Proyectos:agregarActividad.html.twig',
                      array('data' => $data));      
    }    
    
    private function _agregarObjetivoEspecifico($data)
    {             
        $objetivo = new ObjetivoEspecifico();
        
        $objetivo->setProyecto($data['proyecto']);
        $objetivo->setCodigo(str_ireplace(",","",str_ireplace(".","",trim($data['codigo']))));        
        $objetivo->setObjetivoEspecifico(trim($data['objetivoEspecifico']));
        
        $em = $this->getDoctrine()->getManager();       
        $em->persist($objetivo);
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
    
    private function _agregarActividad($data)
    {             
        $actividad = new Actividad();
        $moneda = $this->getDoctrine()
                       ->getRepository('SisproBundle:Moneda')
                       ->find($data['moneda']);        
        if (!$moneda) die(json_encode(array('error'=>'Moneda no encontrada.')));         
        
        $actividad->setMoneda($moneda);       
        $actividad->setObjetivoEspecifico($data['objetivo']);
        
        $actividad->setCodigo(str_ireplace(",","",str_ireplace(".","",trim($data['codigo']))));
        $actividad->setMetaFisica(str_ireplace(",",".",str_ireplace(".","",trim($data['metaFisica']))));
        $actividad->setMonto(str_ireplace(",",".",str_ireplace(".","",trim($data['monto']))));
        $actividad->setActividad(trim($data['actividad']));
        $actividad->setUnidadMedida(trim($data['unidadMedida']));
        $actividad->setFechaIni(\DateTime::createFromFormat('d/m/Y',trim($data['fechaIni'])));
        $actividad->setFechaFin(\DateTime::createFromFormat('d/m/Y',trim($data['fechaFin'])));        
        
        $em = $this->getDoctrine()->getManager();       
        $em->persist($actividad);
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
     * ELIMINA PROYECTO
     */
    public function eliminarProyectoAction(Request $request)
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
       $datos = array('codigo' => $proyecto->getCodigo(),
                      'nombre' => $proyecto->getNombre() );
       
       $error='';
       $em->remove($proyecto);            
       try {       
             $em->flush();
           } catch (\Exception $e) { // Atrapa Error del servidor
               if(stristr($e->getMessage(), 'Foreign key violation'))
               {
                   $error= 'No se pudo eliminar el proyecto. ';
                   $error.='Verifique que no posea Dependencias.';                   
               }else
               {
                   $error = $e->getMessage();
               }
            die(json_encode(array('error'=>$error)));   
       } 
       // Logró ser eliminado
       $this->_bitacoraEliminarProyecto($datos);
       
       die(json_encode(array('error'=>$error)));
    }   
    
    /*
     * ELIMINA OBJETIVO ESPECIFICO
     */
    public function eliminarObjetivoEspecificoAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       $idObjetivo = $request->request->get('id');  
       
       $em = $this->getDoctrine()->getManager();
       
       $objetivo = $em->getRepository('SisproBundle:ObjetivoEspecifico')
                      ->find($idObjetivo);
    
       if (!$objetivo) die(json_encode(array('error'=>'Objetivo Específico no encontrado')));
       
       $datos = array('codigo' => $objetivo->getCodigo(),
                      'objetivoEspecifico' => $objetivo->getObjetivoEspecifico(),
                      'codProy' => $objetivo->getProyecto()->getCodigo(),
                      'proyecto' => $objetivo->getProyecto()->getNombre());
       
       $error='';
       $em->remove($objetivo);            
       try {       
             $em->flush();
           } catch (\Exception $e) { // Atrapa Error del servidor
               if(stristr($e->getMessage(), 'Foreign key violation'))
               {
                   $error= 'No se pudo eliminar el Objetivo Específico. ';
                   $error.='Verifique que no posea Actividades ni otras Dependencias.';                   
               }else
               {
                   $error = $e->getMessage();
               }
            die(json_encode(array('error'=>$error)));   
       } 
       // Logró ser eliminado
        
       $this->_bitacoraEliminarObjetivoEspecifico($datos);
       
       die(json_encode(array('error'=>$error)));
    }       
    
    /*
     * ELIMINA ACTIVIDAD
     */
    public function eliminarActividadAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       $idActividad = $request->request->get('id');  
       
       $em = $this->getDoctrine()->getManager();
       
       $actividad = $em->getRepository('SisproBundle:Actividad')
                       ->find($idActividad);
    
       if (!$actividad) die(json_encode(array('error'=>'Actividad no encontrada')));
       
       $datos = array('codigo' => $actividad->getCodigo(),
                   'actividad' => $actividad->getActividad(),
                     'codProy' => $actividad->getObjetivoEspecifico()
                                            ->getProyecto()->getCodigo(),
                    'proyecto' => $actividad->getObjetivoEspecifico()
                                            ->getProyecto()->getNombre());       
       $error='';
       $em->remove($actividad);            
       try {       
             $em->flush();
           } catch (\Exception $e) { // Atrapa Error del servidor
               if(stristr($e->getMessage(), 'Foreign key violation'))
               {
                   $error= 'No se pudo eliminar la Actividad. ';
                   $error.='Verifique que no posea Dependencias.';                   
               }else
               {
                   $error = $e->getMessage();
               }
            die(json_encode(array('error'=>$error)));   
       } 
       // Logró ser eliminado
        
       $this->_bitacoraEliminarActividad($datos);
       
       die(json_encode(array('error'=>$error)));
    } 
    
    /*
     * Editamos el Formulario 01 de Proyecto: Datos Básicos
     */
    public function editarProyectoForm01Action(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {          
           $id = $request->query->get('idProyecto');
           $form = $request->query->get('form');
           $proyecto = $this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->find($id);
           if (!$proyecto) die(json_encode(array('error'=>'Proyecto no encontrado.')));
               
           $formulario = $this->createForm(new ProyectoType(), $proyecto);           
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');
           $form = $data['form'];
           $proyecto = $this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->find($data['idProyecto']);
           
           if (!$proyecto) die(json_encode(array('error'=>'Proyecto no encontrado.')));
           $formulario = $this->createForm(new ProyectoType(), $proyecto);
           $formulario->bind($request);
           
          if ($formulario->isValid())
          {
            $error='';
            $data['proyecto']=$proyecto; //Le paso el proyecto validado
            $data['form'] = $form; //Le paso el formulario
            $this->_actualizarForm01($data);
            
            $this->_bitacoraActualizarProyecto($data);            
            
            die(json_encode(array('error'=>$error)));
          }                    
       }      
       $data = $this->_hidratarForm01($proyecto);              
       $data['form'] = $form;
       
       return $this->render('SisproBundle:Proyectos:editarForm01.html.twig',
                      array('formulario' => $formulario->createView(),
                                  'data' => $data));       
    }
    
   /*
    * Editamos el Formulario de Proyecto diferente al Form01
    */
    public function editarProyectoAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {          
           $id = $request->query->get('idProyecto');
           $form = $request->query->get('form');
           $proyecto = $this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->find($id);
           if (!$proyecto) die(json_encode(array('error'=>'Proyecto no encontrado.')));
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           $form = $data['form']; 
           $proyecto = $this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->find($data['idProyecto']);
           
           if (!$proyecto) die(json_encode(array('error'=>'Proyecto no encontrado.')));
                                 
           $data['proyecto']=$proyecto;
           
           if ($this->_testForm($data))
           {  
             $error='';
             $this->_actualizarForm($data);
         
             // REGISTRA LA ACCION EN LA BITACORA
             $this->_bitacoraActualizarProyecto($data);
             die(json_encode(array('error'=>$error)));
           }
       }       
       //die(json_encode(array('error'=>$this->_testForm02($data))));

       $data = $this->_hidratarForm($proyecto, $form);               
       $data['form'] = $form;
       
       return $this->render('SisproBundle:Proyectos:editarForm'.$form.'.html.twig',
                      array('data' => $data));       
    }  
    
   /*
    * Editamos el Formulario de Obejtivo Especifico
    */
    public function editarObjetivoEspecificoAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {          
           $idOe = $request->query->get('idOe');
           
           $objetivoEspecifico = $this->getDoctrine()
                            ->getRepository('SisproBundle:ObjetivoEspecifico')
                            ->find($idOe);
           if (!$objetivoEspecifico) die(json_encode(array('error'=>'Objetivo Específico no encontrado.')));
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           
           $objetivoEspecifico = $this->getDoctrine()
                            ->getRepository('SisproBundle:ObjetivoEspecifico')
                            ->find($data['idObjetivoEspecifico']);
           
           if (!$objetivoEspecifico) die(json_encode(array('error'=>'Objetivo Específico no encontrado.')));
           
           $data['objetivo']=$objetivoEspecifico;
           
           if ($this->_testFormObjetivoEspecifico($data))
           {  
             $error='';
             $this->_actualizarFormObjetivoEspecifico($data);
         
             // REGISTRA LA ACCION EN LA BITACORA
             $this->_bitacoraActualizarObjetivoEspecifico($data);
             die(json_encode(array('error'=>$error)));
           }
       }       
       //die(json_encode(array('error'=>$this->_testForm02($data))));       
       $data = $objetivoEspecifico;              
       
       return $this->render('SisproBundle:Proyectos:editarObjetivoEspecifico.html.twig',
                      array('data' => $data));       
    }     
    
   /*
    * Editamos el Formulario de Actividad
    */
    public function editarActividadAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       if ($request->isMethod('GET')) 
       {          
           $idActividad = $request->query->get('idActividad');           
           
           $actividad = $this->getDoctrine()
                             ->getRepository('SisproBundle:Actividad')
                             ->find($idActividad);
           if (!$actividad) die(json_encode(array('error'=>'Actividad no encontrada.')));
       }       
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           
           $actividad = $this->getDoctrine()
                             ->getRepository('SisproBundle:Actividad')
                             ->find($data['idActividad']);
           
           if (!$actividad) die(json_encode(array('error'=>'Actividad no encontrada.')));
           
           $data['act']=$actividad;
           
           if ($this->_testFormActividad($data))
           {  
             $error='';
             $this->_actualizarFormActividad($data);
         
             // REGISTRA LA ACCION EN LA BITACORA
             $this->_bitacoraActualizarActividad($data);
             die(json_encode(array('error'=>$error)));
           }
       }       
       //die(json_encode(array('error'=>$this->_testForm02($data))));       
       $data['actividad'] = $actividad;
       $data['proyecto'] = $actividad->getObjetivoEspecifico()->getProyecto();
       // BUSCAMOS LOS TIPOS DE MONEDAS DISPONIBLES       
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery('SELECT m
                                  FROM SisproBundle:Moneda m                             
                                  ORDER BY m.moneda');
       $data['monedas'] = $query->getResult();
       
       return $this->render('SisproBundle:Proyectos:editarActividad.html.twig',
                      array('data' => $data));       
    }
    
    /*
     * Obtenemos las parroquias en formato html option de un municipio dado
     */
    public function getUsuariosAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }      
       
       $idEstructura = $request->request->get('idEstructura');
       $idUsuario = $request->request->get('idUsuario');
       
       $usuarios = $this->_getComboUsuariosEstructura($idEstructura, $idUsuario);
       
       die($usuarios);
    } 
    
    /*
     * Obtenemos los datos del Usuario Seleccionado
     */
    public function getUsuarioAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }     
       $idUsuario = $request->request->get('idUsuario');
       
       $usuario = $this->getDoctrine()
                       ->getRepository('SisproBundle:Usuario')
                       ->find($idUsuario);
       if (!$usuario) die('Error Usuario no existe');
       $user['cargo']= (trim($usuario->getCargo())!='')?
                            trim($usuario->getCargo()):
                            '- Sin Especificar -';
       $user['telefono']= (trim($usuario->getTelefono())!='')?
                            trim($usuario->getTelefono()):
                            '- Sin Especificar -';
       die(json_encode($user));
    }
    
    /*
     * Obtenemos los municipios en formato html option de un estado dado
     */
    public function getMunicipiosAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }      
       
       $idEstado = $request->request->get('id');       
       
       $municipios = $this->_getComboMunicipios($idEstado);
       
       die($municipios);
    }
    
    /*
     * Obtenemos las parroquias en formato html option de un municipio dado
     */
    public function getParroquiasAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }      
       
       $idMunicipio = $request->request->get('id');       
       
       $parroquias = $this->_getComboParroquias($idMunicipio);
       
       die($parroquias);
    }  
    
    /*
     * Obtenemos la lista de poblados en formato json de un municipio dado
     */
    public function getPobladosAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }      
       
       $idMunicipio = $request->request->get('idMunicipio');
       $frase = mb_convert_case($request->request->get('frase'), MB_CASE_UPPER, 'UTF-8'); 
       
       $poblados=$this->getDoctrine()
                      ->getRepository('SisproBundle:Poblado')
                      ->getListaPoblados($idMunicipio, $frase);
       die($poblados);
    }
    
    /*
     * Obtenemos la información de un municipio a partir de su
     * Código ONAPRE
     */
    public function cargarMunicipioAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }            
       $codigoOnapre = $request->request->get('codigoOnapre');
       $municipios = $request->request->get('municipios');
       
       $donde='';
       if ($municipios!=null)
       {
            foreach($municipios as $m)
            {
                $donde.=' AND m.id!='.$m.' ';
            }unset($m);
       }
       
       // Obtenemos el Municipio Seleccionado
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery("SELECT m, e, r
                                  FROM SisproBundle:Municipio m                                  
                                  JOIN m.estado e
                                  JOIN e.redi r
                                  WHERE m.codigoOnapre = :cod
                                  $donde
                                  ORDER BY e.estado, m.municipio")
                   ->setParameter('cod', $codigoOnapre);

       $data['municipios']=$query->getResult();
       
       return $this->render('SisproBundle:Proyectos:botonMunicipio.html.twig',
                                  array('data' => $data));     
    }

    /*
     * Obtenemos los Estados en formato html option
     */
    private function _getComboEstados($seleccion=0)
    {
       $arrayEstados=$this->getDoctrine()
                          ->getRepository('SisproBundle:Estado')
                          ->getArrayEstados();
       
       return $this->get('utilidades')->getOpciones($arrayEstados, $seleccion);
    }
    
    /*
     * Obtenemos los Municipios en formato html option de un estado dado
     */
    private function _getComboMunicipios($idEstado, $seleccion=0)
    {
       $arrayMunicipios=$this->getDoctrine()
                          ->getRepository('SisproBundle:Municipio')
                          ->getArrayMunicipios($idEstado);
       
       return $this->get('utilidades')->getOpciones($arrayMunicipios, $seleccion);
    }    

    /*
     * Obtenemos las Parroquias en formato html option de un municipio dado
     */
    private function _getComboParroquias($idMunicipio, $seleccion=0)
    {
       $arrayParroquias=$this->getDoctrine()
                             ->getRepository('SisproBundle:Parroquia')
                             ->getArrayParroquias($idMunicipio);       
       
       return $this->get('utilidades')->getOpciones($arrayParroquias, $seleccion);
    }
    
    /*
     * Obtenemos las Unidades Inferiores en formato html option
     */
    private function _getComboUnidadesInferiores($idUnidad, $seleccion=0)
    {
       $arrayUnidadesInferiores=$this->getDoctrine()
                                     ->getRepository('SisproBundle:Estructura')
                                     ->getArrayUnidadesInferiores($idUnidad);       
       
       return $this->get('utilidades')->getOpciones($arrayUnidadesInferiores, $seleccion);
    }
    
    /*
     * Obtenemos los Usuarios de las Unidades Inferiores en formato html option
     */
    private function _getComboUsuariosInferiores($idEstructura, $seleccion=0)
    {
       $arrayUsuariosInferiores=$this->getDoctrine()
                                     ->getRepository('SisproBundle:Usuario')
                                     ->getArrayUsuariosInferiores($idEstructura);       
       
       return $this->get('utilidades')->getOpciones($arrayUsuariosInferiores, $seleccion);
    }
    
    /*
     * Obtenemos usuarios de la unidad en formato hmtl option
     */
    private function _getComboUsuariosEstructura($idEstructura, $seleccion=0)
    {
       $arrayUsuariosEstructura=$this->getDoctrine()
                                     ->getRepository('SisproBundle:Usuario')
                                     ->getArrayUsuariosEstructura($idEstructura);       
       
       return $this->get('utilidades')->getOpciones($arrayUsuariosEstructura, $seleccion);        
    }

    /*
     * Hidratamos los campos del Form01
     */
    private function _hidratarForm01($proyecto)
    {  
       $data = array(); 
       $data['proyecto']=$proyecto;
       
       // Buscamos los id  
       $idParroquia       = $proyecto->getParroquia()->getId();
       $idMunicipio       = $proyecto->getParroquia()->getMunicipio()->getId();
       $data['idPoblado'] = $proyecto->getPoblado()->getId();
       $data['poblado']   = $proyecto->getPoblado()->getPoblado();
       $idEstado          = $proyecto->getParroquia()->getMunicipio()->getEstado()->getId();

       // HIDRATAMOS LOS CAMPOS SELECT
       $data['parroquias'] = $this->_getComboParroquias($idMunicipio, $idParroquia);
       $data['municipios'] = $this->_getComboMunicipios($idEstado, $idMunicipio);
       $data['estados']    = $this->_getComboEstados($idEstado);
       
       // HIDRATAMOS EL PROYECTO CON LAS COORDENADAS              
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery('SELECT c
                                  FROM SisproBundle:Coordenadas c                                                              
                                  WHERE c.proyecto = :proy')
                   ->setParameter('proy', $proyecto);

       $data['coordenadas']=$query->getResult();
       
       return $data;
    }
    
    /*
     * Hidratamos los campos del Form02
     */
    private function _hidratarForm02($proyecto)
    {  
       $data = array();
       $data['proyecto']=$proyecto;
       
       // Buscamos los id  
       $idEstructura = $proyecto->getEstructura()->getId();
       $idUsuario    = $proyecto->getUsuario()->getId();
       
       $estructuraId=$this->getUser()->getEstructura()->getId();       
       
       //die($idEstructura.' - '.$idUsuario);
       // HIDRATAMOS LOS CAMPOS SELECT
       $data['estructuras'] = $this->_getComboUnidadesInferiores($estructuraId, $idEstructura);
       $data['usuarios'] = $this->_getComboUsuariosInferiores($estructuraId, $idUsuario);
       $data['cargo'] = (trim($proyecto->getUsuario()->getCargo())!='')?
                               trim($proyecto->getUsuario()->getCargo()):
                               ' - Sin Especificar -';
       
       $data['telefono']= (trim($proyecto->getUsuario()->getTelefono())!='')?
                               trim($proyecto->getUsuario()->getTelefono()):
                               ' - Sin Especificar -';
       
       // Obtenemos todos los marcos con los del proyecto       
       $data['marcos']=$this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->getMarcosProyecto($proyecto->getId());  
       
       return $data;
    }    

    /*
     * Hidratamos los campos del Form03
     */
    private function _hidratarForm03($proyecto)
    {  
       $data = array();
       $data['proyecto']=$proyecto;       
              
       // Obtenemos todos los tipos de proyecto con los seleccionados
       $data['tipos']=$this->getDoctrine()
                           ->getRepository('SisproBundle:Proyecto')
                           ->getTiposProyecto($proyecto->getId());
       return $data;
    } 
      
    /*
     * Hidratamos los campos del Form05
     */
    private function _hidratarForm05($proyecto)
    {  
       $data = array();
       $data['proyecto']=$proyecto;       
              
       // Obtenemos todos los Objetivos Estratégicos y Nacionales del
       // Plan de la Patria del Proyecto con los seleccionados
       $data['planPatria']=$this->getDoctrine()
                          ->getRepository('SisproBundle:Proyecto')
                          ->getPlanPatriaProyecto($proyecto->getId());
       return $data;
    }     
    
    /*
     * Hidratamos los campos del Form06
     */
    private function _hidratarForm06($proyecto)
    {  
       $data = array();
       $data['proyecto']=$proyecto;       
              
       // Obtenemos todos los Linamientos del PNSB del Proyecto con los seleccionados
       $data['area']=$this->getDoctrine()
                          ->getRepository('SisproBundle:Proyecto')
                          ->getAreaEstrategicaProyecto($proyecto->getId());
       return $data;
    } 
    
    private function _hidratarForm07($proyecto)
    {
       $data = array();
       $data['proyecto']=$proyecto;       
       return $data;
    }
    
    /*
     * Hidratamos los campos del Form08
     */
    private function _hidratarForm08($proyecto)
    {  
       $data = array();
       $data['proyecto']=$proyecto;       
              
       // Obtenemos todos los Municipios Relacionados con el Proyecto 
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery('SELECT m, e, r
                                  FROM SisproBundle:Municipio m
                                  JOIN m.proyecto p
                                  JOIN m.estado e
                                  JOIN e.redi r
                                  WHERE p.id = :proy
                                  ORDER BY e.estado, m.municipio')
                   ->setParameter('proy', $proyecto->getId());

       $data['municipios']=$query->getResult();
       
       return $data;
    }       
    
    /*
     * Hidratamos los campos del Form09
     */
    private function _hidratarForm09($proyecto)
    {  
       $data = array();
       $data['proyecto']=$proyecto;
             
       // BUSCAMOS LOS TIPOS DE MONEDAS DISPONIBLES
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery('SELECT m
                                  FROM SisproBundle:Moneda m                             
                                  ORDER BY m.moneda');
       $data['monedas'] = $query->getResult();
       
       // Obtenemos todos las fuentes con los del proyecto       
       $data['fuentes']=$this->getDoctrine()
                             ->getRepository('SisproBundle:Proyecto')
                             ->getFuentesFinanciamientoProyecto($proyecto->getId()); 
       return $data;
    } 
    
    /*
     * Hidratamos los campos del Form10
     */
    private function _hidratarForm10($proyecto)
    {  
       $data = array();
       $data['proyecto']=$proyecto;
       
       // Obtenemos todos los Objetivos Especificos del Proyecto      
       $data['objetivos'] = $this->getDoctrine()
                                 ->getRepository('SisproBundle:Proyecto')
                                 ->getObjetivosProyecto($proyecto); 
       
       // Obtenemos todos los Objetivos Especificos Originales del Proyecto (si hay)
       if (count($proyecto->getObjetivosOrg())!=0)
       {
         $data['objetivosOrg'] = $this->getDoctrine()
                                      ->getRepository('SisproBundle:Proyecto')
                                      ->getObjetivosOrgProyecto($proyecto); 
           
       }     
       
       // Verificamos los años que cubre el proyecto
       $data['years'] = array();
       $data['yearsOrg'] = array();
       
       $actividades = $this->getDoctrine()
                           ->getRepository('SisproBundle:Proyecto')
                           ->getActividadesProyecto($proyecto);
       
       if (count($actividades) != 0)
       {
           foreach ($actividades as $a)
            {               
               if (!in_array($a->getFechaIni()->format('Y'),$data['years']))
                  array_push($data['years'], $a->getFechaIni()->format('Y'));
               
               if (!in_array($a->getFechaFin()->format('Y'),$data['years']))
                  array_push($data['years'], $a->getFechaFin()->format('Y'));
            }            
            sort($data['years']);
       }
       else
       {
           $fechaActual = new \DateTime('today');
           $data['years'] = array($fechaActual->format('Y'));
       }
       
       $data['cols'] = (count($data['years']) >= 3)? 3:count($data['years']);
       
       $actividadesOrg = $this->getDoctrine()
                              ->getRepository('SisproBundle:Proyecto')
                              ->getActividadesOrgProyecto($proyecto);
       
       if (count($actividadesOrg) != 0)
       {
           foreach ($actividadesOrg as $a)
            {               
               if (!in_array($a->getFechaIni()->format('Y'),$data['yearsOrg']))
                  array_push($data['yearsOrg'], $a->getFechaIni()->format('Y'));
               
               if (!in_array($a->getFechaFin()->format('Y'),$data['yearsOrg']))
                  array_push($data['yearsOrg'], $a->getFechaFin()->format('Y'));
            }            
            sort($data['yearsOrg']);
       }
       
       $data['colsOrg'] = (count($data['yearsOrg']) >= 3)? 3:count($data['yearsOrg']);       
       
       return $data;
    }
    
    /*
     * Hidratamos los campos del Form11
     */
    private function _hidratarForm11($proyecto)
    {  
       $data = array();
       $data['proyecto']=$proyecto;
       
       $chequeo[3] = array('nombre'=>'Tipo de Proyecto',
                           'check' =>(count($proyecto->getTipoProyecto())!=0)?true:false,
                             'msg' =>'Debe seleccionar al menos un tipo de proyecto',
                            'form' => '03');
       $chequeo[5] = array('nombre'=>'Plan de la Patria (2013-20019)',
                           'check' =>(count($proyecto->getOn())!=0)?true:false,
                             'msg' =>'Debe seleccionar al menos un Objetivo',
                            'form' => '05');
       $chequeo[6] = array('nombre'=>'Áreas Estratégicas de Investigación',
                           'check' =>(count($proyecto->getAreaEstrategica())!=0)?true:false,
                             'msg' =>'Debe seleccionar un área estratégica de investigación',
                            'form' => '06');
       $chequeo[7] = array('nombre'=>'Alcances del Proyecto',
                           'check' =>$this->_testForm077($proyecto),
                             'msg' =>'Debe llenar todos los campos correctamente',
                            'form' => '07');
       $chequeo[8] = array('nombre'=>'Municipios Beneficiados',
                           'check' =>(count($proyecto->getMunicipio())!=0 || $proyecto->getNacional())?true:false,
                             'msg' =>'Debe seleccionar al menos un Municipio o indicar si es un proyecto a nivel nacional',
                            'form' => '08');
       $chequeo[9] = array('nombre'=>'Fuentes de Financiamiento',
                           'check' =>(count($proyecto->getFuentes())!=0)?true:false,
                            'msg' =>'Debe definir al menos una fuente de financiamiento',
                            'form' => '09');
       $chequeo[10] = array('nombre'=>'Planificación del Proyecto',
                           'check' =>$proyecto->isPlanificacionCompleta(),
                            'msg' =>'Planificación Incompleta',
                            'form' => '10');       
       
       $data['chequeo'] = $chequeo;
       
       return $data;
    }      
    
    private function _setCoordenadas($data)
    {
       // RECUPERAMOS LOS OBJETOS
       $proyecto = $data['proyecto'];
       
       $em = $this->getDoctrine()->getManager(); // CARGAMOS EL ADMIN ENTIDAD
       
       // BUSCAMOS LAS COORDENADAS DEL PROYECTO. SI NO LAS HAY ENTONCES NULL
       if (count($proyecto->getCoordenadas())!=0)
       {           
           $query = $em->createQuery('SELECT c
                                      FROM SisproBundle:Coordenadas c
                                      LEFT JOIN c.proyecto p                                  
                                      WHERE p = :proy')
                       ->setParameter('proy', $proyecto);       
           $coordenadas = $query->getSingleResult();
       }
       else $coordenadas=null;
       
       // OPCION 01: INSERT: Si el proyecto no posee coordenadas y se le desean agregar
       if (count($proyecto->getCoordenadas())==0 && $data['latgra']!='')
       {
           $coordenadas= new Coordenadas();
           $coordenadas->setProyecto($proyecto);
           $coordenadas->setLatgra($data['latgra']);
           $coordenadas->setLatmin($data['latmin']);
           $coordenadas->setLatseg(str_ireplace(",",".",str_ireplace(".","",$data['latseg'])));
           $coordenadas->setLongra($data['longra']);
           $coordenadas->setLonmin($data['lonmin']);
           $coordenadas->setLonseg(str_ireplace(",",".",str_ireplace(".","",$data['lonseg'])));           
           $em->persist($coordenadas);
           try {
                 $em->flush();
               } catch (\Exception $e) { // Atrapa Error del servidor
                   $error='Coordenadas INSERT. '.$e->getMessage();   
                   die(json_encode(array('error'=>$error)));
           }
           return true;
       }
       
       // OPCION 02: UPDATE: Si el proyecto posee coordenadas y se desean cambiar
       if (count($proyecto->getCoordenadas())!=0 && $data['latgra']!='')
       {              
           $coordenadas
                    ->setLatgra($data['latgra'])
                    ->setLatmin($data['latmin'])
                    ->setLatseg(str_ireplace(",",".",str_ireplace(".","",$data['latseg'])))
                    ->setLongra($data['longra'])
                    ->setLonmin($data['lonmin'])           
                    ->setLonseg(str_ireplace(",",".",str_ireplace(".","",$data['lonseg'])));
           try {       
                 $em->flush();
               } catch (\Exception $e) { // Atrapa Error del servidor
                   $error='Coordenadas UPDATE. '.$e->getMessage();   
                   die(json_encode(array('error'=>$error)));
           }           
           return true;
       }
       
       // OPCION 03: DELETE: Si el proyecto posee coordenadas y se desean eliminar
       if (count($proyecto->getCoordenadas())!=0 && $data['latgra'] =='')
       {                             
           $em->remove($coordenadas);            
           try {       
                 $em->flush();
               } catch (\Exception $e) { // Atrapa Error del servidor
                   $error='Coordenadas DELETE. '.$e->getMessage();   
                   die(json_encode(array('error'=>$error)));
           }             
           return true;
       }
       
       // OPCION 04: NADA: Si el proyecto no posee coordenadas y no hay nuevas
       if (count($proyecto->getCoordenadas())==0 && $data['latgra'] =='')
       {
           return true;
       }
       return false;
    }
    
    private function _actualizarForm01($data)
    {
        $proyecto = $data['proyecto'];
        $parroquia = $this->getDoctrine()
                           ->getRepository('SisproBundle:Parroquia')
                           ->find($data['idParroquia']);
        if (!$parroquia) die(json_encode(array('error'=>'Parroquia no encontrada.')));
        
        $poblado = $this->getDoctrine()
                        ->getRepository('SisproBundle:Poblado')
                        ->find($data['idPoblado']);
        if (!$poblado) die(json_encode(array('error'=>'Poblado no encontrada.')));
        
        $proyecto->setParroquia($parroquia);
        $proyecto->setPoblado($poblado);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($proyecto);
        try {
              $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor
                if(stristr($e->getMessage(), 'Not null violation')) 
                {
                  $error ='No ha llenado todos los campos.';
                }else  $error=$e->getMessage();
                
                die(json_encode(array('error'=>$error)));
            }
       
       // ACTUALIZA LAS COORDENADAS
       $this->_setCoordenadas($data);
       
       return true;
    }
    
    private function _actualizarForm02($data)
    {
        // Actualiza los campos sencillos
        $proyecto = $data['proyecto'];
        $estructura = $this->getDoctrine()
                           ->getRepository('SisproBundle:Estructura')
                           ->find($data['idEstructura']);        
        if (!$estructura) die(json_encode(array('error'=>'Estructura no encontrada.')));
        
        $usuario = $this->getDoctrine()
                        ->getRepository('SisproBundle:Usuario')
                        ->find($data['idUsuario']);
        if (!$usuario) die(json_encode(array('error'=>'Usuario no encontrado.')));        
        
        $proyecto->setEstructura($estructura);
        $proyecto->setUsuario($usuario);        
        $em = $this->getDoctrine()->getManager();
        
        $em->persist($proyecto);
        try {
              $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor
                 if(stristr($e->getMessage(), 'Not null violation')) 
                 {
                   $error ='No ha llenado todos los campos.';
                 }else  $error=$e->getMessage();
                 
                 die(json_encode(array('error'=>$error)));
             }
        
        // Acutaliza los campos de selección múltiple
        // Eliminamos todos los marcos del proyecto
        $query = $em->createQuery('DELETE SisproBundle:ProyectoMarco r
                                   WHERE r.proyecto = :proy')
                    ->setParameter('proy', $proyecto);
                   
        $query->getResult();        
        
        if (isset($data['marco'])) // Si hay marcos
        {               
            // Obtenemos la matriz con los Marcos seleccionados
            $donde='WHERE ';
            foreach ($data['marco'] as $m)
            {
                $donde.='m.id='.$m.' OR ';
            }
            unset ($m);
            $donde =  substr($donde, 0, -3); //die (json_encode(array('error'=>$donde)));
            $query = $em->createQuery("SELECT m
                                       FROM SisproBundle:Marco m
                                       $donde
                                       ORDER BY m.id");
            $marcos = $query->getResult();
            if (!$marcos) die(json_encode(array('error'=>'Marcos no encontrados.')));
            
            $i=0;
            foreach ($marcos as $m)
            {
                $proyectoMarco = new ProyectoMarco();
                $proyectoMarco->setProyecto($proyecto);
                $proyectoMarco->setMarco($m);
                $proyectoMarco->setCodigo(trim($data['codigo'][$i]));
                $proyectoMarco->setYear(trim($data['year'][$i]));

                $em->persist($proyectoMarco);
                try {       
                     $em->flush();
                } catch (\Exception $e) { // Atrapa Error del servidor
                  if(stristr($e->getMessage(), 'Not null violation')) 
                  {
                    $error='Debe llenar todos los campos correctamente';
                  }
                  else if(stristr($e->getMessage(), 'Unique violation')) 
                  {
                    $error='Clave Proyecto-Marco Duplicada';
                  }
                  else $error = $e->getMessage();
                 
                  die (json_encode(array('error'=>$error)));
                 }               
                 $i++;
            } unset($m);
        }        
        
        return true;
    }
    
    private function _actualizarForm03($data)
    {
        $proyecto = $data['proyecto'];

        // Acutaliza los campos de selección múltiple
        // Obtenemos los tipos de proyecto existentes
        $tipos = $proyecto->getTipoProyecto();
        
        // Procedemos a removerlos uno a uno
        foreach ($tipos as $t)
        {
            $proyecto->removeTipoProyecto($t);
        }unset($t);
        
        // Recuperamos los tipos seleccionados
        if (isset($data['tipo'])) // Si hay tipos
        {
            $donde='WHERE ';
            foreach($data['tipo'] as $t)
            {
                $donde .= 't.id='.$t.' OR ';
            }unset($t);
            $donde =  substr($donde, 0, -3); //die (json_encode(array('error'=>$donde)));
            $em = $this->getDoctrine()->getManager();
            
            $query = $em->createQuery("SELECT t
                                       FROM SisproBundle:TipoProyecto t
                                       $donde
                                       ORDER BY t.id");
            $tipos = $query->getResult();
            if (!$tipos) die(json_encode(array('error'=>'Tipo de Proyecto no encontrados.')));
            
            // Agregamos cada tipo al proyecto
            foreach ($tipos as $t)
            {
                $proyecto->addTipoProyecto($t);
            }unset($t);
        }
        return true;
    } 
    
    private function _actualizarForm05($data)
    {
        $proyecto = $data['proyecto'];

        // Acutaliza los campos de selección múltiple
        // Obtenemos los Objetivos Nacionales del Plan de la Patria del proyecto existentes
        $tipos = $proyecto->getOn();
        
        // Procedemos a removerlos uno a uno
        foreach ($tipos as $t)
        {
            $proyecto->removeOn($t);
        }unset($t);
        
        // Recuperamos los tipos seleccionados
        if (isset($data['tipo'])) // Si hay tipos
        {
            $donde='WHERE ';
            foreach($data['tipo'] as $t)
            {
                $donde .= 't.id='.$t.' OR ';
            }unset($t);
            $donde =  substr($donde, 0, -3); //die (json_encode(array('error'=>$donde)));
            $em = $this->getDoctrine()->getManager();
            
            $query = $em->createQuery("SELECT t
                                       FROM SisproBundle:PlanPatriaOn t
                                       $donde
                                       ORDER BY t.id");
            $tipos = $query->getResult();
            if (!$tipos) die(json_encode(array('error'=>'Objetivos Nacionales no encontrados.')));
            
            // Agregamos cada tipo al proyecto
            foreach ($tipos as $t)
            {
                $proyecto->addOn($t);
            }unset($t);
        }
        return true;
    }      
    
    private function _actualizarForm06($data)
    {
        $proyecto = $data['proyecto'];

        // Acutaliza los campos de selección múltiple
        // Obtenemos las Áreas Estratégicas de Investigación del proyecto existentes
        $tipos = $proyecto->getAreaEstrategica();
        
        // Procedemos a removerlos uno a uno
        foreach ($tipos as $t)
        {
            $proyecto->removeAreaEstrategica($t);
        }unset($t);
        
        // Recuperamos los tipos seleccionados
        if (isset($data['tipo'])) // Si hay tipos
        {
            $donde='WHERE ';
            foreach($data['tipo'] as $t)
            {
                $donde .= 't.id='.$t.' OR ';
            }unset($t);
            $donde =  substr($donde, 0, -3); //die (json_encode(array('error'=>$donde)));
            $em = $this->getDoctrine()->getManager();
            
            $query = $em->createQuery("SELECT t
                                       FROM SisproBundle:AreaEstrategica t
                                       $donde
                                       ORDER BY t.id");
            $tipos = $query->getResult();
            if (!$tipos) die(json_encode(array('error'=>'Área Estrategica no encontrados.')));
            
            // Agregamos cada Area al proyecto
            foreach ($tipos as $t)
            {
                $proyecto->addAreaEstrategica($t);
            }unset($t);
        }
        return true;
    }      
    
    private function _actualizarForm07($data)
    {
        $proyecto = $data['proyecto'];
        
        $proyecto->setObjetivoGeneral(trim($data['objetivoGeneral']));
        $proyecto->setProducto(trim($data['producto']));
        $proyecto->setMeta(str_ireplace(",",".",str_ireplace(".","",trim($data['meta']))));
        $proyecto->setUnidadMedida(trim($data['unidadMedida']));
        $proyecto->setIndicador(trim($data['indicador']));
        $proyecto->setAlcance(trim($data['alcance']));
        $proyecto->setPuntoycirculo(trim($data['puntoycirculo']));
        
        $proyecto->setPobFemenina(str_ireplace(",",".",str_ireplace(".","",$data['pobFemenina'])));
        $proyecto->setPobMasculina(str_ireplace(",",".",str_ireplace(".","",$data['pobMasculina'])));
        $proyecto->setPobTotal(str_ireplace(",",".",str_ireplace(".","",$data['pobTotal'])));
      
        $proyecto->setEmpleosDirectosEjecucion(
                str_ireplace(",",".",str_ireplace(".","",$data['empleosDirectosEjecucion'])));
        $proyecto->setEmpleosDirectosOperacion(
                str_ireplace(",",".",str_ireplace(".","",$data['empleosDirectosOperacion'])));
        $proyecto->setEmpleosIndirectosEjecucion(
                str_ireplace(",",".",str_ireplace(".","",$data['empleosIndirectosEjecucion'])));
        $proyecto->setEmpleosIndirectosOperacion(
                str_ireplace(",",".",str_ireplace(".","",$data['empleosIndirectosOperacion'])));        
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($proyecto);
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
    
    private function _actualizarForm08($data)
    {
        $proyecto = $data['proyecto'];
        
        // Acutaliza los campos de selección múltiple
        // Obtenemos los Municipios del proyecto existentes
        $municipios = $proyecto->getMunicipio();
        
        // Procedemos a removerlos uno a uno
        foreach ($municipios as $m)
        {
            $proyecto->removeMunicipio($m);
        }unset($m);
        
        $nacional=true; // Si es nacional
        // Recuperamos los Municipios seleccionados y los guardamos en el Proyecto
        if (isset($data['municipios'])) // Si hay municipios
        {
            $nacional=false; // Si hay municipios no es nacional
            $donde='WHERE ';
            foreach($data['municipios'] as $m)
            {
                $donde .= 'm.id='.$m.' OR ';
            }unset($m); 
            $donde =  substr($donde, 0, -3); //die (json_encode(array('error'=>$donde)));
            $em = $this->getDoctrine()->getManager();            
            $query = $em->createQuery("SELECT m
                                       FROM SisproBundle:Municipio m
                                       $donde
                                       ORDER BY m.id");
            $municipios = $query->getResult();
            if (!$municipios) die(json_encode(array('error'=>'Municipios no encontrados.')));
            
            // Agregamos cada Municipio al proyecto
            foreach ($municipios as $m)
            {
                $proyecto->addMunicipio($m);
            }unset($m);
        }
        
        // Guardamos el campo Nacional
        $proyecto->setNacional($nacional);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($proyecto);
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
    
    private function _actualizarForm09($data)
    {   
        $proyecto = $data['proyecto'];
        
        // Acutaliza los campos de selección múltiple
        // Eliminamos todos las fuentes de financiamiento del proyecto
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('DELETE SisproBundle:ProyectoFuenteFinanciamiento f
                                   WHERE f.proyecto = :proy')
                    ->setParameter('proy', $proyecto);
                   
        $query->getResult();        
        
        if (isset($data['fuente'])) // Si hay fuentes
        {               
            // Obtenemos la matriz con las Fuentes seleccionadas
            $donde='WHERE ';
            foreach ($data['fuente'] as $f)
            {
                $donde.='f.id='.$f.' OR ';
            }
            unset ($f);
            $donde =  substr($donde, 0, -3); //die (json_encode(array('error'=>$donde)));
            $query = $em->createQuery("SELECT f
                                       FROM SisproBundle:FuenteFinanciamiento f
                                       $donde
                                       ORDER BY f.id");
            $fuentes = $query->getResult();
            if (!$fuentes) die(json_encode(array('error'=>'Fuentes de Financiamiento no encontradas.')));
            
            $i=0;
            foreach ($fuentes as $f)
            {
                $moneda = $this->getDoctrine()
                               ->getRepository('SisproBundle:Moneda')
                               ->find($data['moneda'][$i]);        
                if (!$moneda) die(json_encode(array('error'=>'Moneda no encontrada.')));                
                
                $proyectoFuente = new ProyectoFuenteFinanciamiento();
                $proyectoFuente->setProyecto($proyecto);
                $proyectoFuente->setFuenteFinanciamiento($f);
                $proyectoFuente->setMoneda($moneda);
                $proyectoFuente->setMonto(str_ireplace(",",".",str_ireplace(".","",$data['monto'][$i])));

                $em->persist($proyectoFuente);
                try {       
                     $em->flush();
                } catch (\Exception $e) { // Atrapa Error del servidor
                  if(stristr($e->getMessage(), 'Not null violation')) 
                  {
                    $error='Debe llenar todos los campos correctamente';
                  }
                  else if(stristr($e->getMessage(), 'Unique violation')) 
                  {
                    $error='Clave Proyecto-Marco Duplicada';
                  }
                  else $error = $e->getMessage();
                 
                  die (json_encode(array('error'=>$error)));
                 }               
                 $i++;
            } unset($f);
        }        
        
        return true;
    }
    
    /*
     * Envío de Formulario
     */
    private function _actualizarForm11e($data)
    {   
        $proyecto = $data['proyecto'];
        $estatus  = $this->getDoctrine()
                         ->getRepository('SisproBundle:Estatus')
                         ->find(2);     
        if (!$estatus) die(json_encode(array('error'=>'Estatus no encontrado.'))); 
        // ESTABLECEMOS EL ESTATUS A REGISTRADO (2)
        $proyecto->setEstatus($estatus);
        
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
        // PROCEDEMOS A RESPALDAR LOS OBJETIVOS ESPECIFICOS Y LAS ACTIVIDADES
        $this->_respaldarPlanificacion($data);
        
        // PROCEDEMOS A ENVIAR EL CORREO AUTOMÁTICO CON LA NOTIFICACION    
        $this->_emailEnvioProyecto($data);
        
        return true;
    }
    
    /*
     * Respaldo de Planificación del Proyecto
     */
    private function _respaldarPlanificacion($data)
    {
        $proyecto = $data['proyecto'];
        if (count($proyecto->getObjetivosOrg())!=0) // SI YA HAY PLANIFICACION ORIGINAL
        {
            return true;
        }
        
        $objetivos = $proyecto->getObjetivos();
        
        $em = $this->getDoctrine()->getManager();
        // Respaldamos los Objetivos Específicos
        foreach ($objetivos as $o)
        {
            $objetivoOrg = new ObjetivoEspecificoOrg();
            $objetivoOrg->setCodigo($o->getCodigo());
            $objetivoOrg->setObjetivoEspecifico($o->getObjetivoEspecifico());
            $objetivoOrg->setProyecto($proyecto);
              
            $em->persist($objetivoOrg);
            try {
                   $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor
               $error='Ocurrió un error al respaldar la Planificación.'.$e->getMessage();
               die(json_encode(array('error'=>$error)));
            }
            
            $actividades = $o->getActividades();
            // Respaldamos las Actividades del Objetivo Especifico
            foreach ($actividades as $a) 
            {
               $actividadOrg = new ActividadOrg();
               $actividadOrg->setCodigo($a->getCodigo());
               $actividadOrg->setActividad($a->getActividad());
               $actividadOrg->setFechaFin($a->getFechaFin());
               $actividadOrg->setFechaIni($a->getFechaIni());
               $actividadOrg->setMetaFisica($a->getMetaFisica());
               $actividadOrg->setMoneda($a->getMoneda());
               $actividadOrg->setMonto($a->getMonto());
               $actividadOrg->setUnidadMedida($a->getUnidadMedida());
               $actividadOrg->setObjetivoEspecificoOrg($objetivoOrg);

               $em->persist($actividadOrg);
               try {
                      $em->flush();
               } catch (\Exception $e) { // Atrapa Error del servidor
                  $error='Ocurrió un error al respaldar la Planificación.'.$e->getMessage();
                  die(json_encode(array('error'=>$error)));
               }               
            }unset($a);
        }unset($o);
        
        return true;
    }
    
    /*
     * Envío de Solicitud
     */
    private function _actualizarForm11s($data)
    {   
        $proyecto = $data['proyecto'];
        $estatus  = $this->getDoctrine()
                         ->getRepository('SisproBundle:Estatus')
                         ->find(5);     
        if (!$estatus) die(json_encode(array('error'=>'Estatus no encontrado.'))); 
        // ESTABLECEMOS EL ESTATUS A ESPERANDO PERMISO EDICION (5)
        $proyecto->setEstatus($estatus);
        $proyecto->setObservaciones($data['observaciones']);
        
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
        // PROCEDEMOS A ENVIAR EL CORREO AUTOMÁTICO CON LA NOTIFICACION    
        $this->_emailEnvioSolicitud($data);
        
        return true;
    }   
    
    /*
     * Aprobar/Rechazar Solicitud de edición
     */
    private function _actualizarForm11ar($data)
    {   
        $st = ($data['aprobado']==1)?1:2;
        $proyecto = $data['proyecto'];
        $estatus  = $this->getDoctrine()
                         ->getRepository('SisproBundle:Estatus')
                         ->find($st);     
        if (!$estatus) die(json_encode(array('error'=>'Estatus no encontrado.'))); 
        // ESTABLECEMOS EL ESTATUS (1 ó 2)
        $proyecto->setEstatus($estatus);
        $proyecto->setObservaciones('');
        
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
        // PROCEDEMOS A ENVIAR EL CORREO AUTOMÁTICO CON LA NOTIFICACION    
        $this->_emailAprobacionRechazoSolicitud($data);
        
        return true;
    }      
    
    /*
     * Actualizar Objetivo Especifico
     */    
    private function _actualizarFormObjetivoEspecifico($data)
    {
        $objetivo = $data['objetivo'];
        
        $objetivo->setCodigo(str_ireplace(",","",str_ireplace(".","",trim($data['codigo']))));        
        $objetivo->setObjetivoEspecifico(trim($data['objetivoEspecifico']));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($objetivo);
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
     * Actualizar Actividad
     */    
    private function _actualizarFormActividad($data)
    {
        $actividad = $data['act'];        
        
        $moneda = $this->getDoctrine()
                       ->getRepository('SisproBundle:Moneda')
                       ->find($data['moneda']);        
        if (!$moneda) die(json_encode(array('error'=>'Moneda no encontrada.')));         
        
        $actividad->setMoneda($moneda);       
        
        $actividad->setCodigo(str_ireplace(",","",str_ireplace(".","",trim($data['codigo']))));
        $actividad->setMetaFisica(str_ireplace(",",".",str_ireplace(".","",trim($data['metaFisica']))));
        $actividad->setMonto(str_ireplace(",",".",str_ireplace(".","",trim($data['monto']))));
        $actividad->setActividad(trim($data['actividad']));
        $actividad->setUnidadMedida(trim($data['unidadMedida']));
        $actividad->setFechaIni(\DateTime::createFromFormat('d/m/Y',trim($data['fechaIni'])));
        $actividad->setFechaFin(\DateTime::createFromFormat('d/m/Y',trim($data['fechaFin'])));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($actividad);
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
     * Enrutador de Probador de Formularios diferentes al Form01
     */
    private function _testForm($data)
    {
        switch ($data['form'])
        {
            case '02': return $this->_testForm02($data);
                       break;
            case '03': return true;
                       break;
            case '04': return true;
                       break;
            case '05': return true;
                       break;
            case '06': return true;
                       break;
            case '07': return $this->_testForm07($data);
                       break;
            case '08': return true;
                       break;
            case '09': return $this->_testForm09($data);
                       break;
            case '10': return $this->_testForm10($data);
                       break;
            case '11e': return true;
                       break;                   
            case '11s': return true;
                       break;   
            case '11ar': return true;
                       break;                    
        }
    }
    
    /*
     * Enrutador de Actualizador de Formularios diferentes al Form01
     */
    private function _actualizarForm($data)
    {
        switch ($data['form'])
        {
            case '02': return $this->_actualizarForm02($data);
                       break;
            case '03': return $this->_actualizarForm03($data);
                       break;
            case '04': return $this->_actualizarForm04($data);
                       break;
            case '05': return $this->_actualizarForm05($data);
                       break;
            case '06': return $this->_actualizarForm06($data);
                       break;
            case '07': return $this->_actualizarForm07($data);
                       break;
            case '08': return $this->_actualizarForm08($data);
                       break;
            case '09': return $this->_actualizarForm09($data);
                       break;
            case '10': return $this->_actualizarForm10($data);
                       break;
            case '11e': return $this->_actualizarForm11e($data); // ENVIO DE PROYECTO
                       break; 
            case '11s': return $this->_actualizarForm11s($data); // SOLICITUD DE EDICION
                       break;  
            case '11ar': return $this->_actualizarForm11ar($data); // APROBAR/NEGAR SOLICITUD DE EDICION
                       break;                    
        }
    }
    
    /*
     * Enrutador de Hidratador de Formularios diferentes al Form01
     */
    private function _hidratarForm($proyecto, $form)
    {
        switch ($form)
        {
            case '02': return $this->_hidratarForm02($proyecto);
                       break;
            case '03': return $this->_hidratarForm03($proyecto);
                       break;
            case '04': return $this->_hidratarForm04($proyecto);
                       break;
            case '05': return $this->_hidratarForm05($proyecto);
                       break;
            case '06': return $this->_hidratarForm06($proyecto);
                       break;
            case '07': return $this->_hidratarForm07($proyecto);
                       break;
            case '08': return $this->_hidratarForm08($proyecto);
                       break;
            case '09': return $this->_hidratarForm09($proyecto);
                       break;
            case '10': return $this->_hidratarForm10($proyecto);
                       break;
            case '11': return $this->_hidratarForm11($proyecto);
                       break;        
           case '11e': return $this->_hidratarForm11e($proyecto);
                       break;                   
        }
    }
    
    private function _testForm02($data)
    {
        $test = true;
        
        if (intval($data['idEstructura'])==0) $test = false;        
        if (intval($data['idUsuario'])==0) $test = false;
                
        if (isset($data['codigo']))
        {
           foreach ($data['codigo'] as $c)
           {
               if (trim($c)=='') $test = false;
           }
           unset($c);
        }
        
        if (isset($data['year']))
        {
           foreach ($data['year'] as $y)
           {
               if (intval($y)<1990 || intval($y)>2021) $test = false;
           }
           unset($y);
        }                
       
        return $test;        
        // Prueba de AJAX
        // die (json_encode(array('error'=>json_encode($data))));
    }
    
    private function _testForm07($data)
    {        
        $test = true;
     
        if (trim($data['objetivoGeneral'])=='' ||
            trim($data['objetivoGeneral'])=='00') $test = false;
        if (trim($data['producto'])=='' ||
            trim($data['producto'])=='00') $test = false;
        if (trim($data['unidadMedida'])=='' ||
            trim($data['unidadMedida'])=='00') $test = false;
      /*  if (trim($data['indicador'])=='' ||
            trim($data['indicador'])=='00') $test = false;*/
        if (trim($data['alcance'])=='' ||
            trim($data['alcance'])=='00') $test = false;
      /*  if (trim($data['puntoycirculo'])=='' ||
            trim($data['puntoycirculo'])=='00') $test = false;*/
        
        if (intval($data['meta']) == 0 ) $test = false;

        $fem = intval(str_ireplace(",",".",str_ireplace(".","",$data['pobFemenina'])));
        $mas = intval(str_ireplace(",",".",str_ireplace(".","",$data['pobMasculina'])));
        $tot = intval(str_ireplace(",",".",str_ireplace(".","",$data['pobTotal'])));
        
        if ( ( ($fem + $mas) != $tot ) && ( ($fem+$mas) != 0) ) $test = false;
                        
        return $test;
    }
    
    private function _testForm077($data)
    {        
        $test = true;
     
        if (trim($data->getObjetivoGeneral())=='' ||
            trim($data->getObjetivoGeneral())=='00') $test = false;
        if (trim($data->getProducto())=='' ||
            trim($data->getProducto())=='00') $test = false;
        if (trim($data->getUnidadMedida())=='' ||
            trim($data->getUnidadMedida())=='00') $test = false;
       /* if (trim($data->getIndicador())=='' ||
            trim($data->getIndicador())=='00') $test = false;*/
        if (trim($data->getAlcance())=='' ||
            trim($data->getAlcance())=='00') $test = false;
      /*  if (trim($data->getPuntoycirculo())=='' ||
            trim($data->getPuntoycirculo())=='00') $test = false;*/
        
        if ($data->getMeta() == 0 ) $test = false;

        $fem = $data->getPobFemenina();
        $mas = $data->getPobMasculina();
        $tot = $data->getPobTotal();
        
        if ( ( ($fem + $mas) != $tot ) && ( ($fem + $mas) != 0) ) $test = false;
                
        return $test;
    }    
    
    private function _testForm09($data)
    {
        $test = true;        
              
        if (isset($data['moneda']))
        {
           foreach ($data['moneda'] as $m)
           {
               if (trim($m)=='0') $test = false;
           }
           unset($m);
        }
        
        if (isset($data['monto']))
        {
           foreach ($data['monto'] as $m)
           {
               if (intval($m)== 0) $test = false;
           }
           unset($m);
        }                
       
        return $test;        
        // Prueba de AJAX
        // die (json_encode(array('error'=>json_encode($data))));
    } 

    private function _testForm10($data)
    {
        return true;
    }    
    
    private function _testForm11($data)
    {
        return true;
    }
    
    private function _testFormObjetivoEspecifico($data)
    {
        $test = true;
        
        if(intval(trim($data['codigo'])) == 0 ) $test = false;
        if(trim($data['objetivoEspecifico']) == '' ) $test = false;        
        
        return $test;
    } 
    
    private function _testFormActividad($data)
    {
        $test = true;
        
        if(intval(trim($data['codigo'])) == 0 ) $test = false;
        if(intval(trim($data['monto'])) == 0 ) $test = false;
        if(intval(trim($data['metaFisica'])) == 0 ) $test = false;
        if(trim($data['actividad']) == '' ) $test = false;        
        if(trim($data['unidadMedida']) == '' ) $test = false;        
        if(trim($data['fechaIni']) == '' ) $test = false;        
        if(trim($data['fechaFin']) == '' ) $test = false;        
        
        return $test;
    }      
    
    private function _bitacoraActualizarProyecto($data)
    {
       $bitacora = new Bitacora();
       $proyecto = $data['proyecto'];
       
       $registro='Modificación de datos en Form'.$data['form'].' de Proyecto: '.
       $proyecto->getCodigo().' - '.$proyecto->getNombre().'. Realizado por: ';
       $registro.=$this->getUser()->__toString().' ('.$this->getUser()->getCorreo().')';           
             
       $bitacora->setRegistro($registro);
       $bitacora->setEntidad('Proyecto');
       $bitacora->setAccion('UPDATE');
       $bitacora->setUsuario($this->getUser());
       $bitacora->setIp($_SERVER['REMOTE_ADDR']);
       //$bitacora->setIp($this->get('request')->getClientIp());       
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
    
    private function _bitacoraEliminarProyecto($datos)
    {
       $bitacora = new Bitacora();
       
       $registro='Eliminación de Proyecto: '.$datos['codigo'].
                 ' - '.$datos['nombre'].'. Realizado por: ';
       $registro.=$this->getUser()->__toString().' ('.$this->getUser()->getCorreo().')';           
             
       $bitacora->setRegistro($registro);
       $bitacora->setEntidad('Proyecto');
       $bitacora->setAccion('DELETE');
       $bitacora->setUsuario($this->getUser());
       $bitacora->setIp($_SERVER['REMOTE_ADDR']);
       //$bitacora->setIp($this->get('request')->getClientIp());
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
    
    private function _bitacoraAgregarObjetivoEspecifico($data)
    {
       $bitacora = new Bitacora();
       $proyecto = $data['proyecto'];
       
       $registro='Registro de Objetivo Especifico: '.$data['codigo'].' - '.
                 $data['objetivoEspecifico'].'. Para el Proyecto: '.            
                 $proyecto->getCodigo().' - '.$proyecto->getNombre().
                 '. Realizado por: '.
                 $this->getUser()->__toString().' ('.$this->getUser()->getCorreo().')';           
             
       $bitacora->setRegistro($registro);
       $bitacora->setEntidad('ObjetivoEspecifico');
       $bitacora->setAccion('INSERT');
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
    
    private function _bitacoraActualizarObjetivoEspecifico($data)
    {
       $bitacora = new Bitacora();
       
       $objetivo = $data['objetivo'];
       $proyecto = $objetivo->getProyecto();
       
       $registro='Modificación de Objetivo Especifico: '.$data['codigo'].' - '.
                 $data['objetivoEspecifico'].'. Para el Proyecto: '.            
                 $proyecto->getCodigo().' - '.$proyecto->getNombre().
                 '. Realizado por: '.
                 $this->getUser()->__toString().' ('.$this->getUser()->getCorreo().')';           
             
       $bitacora->setRegistro($registro);
       $bitacora->setEntidad('ObjetivoEspecifico');
       $bitacora->setAccion('UPDATE');
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
    
    private function _bitacoraEliminarObjetivoEspecifico($datos)
    {
       $bitacora = new Bitacora();
       
       $registro='Eliminación de Objetivo Específico: '.$datos['codigo'].
                 ' - '.$datos['objetivoEspecifico'].', del Proyecto: '.$datos['codProy'].
                 ' - '.$datos['proyecto'].'. Realizado por: '.
       $this->getUser()->__toString().' ('.$this->getUser()->getCorreo().')';           
             
       $bitacora->setRegistro($registro);
       $bitacora->setEntidad('ObjetivoEspecifico');
       $bitacora->setAccion('DELETE');
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
    
    private function _bitacoraAgregarActividad($data)
    {
       $bitacora = new Bitacora();
       $objetivo = $data['objetivo'];
       
       $registro='Registro de Actividad: '.$data['codigo'].' - '.
                 $data['actividad'].'. Para el Proyecto: '.            
                 $objetivo->getProyecto()->getCodigo().' - '.
                 $objetivo->getProyecto()->getNombre().
                 '. Realizado por: '.
                 $this->getUser()->__toString().' ('.$this->getUser()->getCorreo().')';           
             
       $bitacora->setRegistro($registro);
       $bitacora->setEntidad('Actividad');
       $bitacora->setAccion('INSERT');
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
    
    private function _bitacoraActualizarActividad($data)
    {
       $bitacora = new Bitacora();
       
       $actividad = $data['act'];
       $proyecto = $actividad->getObjetivoEspecifico()->getProyecto();
       
       $registro='Modificación de Actividad: '.$data['codigo'].' - '.
                 $data['actividad'].'. Para el Proyecto: '.            
                 $proyecto->getCodigo().' - '.$proyecto->getNombre().
                 '. Realizado por: '.
                 $this->getUser()->__toString().' ('.$this->getUser()->getCorreo().')';           
             
       $bitacora->setRegistro($registro);
       $bitacora->setEntidad('Actividad');
       $bitacora->setAccion('UPDATE');
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
    
    private function _bitacoraEliminarActividad($datos)
    {
       $bitacora = new Bitacora();
       
       $registro='Eliminación de Actividad: '.$datos['codigo'].
                 ' - '.$datos['actividad'].', del Proyecto: '.$datos['codProy'].
                 ' - '.$datos['proyecto'].'. Realizado por: '.
       $this->getUser()->__toString().' ('.$this->getUser()->getCorreo().')';           
             
       $bitacora->setRegistro($registro);
       $bitacora->setEntidad('Actividad');
       $bitacora->setAccion('DELETE');
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
    
    private function _emailEnvioProyecto($data)
    {               
       $proyecto=$data['proyecto']; 
       $e =(trim($this->getUser()->getEstructura()->getSiglas()).'__SISPRO');
       $t = (trim($this->getUser()->getTelefono())!='')?    
                        'Teléfono: '.trim($this->getUser()->getTelefono()):'';
       
       $correo=$this->get('utilidades')->getCorreoSispro(); // CORREO DE NO REPLAY
       $grupoOesepp=$this->get('utilidades')->getCorreoOesepp(); // CORREO DE GRUPO OESEPP
       
       $data['titulo']='Registro de Proyecto Completado';
       $data['from']=array($correo=>$e);       
       
       $data['mensaje']='<html><head></head><body>'.
                        'El usuario: <strong>'.$this->getUser()->__toString().'</strong> '.
                        '('.$this->getUser()->getCorreo().'), perteneciente a la Unidad: '.
                        $this->getUser()->getEstructura()->getEstructura().' ('.
                        $this->getUser()->getEstructura()->getSiglas().'). <br/>'.
                        '<p>Ha completado el proceso de registro del Proyecto: <strong>'.
                        $proyecto->getCodigo().' - '.$proyecto->getNombre().'</strong>.</p>'.
                        '<p>Gracias por su amable atención.</p>'.
                        $t.
                        '<hr/>'.
                        'Correo electrónico generado por el sistema SISPROOESEPP.<br/>'.
                        '<strong>No responder.</strong>'.
                        '</body></html>';
       
       $message = \Swift_Message::newInstance()
                        ->setSubject($data['titulo'])
                        ->setFrom($data['from'])
                        ->setTo($grupoOesepp)
                        ->setBody($data['mensaje'], 'text/html');
       return $this->get('mailer')->send($message); 
    }
    
    private function _emailEnvioSolicitud($data)
    {               
       $proyecto=$data['proyecto']; 
       $e =(trim($this->getUser()->getEstructura()->getSiglas()).'__SISPRO');
       $t = (trim($this->getUser()->getTelefono())!='')?    
                        'Teléfono: '.trim($this->getUser()->getTelefono()):'';
       
       $correo=$this->get('utilidades')->getCorreoSispro(); // CORREO DE NO REPLAY
       $grupoOesepp=$this->get('utilidades')->getCorreoOesepp(); // CORREO DE GRUPO OESEPP
       
       $data['titulo']='Solicitud de Permiso de Edición de Proyecto';
       $data['from']=array($correo=>$e);       
       
       $data['mensaje']='<html><head></head><body>'.
                        'El usuario: <strong>'.$this->getUser()->__toString().'</strong> '.
                        '('.$this->getUser()->getCorreo().'), perteneciente a la Unidad: '.
                        $this->getUser()->getEstructura()->getEstructura().' ('.
                        $this->getUser()->getEstructura()->getSiglas().'). <br/>'.
                        '<p>Ha solicitado permisos de edición sobre el Proyecto: <strong>'.
                        $proyecto->getCodigo().' - '.$proyecto->getNombre().'</strong>.</p>'.
                        '<p>En espera de su amable y pronta atención.</p>'.
                        $t.
                        '<hr/>'.
                        'Correo electrónico generado por el sistema SISPROOESEPP.<br/>'.
                        '<strong>No responder.</strong>'.
                        '</body></html>';
       
       $message = \Swift_Message::newInstance()
                        ->setSubject($data['titulo'])
                        ->setFrom($data['from'])
                        ->setTo($grupoOesepp)
                        ->setBody($data['mensaje'], 'text/html');
       return $this->get('mailer')->send($message); 
    }    
 
    private function _emailAprobacionRechazoSolicitud($data)
    {
       $proyecto = $data['proyecto']; 
       $destino = $proyecto->getUsuario()->getCorreo();
       $data['nombre'] =  $proyecto->getUsuario()->__toString();
       
       $correo=$this->get('utilidades')->getCorreoSispro(); // CORREO DE NO REPLAY
               
       $data['from'] = array(
           $correo =>'Oficina Estratégica de Seguimiento y Evaluación de Políticas Públicas');       
       
       $data['titulo'] = ($data['aprobado']==1)?
                                'Solicitud de Permiso de Edición: Aprobado':
                                'Solicitud de Permiso de Edición: Rechazado';
       
       $data['proy'] = $proyecto->getCodigo().' - '.$proyecto->getNombre();
       
       $data['resultado'] = ($data['aprobado']==1)?'Aprobada':'Rechazada';
                             
       $message = \Swift_Message::newInstance()
                        ->setSubject($data['titulo'])
                        ->setFrom($data['from'])
                        ->setTo($destino)
                        ->setBody($this->renderView(
                'SisproBundle:Correos:correoAprobacionRechazoSolicitud.html.twig', $data),
                                'text/html');
       return $this->get('mailer')->send($message);
    }
}