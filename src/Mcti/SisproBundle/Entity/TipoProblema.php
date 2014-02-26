<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoProblema
 */
class TipoProblema
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $problema;


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
     * Set problema
     *
     * @param string $problema
     * @return TipoProblema
     */
    public function setProblema($problema)
    {
        $this->problema = $problema;
    
        return $this;
    }

    /**
     * Get problema
     *
     * @return string 
     */
    public function getProblema()
    {
        return $this->problema;
    }
}