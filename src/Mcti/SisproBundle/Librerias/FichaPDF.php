<?php
 namespace Mcti\SisproBundle\Librerias;
/*
 * Clase para generar Ficha en pdf
 */
 
 require_once 'tcpdf/tcpdf.php';
 
// extendemos la Clase TCPF con funciones personalizadas
class FichaPDF extends \TCPDF {

    // Membrete del Documento PDF
    public function Header() 
    {           
        $image_file = __DIR__.'/../Resources/public/img/membrete.png';
	$this->Image($image_file, 12, 7, 256, 0, 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
                
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Reiza Garcia');
        $this->SetTitle('Reportes Consolidados');
        $this->SetSubject('Ficha de Proyecto');     
     
        // set default header data
        //$this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
        
        // set header and footer fonts
        //$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
     
        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
     
        // set margins
        $this->SetMargins(12, 23, 12);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set image scale factor
        //$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // ---------------------------------------------------------
    }
    
    /*
     * ELABORACIÓN DEL DOCUMENTO PDF
     */
    public function cargarData($data)
    {    
        $proyecto = $data['proyecto'];
        $actividades = $data['actividades'];
        
        $this->AddPage();
        // set margins
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, 12);
        // set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);   
        
        $this->SetFont('helvetica', '', 10);
        
        $this->SetLeftMargin(12);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        /*
         * FECHA y CODIGO DEL DOCUMENTO
         */
        $fechaActual = new \DateTime('today');
        $html = '<table border="1" cellpadding="5px"><tr><td> Código: '.
                   $proyecto->getCodigo().
                  '</td><td align="right">Fecha del documento: '.
                  $fechaActual->format('d/m/Y').
                  '</td></tr></table>';
        
        $this->writeHTML($html, true, false, true, false, '');
        
        /*
         * SECCIÓN DATOS BASICOS DEL PROYECTO
         */        
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(2);        
        $this->writeHTMLCell(0, 0, '', '', '<b>DATOS BÁSICOS DEL PROYECTO</b>', 'LRTB', 1, 1, true, 'C', true);
               
        $this->Ln(2);
        
        /*
         * SUBTITULO Ente Ejecutor
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '', '<b>Ente Ejecutor</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', 
                mb_convert_case($proyecto->getEstructura()->getSiglas(), MB_CASE_UPPER, 'UTF-8')
                .' - '.
                mb_convert_case($proyecto->getEstructura()->getEstructura(), MB_CASE_UPPER, 'UTF-8')
                , 'LRTB', 1, 1, true, 'L', true);        

        /*
         * SUBTITULO Nombre del Proyecto
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '', '<b>Nombre del Proyecto</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '<div style="text-align:justify">'.
                 strip_tags(mb_convert_case($proyecto->getNombre(), MB_CASE_UPPER, 'UTF-8')).
                '</div>';        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);
        
        /*
         * SUBTITULO Enmarcado en
         */
        if (count($proyecto->getMarcos()) > 0)
        {        
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Proyecto Enmarcado en</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $html = '<ul>';
        
        foreach($proyecto->getMarcos() as $p)
        {
           $html .= '<li><b>'.$p->getMarco()->getMarco().': </b>'.
                    'Código: '.strip_tags($p->getCodigo()).', año: '.
                    $p->getYear().
                    '</li>'; 
        }        
        $html .= '</ul>';
        
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);        
        }
        
