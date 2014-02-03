<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('report', 'textarea', array('required' => false))
            ->add('comment', 'textarea', array('required' => false))
            ->add('feeding', new FeedingType())
            ->add('sedation', new SedationType());
    }

    public function getName()
    {
        return 'dental';
    }

}