<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fotografia
 */
class Fotografia
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $imagen;

    /**
     * @var string
     */
    private $comentarios;

    /**
     * @var \Mcti\SisproBundle\Entity\Actividad
     */
    private $actividad;


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
     * Set imagen
     *
     * @param string $imagen
     * @return Fotografia
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    
        return $this;
    }

    /**
     * Get imagen
     *
     * @return string 
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return Fotografia
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
    
        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set actividad
     *
     * @param \Mcti\SisproBundle\Entity\Actividad $actividad
     * @return Fotografia
     */
    public function setActividad(\Mcti\SisproBundle\Entity\Actividad $actividad = null)
    {
        $this->actividad = $actividad;
    
        return $this;
    }

    /**
     * Get actividad
     *
     * @return \Mcti\SisproBundle\Entity\Actividad 
     */
    public function getActividad()
    {
        return $this->actividad;
    }
    
    /* * * * * * * * * * * * * * * * * * * * * * * 
     * METODOS CREADOS MANUALMENTE
     */
    
    // Método para obtener la ruta física del archivo
    public function getRuta()
    {
        $ruta = __DIR__.'/../../../../web/adjuntos/';
        $ruta .= $this->actividad->getObjetivoEspecifico()->getProyecto()->getCodigo();
        $ruta .= '/'.$this->imagen;
        
        return $ruta;
    }

    // Método para subir la foto
    public function subirFoto($directorioDestino)
    {
        if (null === $this->imagen) return;
                
        $nombreArchivoFoto = uniqid().'.'.$this->imagen->getClientOriginalExtension();
        $this->_crearDirectorio($directorioDestino);
        $this->imagen->move($directorioDestino, $nombreArchivoFoto);
        $this->setImagen($nombreArchivoFoto);
    }
    
    // METODO PARA REMOVER LA FOTO FISICAMENTE
    public function removerFoto()
    {
       $ruta = $this->getRuta();
       if (file_exists($ruta)) @unlink($ruta);
         
       return true;
    }
    
    // METODO PARA CREAR DIRECTORIO
    private function _crearDirectorio($directorioDestino)
    {
       $ruta = __DIR__.'/../../../../web/adjuntos/';  
       if (!file_exists($ruta)) @mkdir($ruta, 0777, true);
       $this->_crearArchivoIndex($ruta);
       
       if (!file_exists($directorioDestino)) @mkdir($directorioDestino, 0777, true);
       $this->_crearArchivoIndex($directorioDestino);       
    }

    //METODO PARA CREAR ARCHIVOS INDEX EN CARPETA
    private function _crearArchivoIndex($carpeta)
    {
       $archivo = $carpeta.'index.html';
       $contenido = "<html><head><title>403 Prohibido</title></head>".
                    "<body>Recurso Prohibido.</body></html>";

       // CREAMOS EL ARCHIVO SI NO EXISTE
       if (!file_exists($archivo))
       {
          if (!$handle = @fopen($archivo, 'c')) die("No pudo abrir/crear el archivo");
          if (@fwrite($handle, $contenido) === FALSE) die("No pudo escribir en archivo index");            
          @fclose($handle);
       }
       return true;
    }
}