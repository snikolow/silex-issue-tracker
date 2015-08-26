<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Doctrine\ORM\EntityManagerInterface;

class RepositoryType extends AbstractType {

    /** @var EntityManagerInterface */
    private $entityManager;
    
    public function __construct(EntityManagerInterface $manager) {
        $this->entityManager = $manager;
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefined(array(
            'repository_class', 'repository_method'
        ));
        
        $resolver->setRequired(array(
            'repository_class', 'repository_method'
        ));
        
        $choices = function(Options $options) {
            $repository = $this->entityManager->getRepository($options['repository_class']);
            
            return call_user_func_array(
                    array($repository, $options['repository_method']),
                    array()
            );
        };
        
        $resolver->setDefaults(array(
            'choices'     => $choices
        ));
    }
    
    public function getName() {
        return 'repository';
    }
    
    public function getParent() {
        return 'choice';
    }

}
