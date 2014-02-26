<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanPatriaOn
 */
class PlanPatriaOn
{
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var string
     */
    private $codigo;    

    /**
     * @var string
     */
    private $objetivoNacional;

    /**
     * @var \Mcti\SisproBundle\Entity\PlanPatriaOe
     */
    private $oe;

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
     * Set codigo
     *
     * @param string $codigo
     * @return PlanPatriaOn
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }
    
    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }
    
    /**
     * Set objetivoNacional
     *
     * @param string $objetivoNacional
     * @return PlanPatriaOn
     */
    public function setObjetivoNacional($objetivoNacional)
    {
        $this->objetivoNacional = $objetivoNacional;
    
        return $this;
    }

    /**
     * Get objetivoNacional
     *
     * @return string 
     */
    public function getObjetivoNacional()
    {
        return $this->objetivoNacional;
    }

    /**
     * Set oe
     *
     * @param \Mcti\SisproBundle\Entity\PlanPatriaOe $oe
     * @return PlanPatriaOn
     */
    public function setOe(\Mcti\SisproBundle\Entity\PlanPatriaOe $oe = null)
    {
        $this->oe = $oe;
    
        return $this;
    }

    /**
     * Get oe
     *
     * @return \Mcti\SisproBundle\Entity\PlanPatriaOe 
     */
    public function getOe()
    {
        return $this->oe;
    }

    /**
     * Add proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     * @return PlanPatriaOn
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