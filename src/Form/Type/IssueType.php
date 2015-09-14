<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType {

    public function getName() {
        return 'issue_type';
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
                array(
                    'data_class' => 'App\Entity\Issue',
                    'editMode' => false,
                    'attr' => array(
                        'id' => 'issue-form'
                    )
                )
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('subject', 'text');
        $builder->add('assignedTo', 'entity',
                array(
                    'class' => 'App\Entity\User',
                    'property' => 'name',
                    'label' => 'Assignee',
                    'empty_value' => 'None',
                    'required' => false,
                    'attr' => array('data-role' => 'select2')
                )
        );
        $builder->add('tracker', 'entity',
                array(
                    'class' => 'App\Entity\Tracker',
                    'property' => 'title',
                    'label' => 'Tracker',
                    'attr' => array('data-role' => 'select2')
                )
        );
        $builder->add('status', 'entity',
                array(
                    'class' => 'App\Entity\IssueStatus',
                    'property' => 'title',
                    'attr' => array('data-role' => 'select2')
                )
        );
        $builder->add('priority', 'entity',
                array(
                    'class' => 'App\Entity\Priority',
                    'property' => 'title',
                    'label' => 'Priority',
                    'attr' => array('data-role' => 'select2')
                )
        );
        $builder->add('dueDate', 'date',
                array(
                    'widget' => 'single_text',
                    'format' => 'yyyy/MM/dd',
                    'attr' => array(
                        'data-role' => 'datepicker',
                        'data-date-clear-btn' => true,
                        'data-date-today-highlight' => true,
                        'data-date-autoclose' => true,
                        'data-date-week-start' => 1,
                        'data-date-format' => 'yyyy/mm/dd'
                    ),
                    'required' => false
                )
        );
        $builder->add('doneRatio', 'choice',
                array(
                    'choices' => array_map(
                            function($item) {
                                return sprintf('%d (%%)', $item);
                            },
                            range(0, 100, 10)
                    ),
                    'attr' => array('data-role' => 'select2')
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
        $builder->add('submit', 'submit',
            array(
                'attr' => array(
                    'class' => 'btn-success btn-sm',
                    'data-role' => 'ajax-submit'
                )
            )
        );
    }

}
