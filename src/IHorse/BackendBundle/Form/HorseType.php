<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HorseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('required' => true))
                ->add('sex', 'choice', array('choices' => array('Male' => 'Male',
                                                'Female' => 'Female'),
                                                'multiple' => false, 'expanded' => true,
                                                'required' => false, 'empty_value' => false))
                ->add('annotations', 'textarea', array('required' => false))
                ->add('birthdate', 'birthday', array('years' => range(date('Y'), date('Y') - 50),
                                                     'required' => false))
            ->add('owner', 'json_owner', array('required' => true));
    }

    public function getName()
    {
        return 'horse';
    }

}