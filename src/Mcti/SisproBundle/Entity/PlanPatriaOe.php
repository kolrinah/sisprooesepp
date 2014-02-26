<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanPatriaOe
 */
class PlanPatriaOe
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
    private $objetivoEstrategico;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $objnac;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objnac = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set objetivoEstrategico
     *
     * @param string $objetivoEstrategico
     * @return PlanPatriaOe
     */
    public function setObjetivoEstrategico($objetivoEstrategico)
    {
        $this->objetivoEstrategico = $objetivoEstrategico;
    
        return $this;
    }

    /**
     * Get objetivoEstrategico
     *
     * @return string 
     */
    public function getObjetivoEstrategico()
    {
        return $this->objetivoEstrategico;
    }

    /**
     * Add objnac
     *
     * @param \Mcti\SisproBundle\Entity\PlanPatriaOn $objnac
     * @return PlanPatriaOe
     */
    public function addObjnac(\Mcti\SisproBundle\Entity\PlanPatriaOn $objnac)
    {
        $this->objnac[] = $objnac;
    
        return $this;
    }

    /**
     * Remove objnac
     *
     * @param \Mcti\SisproBundle\Entity\PlanPatriaOn $objnac
     */
    public function removeObjnac(\Mcti\SisproBundle\Entity\PlanPatriaOn $objnac)
    {
        $this->objnac->removeElement($objnac);
    }

    /**
     * Get objnac
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObjnac()
    {
        return $this->objnac;
    }
}