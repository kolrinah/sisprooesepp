<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActividadOrg
 */
class ActividadOrg
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
     * @var \Mcti\SisproBundle\Entity\Moneda
     */
    private $moneda;

    /**
     * @var \Mcti\SisproBundle\Entity\ObjetivoEspecificoOrg
     */
    private $objetivoEspecificoOrg;


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
     * @return ActividadOrg
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
     * @return ActividadOrg
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
     * @return ActividadOrg
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
     * @return ActividadOrg
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
     * @return ActividadOrg
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
     * @return ActividadOrg
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
     * @return ActividadOrg
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
     * Set moneda
     *
     * @param \Mcti\SisproBundle\Entity\Moneda $moneda
     * @return ActividadOrg
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
     * Set objetivoEspecificoOrg
     *
     * @param \Mcti\SisproBundle\Entity\ObjetivoEspecificoOrg $objetivoEspecificoOrg
     * @return ActividadOrg
     */
    public function setObjetivoEspecificoOrg(\Mcti\SisproBundle\Entity\ObjetivoEspecificoOrg $objetivoEspecificoOrg = null)
    {
        $this->objetivoEspecificoOrg = $objetivoEspecificoOrg;
    
        return $this;
    }

    /**
     * Get objetivoEspecificoOrg
     *
     * @return \Mcti\SisproBundle\Entity\ObjetivoEspecificoOrg 
     */
    public function getObjetivoEspecificoOrg()
    {
        return $this->objetivoEspecificoOrg;
    }
}