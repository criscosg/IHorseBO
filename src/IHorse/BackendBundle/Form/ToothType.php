<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ToothType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tongue', 'checkbox', array('required' => false))
            ->add('kanten', 'checkbox', array('required' => false))
            ->add('haken', 'checkbox', array('required' => false))
            ->add('missLoose', 'checkbox', array('required' => false))
            ->add('diastasen', 'checkbox', array('required' => false))
            ->add('comment', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'tooth';
    }

}