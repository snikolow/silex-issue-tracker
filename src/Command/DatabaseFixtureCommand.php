<?php

namespace App\Command;

use App\Provider\ConsoleProvider\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity;

class DatabaseFixtureCommand extends Command {
    
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;
    
    /**
     * Register command.
     * 
     * This command should be called after our schema is built.
     */
    protected function configure() {
        $this
                ->setName('seed:init')
                ->setDescription('Populate database with necessary data.')
        ;
    }
    
    /**
     * Execute command
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $app = $this->getSilexApplication();
        $this->entityManager = $app['orm.em'];
        
        // Create and persist users
        $output->writeln('[0] Inserting users...');
        $this->addUser();
        
        // Create and persist priorities
        $output->writeln('[1] Inserting priorities...');
        $this->addPriorities();
        
        // Create and persist trackers
        $output->writeln('[2] Inserting trackers...');
        $this->addTrackers();
        
        // Create and persist issue statuses
        $output->writeln('[3] Inserting statuses...');
        $this->addStatuses();
        
        // Create and persist permissions
        $output->writeln('[4] Inserting permissions...');
        $this->addPermissions();
        
        // Create and persist roles
        $output->writeln('[5] Inserting roles...');
        $this->addRoles();
        
        // Create and persist project
        $output->writeln('[6] Inserting project...');
        $this->addProject();
        
        // Create issues
        $output->writeln('[7] Inserting issues...');
        $this->addIssues();
        
        // Flush everything and keep on dancing!
        $this->entityManager->flush();
        $output->writeln('Done...');
    }
    
    /**
     * Add demo user with admin privilegies
     */
    public function addUser() {
        $app = $this->getSilexApplication();
        $entity = new Entity\User();
        
        $password = $app['security.encoder_factory']->getEncoder($entity)->encodePassword('admin', $entity->getSalt());
        
        $entity->setEmail('admin@demo.com');
        $entity->setIsAdmin(true);
        $entity->setRoles(array('ROLE_DEVELOPER'));
        $entity->setName('Administrator');
        $entity->setPassword($password);
        
        $this->entityManager->persist($entity);
    }
    
    /**
     * Add data to priorities table.
     * 
     * @return void
     */
    private function addPriorities() {
        $data = array(
            'Low', 'Normal', 'High', 'Urgent', 'Immediate'
        );
        
        foreach($data as $priority) {
            $entity = new Entity\Priority();
            $entity->setTitle($priority);
            
            // Since class names are defined like
            // priority-low or priority-normal
            // we can simply use the title as base to format our class name
            $className = sprintf('priority-%s', strtolower($priority));
            $entity->setClassName($className);
            
            $this->entityManager->persist($entity);
        }
    }
    
    /**
     * Add data to trackers table
     * 
     * @return void
     */
    private function addTrackers() {
        $data = array(
            'Bug', 'Feature', 'Support'
        );
        
        foreach($data as $tracker) {
            $entity = new Entity\Tracker();
            $entity->setTitle($tracker);
            
            $this->entityManager->persist($entity);
        }
    }
    
    /**
     * Add data to issue_statuses table
     * 
     * @return void
     */
    private function addStatuses() {
        $data = array(
            'New', 'In Progress', 'Resolved', 'Feedback', 'Closed', 'Rejected'
        );
        
        foreach($data as $key => $status) {
            $entity = new Entity\IssueStatus();
            $entity->setTitle($status);
            
            // We can use the same class name formatting as above
            // Only one of the classes needs specific formatting tho.
            $class = ($key === 1) ? 'progress' : $status;
            $className = sprintf('status-%s', strtolower($class));
            $entity->setClassName($className);
            
            $this->entityManager->persist($entity);
        }
    }
    
    /**
     * Add data to permissions table
     * 
     * @return void
     */
    private function addPermissions() {
        $data = Entity\Permission::getChoices();
        
        foreach($data as $identifier => $permission) {
            $entity = new Entity\Permission();
            $entity->setIdentifier($identifier);
            $entity->setTitle($permission);
            
            $this->entityManager->persist($entity);
        }
    }
    
    /**
     * Add data to roles table
     * 
     * @return void
     */
    private function addRoles() {
        
    }
    
    /**
     * Create new project
     * 
     * @return void
     */
    private function addProject() {
        $entity = new Entity\Project();
        
        $createdBy = $this->entityManager->getReference('App\Entity\User', 1);
        
        $entity->setTitle('Hello world');
        $entity->setIsPublic(true);
        $entity->setDescription('First project for demo purpose only');
        $entity->setCreatedBy($createdBy);
        
        $this->entityManager->persist($entity);
    }
    
    /**
     * Create issues
     */
    private function addIssues() {
        $createdBy = $this->entityManager->getReference('App\Entity\User', 1);
        $project = $this->entityManager->getReference('App\Entity\Project', 1);
        
        foreach(range(1,3) as $number) {
            $priority = $this->entityManager->getReference('App\Entity\Priority', $number);
            $status = $this->entityManager->getReference('App\Entity\IssueStatus', $number);
            $tracker = $this->entityManager->getReference('App\Entity\Tracker', $number);
            
            $entity = new Entity\Issue();
            $entity->setCreatedBy($createdBy);
            $entity->setProject($project);
            $entity->setSubject(sprintf('Issue #%d', $number));
            $entity->setDescription('Some description for this issue');
            $entity->setPriority($priority);
            $entity->setStatus($status);
            $entity->setTracker($tracker);
            
            $this->entityManager->persist($entity);
        }
    }
    
}