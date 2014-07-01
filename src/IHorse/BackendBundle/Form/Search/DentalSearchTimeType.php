<?php

namespace IHorse\BackendBundle\Form\Search;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DentalSearchTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', 'text', array('required' => false))
            ->add('to', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'filtroDentalDates';
    }
}
