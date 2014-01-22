<?php
namespace IHorse\BackendBundle\Form\Type;

use IHorse\BackendBundle\Model\Handler\HorseHandler;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class JsonHorseType extends AbstractType
{
    private $handler;

    public function __construct(HorseHandler $handler)
    {
        $this->handler = $handler;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->getChoices(),
        ));
    }

    private function getChoices()
    {
        $horses = $this->handler->getList('horses', 'horses');
        $final = array();
        $id = 1;
        foreach ($horses as $horse) {
            foreach ($horse as $key => $value) {
                if ($key == 'id') {
                    $id = $value;
                }
                if ($key == 'name'){
                    $final[$id] = $value;
                }
            }
        }

        return $final;
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'json_horse';
    }

}