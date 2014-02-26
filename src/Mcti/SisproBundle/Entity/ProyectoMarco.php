<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProyectoMarco
 */
class ProyectoMarco
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
     * @var integer
     */
    private $year;

    /**
     * @var \Mcti\SisproBundle\Entity\Proyecto
     */
    private $proyecto;

    /**
     * @var \Mcti\SisproBundle\Entity\Marco
     */
    private $marco;


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
     * @return ProyectoMarco
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
     * Set year
     *
     * @param integer $year
     * @return ProyectoMarco
     */
    public function setYear($year)
    {
        $this->year = $year;
    
        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     * @return ProyectoMarco
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

    /**
     * Set marco
     *
     * @param \Mcti\SisproBundle\Entity\Marco $marco
     * @return ProyectoMarco
     */
    public function setMarco(\Mcti\SisproBundle\Entity\Marco $marco = null)
    {
        $this->marco = $marco;
    
        return $this;
    }

    /**
     * Get marco
     *
     * @return \Mcti\SisproBundle\Entity\Marco 
     */
    public function getMarco()
    {
        return $this->marco;
    }
}