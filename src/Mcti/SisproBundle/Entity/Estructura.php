<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estructura
 */
class Estructura
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $estructura;

    /**
     * @var boolean
     */
    private $activo;

    /**
     * @var string
     */
    private $siglas;

    /**
     * @var string
     */
    private $siglas2;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $inferior;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $usuarios;

    /**
     * @var \Mcti\SisproBundle\Entity\Nivel
     */
    private $nivel;

    /**
     * @var \Mcti\SisproBundle\Entity\Estructura
     */
    private $superior;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inferior = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set estructura
     *
     * @param string $estructura
     * @return Estructura
     */
    public function setEstructura($estructura)
    {
        $this->estructura = $estructura;
    
        return $this;
    }

    /**
     * Get estructura
     *
     * @return string 
     */
    public function getEstructura()
    {
        return $this->estructura;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Estructura
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
     * Set siglas
     *
     * @param string $siglas
     * @return Estructura
     */
    public function setSiglas($siglas)
    {
        $this->siglas = $siglas;
    
        return $this;
    }

    /**
     * Get siglas
     *
     * @return string 
     */
    public function getSiglas()
    {
        return $this->siglas;
    }

    /**
     * Set siglas2
     *
     * @param string $siglas2
     * @return Estructura
     */
    public function setSiglas2($siglas2)
    {
        $this->siglas2 = $siglas2;    
        return $this;
    }
    
    /**
     * Get siglas2
     *
     * @return string 
     */
    public function getSiglas2()
    {
        return $this->siglas2;
    }

    /**
     * Add inferior
     *
     * @param \Mcti\SisproBundle\Entity\Estructura $inferior
     * @return Estructura
     */
    public function addInferior(\Mcti\SisproBundle\Entity\Estructura $inferior)
    {
        $this->inferior[] = $inferior;
    
        return $this;
    }

    /**
     * Remove inferior
     *
     * @param \Mcti\SisproBundle\Entity\Estructura $inferior
     */
    public function removeInferior(\Mcti\SisproBundle\Entity\Estructura $inferior)
    {
        $this->inferior->removeElement($inferior);
    }

    /**
     * Get inferior
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInferior()
    {
        return $this->inferior;
    }

    /**
     * Add usuarios
     *
     * @param \Mcti\SisproBundle\Entity\Usuario $usuarios
     * @return Estructura
     */
    public function addUsuario(\Mcti\SisproBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;
    
        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \Mcti\SisproBundle\Entity\Usuario $usuarios
     */
    public function removeUsuario(\Mcti\SisproBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios->removeElement($usuarios);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Set nivel
     *
     * @param \Mcti\SisproBundle\Entity\Nivel $nivel
     * @return Estructura
     */
    public function setNivel(\Mcti\SisproBundle\Entity\Nivel $nivel = null)
    {
        $this->nivel = $nivel;
    
        return $this;
    }

    /**
     * Get nivel
     *
     * @return \Mcti\SisproBundle\Entity\Nivel 
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set superior
     *
     * @param \Mcti\SisproBundle\Entity\Estructura $superior
     * @return Estructura
     */
    public function setSuperior(\Mcti\SisproBundle\Entity\Estructura $superior = null)
    {
        $this->superior = $superior;
    
        return $this;
    }

    /**
     * Get superior
     *
     * @return \Mcti\SisproBundle\Entity\Estructura 
     */
    public function getSuperior()
    {
        return $this->superior;
    }
    
    public function __toString()
    {
        return $this->getSiglas() .' - '. $this->getEstructura();
    }
}