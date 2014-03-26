<?php
namespace IHorse\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('salt', 'hidden', array('required' => false));
        $builder->add('password', 'repeated',
            array('type' => 'password',
                'invalid_message' => 'Las dos contraseñas deben coincidir',
                'first_name' => 'Password',
                'second_name' => 'Confirmar_password',
                'error_bubbling' => 'true'));
    }

    public function getName()
    {
        return 'password';
    }

}
