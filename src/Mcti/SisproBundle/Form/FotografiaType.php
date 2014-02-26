<?php
/**
 * Description of FotografiaType
 *
 * @author Reiza García / 0414-9000273
 */
namespace Mcti\SisproBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FotografiaType extends AbstractType
{  
    protected $lista;
    
    public function __construct($lista) 
    {
        $this->lista=$lista;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
      $builder
        ->add('imagen','file', array('label'=>'Adjuntar imagen',
                                  'required'=>true))  
              
        ->add('actividad','entity', array('label'=>'Actividad Asociada',
                                          'class'=>'SisproBundle:Actividad',
                                        'choices'=>$this->lista,    
                                           'attr'=>array('class'=>'span10 campo',  
                                                         'title'=>'Actividad asociada')))
              
        ->add('comentarios',null, array('label'=>'Comentarios',            
                                         'attr'=>array('rows' =>'2',
                                                   'maxlength'=>'150',
                                                       'class'=>'span10 campo',  
                                                       'title'=>'Describa la fotografía')))
      ;
    }
   
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
      $resolver->setDefaults(array(
                'data_class' => 'Mcti\SisproBundle\Entity\Fotografia',
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
                // una clave única para ayudar generar la ficha secreta
                'intention'       => 'task_item',
      ));
    }
   
    public function getName()
    {
      return 'data';
    }
}

?>
