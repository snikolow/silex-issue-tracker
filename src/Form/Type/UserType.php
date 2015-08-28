<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {

    public function getName() {
        return 'priority_type';
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefault('create', false);
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('email', 'email');
        $builder->add('name', 'text');
        
        if( $options['create'] !== false ) {
            $builder->add('password', 'password');
        }
        
        $builder->add('enabled', 'checkbox', array('required' => false));
        $builder->add('isAdmin', 'checkbox', array('label' => 'Has admin access', 'required' => false));
        $builder->add('submit', 'submit',
            array(
                'attr' => array(
                    'class' => 'btn-success btn-sm'
                )
            )
        );
    }

}
