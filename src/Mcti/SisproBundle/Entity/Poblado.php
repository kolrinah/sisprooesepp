<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Poblado
 */
class Poblado
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $poblado;

    /**
     * @var \Mcti\SisproBundle\Entity\Municipio
     */
    private $municipio;


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
     * Set poblado
     *
     * @param string $poblado
     * @return Poblado
     */
    public function setPoblado($poblado)
    {
        $this->poblado = $poblado;
    
        return $this;
    }

    /**
     * Get poblado
     *
     * @return string 
     */
    public function getPoblado()
    {
        return $this->poblado;
    }

    /**
     * Set municipio
     *
     * @param \Mcti\SisproBundle\Entity\Municipio $estado
     * @return Poblado
     */
    public function setMunicipio(\Mcti\SisproBundle\Entity\Municipio $municipio = null)
    {
        $this->municipio = $municipio;
    
        return $this;
    }

    /**
     * Get municipio
     *
     * @return \Mcti\SisproBundle\Entity\Municipio 
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }
}