<?php
/**
 * Description of ProyectoType
 *
 * @author Reiza García / 0414-9000273
 */
namespace Mcti\SisproBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProyectoType extends AbstractType
{    
   public function buildForm(FormBuilderInterface $builder, array $options)
   {  
      $title['nombre']= "Identificar y expresar de manera clara el nombre del Proyecto.\n ".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";
      $title['codigo']='Código Interno del Proyecto';
      $title['descripcion']="¿En qué consiste el proyecto?, su alcance, finalidad, ".
              "resultado esperado, y en cuantas fases o etapas se logra.\n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";
      $title['problema']="Problema: una situación negativa, un estado de insatisfacción ".
              "para un actor o varios actores en un momento dado. ".              
              "Es también, la distancia existente entre una situación ".
              "dada de insatisfacción y una situación deseada, considerada como óptima.\n".    
              "Necesidad sentida o demanda social: Cuando es el pueblo o los propios ".
              "actores sociales, los que reconocen y expresan el problema, ".
              "demandan una intervención del Estado y/o acciones conjuntas para resolverla.\n".
              "Necesidad percibida o necesidad social: Cuando es el propio gobierno el ".
              "que reconoce el problema como una necesidad social que se debe satisfacer, ".
              "detecta el problema y reconoce la necesidad social y pasa a formar parte ".
              "de la agenda política. \n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";        
      $title['objetivoGeneral']="Propósito central del proyecto. Expresa qué se quiere hacer ".
              "o se espera alcanzar con el proyecto (logros definidos que se busca alcanzar).\n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";
      $title['producto']="Bien o servicio que surge como resultado cualitativo y cuantitativo ".
              "de la ejecución del proyecto.\n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";    
      $title['meta']="Expresión concreta y cuantificable del bien o servicio que se ".
              "espera obtener de la ejecución del proyecto, usando criterios ".
              "de cantidad calidad y tiempo.\n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";
      $title['unidadMedida']="Descripción cualitativa del producto o el resultado ".
              "de la ejecución del proyecto.\n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";
      $title['indicador']="Miden los cambios que se esperan lograr al final del proyecto, ".
              "e incluso más allá de su finalización, y que son definidos en el Objetivo ".
              "general. Debe estar en correspondencia con el impacto o alcance esperado ".
              "del proyecto. Debe estar expresado de manera específica, explícita y ".
              "objetivamente verificable. \n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";
      $title['alcance']="Debe quedar expresado de manera clara y precisa cómo los ".
              "resultados y logros de este proyecto contribuyen con la consolidación ".
              "y/o fortalecimiento de los objetivos del Plan Patria, ".
              "de las áreas estratégicas de investigación y/o de los lineamientos ".
              "institucionales. Vale recalcar, que no se quiere repetir los ".
              "conceptos definidos, si no expresar los resultados, logros del ".
              "proyecto y su impacto en la población, su aporte al fortalecimiento ".
              "del poder popular, del nuevo modelo productivo y relaciones de ".
              "producción, de la transformación de valores, de hacer revolución.\n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";              
      $title['direccion']="Colocar la ubicación física del proyecto. En caso de no ".
              "poseer una, se colocará la dirección del ente ejecutor. \n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";              
      $title['puntoycirculo']="Modelo de integración y articulación en el proceso de ".
              "transición al socialismo, que plantea la gestación de unidades ".
              "productivas en las comunidades a partir de las vocaciones y ".
              "potencialidades productivas promoviendo y consolidando la participación ".
              "protagónica del pueblo organizado, de los trabajadores y las ".
              "trabajadoras en Concejos Comunales, la conformación de las Comunas ".
              "en el proceso de construcción del estado Comunal, socialista, ".
              "bolivariano y chavista.\n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";              
      $title['pobFemenina']="Cantidad aproximada de personas del sexo femenino ".
              "que se beneficiarán de forma directa o indirecta con el proyecto.\n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";              
      $title['pobMasculina']="Cantidad aproximada de personas del sexo masculino ".
              "que se beneficiarán de forma directa o indirecta con el proyecto.\n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";              
      $title['pobTotal']="Cantidad aproximada de personas que se beneficiarán de ".
              "forma directa o indirecta con el proyecto.\n".
              "Usar solo letras capitales al inicio ".
              "de cada párrafo. Use los signos de puntuación adecuada y correctamente.";
      
      $builder
              ->add('codigo', null, array('label'=>'Código',                                           
                                           'attr'=>array('title'=>$title['codigo'],
                                               'readonly'=>'readonly',)))
              
              ->add('nombre', null, array('label'=>'Nombre del Proyecto',
                                           'attr'=>array('rows' =>'1',
                                                     'maxlength'=>'150',
                                                         'class'=>'span10 campo',
                                                         'title'=>$title['nombre'])))
              
              ->add('descripcion', null, array('label'=>'Breve Descripción',
                                           'attr'=>array('rows' =>'1',
                                                         'maxlength'=>'400',
                                                         'class'=>'span10 campo',
                                                         'title'=>$title['descripcion'])))
            
              ->add('problema', null, array('label'=>'Problema o necesidad social que plantea resolver',
                                           'attr'=>array('rows' =>'1',
                                                         'maxlength'=>'200',
                                                         'class'=>'span10 campo',
                                                         'title'=>$title['problema'])))
              
              ->add('direccion', null, array('label'=>'Dirección',
                                           'attr'=>array('rows' =>'1',
                                                     'maxlength'=>'200',
                                                         'class'=>'span10 campo',
                                                         'title'=>$title['direccion'])))
              
  /*            ->add('objetivoGeneral', null, array('label'=>'Objetivo General',
                                           'attr'=>array('rows' =>'1',
                                                         'class'=>'span10 campo',
                                                         'title'=>$title['objetivoGeneral'])))
              
              ->add('producto', null, array('label'=>'Producto',
                                           'attr'=>array('title'=>$title['producto'])))
              
              
              ->add('meta', null, array('label'=>'Meta',
                                           'attr'=>array('title'=>$title['meta'])))
            
              
              ->add('unidadMedida', null, array('label'=>'Unidad de Medida',
                                           'attr'=>array('title'=>$title['unidadMedida'])))
              
              ->add('indicador', null, array('label'=>'Indicador',
                                           'attr'=>array('rows' =>'1',
                                                         'class'=>'campo',
                                                         'maxlength'=>'100',
                                                         'title'=>$title['indicador'])))
              
              ->add('alcance', null, array('label'=>'Alcance / Impacto del proyecto',
                                           'attr'=>array('rows' =>'1',
                                                         'class'=>'span10 campo',
                                                         'title'=>$title['alcance'])))
              
              ->add('puntoycirculo', null, array('label'=>'Punto y Círculo',
                                           'attr'=>array('rows' =>'1',
                                                         'class'=>'span10 campo',
                                                         'title'=>$title['puntoycirculo'])))   

              
              ->add('pobFemenina', null, array('label'=>'Población Beneficiaria (mujeres)',
                                           'attr'=>array('title'=>$title['pobFemenina'])))
              
              ->add('pobMasculina', null, array('label'=>'Población Beneficiaria (Hombres)',
                                           'attr'=>array('title'=>$title['pobMasculina'])))
              
              ->add('pobTotal', null, array('label'=>'Población Beneficiaria (Total)',
                                           'attr'=>array('title'=>$title['pobTotal'])))  */
      ;
   }
   
   public function setDefaultOptions(OptionsResolverInterface $resolver)
   {
      $resolver->setDefaults(array(
                'data_class' => 'Mcti\SisproBundle\Entity\Proyecto',
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
                // una clave única para ayudar generar la ficha secreta
                'intention'       => 'task_item',
      ));
   }
   
   public function getName()
   {
      return 'proyecto';
   }
}

?>