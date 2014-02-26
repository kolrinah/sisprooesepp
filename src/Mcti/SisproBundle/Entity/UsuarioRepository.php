<?php

namespace Mcti\SisproBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UsuarioRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->select('u, r')
            ->leftJoin('u.role', 'r')                
            ->where('u.usuario = :username OR u.correo = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery() ;
        
        try {
            // El método Query::getSingleResult() lanza una excepción
            // si no hay algún registro que coincida con los criterios.
            $user = $q->getSingleResult();            
        } catch (NoResultException $e) {
            $message = sprintf(
                'Incapaz de encontrar un Admin Activo SisproBundle:Usuario objeto identificado por "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }        
        /* Si el usuario se encuentra bloqueado lanzamos una excepcion */
        if ($this->usuarioBloqueado($user)) throw new LockedException('Ha excedido el número de intentos fallidos',0);
        return $user;        
    }   
    
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }
        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }
    
    // Verificar si el usuario está bloqueado
    public function usuarioBloqueado(UserInterface $user)
    {
        if ($user->getTiempoBloqueo()>0) /* Si se encuentra bloqueado */
        {            
            if ( time() > ($user->getTiempoBloqueo() + Usuario::TIEMPO_BLOQUEO ) )
            {
               $q = $this
                    ->createQueryBuilder('u')
                    ->update()   
                    ->set('u.tiempoBloqueo',':res')   /* Desbloquea el Usuario */
                    ->set('u.intentos',':res')       /* Reinicia el Contador */
                    ->where('u.id= :iduser')   
                    ->setParameter('res', 0)
                    ->setParameter('iduser', $user->getId())
                    ->getQuery();               
               $q->execute();
               return false;
            }
            else { return true; }
        }
        return false;
    } 
    
    // Borra los intentos fallidos del usuario por su id
    public function limpiaIntentosById($id)
    {
         $q = $this
                    ->createQueryBuilder('u')
                    ->update()   
                    ->set('u.tiempoBloqueo',':reset')   /* Desbloquea el Usuario */
                    ->set('u.intentos',':reset')       /* Reinicia el Contador */
                    ->where('u.id= :iduser')   
                    ->setParameter('reset', 0)
                    ->setParameter('iduser', $id)
                    ->getQuery();               
               $q->execute();   
          return true;
    }
    
    /*
     * Obtiene los Usuarios pertenecientes a las unidades Inferiores a la
     * Estructura dada Incluyéndola
     */
    public function getUsuariosUnidadesInferiores(Estructura $estructura)
    {
       // Si se trata de la 1era Estructura (id=1)
       if ($estructura->getId()<3)
       {
          return $this->getEntityManager()
                      ->createQuery('SELECT u, e, r
                                     FROM SisproBundle:Usuario u
                                     JOIN u.estructura e
                                     LEFT JOIN u.role r
                                     ORDER BY e.id, u.correo ASC')
                      ->getResult();
       }
       
       return $this->getEntityManager()
                   ->createQuery("SELECT u,e,o,s, r 
                                  FROM SisproBundle:Usuario u
                                  JOIN u.estructura e
                                  JOIN e.superior o  
                                  JOIN o.superior s
                                  LEFT JOIN u.role r
                                  WHERE (o.id=:id
                                  OR s.id=:id
                                  OR e.id=:id )
                                  AND e.activo=TRUE
                                  AND o.activo=TRUE
                                  AND s.activo=TRUE
                                  ORDER BY e.id, u.correo ASC")
                   ->setParameter('id', $estructura->getId())
                   ->getResult();        
    }
    
    /*
     * Obtiene un array con los Usuarios de las Unidades Inferiores a la Estructura dada
     */
    public function getArrayUsuariosInferiores($idEstructura)
    {
        $usuarios=$this->getEntityManager()
                       ->createQuery("SELECT u,e,o,s,ss
                                      FROM SisproBundle:Usuario u
                                      JOIN u.estructura e
                                      JOIN e.superior o  
                                      JOIN o.superior s
                                      JOIN s.superior ss
                                      WHERE (o.id=:id
                                      OR s.id=:id
                                      OR ss.id=:id
                                      OR e.id=:id )
                                      AND e.activo=TRUE
                                      AND o.activo=TRUE
                                      AND s.activo=TRUE
                                      AND ss.activo=TRUE
                                      ORDER BY u.nombre ASC")
                       ->setParameter('id', $idEstructura)
                       ->getArrayResult();
       
        if (count($usuarios)==0) return array('0'=>'');
            
        $a=array('0'); $b=array('[Seleccione]');
        
        foreach ($usuarios as $fila)
        {
           array_push($a,$fila['id']);
           array_push($b,mb_convert_case($fila['nombre'],MB_CASE_TITLE,'UTF-8').
                     ' '.mb_convert_case($fila['apellido'],MB_CASE_TITLE, 'UTF-8').
                     ' ('.$fila['correo'].')');
        }         
        $usuarios=array_combine($a,$b);    
        return $usuarios;
    }    

    /*
     * Obtiene un array con los Usuarios de la Estructura dada
     */
    public function getArrayUsuariosEstructura($idEstructura)
    {
        $usuarios=$this->getEntityManager()
                       ->createQuery("SELECT u,e
                                      FROM SisproBundle:Usuario u
                                      JOIN u.estructura e                                      
                                      WHERE e.id=:id 
                                      AND e.activo=TRUE                                      
                                      ORDER BY u.nombre ASC")
                       ->setParameter('id', $idEstructura)
                       ->getArrayResult();
       
        if (count($usuarios)==0) return array('0'=>'Estructura Sin Usuarios');
            
        $a=array('0'); $b=array('[Seleccione]');
        
        foreach ($usuarios as $fila)
        {
           array_push($a,$fila['id']);
           array_push($b,mb_convert_case($fila['nombre'],MB_CASE_TITLE,'UTF-8').
                     ' '.mb_convert_case($fila['apellido'],MB_CASE_TITLE, 'UTF-8').
                     ' ('.$fila['correo'].')');
        }         
        $usuarios=array_combine($a,$b);    
        return $usuarios;
    }         
}

?>