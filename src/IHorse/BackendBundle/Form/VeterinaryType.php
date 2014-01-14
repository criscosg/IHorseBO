<?php
namespace IHorse\BackendBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VeterinaryType extends AdminUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        //$builder->add('clinic', 'entity', array('class'=>'VeterinaryBundle:Clinic', 'required'=>true));
    }

    public function getName()
    {
        return 'veterinary';
    }

}