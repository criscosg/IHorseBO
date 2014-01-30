<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('feed', 'text', array('required' => false))
            ->add('hay', 'checkbox', array('required' => false))
            ->add('silage', 'checkbox', array('required' => false))
            ->add('pasture', 'text', array('required' => false))
            ->add('comment', 'textarea', array('required' => false));
    }

    public function getName()
    {
        return 'feeding';
    }

}