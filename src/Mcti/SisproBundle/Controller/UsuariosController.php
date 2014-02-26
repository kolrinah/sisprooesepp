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
use Mcti\SisproBundle\Entity\Usuario;
use Mcti\SisproBundle\Entity\Bitacora;
use Mcti\SisproBundle\Form\UsuarioType;
use Mcti\SisproBundle\Form\PerfilUsuarioType;
use Mcti\SisproBundle\Form\CambiarClaveType;
use Symfony\Component\HttpFoundation\Request;

class UsuariosController extends Controller
{
    /*
     * LISTA LOS USUARIOS DE LAS UNIDADES INFERIORES AL USUARIO AUTENTICADO
     */
    public function listarUsuariosAction()
    {      
      $session = $this->get('Session');
      if (time() - $session->getMetadataBag()->getLastUsed()>6000)
      {
          $session->invalidate();
          return $this->redirect($this->generateUrl('logout'), 301);
      }
      
      // Buscamos los Objetos Usuario de las estructuras inferiores
      $usuarios = $this->getDoctrine()
                       ->getRepository('SisproBundle:Usuario')
                       ->getUsuariosUnidadesInferiores($this->getUser()->getEstructura());
      
      if (!$usuarios)return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
      
      return $this->render('SisproBundle:Usuarios:listar.html.twig', 
                array('usuarios'=>$usuarios));
    }
    
    /*
     * PREPARA FORMULARIO PARA REGISTRAR USUARIO
     */
    public function nuevoUsuarioAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       $usuario = new Usuario();
       $estructuras = $this->getDoctrine()
                           ->getRepository('SisproBundle:Estructura')
                           ->getUnidadesInferiores($this->getUser()->getEstructura());
               
       $formulario = $this->createForm(new UsuarioType(), $usuario);
       
