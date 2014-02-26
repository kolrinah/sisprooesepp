<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProyectoFuenteFinanciamiento
 */
class ProyectoFuenteFinanciamiento
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $monto;

    /**
     * @var \Mcti\SisproBundle\Entity\Proyecto
     */
    private $proyecto;

    /**
     * @var \Mcti\SisproBundle\Entity\FuenteFinanciamiento
     */
    private $fuenteFinanciamiento;

    /**
     * @var \Mcti\SisproBundle\Entity\Moneda
     */
    private $moneda;

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
     * Set monto
     *
     * @param float $monto
     * @return ProyectoFuenteFinanciamiento
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;
    
        return $this;
    }

    /**
     * Get monto
     *
     * @return float 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     * @return ProyectoFuenteFinanciamiento
     */
    public function setProyecto(\Mcti\SisproBundle\Entity\Proyecto $proyecto = null)
    {
        $this->proyecto = $proyecto;
    
        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \Mcti\SisproBundle\Entity\Proyecto 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }

    /**
     * Set fuenteFinanciamiento
     *
     * @param \Mcti\SisproBundle\Entity\FuenteFinanciamiento $fuenteFinanciamiento
     * @return ProyectoFuenteFinanciamiento
     */
    public function setFuenteFinanciamiento(\Mcti\SisproBundle\Entity\FuenteFinanciamiento $fuenteFinanciamiento = null)
    {
        $this->fuenteFinanciamiento = $fuenteFinanciamiento;
    
        return $this;
    }

    /**
     * Get fuenteFinanciamiento
     *
     * @return \Mcti\SisproBundle\Entity\FuenteFinanciamiento 
     */
    public function getFuenteFinanciamiento()
    {
        return $this->fuenteFinanciamiento;
    }

    /**
     * Set moneda
     *
     * @param \Mcti\SisproBundle\Entity\Moneda $moneda
     * @return ProyectoFuenteFinanciamiento
     */
    public function setMoneda(\Mcti\SisproBundle\Entity\Moneda $moneda = null)
    {
        $this->moneda = $moneda;
    
        return $this;
    }

    /**
     * Get moneda
     *
     * @return \Mcti\SisproBundle\Entity\Moneda 
     */
    public function getMoneda()
    {
        return $this->moneda;
    }
}