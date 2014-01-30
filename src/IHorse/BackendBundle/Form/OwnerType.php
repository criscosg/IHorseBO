<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OwnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', array('required' => true))
            ->add('name', 'text', array('required' => true))
            ->add('last_name', 'text', array('required' => false))
            ->add('clinic', 'json_clinic', array('required'=>true))
            ->add('address', 'text', array('required' => false))
            ->add('phone', 'text', array('required' => false))
            ->add('mobile', 'text', array('required' => false))
            ->add('text', 'textarea', array('required' => false));
    }

    public function getName()
    {
        return 'owner';
    }

}