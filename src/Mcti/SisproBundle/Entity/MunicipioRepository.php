<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MunicipioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MunicipioRepository extends EntityRepository
{
    /*
     * Obtiene un array con los municipios del estado dado
     */
    public function getArrayMunicipios($idEstado)
    {
        $municipios= $this->getEntityManager()
                       ->createQuery('SELECT m.id, m.municipio
                                      FROM SisproBundle:Municipio m
                                      JOIN m.estado e
                                      WHERE e.id=:id
                                      ORDER BY m.municipio ASC')
                       ->setParameter('id', $idEstado)
                       ->getResult();
       
        if (count($municipios)==0) return array('0'=>'');
            
        $a=array('0'); $b=array('[Seleccione]');
        
        foreach ($municipios as $fila)
        {
           array_push($a,$fila['id']);
           array_push($b,$fila['municipio']);
        }         
        $municipios=array_combine($a,$b);
        //ksort($estados);        
        return $municipios;
    }
    
    public function getEntidades($entidad)
    {
        $sql=($entidad=='E00')?  
              "SELECT e FROM SisproBundle:Estado e"
              :
              "SELECT m FROM SisproBundle:Municipio m
               JOIN m.estado e
               WHERE e.codigoOnapre='$entidad'
               ORDER BY m.codigoOnapre" ;
     
        $entidades = $this->getEntityManager()
                          ->createQuery($sql)
                          ->getResult();
        return $entidades;        
    }    
}
