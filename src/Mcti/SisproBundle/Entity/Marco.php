<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marco
 */
class Marco
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $marco;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $proyectos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set marco
     *
     * @param string $marco
     * @return Marco
     */
    public function setMarco($marco)
    {
        $this->marco = $marco;
    
        return $this;
    }

    /**
     * Get marco
     *
     * @return string 
     */
    public function getMarco()
    {
        return $this->marco;
    }

    /**
     * Add proyectos
     *
     * @param \Mcti\SisproBundle\Entity\ProyectoMarco $proyectos
     * @return Marco
     */
    public function addProyecto(\Mcti\SisproBundle\Entity\ProyectoMarco $proyectos)
    {
        $this->proyectos[] = $proyectos;
    
        return $this;
    }

    /**
     * Remove proyectos
     *
     * @param \Mcti\SisproBundle\Entity\ProyectoMarco $proyectos
     */
    public function removeProyecto(\Mcti\SisproBundle\Entity\ProyectoMarco $proyectos)
    {
        $this->proyectos->removeElement($proyectos);
    }

    /**
     * Get proyectos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProyectos()
    {
        return $this->proyectos;
    }
}