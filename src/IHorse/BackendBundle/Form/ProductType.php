<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('product_id', 'text', array('required' => true))
            ->add('title', 'text', array('required' => true))
            ->add('duration', 'number', array('required' => true))
            ->add('description', 'textarea', array('required' => false))
            ->add('price', 'text', array('required' => true))
            ->add('charts', 'number',  array('required' => true));
    }

    public function getName()
    {
        return 'product';
    }

}