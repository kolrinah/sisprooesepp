<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FrasesChavez
 */
class FrasesChavez
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $frase;

    /**
     * @var string
     */
    private $cita;

    /**
     * @var string
     */
    private $ruta;


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
     * Set frase
     *
     * @param string $frase
     * @return FrasesChavez
     */
    public function setFrase($frase)
    {
        $this->frase = $frase;
    
        return $this;
    }

    /**
     * Get frase
     *
     * @return string 
     */
    public function getFrase()
    {
        return $this->frase;
    }

    /**
     * Set cita
     *
     * @param string $cita
     * @return FrasesChavez
     */
    public function setCita($cita)
    {
        $this->cita = $cita;
    
        return $this;
    }

    /**
     * Get cita
     *
     * @return string 
     */
    public function getCita()
    {
        return $this->cita;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     * @return FrasesChavez
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
    
        return $this;
    }

    /**
     * Get ruta
     *
     * @return string 
     */
    public function getRuta()
    {
        return $this->ruta;
    }
}