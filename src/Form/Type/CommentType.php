<?php

namespace Tracker\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType {

    public function getName() {
        return 'comment_type';
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefault('data_class', 'Tracker\Entity\Comment');
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('content', 'textarea',
            array(
                'label' => false,
                'attr' => array(
                    'class' => 'wyshtml5-area',
                    'data-role' => 'wyshtml5-area'
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
