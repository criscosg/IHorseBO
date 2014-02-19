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
            ->add('kanten', 'choice', array('choices' => array('mmg' => 'mmg', 'cge' => 'cge'), 'required' => false))
            ->add('haken', 'choice', array('choices' => array('mmg' => 'mmg', 'cge' => 'cge'), 'required' => false))
            ->add('missLoose', 'checkbox', array('required' => false))
            ->add('diastasen', 'checkbox', array('required' => false))
            ->add('comment', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'tooth';
    }

}