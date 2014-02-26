<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FuenteFinanciamiento
 */
class FuenteFinanciamiento
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $fuente;

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
     * Set fuente
     *
     * @param string $fuente
     * @return FuenteFinanciamiento
     */
    public function setFuente($fuente)
    {
        $this->fuente = $fuente;
    
        return $this;
    }

    /**
     * Get fuente
     *
     * @return string 
     */
    public function getFuente()
    {
        return $this->fuente;
    }
}