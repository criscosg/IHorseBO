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
            ->add('lastName', 'text', array('required' => false))
            ->add('city', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'owner';
    }

}