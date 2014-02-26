<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Redi
 */
class Redi
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $redi;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $estado;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->estado = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set redi
     *
     * @param string $redi
     * @return Redi
     */
    public function setRedi($redi)
    {
        $this->redi = $redi;
    
        return $this;
    }

    /**
     * Get redi
     *
     * @return string 
     */
    public function getRedi()
    {
        return $this->redi;
    }

    /**
     * Add estado
     *
     * @param \Mcti\SisproBundle\Entity\Estado $estado
     * @return Redi
     */
    public function addEstado(\Mcti\SisproBundle\Entity\Estado $estado)
    {
        $this->estado[] = $estado;
    
        return $this;
    }

    /**
     * Remove estado
     *
     * @param \Mcti\SisproBundle\Entity\Estado $estado
     */
    public function removeEstado(\Mcti\SisproBundle\Entity\Estado $estado)
    {
        $this->estado->removeElement($estado);
    }

    /**
     * Get estado
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}