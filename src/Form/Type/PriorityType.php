<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriorityType extends AbstractType {

    public function getName() {
        return 'priority_type';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', 'text');
        $builder->add('className', 'text');
        $builder->add('submit', 'submit',
            array(
                'attr' => array(
                    'class' => 'btn-success btn-sm'
                )
            )
        );
    }

}
