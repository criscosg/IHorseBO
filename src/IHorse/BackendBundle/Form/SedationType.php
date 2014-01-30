<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SedationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('zone1', 'text', array('required' => false))
            ->add('zone2', 'text', array('required' => false))
            ->add('zone3', 'text', array('required' => false))
            ->add('zone4', 'text', array('required' => false))
            ->add('comment', 'textarea', array('required' => false));
    }

    public function getName()
    {
        return 'sedation';
    }

}