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
                                                          'title'=>'Correo Electrónico',
                                                        'maxlength'=>150)))
              
              ->add('nombre', null, array('label'=>'Nombres',
                                          'attr'=>array('title'=>'Nombres',
                                                        'maxlength'=>50)))
              
              ->add('apellido', null, array('label'=>'Apellidos',
                                            'attr'=>array('title'=>'Apellidos',
                                                        'maxlength'=>50)))
              
              ->add('cargo', null, array('label'=>'Cargo',
                                            'attr'=>array('title'=>'Cargo',
                                                        'maxlength'=>50)))
              
              ->add('telefono', null, array('label'=>'Teléfono',
                                            'attr'=>array('title'=>'Teléfono',
                                                        'maxlength'=>50))) 
              
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
