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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class CambiarClaveType extends AbstractType
{    
   public function buildForm(FormBuilderInterface $builder, array $options)
   {       
      $builder
              ->add('correo','email', array('label'=>'Correo Electrónico',
                                             'attr'=>array('readonly'=>'readonly',
                                                              'title'=>'Correo Electrónico')))     

              ->add('clave', 'repeated', array('type' => 'password',                                        
                                    'invalid_message' => ' Las dos contraseñas deben coincidir ',
                   'constraints' => array(
                                   new NotNull(array('message'=>'Campo Requerido')),
                                   new Length(array('min' => 6,
                                                    'max' => 50,
                                   'minMessage' => ' La contraseña de tener al menos 6 caracteres ',
                                   'maxMessage' => ' Clave demasiado larga '))),
                   'attr'=> array('title'=>'Introduzca su contraseña')))
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
      return 'mcti_sisprobundle_cambiarclavetype';
   }
}

?>
