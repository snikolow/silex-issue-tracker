<?php

namespace Tracker\Form\Type;

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
                    'data_class' => 'Tracker\Entity\Issue',
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
                    'class' => 'Tracker\Entity\User',
                    'choice_label' => 'name',
                    'label' => 'Assignee',
                    'empty_value' => 'None',
                    'required' => false,
                    'attr' => array('data-role' => 'select2')
                )
        );
        $builder->add('tracker', 'entity',
                array(
                    'class' => 'Tracker\Entity\Tracker',
                    'choice_label' => 'title',
                    'label' => 'Tracker',
                    'attr' => array('data-role' => 'select2')
                )
        );
        $builder->add('status', 'entity',
                array(
                    'class' => 'Tracker\Entity\IssueStatus',
                    'choice_label' => 'title',
                    'attr' => array('data-role' => 'select2')
                )
        );
        $builder->add('priority', 'entity',
                array(
                    'class' => 'Tracker\Entity\Priority',
                    'choice_label' => 'title',
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
