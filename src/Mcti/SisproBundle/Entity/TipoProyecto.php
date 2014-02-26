<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoProyecto
 */
class TipoProyecto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $tipo;

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
     * Set tipo
     *
     * @param string $tipo
     * @return TipoProyecto
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     * @return TipoProyecto
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