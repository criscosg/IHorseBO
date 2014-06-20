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
            ->add('duration', 'number', array('required' => true))
            ->add('price', 'text', array('required' => true))
            ->add('charts', 'number',  array('required' => true))
            ->add('translations', 'collection', array('type' => new ProdTranslationType()));
    }

    public function getName()
    {
        return 'product';
    }

}