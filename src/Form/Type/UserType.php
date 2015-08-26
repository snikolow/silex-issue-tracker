<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {

    public function getName() {
        return 'priority_type';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('email', 'email');
        $builder->add('name', 'text');
        $builder->add('enabled', 'checkbox');
        $builder->add('isAdmin', 'checkbox', array('label' => 'Has admin access'));
        $builder->add('submit', 'submit',
            array(
                'attr' => array(
                    'class' => 'btn-success btn-sm'
                )
            )
        );
    }

}
