<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estado
 */
class Estado
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $estado;

    /**
     * @var string
     */
    private $codigoOnapre;

    /**
     * @var string
     */
    private $gobernacion;

    /**
     * @var string
     */
    private $capitalEstado;

    /**
     * @var string
     */
    private $mapArea;
    
    /**
     * @var string
     */
    private $sigla;    

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $redi;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->redi = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set estado
     *
     * @param string $estado
     * @return Estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set codigoOnapre
     *
     * @param string $codigoOnapre
     * @return Estado
     */
    public function setCodigoOnapre($codigoOnapre)
    {
        $this->codigoOnapre = $codigoOnapre;
    
        return $this;
    }

    /**
     * Get codigoOnapre
     *
     * @return string 
     */
    public function getCodigoOnapre()
    {
        return $this->codigoOnapre;
    }

    /**
     * Set gobernacion
     *
     * @param string $gobernacion
     * @return Estado
     */
    public function setGobernacion($gobernacion)
    {
        $this->gobernacion = $gobernacion;
    
        return $this;
    }

    /**
     * Get gobernacion
     *
     * @return string 
     */
    public function getGobernacion()
    {
        return $this->gobernacion;
    }

    /**
     * Set capitalEstado
     *
     * @param string $capitalEstado
     * @return Estado
     */
    public function setCapitalEstado($capitalEstado)
    {
        $this->capitalEstado = $capitalEstado;
    
        return $this;
    }

    /**
     * Get capitalEstado
     *
     * @return string 
     */
    public function getCapitalEstado()
    {
        return $this->capitalEstado;
    }

    /**
     * Set mapArea
     *
     * @param string $mapArea
     * @return Estado
     */
    public function setMapArea($mapArea)
    {
        $this->mapArea = $mapArea;
    
        return $this;
    }

    /**
     * Get mapArea
     *
     * @return string 
     */
    public function getMapArea()
    {
        return $this->mapArea;
    }
    
    /**
     * Set sigla
     *
     * @param string $sigla
     * @return Estado
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
    
        return $this;
    }

    /**
     * Get sigla
     *
     * @return string 
     */
    public function getSigla()
    {
        return $this->sigla;
    }    

    /**
     * Add redi
     *
     * @param \Mcti\SisproBundle\Entity\Redi $redi
     * @return Estado
     */
    public function addRedi(\Mcti\SisproBundle\Entity\Redi $redi)
    {
        $this->redi[] = $redi;
    
        return $this;
    }

    /**
     * Remove redi
     *
     * @param \Mcti\SisproBundle\Entity\Redi $redi
     */
    public function removeRedi(\Mcti\SisproBundle\Entity\Redi $redi)
    {
        $this->redi->removeElement($redi);
    }

    /**
     * Get redi
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRedi()
    {
        return $this->redi;
    }
}