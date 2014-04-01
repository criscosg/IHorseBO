<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeethDentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comment', 'text', array('required' => false))
                ->add('salt', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'tooth';
    }

}