<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bitacora
 */
class Bitacora
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var string
     */
    private $entidad;

    /**
     * @var string
     */
    private $accion;

    /**
     * @var string
     */
    private $registro;

    /**
     * @var \Mcti\SisproBundle\Entity\Usuario
     */
    private $usuario;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Bitacora
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Bitacora
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     * @return Bitacora
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    
        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string 
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set entidad
     *
     * @param string $entidad
     * @return Bitacora
     */
    public function setEntidad($entidad)
    {
        $this->entidad = $entidad;
    
        return $this;
    }

    /**
     * Get entidad
     *
     * @return string 
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * Set accion
     *
     * @param string $accion
     * @return Bitacora
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;
    
        return $this;
    }

    /**
     * Get accion
     *
     * @return string 
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set registro
     *
     * @param string $registro
     * @return Bitacora
     */
    public function setRegistro($registro)
    {
        $this->registro = $registro;
    
        return $this;
    }

    /**
     * Get registro
     *
     * @return string 
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * Set usuario
     *
     * @param \Mcti\SisproBundle\Entity\Usuario $usuario
     * @return Bitacora
     */
    public function setUsuario(\Mcti\SisproBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Mcti\SisproBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}