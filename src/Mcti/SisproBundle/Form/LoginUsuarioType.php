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

class LoginUsuarioType extends AbstractType
{     
   public function buildForm(FormBuilderInterface $builder, array $options)
   {               
      $builder
              ->add('correo','email', array('label'=>'Correo Electrónico',
                                            'attr'=>array('name' =>'_username',
                                                          'class'=>'span4',
                                                          'maxlength'=>'40',
                                                          'required'=>'required',
                                                          'placeholder'=>'Correo Electrónico',
                                                          'title'=>'Correo Electrónico')))             
                            
              ->add('captcha', 'captcha', array(
                                                'width' => 200,
                                                'height' => 50,
                                                'length' => 6 ))              
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
      return 'login';
   }
}

?>
