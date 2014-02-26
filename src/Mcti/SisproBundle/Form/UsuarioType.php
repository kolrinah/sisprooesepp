<?php
/**
 * Description of UsuarioType
 *
 * @author Reiza García / 0414-9000273
 */
namespace Mcti\SisproBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType
{    
   public function buildForm(FormBuilderInterface $builder, array $options)
   {       
      $builder
              ->add('nombre', null, array('label'=>'Nombres',
                                          'attr'=>array('title'=>'Nombres')))
              ->add('apellido', null, array('label'=>'Apellidos',
                                            'attr'=>array('title'=>'Apellidos')))
              ->add('correo','email', array('label'=>'Correo Electrónico',
                                            'attr'=>array('title'=>'Correo Electrónico')))
              
              ->add('estructura',null, array('attr'=>array('style'=>'width:90%',
                                                           'class'=>'oculto')))              
              ->add('cargo', null, array('attr'=>array('title'=>'Cargo')))           
              ->add('telefono', null, array('attr'=>array('title'=>'Teléfono')))
              ->add('role', null, array('label'=>'Permisos Especiales',
                                      'multiple'=>true,
                                      'expanded'=>true))
              ->add('activo')
      ;
   }
   
   public function setDefaultOptions(OptionsResolverInterface $resolver)
   {
      $resolver->setDefaults(array(
                'data_class' => 'Mcti\SisproBundle\Entity\Usuario',
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
                // una clave única para ayudar generar la ficha secreta
                'intention'       => 'task_item',
      ));
   }
   
   public function getName()
   {
      return 'mcti_sisprobundle_usuariotype';
   }
}

?>
