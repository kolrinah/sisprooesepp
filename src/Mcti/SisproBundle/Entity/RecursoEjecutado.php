<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecursoEjecutado
 */
class RecursoEjecutado
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
     * @var \Mcti\SisproBundle\Entity\Actividad
     */
    private $actividad;

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
     * @return RecursoEjecutado
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
     * @return RecursoEjecutado
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
     * @return RecursoEjecutado
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
     * Set actividad
     *
     * @param \Mcti\SisproBundle\Entity\Actividad $actividad
     * @return RecursoEjecutado
     */
    public function setActividad(\Mcti\SisproBundle\Entity\Actividad $actividad = null)
    {
        $this->actividad = $actividad;
    
        return $this;
    }

    /**
     * Get actividad
     *
     * @return \Mcti\SisproBundle\Entity\Actividad 
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set moneda
     *
     * @param \Mcti\SisproBundle\Entity\Moneda $moneda
     * @return RecursoEjecutado
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
