<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObjetivoEspecifico
 */
class ObjetivoEspecifico
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $objetivoEspecifico;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $actividades;

    /**
     * @var \Mcti\SisproBundle\Entity\Proyecto
     */
    private $proyecto;

    /**
     * @var integer
     */
    private $codigo;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actividades = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set objetivoEspecifico
     *
     * @param string $objetivoEspecifico
     * @return ObjetivoEspecifico
     */
    public function setObjetivoEspecifico($objetivoEspecifico)
    {
        $this->objetivoEspecifico = $objetivoEspecifico;
    
        return $this;
    }

    /**
     * Get objetivoEspecifico
     *
     * @return string 
     */
    public function getObjetivoEspecifico()
    {
        return $this->objetivoEspecifico;
    }
    
    /**
     * Set codigo
     *
     * @param integer $codigo
     * @return ObjetivoEspecifico
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return integer 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }    

    /**
     * Add actividades
     *
     * @param \Mcti\SisproBundle\Entity\Actividad $actividades
     * @return ObjetivoEspecifico
     */
    public function addActividades(\Mcti\SisproBundle\Entity\Actividad $actividades)
    {
        $this->actividades[] = $actividades;
    
        return $this;
    }

    /**
     * Remove actividades
     *
     * @param \Mcti\SisproBundle\Entity\Actividad $actividades
     */
    public function removeActividades(\Mcti\SisproBundle\Entity\Actividad $actividades)
    {
        $this->actividades->removeElement($actividades);
    }

    /**
     * Get actividades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActividades()
    {
        return $this->actividades;
    }

    /**
     * Set proyecto
     *
     * @param \Mcti\SisproBundle\Entity\Proyecto $proyecto
     * @return ObjetivoEspecifico
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
}