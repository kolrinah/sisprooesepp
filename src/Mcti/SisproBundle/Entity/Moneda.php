<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moneda
 */
class Moneda
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $moneda;

    /**
     * @var float
     */
    private $precioBs;

    /**
     * @var string
     */
    private $iso;

    /**
     * @var string
     */
    private $simbolo;


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
     * Set moneda
     *
     * @param string $moneda
     * @return Moneda
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;
    
        return $this;
    }

    /**
     * Get moneda
     *
     * @return string 
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Set precioBs
     *
     * @param float $precioBs
     * @return Moneda
     */
    public function setPrecioBs($precioBs)
    {
        $this->precioBs = $precioBs;
    
        return $this;
    }

    /**
     * Get precioBs
     *
     * @return float 
     */
    public function getPrecioBs()
    {
        return $this->precioBs;
    }

    /**
     * Set iso
     *
     * @param string $iso
     * @return Moneda
     */
    public function setIso($iso)
    {
        $this->iso = $iso;
    
        return $this;
    }

    /**
     * Get iso
     *
     * @return string 
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Set simbolo
     *
     * @param string $simbolo
     * @return Moneda
     */
    public function setSimbolo($simbolo)
    {
        $this->simbolo = $simbolo;
    
        return $this;
    }

    /**
     * Get simbolo
     *
     * @return string 
     */
    public function getSimbolo()
    {
        return $this->simbolo;
    }
}