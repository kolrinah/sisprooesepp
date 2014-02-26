<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proyecto
 */
class Proyecto
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
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var string
     */
    private $problema;

    /**
     * @var string
     */
    private $objetivoGeneral;

    /**
     * @var string
     */
    private $producto;

    /**
     * @var float
     */
    private $meta;

    /**
     * @var string
     */
    private $unidadMedida;

    /**
     * @var string
     */
    private $indicador;

    /**
     * @var string
     */
    private $alcance;

    /**
     * @var string
     */
    private $puntoycirculo;

    /**
     * @var string
     */
    private $direccion;

    /**
     * @var boolean
     */
    private $nacional;

    /**
     * @var integer
     */
    private $pobFemenina;

    /**
     * @var integer
     */
    private $pobMasculina;

    /**
     * @var integer
     */
    private $pobTotal;

    /**
     * @var string
     */
    private $observaciones;
    
    /**
     * @var \DateTime
     */
    private $fechaRegistro; 
    
    /**
     * @var integer
     */
    private $empleosDirectosEjecucion;

    /**
     * @var integer
     */
    private $empleosIndirectosEjecucion;

    /**
     * @var integer
     */
    private $empleosDirectosOperacion;

    /**
     * @var integer
     */
    private $empleosIndirectosOperacion;  

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $objetivos;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $objetivosOrg;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $fuentes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $marcos;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $coordenadas;

    /**
     * @var \Mcti\SisproBundle\Entity\Estatus
     */
    private $estatus;

    /**
     * @var \Mcti\SisproBundle\Entity\Parroquia
     */
    private $parroquia;

    /**
     * @var \Mcti\SisproBundle\Entity\Poblado
     */
    private $poblado;

    /**
     * @var \Mcti\SisproBundle\Entity\Estructura
     */
    private $estructura;

    /**
     * @var \Mcti\SisproBundle\Entity\Usuario
     */
    private $usuario;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tipoProyecto;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $areaEstrategica;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $on;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $municipio;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recursoRecibido;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $registroProblema;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $registroNotificacion;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objetivos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->objetivosOrg = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fuentes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marcos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coordenadas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tipoProyecto = new \Doctrine\Common\Collections\ArrayCollection();       
        $this->areaEstrategica = new \Doctrine\Common\Collections\ArrayCollection();
        $this->on = new \Doctrine\Common\Collections\ArrayCollection();
        $this->municipio = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recursoRecibido = new \Doctrine\Common\Collections\ArrayCollection();
        $this->registroProblema = new \Doctrine\Common\Collections\ArrayCollection();
        $this->registroNotificacion = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codigo
     *
     * @param string $codigo
     * @return Proyecto
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
     * Set nombre
     *
     * @param string $nombre
     * @return Proyecto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Proyecto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set problema
     *
     * @param string $problema
     * @return Proyecto
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

    /**
     * Set objetivoGeneral
     *
     * @param string $objetivoGeneral
     * @return Proyecto
     */
    public function setObjetivoGeneral($objetivoGeneral)
    {
        $this->objetivoGeneral = $objetivoGeneral;
    
        return $this;
    }

    /**
     * Get objetivoGeneral
     *
     * @return string 
     */
    public function getObjetivoGeneral()
    {
        return $this->objetivoGeneral;
    }

    /**
     * Set producto
     *
     * @param string $producto
     * @return Proyecto
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;
    
        return $this;
    }

    /**
     * Get producto
     *
     * @return string 
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set meta
     *
     * @param float $meta
     * @return Proyecto
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    
        return $this;
    }

    /**
     * Get meta
     *
     * @return float 
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set unidadMedida
     *
     * @param string $unidadMedida
     * @return Proyecto
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
     * Set indicador
     *
     * @param string $indicador
     * @return Proyecto
     */
    public function setIndicador($indicador)
    {
        $this->indicador = $indicador;
    
        return $this;
    }

    /**
     * Get indicador
     *
     * @return string 
     */
    public function getIndicador()
    {
        return $this->indicador;
    }

    /**
     * Set alcance
     *
     * @param string $alcance
     * @return Proyecto
     */
    public function setAlcance($alcance)
    {
        $this->alcance = $alcance;
    
        return $this;
    }

    /**
     * Get alcance
     *
     * @return string 
     */
    public function getAlcance()
    {
        return $this->alcance;
    }

    /**
     * Set puntoycirculo
     *
     * @param string $puntoycirculo
     * @return Proyecto
     */
    public function setPuntoycirculo($puntoycirculo)
    {
        $this->puntoycirculo = $puntoycirculo;
    
        return $this;
    }

    /**
     * Get puntoycirculo
     *
     * @return string 
     */
    public function getPuntoycirculo()
    {
        return $this->puntoycirculo;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Proyecto
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set nacional
     *
     * @param boolean $nacional
     * @return Proyecto
     */
    public function setNacional($nacional)
    {
        $this->nacional = $nacional;
    
        return $this;
    }

    /**
     * Get nacional
     *
     * @return boolean 
     */
    public function getNacional()
    {
        return $this->nacional;
    }

    /**
     * Set pobFemenina
     *
     * @param integer $pobFemenina
     * @return Proyecto
     */
    public function setPobFemenina($pobFemenina)
    {
        $this->pobFemenina = $pobFemenina;
    
        return $this;
    }

    /**
     * Get pobFemenina
     *
     * @return integer 
     */
    public function getPobFemenina()
    {
        return $this->pobFemenina;
    }

    /**
     * Set pobMasculina
     *
     * @param integer $pobMasculina
     * @return Proyecto
     */
    public function setPobMasculina($pobMasculina)
    {
        $this->pobMasculina = $pobMasculina;
    
        return $this;
    }

    /**
     * Get pobMasculina
     *
     * @return integer 
     */
    public function getPobMasculina()
    {
        return $this->pobMasculina;
    }

    /**
     * Set pobTotal
     *
     * @param integer $pobTotal
     * @return Proyecto
     */
    public function setPobTotal($pobTotal)
    {
        $this->pobTotal = $pobTotal;
    
        return $this;
    }

    /**
     * Get pobTotal
     *
     * @return integer 
     */
    public function getPobTotal()
    {
        return $this->pobTotal;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Proyecto
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
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     * @return Proyecto
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;
    
        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime 
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }    

    /**
     * Set empleosDirectosEjecucion
     *
     * @param integer $empleosDirectosEjecucion
     * @return Proyecto
     */
    public function setEmpleosDirectosEjecucion($empleosDirectosEjecucion)
    {
        $this->empleosDirectosEjecucion = $empleosDirectosEjecucion;
    
        return $this;
    }

    /**
     * Get empleosDirectosEjecucion
     *
     * @return integer 
     */
    public function getEmpleosDirectosEjecucion()
    {
        return $this->empleosDirectosEjecucion;
    }

    /**
     * Set empleosIndirectosEjecucion
     *
     * @param integer $empleosIndirectosEjecucion
     * @return Proyecto
     */
    public function setEmpleosIndirectosEjecucion($empleosIndirectosEjecucion)
    {
        $this->empleosIndirectosEjecucion = $empleosIndirectosEjecucion;
    
        return $this;
    }

    /**
     * Get empleosIndirectosEjecucion
     *
     * @return integer 
     */
    public function getEmpleosIndirectosEjecucion()
    {
        return $this->empleosIndirectosEjecucion;
    }

    /**
     * Set empleosDirectosOperacion
     *
     * @param integer $empleosDirectosOperacion
     * @return Proyecto
     */
    public function setEmpleosDirectosOperacion($empleosDirectosOperacion)
    {
        $this->empleosDirectosOperacion = $empleosDirectosOperacion;
    
        return $this;
    }

    /**
     * Get empleosDirectosOperacion
     *
     * @return integer 
     */
    public function getEmpleosDirectosOperacion()
    {
        return $this->empleosDirectosOperacion;
    }

    /**
     * Set empleosIndirectosOperacion
     *
     * @param integer $empleosIndirectosOperacion
     * @return Proyecto
     */
    public function setEmpleosIndirectosOperacion($empleosIndirectosOperacion)
    {
        $this->empleosIndirectosOperacion = $empleosIndirectosOperacion;
    
        return $this;
    }

    /**
     * Get empleosIndirectosOperacion
     *
     * @return integer 
     */
    public function getEmpleosIndirectosOperacion()
    {
        return $this->empleosIndirectosOperacion;
    }    
    
    /**
     * Add objetivos
     *
     * @param \Mcti\SisproBundle\Entity\ObjetivoEspecifico $objetivos
     * @return Proyecto
     */
    public function addObjetivo(\Mcti\SisproBundle\Entity\ObjetivoEspecifico $objetivos)
    {
        $this->objetivos[] = $objetivos;
    
        return $this;
    }

    /**
     * Remove objetivos
     *
     * @param \Mcti\SisproBundle\Entity\ObjetivoEspecifico $objetivos
     */
    public function removeObjetivo(\Mcti\SisproBundle\Entity\ObjetivoEspecifico $objetivos)
    {
        $this->objetivos->removeElement($objetivos);
    }

    /**
     * Get objetivos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObjetivos()
    {
        return $this->objetivos;
    }

    /**
     * Add objetivosOrg
     *
     * @param \Mcti\SisproBundle\Entity\ObjetivoEspecificoOrg $objetivosOrg
     * @return Proyecto
     */
    public function addObjetivosOrg(\Mcti\SisproBundle\Entity\ObjetivoEspecificoOrg $objetivosOrg)
    {
        $this->objetivosOrg[] = $objetivosOrg;
    
        return $this;
    }

    /**
     * Remove objetivosOrg
     *
     * @param \Mcti\SisproBundle\Entity\ObjetivoEspecificoOrg $objetivosOrg
     */
    public function removeObjetivosOrg(\Mcti\SisproBundle\Entity\ObjetivoEspecificoOrg $objetivosOrg)
    {
        $this->objetivosOrg->removeElement($objetivosOrg);
    }

    /**
     * Get objetivosOrg
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObjetivosOrg()
    {
        return $this->objetivosOrg;
    }

    /**
     * Add fuentes
     *
     * @param \Mcti\SisproBundle\Entity\ProyectoFuenteFinanciamiento $fuentes
     * @return Proyecto
     */
    public function addFuente(\Mcti\SisproBundle\Entity\ProyectoFuenteFinanciamiento $fuentes)
    {
        $this->fuentes[] = $fuentes;
    
        return $this;
    }

    /**
     * Remove fuentes
     *
     * @param \Mcti\SisproBundle\Entity\ProyectoFuenteFinanciamiento $fuentes
     */
    public function removeFuente(\Mcti\SisproBundle\Entity\ProyectoFuenteFinanciamiento $fuentes)
    {
        $this->fuentes->removeElement($fuentes);
    }

    /**
     * Get fuentes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFuentes()
    {
        return $this->fuentes;
    }

    /**
     * Add marcos
     *
     * @param \Mcti\SisproBundle\Entity\ProyectoMarco $marcos
     * @return Proyecto
     */
    public function addMarco(\Mcti\SisproBundle\Entity\ProyectoMarco $marcos)
    {
        $this->marcos[] = $marcos;
    
        return $this;
    }

    /**
     * Remove marcos
     *
     * @param \Mcti\SisproBundle\Entity\ProyectoMarco $marcos
     */
    public function removeMarco(\Mcti\SisproBundle\Entity\ProyectoMarco $marcos)
    {
        $this->marcos->removeElement($marcos);
    }

    /**
     * Get marcos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarcos()
    {
        return $this->marcos;
    }

    /**
     * Add coordenadas
     *
     * @param \Mcti\SisproBundle\Entity\Coordenadas $coordenadas
     * @return Proyecto
     */
    public function addCoordenada(\Mcti\SisproBundle\Entity\Coordenadas $coordenadas)
    {
        $this->coordenadas[] = $coordenadas;
    
        return $this;
    }

    /**
     * Remove coordenadas
     *
     * @param \Mcti\SisproBundle\Entity\Coordenadas $coordenadas
     */
    public function removeCoordenada(\Mcti\SisproBundle\Entity\Coordenadas $coordenadas)
    {
        $this->coordenadas->removeElement($coordenadas);
    }

    /**
     * Get coordenadas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCoordenadas()
    {
        return $this->coordenadas;
    }

    /**
     * Set estatus
     *
     * @param \Mcti\SisproBundle\Entity\Estatus $estatus
     * @return Proyecto
     */
    public function setEstatus(\Mcti\SisproBundle\Entity\Estatus $estatus = null)
    {
        $this->estatus = $estatus;
    
        return $this;
    }

    /**
     * Get estatus
     *
     * @return \Mcti\SisproBundle\Entity\Estatus 
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set parroquia
     *
     * @param \Mcti\SisproBundle\Entity\Parroquia $parroquia
     * @return Proyecto
     */
    public function setParroquia(\Mcti\SisproBundle\Entity\Parroquia $parroquia = null)
    {
        $this->parroquia = $parroquia;
    
        return $this;
    }

    /**
     * Get parroquia
     *
     * @return \Mcti\SisproBundle\Entity\Parroquia 
     */
    public function getParroquia()
    {
        return $this->parroquia;
    }

    /**
     * Set poblado
     *
     * @param \Mcti\SisproBundle\Entity\Poblado $poblado
     * @return Proyecto
     */
    public function setPoblado(\Mcti\SisproBundle\Entity\Poblado $poblado = null)
    {
        $this->poblado = $poblado;
    
        return $this;
    }

    /**
     * Get poblado
     *
     * @return \Mcti\SisproBundle\Entity\Poblado 
     */
    public function getPoblado()
    {
        return $this->poblado;
    }

    /**
     * Set estructura
     *
     * @param \Mcti\SisproBundle\Entity\Estructura $estructura
     * @return Proyecto
     */
    public function setEstructura(\Mcti\SisproBundle\Entity\Estructura $estructura = null)
    {
        $this->estructura = $estructura;
    
        return $this;
    }

    /**
     * Get estructura
     *
     * @return \Mcti\SisproBundle\Entity\Estructura 
     */
    public function getEstructura()
    {
        return $this->estructura;
    }

    /**
     * Set usuario
     *
     * @param \Mcti\SisproBundle\Entity\Usuario $usuario
     * @return Proyecto
     */
    public function setUsuario(\Mcti\SisproBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Mcti\SisproBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Add tipoProyecto
     *
     * @param \Mcti\SisproBundle\Entity\TipoProyecto $tipoProyecto
     * @return Proyecto
     */
    public function addTipoProyecto(\Mcti\SisproBundle\Entity\TipoProyecto $tipoProyecto)
    {
        $this->tipoProyecto[] = $tipoProyecto;
    
        return $this;
    }

    /**
     * Remove tipoProyecto
     *
     * @param \Mcti\SisproBundle\Entity\TipoProyecto $tipoProyecto
     */
    public function removeTipoProyecto(\Mcti\SisproBundle\Entity\TipoProyecto $tipoProyecto)
    {
        $this->tipoProyecto->removeElement($tipoProyecto);
    }

    /**
     * Get tipoProyecto
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTipoProyecto()
    {
        return $this->tipoProyecto;
    }

    /**
     * Add areaEstrategica
     *
     * @param \Mcti\SisproBundle\Entity\AreaEstrategica $areaEstrategica
     * @return Proyecto
     */
    public function addAreaEstrategica(\Mcti\SisproBundle\Entity\AreaEstrategica $areaEstrategica)
    {
        $this->areaEstrategica[] = $areaEstrategica;
    
        return $this;
    }

    /**
     * Remove areaEstrategica
     *
     * @param \Mcti\SisproBundle\Entity\AreaEstrategica $areaEstrategica
     */
    public function removeAreaEstrategica(\Mcti\SisproBundle\Entity\AreaEstrategica $areaEstrategica)
    {
        $this->areaEstrategica->removeElement($areaEstrategica);
    }

    /**
     * Get areaEstrategica
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAreaEstrategica()
    {
        return $this->areaEstrategica;
    }

    /**
     * Add on
     *
     * @param \Mcti\SisproBundle\Entity\PlanPatriaOn $on
     * @return Proyecto
     */
    public function addOn(\Mcti\SisproBundle\Entity\PlanPatriaOn $on)
    {
        $this->on[] = $on;
    
        return $this;
    }

    /**
     * Remove on
     *
     * @param \Mcti\SisproBundle\Entity\PlanPatriaOn $on
     */
    public function removeOn(\Mcti\SisproBundle\Entity\PlanPatriaOn $on)
    {
        $this->on->removeElement($on);
    }

    /**
     * Get on
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOn()
    {
        return $this->on;
    }

    /**
     * Add municipio
     *
     * @param \Mcti\SisproBundle\Entity\Municipio $municipio
     * @return Proyecto
     */
    public function addMunicipio(\Mcti\SisproBundle\Entity\Municipio $municipio)
    {
        $this->municipio[] = $municipio;
    
        return $this;
    }

    /**
     * Remove municipio
     *
     * @param \Mcti\SisproBundle\Entity\Municipio $municipio
     */
    public function removeMunicipio(\Mcti\SisproBundle\Entity\Municipio $municipio)
    {
        $this->municipio->removeElement($municipio);
    }

    /**
     * Get municipio
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Add recursoRecibido
     *
     * @param \Mcti\SisproBundle\Entity\RecursoRecibido $recursoRecibido
     * @return Proyecto
     */
    public function addRecursoRecibido(\Mcti\SisproBundle\Entity\RecursoRecibido $recursoRecibido)
    {
        $this->recursoRecibido[] = $recursoRecibido;
    
        return $this;
    }

    /**
     * Remove recursoRecibido
     *
     * @param \Mcti\SisproBundle\Entity\RecursoRecibido $recursoRecibido
     */
    public function removeRecursoRecibido(\Mcti\SisproBundle\Entity\RecursoRecibido $recursoRecibido)
    {
        $this->recursoRecibido->removeElement($recursoRecibido);
    }

    /**
     * Get recursoRecibido
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecursoRecibido()
    {
        return $this->recursoRecibido;
    }

    /**
     * Add registroProblema
     *
     * @param \Mcti\SisproBundle\Entity\RegistroProblema $registroProblema
     * @return Proyecto
     */
    public function addRegistroProblema(\Mcti\SisproBundle\Entity\RegistroProblema $registroProblema)
    {
        $this->registroProblema[] = $registroProblema;
    
        return $this;
    }

    /**
     * Remove registroProblema
     *
     * @param \Mcti\SisproBundle\Entity\RegistroProblema $registroProblema
     */
    public function removeRegistroProblema(\Mcti\SisproBundle\Entity\RegistroProblema $registroProblema)
    {
        $this->registroProblema->removeElement($registroProblema);
    }

    /**
     * Get registroProblema
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRegistroProblema()
    {
        return $this->registroProblema;
    }

    /**
     * Add registroNotificacion
     *
     * @param \Mcti\SisproBundle\Entity\RegistroNotificacion $registroNotificacion
     * @return Proyecto
     */
    public function addRegistroNotificacion(\Mcti\SisproBundle\Entity\RegistroNotificacion $registroNotificacion)
    {
        $this->registroNotificacion[] = $registroNotificacion;
    
        return $this;
    }

    /**
     * Remove registroNotificacion
     *
     * @param \Mcti\SisproBundle\Entity\RegistroNotificacion $registroNotificacion
     */
    public function removeRegistroNotificacion(\Mcti\SisproBundle\Entity\RegistroNotificacion $registroNotificacion)
    {
        $this->registroNotificacion->removeElement($registroNotificacion);
    }

    /**
     * Get registroNotificacion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRegistroNotificacion()
    {
        return $this->registroNotificacion;
    }
    
    /***********************************************
     * GETTERS DESARROLLADOS MANUALMENTE POR REIZA
     ***********************************************/
    
    /**
     * Obtener la suma del monto planificado de las actividades
     */
    public function getMontoPlanificado()
    {
        $montoPlanificado = 0;
        foreach ($this->objetivos as $objetivo)
        {
            foreach($objetivo->getActividades() as $actividad)
            {
                $moneda=$actividad->getMoneda();
                $montoPlanificado+=($actividad->getMonto()*$moneda->getPrecioBs());
            }
        }
        return $montoPlanificado;
    }    
    
    /*
     * Obtener la suma de las fuentes de financiamiento
     */
    public function getMontoFinanciamiento()
    {
        $montoFinanciamiento = 0;
        foreach($this->fuentes as $fuente)
        {
            $moneda=$fuente->getMoneda();
            $montoFinanciamiento+=($fuente->getMonto()*$moneda->getPrecioBs());
        }
        return $montoFinanciamiento;
    }
    
    /**
     * Obtener la suma del monto recibido por proyecto
     */
    public function getMontoRecibido()
    {
        $montoRecibido = 0;
        foreach ($this->recursoRecibido as $recibido)
        {
          $moneda=$recibido->getMoneda();
          $montoRecibido+=($recibido->getMonto()*$moneda->getPrecioBs());
        }
        return $montoRecibido;
    }    
    
    /**
     * Obtener la suma del monto ejecutado por proyecto
     */
    public function getMontoEjecutado()
    {
        $montoEjecutado = 0;
        foreach ($this->objetivos as $objetivo)
        {
           if (count($objetivo->getActividades())!=0)
           {
              foreach($objetivo->getActividades() as $actividad)
              {
                if (count($actividad->getRecursoEjecutado())!=0)
                {
                  foreach($actividad->getRecursoEjecutado() as $ejecutado)
                  {
                    $moneda = $ejecutado->getMoneda();
                    $montoEjecutado+=($ejecutado->getMonto()*$moneda->getPrecioBs());
                  }
                }
              }               
           }
        }
        return $montoEjecutado;
    }

    /**
     * Obtener la suma total de las metas alcanzadas de las actividades por proyecto
     */
    public function getMetasAlcanzadas()
    {
        $metasAlcanzadas = 0;
        foreach ($this->objetivos as $objetivo)
        {
           if (count($objetivo->getActividades())!=0)
           {
              foreach($objetivo->getActividades() as $actividad)
              {
                if (count($actividad->getMetaAlcanzada())!=0)
                {
                  foreach($actividad->getMetaAlcanzada() as $metaAlcanzada)
                  {                    
                    $metasAlcanzadas+=$metaAlcanzada->getMeta();
                  }
                }
              }               
           }
        }
        return $metasAlcanzadas;
    }
    
    /**
     * Obtener la suma total de las metas físicas planificadas de las actividades por proyecto
     */
    public function getMetasPlanificadas()
    {
        $metasPlanificadas = 0;
        foreach ($this->objetivos as $objetivo)
        {
           if (count($objetivo->getActividades())!=0)
           {
              foreach($objetivo->getActividades() as $actividad)
              {      
                 $metasPlanificadas+=$actividad->getMetaFisica();               
              }               
           }
        }
        return $metasPlanificadas;
    }       
    
    /**
     * Obtener la Fecha de inicio del proyecto a partir 
     * de la fecha de sus actividades
     */
    public function getFechaInicio()
    {
        $fechaInicio = null;
        if (count($this->objetivos)==0) return $fechaInicio;
        foreach ($this->objetivos as $objetivo)
        {
            if (count($objetivo->getActividades())!=0)
            {
                foreach($objetivo->getActividades() as $actividad)
                {
                   $fechaActividad = $actividad->getFechaIni(); 
                   $fechaInicio = (($fechaActividad < $fechaInicio)||
                                    $fechaInicio==null)? $fechaActividad:$fechaInicio;
                }
            }
        }
        return $fechaInicio;
    } 

    /**
     * Obtener la Fecha de Culminación del proyecto a partir
     * de la fecha de sus actividades
     */
    public function getFechaFin()
    {
        $fechaFin = null;
        if (count($this->objetivos)==0) return $fechaFin;
        foreach ($this->objetivos as $objetivo)
        {
            if (count($objetivo->getActividades())!=0)
            {
                foreach($objetivo->getActividades() as $actividad)
                {
                   $fechaActividad = $actividad->getFechaFin(); 
                   $fechaFin = (($fechaActividad > $fechaFin)||
                                    $fechaFin==null)? $fechaActividad:$fechaFin;
                }
            }
        }
        return $fechaFin;
    }
    
    /*
     * Obtener la duración del proyecto según la fecha de sus actividades
     */
    public function getDuracion()
    {
        $duracion = null;
        $fechaIni = $this->getFechaInicio();
        $fechaFin = $this->getFechaFin();
        if ($fechaIni!=null)
        {  // Obtenemos un valor en segundos
           $duracion = $fechaFin->getTimestamp() - $fechaIni->getTimestamp();
           if ($duracion<5184000) return ($duracion/ 60 / 60 / 24).' días';
           $duracion=floor($duracion / 60 / 60 / 24 / 30).' meses'; 
        }
        return $duracion;
    }

    /*
     * Verificamos si la planificación está completa:
     * 1- Tener más de un objetivo específico
     * 2- Cada objetivo específico debe tener al menos una actividad
     * 3- El monto Planificado debe ser igual al monto del financiamiento
     */
    public function isPlanificacionCompleta()
    {
        $planificacion=FALSE;
        if ( (round($this->getMontoFinanciamiento(),2) == round($this->getMontoPlanificado(),2)) &&
             ($this->getMontoFinanciamiento() != 0) )
        {
            if(count($this->objetivos)!=0)
            {
                foreach ($this->objetivos as $objetivo)
                {
                    if (count($objetivo->getActividades())!=0)
                    {
                        $planificacion = ($planificacion || TRUE);
                    }
                    else 
                    {
                        $planificacion = ($planificacion && FALSE);
                    }
                }
            }
        }
        return $planificacion;
    }        
}