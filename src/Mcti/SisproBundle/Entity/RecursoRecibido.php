<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecursoRecibido
 */
class RecursoRecibido
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
     * @var float
     */
    private $monto;

    /**
     * @var string
     */
    private $observaciones;

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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return RecursoRecibido
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
     * Set monto
     *
     * @param float $monto
     * @return RecursoRecibido
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
     * Set observaciones
     *
     * @param string $observaciones
     * @return RecursoRecibido
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     * @return RecursoRecibido
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
     * @return RecursoRecibido
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
     * @return RecursoRecibido
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
