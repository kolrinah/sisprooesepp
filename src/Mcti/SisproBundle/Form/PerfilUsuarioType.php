<?php
/**
 * Description of ActivarType
 *
 * @author Reiza García / 0414-9000273
 */
namespace Mcti\SisproBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PerfilUsuarioType extends AbstractType
{     
   public function buildForm(FormBuilderInterface $builder, array $options)
   {               
      $builder
              ->add('correo','email', array('label'=>'Correo Electrónico',
                                            'attr'=>array('readonly'=>'readonly',
                                                          'title'=>'Correo Electrónico')))
              
              ->add('nombre', null, array('label'=>'Nombres',
                                          'attr'=>array('title'=>'Nombres')))
              
              ->add('apellido', null, array('label'=>'Apellidos',
                                            'attr'=>array('title'=>'Apellidos')))
              
              ->add('cargo')
              
              ->add('telefono') 
              
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
      return 'mcti_sisprobundle_perfilusuariotype';
   }
}

?>
