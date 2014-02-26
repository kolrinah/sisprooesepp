<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MetaAlcanzada
 */
class MetaAlcanzada
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $meta;

    /**
     * @var string
     */
    private $observaciones;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var \Mcti\SisproBundle\Entity\Actividad
     */
    private $actividad;


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
     * Set meta
     *
     * @param float $meta
     * @return MetaAlcanzada
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    
        return $this;
    }

    /**
     * Get meta
     *
     * @return float 
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return MetaAlcanzada
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return MetaAlcanzada
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
     * Set actividad
     *
     * @param \Mcti\SisproBundle\Entity\Actividad $actividad
     * @return MetaAlcanzada
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
}