        /*
         * SUBTITULO Ubicación Territorial del Proyecto
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '', '<b>Ubicación Territorial</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '<div style="text-align:justify">'.
             strip_tags($proyecto->getDireccion()).
                '.<br/>Parroquia: '.
             $proyecto->getParroquia()->getParroquia().', municipio: '.
             $proyecto->getParroquia()->getMunicipio()->getMunicipio().', '.
             mb_convert_case($proyecto->getPoblado()->getPoblado(),
                     MB_CASE_TITLE, 'UTF-8').', Edo. '.   
             mb_convert_case($proyecto->getParroquia()->getMunicipio()->getEstado()->getEstado(),
                     MB_CASE_TITLE, 'UTF-8').'</div>';
        if (count($proyecto->getCoordenadas()) > 0 )
        {
            foreach ($proyecto->getCoordenadas() as $c)
            {
              $html .='<br/><b>Coordenadas:</b> Latitud: '.$c->getLatitudToString().
                        ' Longitud: '.$c->getLongitudToString();
            }            
        }
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);
        
        /*
         * SUBTITULO Tipo de Proyecto
         */
        if (count($proyecto->getTipoProyecto()) > 0)
        {    
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '', '<b>Tipo de Proyecto</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '<ul>';        
        foreach($proyecto->getTipoProyecto() as $p)
        {
           $html .= '<li>'.$p->getTipo().'</li>'; 
        }        
        $html .= '</ul>';
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);
        
        }
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Breve Descripción
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '', '<b>Breve Descripción</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '<div style="text-align:justify">'.
                strip_tags($proyecto->getDescripcion()).'</div>';
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);

        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Problema o Necesidad Social
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Problema o Necesidad Social que Plantea Resolver</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '<div style="text-align:justify">'.strip_tags($proyecto->getProblema()).'</div>';
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);
        
        /***********************************/
        $this->Ln(5);
        $this->_saltoPagina($this->getY());
        
        /*
         * SECCIÓN VINCULACIÓN POLITICO ESTRATEGICA
         */        
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(2);        
        $this->writeHTMLCell(0, 0, '', '', '<b>VINCULACIÓN POLÍTICA Y ESTRATÉGICA DEL PROYECTO</b>',
                'LRTB', 1, 1, true, 'C', true);
               
        $this->Ln(2);        
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Plan de la Patria
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '', '<b>Objetivos del Plan de la Patria</b>', 'LRTB', 1, 1, true, 'L', true);
                
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        
        $html = 'Sin Objetivos Definidos';
        $Oe = '';
        if (count($proyecto->getOn()) > 0)
        {
           $html = '<table border="0" style="text-align:justify">';
           foreach($proyecto->getOn() as $p) 
           {
              if ($Oe != $p->getOe()->getCodigo())
              {
                $html .= '<tr><td colspan="2"><b>'.$p->getOe()->getCodigo().' '.
                        $p->getOe()->getObjetivoEstrategico().'</b></td></tr>';
                $Oe = $p->getOe()->getCodigo();
              }
               
              $html .= '<tr><td width="2%"></td><td width="98%">'.
                      $p->getCodigo().' '.$p->getObjetivoNacional().'</td></tr>';
           }
           $html .='</table>';
        }
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);   
       
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Area Estratégica de Investigación
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Área Estratégica de Investigación</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $html = 'Sin área estratégica de investigación definida';
        if (count($proyecto->getAreaEstrategica()) > 0)
        {
           $html ='';
           foreach($proyecto->getAreaEstrategica() as $p)
           {
              $html .= '<div style="text-align:justify"><b>'.
                      $p->getArea().'</b><br/>'.$p->getDefinicion().'</div>'; 
           }
        }        
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Municipios Beneficiados
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
             '<b>Municipios Beneficiados</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        
        $html = 'Proyecto a Nivel Nacional';
        
        if (!$proyecto->getNacional())        
        {
          if (count($proyecto->getMunicipio()) > 0)
          {
            $html = '<table border="1" cellpadding="3"><tr><td width="25%"><b>Municipio</b></td>'.
                    '<td width="15%"><b>Estado</b></td><td width="15%"><b>REDI</b></td></tr>';              
           foreach($proyecto->getMunicipio() as $p)
           {
              $redi='' ;
              foreach($p->getEstado()->getRedi() as $r) 
              {
                  $redi = $r->getRedi();
              }
              $html .= '<tr><td>'.
                     mb_convert_case($p->getMunicipio(), MB_CASE_TITLE, 'UTF-8').
                     '</td><td>'.
                     mb_convert_case($p->getEstado()->getEstado(), MB_CASE_TITLE, 'UTF-8').
                     '</td><td>'.
                     mb_convert_case($redi, MB_CASE_TITLE, 'UTF-8'). 
                      '</td></tr>'; 
           }
           $html .='</table>';
          }
        }        
            
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);   
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Población Beneficiada por el Proyecto
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Población Beneficiada por el Proyecto</b>', 'LRTB', 1, 1, true, 'L', true);

        $html = 'Indeterminado';
        if ($proyecto->getPobTotal()> 0)
        {
           $html = '<table border="0">'; 
           if ($proyecto->getPobFemenina()>0) 
              $html .='<tr><td width="15%" align="left">'. 
                      '<b>Población Femenina: </b></td>'.
                      '<td width="10%" align="right">'.
                   \number_format($proyecto->getPobFemenina(), 0, ',', '.').'</td></tr>';
           
           if ($proyecto->getPobMasculina()>0) 
              $html .='<tr><td width="15%" align="left">'. 
                      '<b>Población Masculina: </b></td>'.
                      '<td width="10%" align="right">'.
                   \number_format($proyecto->getPobMasculina(), 0, ',', '.').'</td></tr>';
           
           $html .='<tr><td width="15%" align="left">'. 
                   '<b>Población Total: </b></td>'.
                   '<td width="10%" align="right">'.
                   \number_format($proyecto->getPobTotal(), 0, ',', '.').'</td></tr>';
           
           $html .= '</table>';
        }       
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);        
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Empleos Directos e Indirectos
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Empleos Directos e Indirectos</b>', 'LRTB', 1, 1, true, 'L', true);

        $html = 'Indeterminado'; 
        if (($proyecto->getEmpleosDirectosEjecucion()> 0) ||
            ($proyecto->getEmpleosIndirectosEjecucion()> 0) ||
            ($proyecto->getEmpleosDirectosOperacion()> 0) ||
            ($proyecto->getEmpleosIndirectosOperacion()> 0)    )
        {
           $html = '<table border="1" cellpadding="3">'.
                    '<tr><td width="20%"></td>'.
                    '<td width="15%" align="right"><b>En Ejecución</b></td>'.
                    '<td width="15%" align="right"><b>En Operación</b></td></tr>';
           
           $html .='<tr><td align="right"><b>Empleos Directos</b></td>'.                       
                   '<td align="right">'.           
                   (($proyecto->getEmpleosDirectosEjecucion()>0)?
                    (\number_format($proyecto->getEmpleosDirectosEjecucion(), 0, ',', '.')):
                      'Indeterminado').'</td>'.'<td align="right">'.           
                   (($proyecto->getEmpleosDirectosOperacion()>0)?
                   (\number_format($proyecto->getEmpleosDirectosOperacion(), 0, ',', '.')):
                      'Indeterminado').'</td></tr>';

           $html .='<tr><td align="right"><b>Empleos Indirectos</b></td>'.                       
                   '<td align="right">'.
                   (($proyecto->getEmpleosIndirectosEjecucion()>0)?
                    \number_format($proyecto->getEmpleosIndirectosEjecucion(), 0, ',', '.'):
                      'Indeterminado').'</td>'.'<td align="right">'.           
                   (($proyecto->getEmpleosIndirectosOperacion()>0)?
                    \number_format($proyecto->getEmpleosIndirectosOperacion(), 0, ',', '.'):
                      'Indeterminado').'</td></tr>';
           
           $html .= '</table>';
        }     
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);         
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Punto y Círculo
         */
        if ($proyecto->getPuntoycirculo() != null)
        {
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Punto y Círculo</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '<div style="text-align:justify">'.
                strip_tags($proyecto->getPuntoycirculo()).'</div>';
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);
        }   
        
        /***********************************/
        $this->Ln(5);                    
        $this->_saltoPagina($this->getY());
        /*
         * SECCIÓN FORMULACIÓN METODOLÓGICA DEL PROYECTO
         */        
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(2);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>FORMULACIÓN METODOLÓGICA DEL PROYECTO</b>',
                'LRTB', 1, 1, true, 'C', true);
               
        $this->Ln(2);
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Objetivo General del Proyecto
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Objetivo General del Proyecto</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '<div style="text-align:justify">'.
                strip_tags($proyecto->getObjetivoGeneral()).'</div>';
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Producto del Proyecto
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Producto del Proyecto</b>', 'LRTB', 1, 1, true, 'L', true);

        $html = '<div style="text-align:justify">'.
                '<b>Producto: </b>'.$proyecto->getProducto().
                '<br/><b>Meta Física: </b>'.number_format(($proyecto->getMeta()), 2, ',', '.')
                .' '.strip_tags($proyecto->getUnidadMedida()).
                '<br/><b>Indicadores: </b>'.strip_tags($proyecto->getIndicador()).
                '</div>';
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Duración del Proyecto
         */
        if ($proyecto->getFechaInicio() != null)
        {
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '', '<b>Duración del Proyecto</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = $proyecto->getDuracion().', desde: '.$proyecto->getFechaInicio()->format('d/m/Y').
                ' hasta: '.$proyecto->getFechaFin()->format('d/m/Y');
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);  
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Alcance / Impacto
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Alcance / Impacto del Proyecto</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '<div style="text-align:justify">'.
                strip_tags($proyecto->getAlcance()).'</div>';
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);
        }
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Objetivos Específicos / Actividades
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Objetivos Específicos / Actividades</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = 'Sin Objetivos Específicos Definidos';
        if (count($proyecto->getObjetivos()) > 0)
        {
           $html = '<table border="1" cellpadding="3" style="text-align:justify">'; 
           foreach ($proyecto->getObjetivos() as $p) 
           {
              $html .= '<tr><td width="4%" align="center"><b>'.
                       $p->getCodigo().'</b></td>'.
                       '<td colspan="2" width="96%"><b>'.
                       strip_tags($p->getObjetivoEspecifico()).
                       '</b></td></tr>'; 
              if ($p->getActividades() != null)
              {
                 foreach ($p->getActividades() as $a) 
                 {
                    $html .= '<tr><td colspan="2" width="6%" align="right">'.
                             $p->getCodigo().'.'.$a->getCodigo().'</td><td width="94%">'.
                             strip_tags($a->getActividad()).'<br/>'.
                             'Meta Física: '.
                             \number_format($a->getMetaFisica(), 2, ',', '.').' '.
                             strip_tags($a->getUnidadMedida()).'<br/>'.                             
                             'Monto Presupuestado: '.
                             $a->getMoneda()->getSimbolo().' '.
                             \number_format($a->getMonto(), 2, ',', '.').' ('.
                             $a->getMoneda()->getIso().')'.
                             '</td></tr>';
                 }
              }
           }
           $html .='</table>';
        }        
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);
        
        /***********************************/
        $this->Ln(5);                    
        $this->_saltoPagina($this->getY());
        /*
         * SECCIÓN INVERSIÓN DEL PROYECTO
         */        
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(2);        
        $this->writeHTMLCell(0, 0, '', '',
                '<b>INVERSIÓN DEL PROYECTO</b>', 'LRTB', 1, 1, true, 'C', true);               
        $this->Ln(2);
                
        /*
         * SUBTITULO Fuentes de Financiamiento
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Fuentes de Financiamiento</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = 'No Definidas';
        if (count($proyecto->getFuentes()) > 0)
        {
           $html = '<table border="1" cellpadding="3">'.
                    '<tr><td width="35%" align="center"><b>Fuente Financiera</b></td>'.
                    '<td width="15%" align="center"><b>Monto</b></td>'.
                    '<td width="15%" align="center"><b>Moneda</b></td></tr>';           
           foreach ($proyecto->getFuentes() as $p)
           {
              $html .='<tr><td>'.$p->getFuenteFinanciamiento()->getFuente().'</td>'.
                      '<td align="right">'.$p->getMoneda()->getSimbolo().' '.
                      \number_format($p->getMonto(), 2, ',', '.').'</td>'.
                      '<td>&nbsp;&nbsp;'.$p->getMoneda()->getMoneda().'</td></tr>';
           }           
           $html .= '</table>';
        }   
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);        
        
        /***********************************/
        $this->Ln(5);                    
        $this->_saltoPagina($this->getY());
        /*
         * SECCIÓN CRONOGRAMA DE EJECUCIÓN DEL PROYECTO
         */        
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(2);        
        $this->writeHTMLCell(0, 0, '', '',
                '<b>CRONOGRAMA DE EJECUCIÓN DEL PROYECTO</b>', 'LRTB', 1, 1, true, 'C', true);               
        $this->Ln(2);
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Cronograma de Ejecución Presupuestaria
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
                '<b>Cronograma de Ejecución Presupuestaria</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '';
        if (count($actividades) != 0)
        {
            $years = array();
            foreach ($actividades as $a)
            {
               if (!in_array($a->getFechaIni()->format('Y'),$years))
                  array_push($years, $a->getFechaIni()->format('Y'));
               
               if (!in_array($a->getFechaFin()->format('Y'),$years))
                  array_push($years, $a->getFechaFin()->format('Y'));
            }            
            sort($years);            
            
            $ancho = 90; // ANCHO DEL ESPACIO PARA EL CRONOGRAMA
            $html = '<table cellpadding="2"><tr><td width="10%" ></td>';
            
            // LINEA 1  |     | año 1 | año n |            
            $cols = (count($years) >= 3)? 3:count($years);
            $fontSize = ($cols !=3)?10:7; // TAMAÑO DE LOS NUMEROS
            $i = 0;
            foreach ($years as $y)
            {
               if ($i < $cols)               
               $html .= '<td width="'.intval($ancho/$cols).'%" colspan="4" align="center" border="1">'.
                        '<b>'.$y.'</b></td>';               
               $i++;
            }
            $html .= '</tr>';
            // LINEA 2 | actividades | I | II | III | IV |
            $html .= '<tr><td align="center" border="1"><b>Actividad</b></td>';
            $ancho = $ancho /($cols*4);
            $i = 0;
            foreach ($years as $y)
            {
               if ($i < $cols)
               $html .= '<td width="'.$ancho.'%" align="center" border="1"><b>I</b></td>'.
                        '<td width="'.$ancho.'%" align="center" border="1"><b>II</b></td>'.
                        '<td width="'.$ancho.'%" align="center" border="1"><b>III</b></td>'.
                        '<td width="'.$ancho.'%" align="center" border="1"><b>IV</b></td>';
               $i++;
            }            
            $html .= '</tr>';
            
            // Fila Totales
            for ($i = 0; $i < ($cols*4); $i++) $filaTotales[$i] = 0; 
            
            foreach ($actividades as $a)
            {
               // Columnas de la fila
               for ($i = 0; $i < ($cols*4); $i++) $fila[$i] = '';
               
               $trim =  intval((intval($a->getFechaIni()->format('m'))-1)/3);
               $year =  array_search($a->getFechaIni()->format('Y'), $years);
               
               $pos = $year*4 + $trim;
               
               if ($pos < ($cols*4)) 
               {
                   $fila[$pos] = $a->getMonto();
                   $filaTotales[$pos] += ($a->getMonto()*$a->getMoneda()->getPrecioBs());
               }

               else $fila = 'Actividad Planificada para el año '.$a->getFechaIni()->format('Y');
                
               $html .= '<tr><td align="center" border="1"><b>'.
                        $a->getObjetivoEspecifico()->getCodigo().'.'.$a->getCodigo().
                        '</b></td>';
               
               if ($pos < ($cols*4))               
                  foreach ($fila as $f) $html .='<td align="right" border="1" '.
                        'style="font-size:'.$fontSize.'">'.
                       ((is_numeric($f))? 
                           ($a->getMoneda()->getSimbolo().number_format($f, 0, ',', '.')):'').'</td>';
               
               else $html .='<td colspan="'.($cols*4).'" align="center" border="1">'.$fila.'</td>'; 
                   
               $html .='</tr>';  
               
               unset($fila);
            }         
            $html .= '<tr><td align="center" border="1"><b>% TOTAL</b></td>';
            foreach ($filaTotales as $f) $html .='<td align="right" border="1" '.
                    'style="font-size:10"><b>'.
                    number_format(($f*100/($proyecto->getMontoPlanificado())), 2, ',', '.').
                    '%</b></td>';
            $html .= '</tr>';
            $html .= '</table><br/>';
            
        }else $html = 'Sin Actividades Planificadas';
                
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);                 
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Cronograma de Ejecución Física
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
                '<b>Cronograma de Ejecución Física de Actividades</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '';
        if (count($actividades) != 0)
        {
            $years = array();
            foreach ($actividades as $a)
            {               
               if (!in_array($a->getFechaFin()->format('Y'),$years))
                  array_push($years, $a->getFechaFin()->format('Y'));
            }            
            sort($years);            
            
            $ancho = 90; // ANCHO DEL ESPACIO PARA EL CRONOGRAMA
            $html = '<table cellpadding="2"><tr><td width="10%" ></td>';
            
            // LINEA 1  |     | año 1 | año n |            
            $cols = (count($years) >= 3)? 3:count($years);
            $fontSize = ($cols !=3)?10:9; // TAMAÑO DE LOS NUMEROS
            $i = 0;
            foreach ($years as $y)
            {
               if ($i < $cols)               
               $html .= '<td width="'.intval($ancho/$cols).'%" colspan="4" align="center" border="1">'.
                        '<b>'.$y.'</b></td>';               
               $i++;
            }
            $html .= '</tr>';
            // LINEA 2 | actividades | I | II | III | IV |
            $html .= '<tr><td align="center" border="1"><b>Actividad</b></td>';
            $ancho = $ancho /($cols*4);
            $i = 0;
            foreach ($years as $y)
            {
               if ($i < $cols)
               $html .= '<td width="'.$ancho.'%" align="center" border="1"><b>I</b></td>'.
                        '<td width="'.$ancho.'%" align="center" border="1"><b>II</b></td>'.
                        '<td width="'.$ancho.'%" align="center" border="1"><b>III</b></td>'.
                        '<td width="'.$ancho.'%" align="center" border="1"><b>IV</b></td>';
               $i++;
            }            
            $html .= '</tr>';
            
            // Fila Totales
            for ($i = 0; $i < ($cols*4); $i++) $filaTotales[$i] = 0; 
            
            foreach ($actividades as $a)
            {
               // Columnas de la fila
               for ($i = 0; $i < ($cols*4); $i++) $fila[$i] = '';
               
               $trim =  intval((intval($a->getFechaFin()->format('m'))-1)/3);
               $year =  array_search($a->getFechaFin()->format('Y'), $years);
               
               $pos = $year*4 + $trim;
               
               if ($pos < ($cols*4)) 
               {
                   $fila[$pos] = $a->getMetaFisica();
                   $filaTotales[$pos] += $a->getMetaFisica();
               }
               else $fila = 'Actividad Planificada para el año '.$a->getFechaFin()->format('Y');
                
               $html .= '<tr><td align="center" border="1"><b>'.
                        $a->getObjetivoEspecifico()->getCodigo().'.'.$a->getCodigo().
                        '</b></td>';
               
               if ($pos < ($cols*4))               
                  foreach ($fila as $f) $html .='<td align="right" border="1" '.
                        'style="font-size:'.$fontSize.'">'.
                       ((is_numeric($f))? 
                           (number_format($f, 0, ',', '.')):'').'</td>';
               
               else $html .='<td colspan="'.($cols*4).'" align="center" border="1">'.$fila.'</td>'; 
                   
               $html .='</tr>';  
               
               unset($fila);
            }         
            $html .= '<tr><td align="center" border="1"><b>% TOTAL</b></td>';
            foreach ($filaTotales as $f) $html .='<td align="right" border="1" '.
                    'style="font-size:'.$fontSize.'"><b>'.
                    number_format(($f*100/$proyecto->getMetasPlanificadas()), 2, ',', '.').
                    '%</b></td>';
            $html .= '</tr>';
            $html .= '</table><br/>';
            
        }else $html = 'Sin Actividades Planificadas';
                
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true); 
        
        /***********************************/
        $this->Ln(5);                    
        $this->_saltoPagina($this->getY());
        /*
         * SECCIÓN RESPONSABLE DEL PROYECTO
         */        
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(2);        
        $this->writeHTMLCell(0, 0, '', '',
                '<b>RESPONSABLE DEL PROYECTO</b>', 'LRTB', 1, 1, true, 'C', true);               
        $this->Ln(2);
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Datos de Contacto
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '','<b>Datos de Contacto</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '<table border="0" cellpadding="2">'.
                '<tr><td width="20%" align="right"><b>Nombre y Apellido:</b></td>'.
                '<td width="30%">'.
                $proyecto->getUsuario()->__toString().'</td><td></td></tr>'.
                '<tr><td align="right"><b>Cargo:</b></td><td>'.
                $proyecto->getUsuario()->getCargo().'</td><td></td></tr>'.
                '<tr><td align="right"><b>Correo Electrónico:</b></td><td>'.
                $proyecto->getUsuario()->getCorreo().'</td><td></td></tr>'.                
                '<tr><td align="right"><b>Teléfono:</b></td><td>'.
                $proyecto->getUsuario()->getTelefono().'</td>'.
                '<td width="40%" align="center" style="border-top: black solid 1px">'.
                '<b>FIRMA DEL RESPONSABLE DEL PROYECTO</b></td></tr>';
        
        $html .='</table>';
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true); 
        
        $this->_saltoPagina($this->getY());
        /*
         * SUBTITULO Revisión del Proyecto
         */
        $this->SetFillColor(223,223,223);        
        $this->SetCellPadding(1);        
        $this->writeHTMLCell(0, 0, '', '',
            '<b>Revisión del Proyecto</b>', 'LRTB', 1, 1, true, 'L', true);
        
        $html = '<table border="0" cellpadding="2">'.
                '<tr><td width="5%"></td><td width="40%"></td>'.
                '<td width="10%"></td><td></td></tr>'.
                '<tr><td colspan="4"></td></tr>'.
                '<tr><td colspan="4"></td></tr>'.
                '<tr><td colspan="4"></td></tr>'.
                '<tr><td colspan="4"></td></tr>'.
                '<tr><td></td><td align="center" style="border-top: black solid 1px"><b>'.
                'FIRMA DEL PRESIDENTE(A) DE LA INSTITUCIÓN</b><br/>'.
                mb_convert_case($proyecto->getEstructura()->getEstructura(),
                                MB_CASE_UPPER, 'UTF-8').' - '.
                mb_convert_case($proyecto->getEstructura()->getSiglas(),
                                MB_CASE_UPPER, 'UTF-8').
                '</td><td></td>'.
                '<td width="40%" align="center" style="border-top: black solid 1px">'.
                '<b>SELLO DE LA INSTITUCIÓN</b></td></tr>';
        
        $html .='</table>';
        
        $this->SetFillColor(255,255,255);
        $this->SetCellPadding(2);
        $this->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 1, true, 'L', true);         
        
    }
    
    private function _saltoPagina($altura=200)
    {
       if ($altura>150)
       {
           $this->AddPage();
           $this->SetLeftMargin(12);
       }            
       return true;
    }
}

?>
