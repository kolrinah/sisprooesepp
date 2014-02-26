<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegistroProblema
 */
class RegistroProblema
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $observaciones;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var \Mcti\SisproBundle\Entity\Proyecto
     */
    private $proyecto;
    
    /**
     * @var \Mcti\SisproBundle\Entity\TipoProblema
     */
    private $tipoProblema;


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
     * Set observaciones
     *
     * @param string $observaciones
     * @return RegistroProblema
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return RegistroProblema
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     * @return RegistroProblema
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
     * Set tipoProblema
     *
     * @param \Mcti\SisproBundle\Entity\TipoProblema $tipoProblema
     * @return RegistroProblema
     */
    public function setTipoProblema(\Mcti\SisproBundle\Entity\TipoProblema $tipoProblema = null)
    {
        $this->tipoProblema = $tipoProblema;
    
        return $this;
    }

    /**
     * Get tipoProblema
     *
     * @return \Mcti\SisproBundle\Entity\TipoProblema 
     */
    public function getTipoProblema()
    {
        return $this->tipoProblema;
    }    
}
