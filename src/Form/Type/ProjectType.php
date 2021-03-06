<?php

namespace Tracker\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectType extends AbstractType {

    public function getName() {
        return 'project_type';
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
                array(
                    'data_class' => 'Tracker\Entity\Project',
                    'edit' => false,
                )
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', 'text',
            array(
                'constraints' => array(
                    new Assert\Length(array('min' => 3))
                )
            )
        );
        $builder->add('identifier', 'text', array('required' => false, 'disabled' => $options['edit']));
        $builder->add('category', 'entity',
                array(
                    'class' => 'Tracker\Entity\Category',
                    'choice_label' => 'title',
                    'required' => false,
                    'empty_value' => '-- Please select --',
                    'attr' => array('data-role' => 'select2')
                )
        );
        $builder->add('isPublic', 'checkbox',
            array(
                'label' => 'Make project public',
                'required' => false
            )
        );
        $builder->add('trackers', 'entity',
            array(
                'class' => 'Tracker\Entity\Tracker',
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'label_attr' => array(
                    'class' => 'checkbox-inline'
                ),
            )
        );
        $builder->add('description', 'textarea',
            array(
                'attr' => array(
                    'class' => 'wyshtml5-area',
                    'data-role' => 'wyshtml5-area'
                )
            )
        );
        if( $options['edit'] === true ) {
            $builder->add('roles', 'entity',
                    array(
                        'mapped' => false,
                        'class' => 'Tracker\Entity\Role',
                        'choice_label' => 'title',
                        'multiple' => true,
                        'expanded' => true,
                        'attr' => array('data-role' => 'roles')
                    )
            );
        }
        $builder->add('submit', 'submit',
            array(
                'attr' => array(
                    'class' => 'btn-success btn-sm'
                )
            )
        );
    }

}
