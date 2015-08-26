<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType {

    public function getName() {
        return 'role_type';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', 'text');
        $builder->add('identifier', 'text');
        $builder->add('permissions', 'entity',
                array(
                    'class' => 'App\Entity\Permission',
                    'property' => 'title',
                    'expanded' => true,
                    'multiple' => true,
                    'label_attr' => array(
                        'class' => 'checkbox-inline'
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
