<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Municipio
 */
class Municipio
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $municipio;

    /**
     * @var integer
     */
    private $poblacion;

    /**
     * @var integer
     */
    private $ica;

    /**
     * @var string
     */
    private $capitalMunicipio;

    /**
     * @var string
     */
    private $codigoOnapre;

    /**
     * @var float
     */
    private $superficie;

    /**
     * @var string
     */
    private $mapArea;

    /**
     * @var \Mcti\SisproBundle\Entity\Estado
     */
    private $estado;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $proyecto;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->proyecto = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set municipio
     *
     * @param string $municipio
     * @return Municipio
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    
        return $this;
    }

    /**
     * Get municipio
     *
     * @return string 
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Set poblacion
     *
     * @param integer $poblacion
     * @return Municipio
     */
    public function setPoblacion($poblacion)
    {
        $this->poblacion = $poblacion;
    
        return $this;
    }

    /**
     * Get poblacion
     *
     * @return integer 
     */
    public function getPoblacion()
    {
        return $this->poblacion;
    }

    /**
     * Set ica
     *
     * @param integer $ica
     * @return Municipio
     */
    public function setIca($ica)
    {
        $this->ica = $ica;
    
        return $this;
    }

    /**
     * Get ica
     *
     * @return integer 
     */
    public function getIca()
    {
        return $this->ica;
    }

    /**
     * Set capitalMunicipio
     *
     * @param string $capitalMunicipio
     * @return Municipio
     */
    public function setCapitalMunicipio($capitalMunicipio)
    {
        $this->capitalMunicipio = $capitalMunicipio;
    
        return $this;
    }

    /**
     * Get capitalMunicipio
     *
     * @return string 
     */
    public function getCapitalMunicipio()
    {
        return $this->capitalMunicipio;
    }

    /**
     * Set codigoOnapre
     *
     * @param string $codigoOnapre
     * @return Municipio
     */
    public function setCodigoOnapre($codigoOnapre)
    {
        $this->codigoOnapre = $codigoOnapre;
    
        return $this;
    }

    /**
     * Get codigoOnapre
     *
     * @return string 
     */
    public function getCodigoOnapre()
    {
        return $this->codigoOnapre;
    }

    /**
     * Set superficie
     *
     * @param float $superficie
     * @return Municipio
     */
    public function setSuperficie($superficie)
    {
        $this->superficie = $superficie;
    
        return $this;
    }

    /**
     * Get superficie
     *
     * @return float 
     */
    public function getSuperficie()
    {
        return $this->superficie;
    }

    /**
     * Set mapArea
     *
     * @param string $mapArea
     * @return Municipio
     */
    public function setMapArea($mapArea)
    {
        $this->mapArea = $mapArea;
    
        return $this;
    }

    /**
     * Get mapArea
     *
     * @return string 
     */
    public function getMapArea()
    {
        return $this->mapArea;
    }

    /**
     * Set estado
     *
     * @param \Mcti\SisproBundle\Entity\Estado $estado
     * @return Municipio
     */
    public function setEstado(\Mcti\SisproBundle\Entity\Estado $estado = null)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return \Mcti\SisproBundle\Entity\Estado 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Add proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     * @return Municipio
     */
    public function addProyecto(\Mcti\SisproBundle\Entity\Proyecto $proyecto)
    {
        $this->proyecto[] = $proyecto;
    
        return $this;
    }

    /**
     * Remove proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     */
    public function removeProyecto(\Mcti\SisproBundle\Entity\Proyecto $proyecto)
    {
        $this->proyecto->removeElement($proyecto);
    }

    /**
     * Get proyecto
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }
}