<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actividad
 */
class Actividad
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $actividad;

    /**
     * @var float
     */
    private $monto;

    /**
     * @var float
     */
    private $metaFisica;

    /**
     * @var string
     */
    private $unidadMedida;

    /**
     * @var \DateTime
     */
    private $fechaIni;

    /**
     * @var \DateTime
     */
    private $fechaFin;
    
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var \Mcti\SisproBundle\Entity\ObjetivoEspecifico
     */
    private $objetivoEspecifico;

    /**
     * @var \Mcti\SisproBundle\Entity\Moneda
     */
    private $moneda;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recursoEjecutado;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $metaAlcanzada;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $fotografia;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recursoEjecutado = new \Doctrine\Common\Collections\ArrayCollection();
        $this->metaAlcanzada = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fotografia = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set actividad
     *
     * @param string $actividad
     * @return Actividad
     */
    public function setActividad($actividad)
    {
        $this->actividad = $actividad;
    
        return $this;
    }

    /**
     * Get actividad
     *
     * @return string 
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set monto
     *
     * @param float $monto
     * @return Actividad
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;
    
        return $this;
    }

    /**
     * Get monto
     *
     * @return float 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set metaFisica
     *
     * @param float $metaFisica
     * @return Actividad
     */
    public function setMetaFisica($metaFisica)
    {
        $this->metaFisica = $metaFisica;
    
        return $this;
    }

    /**
     * Get metaFisica
     *
     * @return float 
     */
    public function getMetaFisica()
    {
        return $this->metaFisica;
    }

    /**
     * Set unidadMedida
     *
     * @param string $unidadMedida
     * @return Actividad
     */
    public function setUnidadMedida($unidadMedida)
    {
        $this->unidadMedida = $unidadMedida;
    
        return $this;
    }

    /**
     * Get unidadMedida
     *
     * @return string 
     */
    public function getUnidadMedida()
    {
        return $this->unidadMedida;
    }

    /**
     * Set fechaIni
     *
     * @param \DateTime $fechaIni
     * @return Actividad
     */
    public function setFechaIni($fechaIni)
    {
        $this->fechaIni = $fechaIni;
    
        return $this;
    }

    /**
     * Get fechaIni
     *
     * @return \DateTime 
     */
    public function getFechaIni()
    {
        return $this->fechaIni;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Actividad
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    
        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set codigo
     *
     * @param integer $codigo
     * @return Actividad
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
     * Set objetivoEspecifico
     *
     * @param \Mcti\SisproBundle\Entity\ObjetivoEspecifico $objetivoEspecifico
     * @return Actividad
     */
    public function setObjetivoEspecifico(\Mcti\SisproBundle\Entity\ObjetivoEspecifico $objetivoEspecifico = null)
    {
        $this->objetivoEspecifico = $objetivoEspecifico;
    
        return $this;
    }

    /**
     * Get objetivoEspecifico
     *
     * @return \Mcti\SisproBundle\Entity\ObjetivoEspecifico 
     */
    public function getObjetivoEspecifico()
    {
        return $this->objetivoEspecifico;
    }

    /**
     * Set moneda
     *
     * @param \Mcti\SisproBundle\Entity\Moneda $moneda
     * @return Actividad
     */
    public function setMoneda(\Mcti\SisproBundle\Entity\Moneda $moneda = null)
    {
        $this->moneda = $moneda;
    
        return $this;
    }

    /**
     * Get moneda
     *
     * @return \Mcti\SisproBundle\Entity\Moneda 
     */
    public function getMoneda()
    {
        return $this->moneda;
    }   
    
    /**
     * Add recursoEjecutado
     *
     * @param \Mcti\SisproBundle\Entity\RecursoEjecutado $recursoEjecutado
     * @return Actividad
     */
    public function addRecursoEjecutado(\Mcti\SisproBundle\Entity\RecursoEjecutado $recursoEjecutado)
    {
        $this->recursoEjecutado[] = $recursoEjecutado;
    
        return $this;
    }

    /**
     * Remove recursoEjecutado
     *
     * @param \Mcti\SisproBundle\Entity\RecursoEjecutado $recursoEjecutado
     */
    public function removeRecursoEjecutado(\Mcti\SisproBundle\Entity\RecursoEjecutado $recursoEjecutado)
    {
        $this->recursoEjecutado->removeElement($recursoEjecutado);
    }

    /**
     * Get recursoEjecutado
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecursoEjecutado()
    {
        return $this->recursoEjecutado;
    }

    /**
     * Add metaAlcanzada
     *
     * @param \Mcti\SisproBundle\Entity\MetaAlcanzada $metaAlcanzada
     * @return Actividad
     */
    public function addMetaAlcanzada(\Mcti\SisproBundle\Entity\MetaAlcanzada $metaAlcanzada)
    {
        $this->metaAlcanzada[] = $metaAlcanzada;
    
        return $this;
    }

    /**
     * Remove metaAlcanzada
     *
     * @param \Mcti\SisproBundle\Entity\MetaAlcanzada $metaAlcanzada
     */
    public function removeMetaAlcanzada(\Mcti\SisproBundle\Entity\MetaAlcanzada $metaAlcanzada)
    {
        $this->metaAlcanzada->removeElement($metaAlcanzada);
    }

    /**
     * Get metaAlcanzada
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMetaAlcanzada()
    {
        return $this->metaAlcanzada;
    }

    /**
     * Add fotografia
     *
     * @param \Mcti\SisproBundle\Entity\Fotografia $fotografia
     * @return Actividad
     */
    public function addFotografia(\Mcti\SisproBundle\Entity\Fotografia $fotografia)
    {
        $this->fotografia[] = $fotografia;
    
        return $this;
    }

    /**
     * Remove fotografia
     *
     * @param \Mcti\SisproBundle\Entity\Fotografia $fotografia
     */
    public function removeFotografia(\Mcti\SisproBundle\Entity\Fotografia $fotografia)
    {
        $this->fotografia->removeElement($fotografia);
    }

    /**
     * Get fotografia
     *
     * @return \Doctrine\Common\Collections\Collection 
     */    
    public function getFotografia()
    {
        return $this->fotografia;
    }   
    
    /* * * * * * * * * * * * * * * * 
     * FUNCIONES AGREGADAS MANUALMENTE
     */    
    public function getProyecto()
    {
        return $this->getObjetivoEspecifico()->getProyecto();
    }
    
    public function __toString()
    {
       return $this->getObjetivoEspecifico()->getCodigo().'.'.$this->getCodigo().' '.
              $this->getActividad() ;
    }    
}