       if ($request->isMethod('POST')) 
       {          
           $paquete = $request->request->get('mcti_sisprobundle_usuariotype');
           // VERIFICAMOS LA INTEGRIDAD DE LOS DATOS
           if (!isset($paquete['correo'])) die ('ERROR: Datos del Método POST Corruptos');
                  
           $paquete['role'][]   = '1'; // ESTABLECE EL ROLE_USER POR OMISIÓN
           
           $request->request->set('mcti_sisprobundle_usuariotype',$paquete);
           
           $formulario->bind($request);
          
          if ($formulario->isValid())
          {             
            $usuario = $this->_claveInicial($usuario);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);            
            try {       
                $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor
                if(stristr($e->getMessage(), 'Not null violation')) 
                {
                  die('Error: No ha seleccionado la Unidad o Ente');
                }else
                {
                    if(stristr($e->getMessage(), 'Unique violation')) 
                    {
                       die('Error: Usuario Duplicado en el Servidor');
                    }
                    die('Error: '.$paquete['nombre'].' '.$e->getMessage());
                }
            }
            // AL SER CREADO EXITOSAMENTE SE LE ENVIA UN CORREO PARA ACTIVAR LA CUENTA
            $this->enviarCorreo($usuario);
            // GUARDA LA ACCION EN LA BITACORA;
            $bitacora= new Bitacora();
            $registro='Registro de Usuario: '.$usuario->getCorreo().', realizado por: ';
            $registro.=$this->getUser()->__toString();           
            
            $bitacora->setRegistro($registro);
            $bitacora->setEntidad('Usuario');
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
              die('Ocurrió un error al registrar en la Bitácora: '.$e->getMessage());  
            }            
            die('Exito. Usuario Registrado Satisfactoriamente.');
          }

        }       
        return $this->render('SisproBundle:Usuarios:nuevo.html.twig',
                      array('formulario' => $formulario->createView(),
                            'estructuras'=> $estructuras)
        ); 
    }
    
    /*
     * PREPARA FORMULARIO PARA EDITAR USUARIO
     */
    public function editarUsuarioAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) // Verifica si la petición No es de AJAX 
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       $data['mcti']=false;
       
       $estructuras = $this->getDoctrine()
                           ->getRepository('SisproBundle:Estructura')
                           ->getUnidadesInferiores($this->getUser()->getEstructura());
       
       if ($request->isMethod('GET')) 
       {          
           $id = $request->query->get('id');
           $usuario = $this->getDoctrine()
                           ->getRepository('SisproBundle:Usuario')
                           ->find($id);
           if (!$usuario) die('Error: Usuario no encontrado');
           $formulario = $this->createForm(new UsuarioType(), $usuario);
       }           
       
       if ($request->isMethod('POST')) 
       {    
           $paquete = $request->request->get('mcti_sisprobundle_usuariotype');                     
           $usuario = $this->getDoctrine()
                           ->getRepository('SisproBundle:Usuario')
                           ->findOneByCorreo($paquete['correo']);          
           
           if (!$usuario) die('Error: Usuario no encontrado');
           $formulario = $this->createForm(new UsuarioType(), $usuario);
           $formulario->bind($request);            
           
          if ($formulario->isValid())
          {             
            $em = $this->getDoctrine()->getManager();           
            try {       
                $em->flush();            
            } catch (\Exception $e) { // Atrapa Error del servidor
                if(stristr($e->getMessage(), 'Not null violation')) 
                {
                  die('Error: No ha seleccionado la Unidad o Ente');
                }else
                {
                    if(stristr($e->getMessage(), 'Unique violation')) 
                    {
                       die('Error: Usuario Duplicado en el Servidor');
                    }
                    die('Error: '.$e->getMessage());
                }
            }
            // REGISTRA LA ACCION EN LA BITACORA
            $bitacora= new Bitacora();
            $registro='Modificación de datos de Usuario: '.$usuario->getCorreo().', realizado por: ';
            $registro.=$this->getUser()->__toString();           
            
            $bitacora->setRegistro($registro);
            $bitacora->setEntidad('Usuario');
            $bitacora->setAccion('UPDATE');
            $bitacora->setUsuario($this->getUser());
            $bitacora->setIp($_SERVER['REMOTE_ADDR']);            
            $bitacora->setUserAgent($this->getRequest()->headers->get('user-agent'));
            $bitacora->setFecha(new \DateTime('now'));
                    
            $em = $this->getDoctrine()->getManager();
            $em->persist($bitacora);            
            try {       
                  $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor
              die('Ocurrió un error al registrar en la Bitácora: '.$e->getMessage());  
            }                 
            die('Exito. Usuario Actualizado Satisfactoriamente.');
          }         
       }
       $data['mcti']=$usuario->isMcti();
       return $this->render('SisproBundle:Usuarios:editar.html.twig',
                      array('formulario' => $formulario->createView(),
                            'estructuras'=> $estructuras,
                                  'data' => $data )
        ); 
    }
    
    /*
     * Modificar el Perfil de usuario
     */
    public function perfilUsuarioAction(Request $request)
    {
       if ($request->isMethod('GET')) 
       {          
           $id = $this->getUser()->getId();
           $usuario = $this->getDoctrine()
                           ->getRepository('SisproBundle:Usuario')
                           ->find($id);
           if (!$usuario) die('Error: Usuario no encontrado');          
            
           $formulario = $this->createForm(new PerfilUsuarioType(), $usuario);           
       }           
       
       if ($request->isMethod('POST')) 
       {    
           $paquete = $request->request->get('mcti_sisprobundle_perfilusuariotype');                     
           $usuario = $this->getDoctrine()
                           ->getRepository('SisproBundle:Usuario')
                           ->findOneByCorreo($paquete['correo']);          
           
           if (!$usuario) die('Error: Usuario no encontrado');
           $formulario = $this->createForm(new PerfilUsuarioType(), $usuario);
           $formulario->bind($request);            
           
          if ($formulario->isValid())
          {             
            $em = $this->getDoctrine()->getManager();             
            try {       
                $data['titulo']="Exito";
                $data['mensaje']="Usuario Actualizado Satisfactoriamente.";
                $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor               
              $data['titulo']="Error";
              $data['mensaje']=$e->getMessage();
            }
            // REGISTRA LA ACCION EN LA BITACORA
            $bitacora= new Bitacora();
            $registro='Actualización de Perfil de Usuario: '.$usuario->getCorreo().', realizado por: ';
            $registro.=$this->getUser()->__toString();           
            
            $bitacora->setRegistro($registro);
            $bitacora->setEntidad('Usuario');
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
              die('Ocurrió un error al registrar en la Bitácora: '.$e->getMessage());  
            } 
            
            return $this->render('SisproBundle:Plantillas:mensajes.html.twig', $data);
          }         
       }
       return $this->render('SisproBundle:Usuarios:perfil.html.twig',
                      array('formulario' => $formulario->createView())
        );         
    }
    
    /*
     * Cambiar clave de Usuario
     */
    public function cambiarClaveAction(Request $request)
    {
       $data['mensaje']='';
       if ($request->isMethod('GET')) 
       {          
           $id = $this->getUser()->getId();
           $usuario = $this->getDoctrine()
                           ->getRepository('SisproBundle:Usuario')
                           ->find($id);
           if (!$usuario) die('Error: Usuario no encontrado');          
            
           $formulario = $this->createForm(new CambiarClaveType(), $usuario);           
       }           
       
       if ($request->isMethod('POST')) 
       {              
           $paquete = $request->request->get('mcti_sisprobundle_cambiarclavetype');
           $paquete['clave']['first']=trim($paquete['clave']['first']);
           $paquete['clave']['second']=trim($paquete['clave']['second']);
           $request->request->set('mcti_sisprobundle_cambiarclavetype',$paquete);
           
           $usuario = $this->getDoctrine()
                           ->getRepository('SisproBundle:Usuario')
                           ->findOneByCorreo($paquete['correo']);          
           
           if (!$usuario) die('Error: Usuario no encontrado');
           
           // Verificamos que haya introducido la contraseña actual
           $vieja= $request->request->get('clavevieja');
           $encoder = $this->get('security.encoder_factory')
                           ->getEncoder($usuario);
           
           $claveCodificada = $encoder->encodePassword($vieja, $usuario->getSalt());
           $formulario = $this->createForm(new CambiarClaveType(), $usuario);
           if ($claveCodificada == $usuario->getClave())
           {              
              $formulario->bind($request);            
              if ($formulario->isValid())
              {             
                $usuario->setSalt(md5(time()));
                $claveCodificada = $encoder->encodePassword($usuario->getClave(),
                                                            $usuario->getSalt());
                $usuario->setClave($claveCodificada);
                $usuario->setActivo(true);                  
                  
                $em = $this->getDoctrine()->getManager();             
                //$em->persist($usuario);
                try {       
                    $data['titulo']="Exito";
                    $data['mensaje']="Usuario Actualizado Satisfactoriamente.";
                    $em->flush();
                } catch (\Exception $e) { // Atrapa Error del servidor               
                $data['titulo']="Error";
                $data['mensaje']=$e->getMessage();
                }
                return $this->render('SisproBundle:Plantillas:mensajes.html.twig', $data);
              }         
           }else
           {
              $data['mensaje']='Contraseña incorrecta.';
           }
       }
       return $this->render('SisproBundle:Usuarios:clave.html.twig',
                               array('formulario' => $formulario->createView(), 
                                           'data' => $data ) ); 
    }
    
    /*
     * Reiniciar Clave
     */
    public function reiniciarClaveAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
         return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }       
       $correo = $request->request->get('correo'); 
      
       $usuario = $this->getDoctrine()
                       ->getRepository('SisproBundle:Usuario')
                       ->findOneByCorreo($correo);
       if (!$usuario) die('Error: Usuario no encontrado');
       
       $usuario = $this->_claveInicial($usuario);
       
       $em = $this->getDoctrine()->getManager(); 
       
       try {            
            $em->flush();
            } catch (\Exception $e) { // Atrapa Error del servidor               
              die('Error: '.$e->getMessage());
            }       
       $e=$this->enviarCorreo($usuario);
       die('Exito. Revise la Bandeja de entrada de su correo.');
       // Enviar correo
    }
    
    /*
     * METODO PARA ENVIAR CORREO DE ACTIVACIÓN
     */
    public function enviarCorreo($usuario)
    {
       $url=$this->generateUrl('activarCuenta',
                            array('cuenta'=>$usuario->getCorreo(),
                                    'salt'=>$usuario->getSalt()),true);      
      
       $correo=$this->get('utilidades')->getCorreoSispro();
       
       $data['titulo']='Activación de cuenta';
       $data['from']=array(
         $correo => 'Oficina Estratégica de Seguimiento y Evaluación de Políticas Públicas');
       
       $data['nombre']=$usuario->getNombre();
       $data['url']=$url;
       
       $message = \Swift_Message::newInstance()
                        ->setSubject($data['titulo'])
                        ->setFrom($data['from'])
                        ->setTo($usuario->getCorreo())
                        ->setBody( $this->renderView(
            'SisproBundle:Correos:correoActivacion.html.twig', $data),'text/html');
       return $this->get('mailer')->send($message);        
    }
    
    /*
     * BÚSQUEDA DE USUARIO EN BASE DE DATOS Y EN LDAP
     */
    public function buscarUsuarioAction(Request $request)
    {
       if (!($this->getRequest()->isXmlHttpRequest())) /* Verifica si la petición No es de AJAX */
       {
          return $this->render('SisproBundle:Plantillas:prohibido.html.twig');
       }
       
       $correo = \mb_convert_case(\trim($request->request->get('correo')),\MB_CASE_LOWER);       
       /* Buscamos al usuario en la Entidad Usuario */
       $usuarios = $this->getDoctrine()
                        ->getRepository('SisproBundle:Usuario');
       
       $usuario = $usuarios->findOneByCorreo($correo);
       /* Si el usuario no fue encontrado */
       if(!$usuario)
       {
          $dns =  explode('@', $correo);          
               
          if($dns[1] ==='mcti.gob.ve') /* Si el usuario pertenece al mcti */          
          {
              $userLDAP = $this->_buscarLDAP($correo);  /* Buscamos al usuario en LDAP */
              if (!$userLDAP) /* Error de conexión con LDAP */
              {
                  /* 0 ERROR DE CONEXIÓN CON LDAP */
                  die(json_encode(array('status' => 0,
                                        'mensaje'=> 'No se pudo establecer conexión con LDAP')));
              }else
                   { /* 1 USUARIO NO ENCONTRADO EN LDAP */
                     if ($userLDAP['status']==1)
                     {
                        $userLDAP['mensaje'] = 'Usuario no encontrado en LDAP';
                        die(json_encode($userLDAP));
                     }
                     /* 2 USUARIO NO EXISTE EN BD PERO SI EN LDAP */
                     $userLDAP['status'] = 2;
                     $userLDAP['mensaje']='Usuario encontrado en LDAP. Complete los campos para guardar';
                     die(json_encode($userLDAP));
                   }
          }else  /* Usuario no es del MCTI */
               {
                /* 3 USUARIO NO EXISTE EN BD Y NO ES MCTI */
                die(json_encode(array('status'=>3)));
               }
       }
       /* 4 USUARIO YA EXISTE EN BD ERROR*/
       die(json_encode(array('status'=>4,
                             'mensaje'=>'El usuario ya se encuentra registrado en Sistema',                             
                             'nombre'=>$usuario->getNombre(),
                             'apellido'=>$usuario->getApellido(),
                             'estructura'=>$usuario->getEstructura()->getEstructura())));
    }

    /*
     * BÚQUEDA DE USUARIO EN LDAP
     */    
    private function _buscarLDAP($correo)
    {   
        //return false; // Comentar cuando esté en servidor
       /* return (array('status'  => 2,
                      'nombre'  =>'Blanca',
                      'apellido'=>'Nieves')); // Comentar cuando esté en el servidor*/
       
        $ds = ldap_connect("ldap.mct.gob.ve");
        if(!$ds){return false;} // NO SE PUDO ESTABLECER CONEXION CON LDAP
        
        $dn = "o=MCTI,dc=mcti,dc=gob,dc=ve";
        $necesito=array("givenname","sn","mail");
        $busqueda= ldap_search($ds,$dn,"mail=$correo",$necesito);
        $resultado= ldap_get_entries($ds,$busqueda);		
        if($resultado["count"]==0)  // USUARIO NO ENCONTRADO EN LDAP
        {
            return array('status'=>1);
        }
       // USUARIO ENCONTRADO
       $nombre=$resultado[0]["givenname"];
       $apellido=$resultado[0]["sn"];       
       $userLDAP=array(
           'status'   => 2,
           'nombre'   => mb_convert_case(trim($nombre[0]),MB_CASE_TITLE,'UTF-8'),
           'apellido' => mb_convert_case(trim($apellido[0]),MB_CASE_TITLE,'UTF-8')         
        );       
        return $userLDAP;
    }
    
    /*
     * Método para crear una clave y salt inicial
     */
    private function _claveInicial($usuario)
    {
       $usuario->setActivo(\FALSE); // CREAMOS AL USUARIO INACTIVO PARA ACTIVARLO VIA EMAIL
           
       $encoder = $this->get('security.encoder_factory')
                       ->getEncoder($usuario);
       $usuario->setSalt(md5(time()));
       
       $passwordCodificado = $encoder->encodePassword(
                                      '24121973',
                                      $usuario->getSalt() );  

       $usuario->setClave($passwordCodificado); 
       $usuario->setTiempoBloqueo(0);
       $usuario->setIntentos(0);
        
       return $usuario;
    }
    
}