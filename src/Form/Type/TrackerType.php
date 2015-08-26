<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class TrackerType extends AbstractType {

    public function getName() {
        return 'trackers_type';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', 'text',
            array(
                'constraints' => array(
                    new Assert\Length(array('min' => 3))
                )
            )
        );
        $builder->add('submit', 'submit',
            array(
                'attr' => array(
                    'class' => 'btn-success btn-sm'
                )
            )
        );
    }

}
