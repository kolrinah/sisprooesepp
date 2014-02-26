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
use Mcti\SisproBundle\Entity\Fotografia;
use Mcti\SisproBundle\Form\FotografiaType;

use Symfony\Component\HttpFoundation\Request;

class FotografiaController extends Controller
{
    /*
     * GET  Prepara Formulario para Registrar Fotografia
     * POST Guarda el Registro
     */
    public function registrarFotografiaAction(Request $request)
    {
       $fotografia= new Fotografia();
       
       if ($request->isMethod('GET')) 
       {                   
           if (!$request->query->has('id'))
           return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
                      
           $idProyecto = $request->query->get('id');
           
           $data['idProyecto'] = $idProyecto;
       
           // Hidratamos los campos del formulario
           $data = $this->_hidratarForm($data);
           
           $formulario = $this->createForm(new FotografiaType($data['actividades']), $fotografia);
       }   
       
       if ($request->isMethod('POST'))
       {
           $data = $request->request->get('data');           
           
           $actividad = $this->getDoctrine()
                             ->getRepository('SisproBundle:Actividad')
                             ->find($data['actividad']);           
           if (!$actividad) die('Actividad no encontrada.');
           
           $proyecto = $actividad->getProyecto();
           $data['proyecto'] = $proyecto;
           $data['idProyecto'] = $proyecto->getId();
           
           // Hidratamos los campos del formulario
           $data = $this->_hidratarForm($data);
           
           $formulario = $this->createForm(new FotografiaType($data['actividades']), $fotografia);
           
           $formulario->bind($request);           
           
           $data['fotografia'] = $fotografia;
           
           if ($formulario->isValid())
           {  
               $this->_registrarFotografia($data);
              
               // REGISTRA LA ACCION EN LA BITACORA
               $data['registro']='Registro de Fotografia en el proyecto: '.
                                  $proyecto->getCodigo().' - '.$proyecto->getNombre().            
                                  '. Realizado por: '.$this->getUser()->__toString(); 
               $data['entidad']='Fotografia';
               $data['operacion']='INSERT';               
               
               $this->_registrarBitacora($data);

               return $this->render('SisproBundle:Ejecucion:exitoFotografia.html.twig',       
                      array('data' => $data));
           }
       }  
       // REVISAMOS PERMISOLOGÍA PARA VER EL PROYECTO
       if (!$this->_revisarPermisologia($data))
       return $this->render('SisproBundle:Plantillas:prohibido.html.twig'); 
       
       // Hidratamos las fotos
       $data = $this->_hidratarFotos($data);
       
       return $this->render('SisproBundle:Ejecucion:registrarFotografia.html.twig',       
                      array('formulario' => $formulario->createView(),
                                  'data' => $data));              
    }

    /*
     * ELIMINA FOTOGRAFIA
     */
    public function eliminarFotografiaAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }       
       
       $idFoto = $request->request->get('id');  
       
       $em = $this->getDoctrine()->getManager();
       
       $foto = $em->getRepository('SisproBundle:Fotografia')
                  ->find($idFoto);
    
       if (!$foto) die(json_encode(array('error'=>'Registro no encontrado')));
       
       $proyecto = $foto->getActividad()->getObjetivoEspecifico()->getProyecto();
       $data['proyecto'] = $proyecto;
       $data['idProyecto'] = $proyecto->getId();
       //Información para el registro en bitácora
       $data['registro'] ="Eliminación de Fotografia id: $idFoto, en el Proyecto: ".
                          $proyecto->getCodigo()." - ".$proyecto->getNombre().
                          ". Realizado por: ".
                          $this->getUser()->__toString().".";
       
       $data['entidad']="Fotografia";
       $data['operacion']="DELETE";
       
       $error='';
       $foto->removerFoto($foto); // REMOVEMOS ARCHIVO DEL DISCO      
       
       $em->remove($foto);            
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
       $data = $this->_hidratarForm($data);
       $data = $this->_hidratarFotos($data);
       
       $this->_registrarBitacora($data);
       
       die(json_encode(array('error'=>$error,
                             'badge'=>count($data['fotos']),
                             'panel'=>$this->_panelFotografia($data))) );
    }  
    
    /*
     * Actualiza Panel de Fotografia
     */
    private function _panelFotografia($data)
    {      
       return $this->renderView('SisproBundle:Ejecucion:fotografias.html.twig',
                      array('data' => $data));
    }    
       
    /*
     * Hidratamos los campos del formulario
     */
    private function _hidratarForm($data)
    {       
       // BUSCAMOS LAS ACTIVIDADES Y SUS OBJETIVOS ESPECIFICOS
       $em = $this->getDoctrine()->getManager();
       $query = $em->createQuery('SELECT a
                                  FROM SisproBundle:Actividad a
                                  JOIN a.objetivoEspecifico oe
                                  JOIN oe.proyecto p
                                  WHERE p.id = :proy
                                  ORDER BY oe.codigo, a.codigo')
                   ->setParameter('proy', $data['idProyecto'] );
       $data['actividades'] = $query->getResult();            
       
       if (!isset($data['proyecto']))
       {
           $proyecto = $this->getDoctrine()
                            ->getRepository('SisproBundle:Proyecto')
                            ->find($data['idProyecto']);
           if (!$proyecto) die('Proyecto no encontrado.');
       
           $data['proyecto'] = $proyecto;
       }                         
       return $data;
    }
    
    /*
     * Hidratamos la lista de fotos
     */
    private function _hidratarFotos($data)
    {
        $data['fotos'] = $this->getDoctrine()
                              ->getRepository('SisproBundle:Proyecto')
                              ->getFotografiasProyecto($data['proyecto']);
        return $data;
    }


    /*
     * Guardamos el Registro de Recursos Ejecutados
     */
    private function _registrarFotografia($data)
    {     
       $proyecto = $data['proyecto'];
       $carpeta = $this->container->getParameter('dir.adjuntos').$proyecto->getCodigo().'/';
       
       $fotografia = $data['fotografia'];
       
       $fotografia->subirFoto($carpeta);
       
       $em = $this->getDoctrine()->getManager();       
       $em->persist($fotografia);
       try {
             $em->flush();
           } catch (\Exception $e) { // Atrapa Error del servidor
                if(stristr($e->getMessage(), 'Not null violation')) 
                {
                  $error ='No ha llenado todos los campos.';
                }else  $error=$e->getMessage();
                
                die(json_encode(array('error'=>$error)));
       } 
       /* Luego de ser exitosa la subidad de la foto, procedemos a verificar su tamaño 
        * y ajustarlo de ser necesario
        */
       $rutaImagen = $carpeta.$fotografia->getImagen();
       $imagenInfo=@getimagesize($rutaImagen);
      
       if ($imagenInfo['0']>800)
       {
          $config['image_library'] = 'gd2';
          $config['source_image'] = $rutaImagen;
          $config['create_thumb'] = FALSE;
          $config['maintain_ratio'] = TRUE;
          $config['width'] = 800;  
          $config['height'] = 600; 
          $config['master_dim'] = 'width';  
          
          $imagen = $this->get('imageLib');
          $imagen->initialize($config);
          if (!$imagen->resize())die(json_encode(array('error'=>$imagen->display_errors()))); 
       }
        return true;    
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
            ($this->get('security.context')->isGranted('ROLE_ENLACE') ) )  //ROL ENLACE
       
       return true;
       else return false;
    }    
}

// $session = $this->get('Session');
// \Doctrine\Common\Util\Debug::dump($session);
// $session->getFlashBag()->add('info', 'Archivo guardado Satisfactoriamente');