<?php
namespace IHorse\BackendBundle\Form\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OwnerSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('required' => false))
            ->add('clinic', 'json_clinic', array('required'=>false, 'empty_value'=>'Seleccione un dueño'))
            ->add('address', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'search_owner';
    }

}