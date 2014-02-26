<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coordenadas
 */
class Coordenadas
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $latgra;

    /**
     * @var integer
     */
    private $latmin;

    /**
     * @var float
     */
    private $latseg;

    /**
     * @var integer
     */
    private $longra;

    /**
     * @var integer
     */
    private $lonmin;

    /**
     * @var float
     */
    private $lonseg;

    /**
     * @var \Mcti\SisproBundle\Entity\Proyecto
     */
    private $proyecto;


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
     * Set latgra
     *
     * @param integer $latgra
     * @return Coordenadas
     */
    public function setLatgra($latgra)
    {
        $this->latgra = $latgra;
    
        return $this;
    }

    /**
     * Get latgra
     *
     * @return integer 
     */
    public function getLatgra()
    {
        return $this->latgra;
    }

    /**
     * Set latmin
     *
     * @param integer $latmin
     * @return Coordenadas
     */
    public function setLatmin($latmin)
    {
        $this->latmin = $latmin;
    
        return $this;
    }

    /**
     * Get latmin
     *
     * @return integer 
     */
    public function getLatmin()
    {
        return $this->latmin;
    }

    /**
     * Set latseg
     *
     * @param float $latseg
     * @return Coordenadas
     */
    public function setLatseg($latseg)
    {
        $this->latseg = $latseg;
    
        return $this;
    }

    /**
     * Get latseg
     *
     * @return float 
     */
    public function getLatseg()
    {
        return $this->latseg;
    }

    /**
     * Set longra
     *
     * @param integer $longra
     * @return Coordenadas
     */
    public function setLongra($longra)
    {
        $this->longra = $longra;
    
        return $this;
    }

    /**
     * Get longra
     *
     * @return integer 
     */
    public function getLongra()
    {
        return $this->longra;
    }

    /**
     * Set lonmin
     *
     * @param integer $lonmin
     * @return Coordenadas
     */
    public function setLonmin($lonmin)
    {
        $this->lonmin = $lonmin;
    
        return $this;
    }

    /**
     * Get lonmin
     *
     * @return integer 
     */
    public function getLonmin()
    {
        return $this->lonmin;
    }

    /**
     * Set lonseg
     *
     * @param float $lonseg
     * @return Coordenadas
     */
    public function setLonseg($lonseg)
    {
        $this->lonseg = $lonseg;
    
        return $this;
    }

    /**
     * Get lonseg
     *
     * @return float 
     */
    public function getLonseg()
    {
        return $this->lonseg;
    }

    /**
     * Set proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     * @return Coordenadas
     */
    public function setProyecto(\Mcti\SisproBundle\Entity\Proyecto $proyecto = null)
    {
        $this->proyecto = $proyecto;
    
        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \Mcti\SisproBundle\Entity\Proyecto 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }
    
    /*
     * METODO AGREGADO MANUALMENTE
     */
    public function getLatitudToString() 
    {
       return $this->latgra.'º '.$this->latmin.'\' '.
               \number_format(($this->latseg), 2, ',', '.').'"' ;
    }
    
    public function getLongitudToString() 
    {
       return $this->longra.'º '.$this->lonmin.'\' '.
               \number_format(($this->lonseg), 2, ',', '.').'"' ;
    }
}