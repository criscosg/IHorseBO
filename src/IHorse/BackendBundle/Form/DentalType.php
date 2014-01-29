<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('report', 'text', array('required' => false))
            ->add('feeding', 'text', array('required' => false))
            ->add('sedation', 'text', array('required' => false))
            ->add('comment', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'dental';
    }

}