<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AreaEstrategica
 */
class AreaEstrategica
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $area;

    /**
     * @var string
     */
    private $definicion;

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
     * Set area
     *
     * @param string $area
     * @return AreaEstrategica
     */
    public function setArea($area)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return string 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set definicion
     *
     * @param string $definicion
     * @return AreaEstrategica
     */
    public function setDefinicion($definicion)
    {
        $this->definicion = $definicion;
    
        return $this;
    }

    /**
     * Get definicion
     *
     * @return string 
     */
    public function getDefinicion()
    {
        return $this->definicion;
    }

    /**
     * Add proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     * @return AreaEstrategica
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