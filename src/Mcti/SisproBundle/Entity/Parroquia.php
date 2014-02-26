<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parroquia
 */
class Parroquia
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $parroquia;

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
     * Set parroquia
     *
     * @param string $parroquia
     * @return Parroquia
     */
    public function setParroquia($parroquia)
    {
        $this->parroquia = $parroquia;
    
        return $this;
    }

    /**
     * Get parroquia
     *
     * @return string 
     */
    public function getParroquia()
    {
        return $this->parroquia;
    }

    /**
     * Set municipio
     *
     * @param \Mcti\SisproBundle\Entity\Municipio $municipio
     * @return Parroquia
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