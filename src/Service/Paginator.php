<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class Paginator implements \IteratorAggregate {
    
    /** @var DoctrinePaginator */
    private $paginator;
    
    /** @var int */
    private $page;
    
    /** @var int */
    private $limit;
    
    /** @var int */
    private $hydrateMode;
    
    /**
     * 
     * @param Query|QueryBuilder $query
     * @param int $page
     * @param int $limit
     * @param int $hydrateMode
     * @throws \InvalidArgumentException
     * @return array
     */
    public function paginate($query, $page = 1, $limit = 10, $hydrateMode = Query::HYDRATE_OBJECT) {
        /**
         * Paginator class of Doctrine expects $query to be an instance of
         * either Query or QueryBuilder. So we need to make sure here as well
         * that we have the right instance for $query.
         */
        if( ! $query instanceof Query && ! $query instanceof QueryBuilder ) {
            throw new \InvalidArgumentException(sprintf(
                    '$query must be an instance of either Doctrine\ORM\Query or Doctrine\ORM\QueryBuilder. %s given',
                    (is_object($query)) ? get_class($query) : $query
            ));
        }
        
        $paginator = new DoctrinePaginator($query);
        $this->hydrateMode = $hydrateMode;
        
        return $this
                ->setPaginator($paginator)
                ->setLimit($limit)
                ->setCurrentPage($page);
    }
    
    /**
     * 
     * @return int
     */
    public function getResultCount() {
        if( $this->paginator instanceof DoctrinePaginator ) {
            return $this->paginator->count();
        }
        
        return 0;
    }
    
    /**
     * 
     * @return int
     */
    public function getTotalPages() {
        $total = ceil( $this->getResultCount() / $this->limit ) ?: 1;
        
        return intval($total);
    }
    
    /**
     * 
     * @param int $page
     * @param int $offset
     * @return array
     */
    public function generatePagesRange($offset = 2) {
        $page = $this->getCurrentPage();
        
        $first = max(($page - $offset), 1);
        $last = min(($page + $offset), $this->getTotalPages());
        
        return range($first, $last);
    }
    
    /**
     * 
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->getQuery()->getResult($this->hydrateMode));
    }
    
    /**
     * @return boolean
     */
    public function hasPrevioisPage() {
        return $this->getCurrentPage() > 1;
    }
    
    /**
     * 
     * @return int
     */
    public function getPreviousPage() {
        $page = $this->getCurrentPage() - 1;
        
        if( $page < 1 ) {
            $page = 1;
        }
        
        return $page;
    }
    
    /**
     * 
     * @return boolean
     */
    public function hasNextPage() {
        return $this->getCurrentPage() < $this->getTotalPages();
    }
    
    /**
     * 
     * @return int
     */
    public function getNextPage() {
        $page = $this->getCurrentPage() + 1;
        
        if( $page > $this->getTotalPages() ) {
            $page = $this->getTotalPages();
        }
        
        return $page;
    }
    
    /**
     * 
     * @param DoctrinePaginator $paginator
     * @return \App\Service\Paginator
     */
    private function setPaginator(DoctrinePaginator $paginator) {
        $this->paginator = $paginator;
        
        return $this;
    }
    
    /**
     * 
     * @return int
     */
    public function getCurrentPage() {
        return intval( $this->page );
    }
    
    /**
     * 
     * @param int $page
     * @return boolean
     */
    public function isPageActive($page) {
        return intval($page) === $this->getCurrentPage();
    }
    
    /**
     * 
     * @param int $page
     * @return \App\Service\Paginator
     */
    private function setCurrentPage($page) {
        if( $page < 1 ) {
            $page = 1;
        }
        
        $this->page = intval($page);
        
        $this->getQuery()->setFirstResult($this->limit * ($this->page - 1));
        
        return $this;
    }
    
    /**
     * 
     * @param int $limit
     * @return \App\Service\Paginator
     */
    private function setLimit($limit) {
        $this->limit = intval($limit);
        
        $this->getQuery()->setMaxResults($this->limit);
        
        return $this;
    }
    
    /**
     * 
     * @return \Doctrine\ORM\Query
     */
    private function getQuery() {
        return $this->paginator->getQuery();
    }
    
}