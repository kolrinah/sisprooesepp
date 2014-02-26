<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Usuario
 */
class Usuario implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer 15 minutos de bloqueo
     */
    const TIEMPO_BLOQUEO = 900;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $apellido;

    /**
     * @var string
     */
    private $correo;

    /**
     * @var string
     */
    private $clave;

    /**
     * @var string
     */
    private $cargo;

    /**
     * @var string
     */
    private $telefono;

    /**
     * @var string
     */
    private $usuario;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var string
     */
    private $salt;
    
    /**
     * @var integer
     */
    private $tiempoBloqueo;    
    
    /**
     * @var integer
     */
    private $intentos;      

    /**
     * @var \Mcti\SisproBundle\Entity\Estructura
     */
    private $estructura;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $role;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->role = new \Doctrine\Common\Collections\ArrayCollection();
        $this->role[] = 'ROLE_USER'; // VALOR POR OMISION        
        $this->activo = false; // Crea al usuario inactivo para validarlo vía correo
        $this->tiempoBloqueo = 0;
        $this->intentos = 0;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = \mb_convert_case(\trim($nombre),\MB_CASE_TITLE, "UTF-8");    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return Usuario
     */
    public function setApellido($apellido)
    {
        $this->apellido = \mb_convert_case(\trim($apellido),\MB_CASE_TITLE, "UTF-8");
    
        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Usuario
     */
    public function setCorreo($correo)
    {
        $this->correo = \mb_convert_case(\trim($correo),\MB_CASE_LOWER, "UTF-8"); 
        $user=\explode('@', $correo); // Para crear el usuario
        $this->usuario = $user['0'];        
        return $this;
    }

    /**
     * Get correo
     *
     * @return string 
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set clave
     *
     * @param string $clave
     * @return Usuario
     */
    public function setClave($clave)
    {
        $this->clave = $clave;
    
        return $this;
    }

    /**
     * Get clave
     *
     * @return string 
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Set cargo
     *
     * @param string $cargo
     * @return Usuario
     */
    public function setCargo($cargo)
    {
        $this->cargo = \mb_convert_case(\trim($cargo),\MB_CASE_TITLE, "UTF-8");    
        return $this;
    }

    /**
     * Get cargo
     *
     * @return string 
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set telefonoOficina
     *
     * @param string $telefonoOficina
     * @return Usuario
     */
    public function setTelefono($telefono)
    {
        $this->telefono = \trim($telefono);
        return $this;
    }

    /**
     * Get telefonoOficina
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Get usuario
     *
     * @return string 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Usuario
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set tiempoBloqueo
     *
     * @param integer $tiempoBloqueo
     * @return Usuario
     */
    public function setTiempoBloqueo($tiempoBloqueo)
    {
        $this->tiempoBloqueo = $tiempoBloqueo;
    
        return $this;
    }

    /**
     * Get tiempoBloqueo
     *
     * @return integer 
     */
    public function getTiempoBloqueo()
    {
        return $this->tiempoBloqueo;
    }
    
    /**
     * Set intentos
     *
     * @param integer $intentos
     * @return Usuario
     */
    public function setIntentos($intentos)
    {
        $this->intentos = $intentos;
    
        return $this;
    }

    /**
     * Get instentos
     *
     * @return integer 
     */
    public function getIntentos()
    {
        return $this->intentos;
    }    
    
    /**
     * Set estructura
     *
     * @param \Mcti\SisproBundle\Entity\Estructura $estructura
     * @return Usuario
     */
    public function setEstructura(\Mcti\SisproBundle\Entity\Estructura $estructura)
    {
        $this->estructura = $estructura;
    
        //return $this;
    }

    /**
     * Get estructura
     *
     * @return \Mcti\SisproBundle\Entity\Estructura 
     */
    public function getEstructura()
    {
        return $this->estructura;
    }

    /**
     * Add role
     *
     * @param \Mcti\SisproBundle\Entity\Role $role
     * @return Usuario
     */
    public function addRole(\Mcti\SisproBundle\Entity\Role $role)
    {
        $this->role[] = $role;
    
        return $this;
    }

    /**
     * Remove role
     *
     * @param \Mcti\SisproBundle\Entity\Role $role
     */
    public function removeRole(\Mcti\SisproBundle\Entity\Role $role)
    {
        $this->role->removeElement($role);
    }

    /**
     * Get role
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRole()
    {
        return $this->role;
    }
    
    // METODOS AGREGADOS CON UserInterface
    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->usuario;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->clave;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        //return array('ROLE_USER'); 
        return $this->role->toArray();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }   
    
    /* Métodos nuevos con AdvancedUserInterface
     * 
     */
    public function isAccountNonExpired()
    {
         return true;
    }
    
    /**
     * Verfica Bloqueo
     *
     * @return boolean 
     */
    public function isAccountNonLocked()
    {
      if ($this->tiempoBloqueo>0) /* Si se encuentra bloqueado */
      {            
          if ( time() > ($this->tiempoBloqueo + self::TIEMPO_BLOQUEO ) )
          {
            $this->tiempoBloqueo = 0; /* Desbloqueo del usuario */   
            return true;
          }
          else { return false; }
      }
      return true;
    }

    /**
     * Verfica Bloqueo
     *
     * @return boolean 
     */    
    public function isCredentialsNonExpired()
    {
            return true;
    }

    /**
     * Verfica Inhabilitacion
     *
     * @return boolean 
     */
    public function isEnabled()
    {
        return $this->activo;
    }
    
    /*
     * Verificamos si la cuenta es del MCTI
     */
    public function isMcti()
    {
        $mcti=explode('@', $this->correo);
        
        if ($mcti[1]=='mcti.gob.ve') return true;        
        return false;
    }
       
    public function __toString()
    {
       return $this->getNombre().' '.$this->getApellido();
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     * @return Usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    
        return $this;
    }
}