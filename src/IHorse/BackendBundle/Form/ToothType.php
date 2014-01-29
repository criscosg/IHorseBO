<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ToothType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tongue', 'boolean', array('required' => false))
            ->add('kanten', 'boolean', array('required' => false))
            ->add('haken', 'boolean', array('required' => false))
            ->add('missLoos', 'text', array('required' => false))
            ->add('comment', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'tooth';
    }

}