<?php
/**
 * Created by PhpStorm.
 * User: Damien
 * Date: 07/02/2017
 * Time: 16:41
 */

namespace DamDan\AppBundle\Services;


use Doctrine\Common\Collections\ArrayCollection;
use Traversable;

class Paginator implements \IteratorAggregate
{
    private $objectsPerPage;
    private $nbPages;
    private $objects;
    private $currentPage;

    public function __construct($objectsArray, $objectsPerPage, $currentPage)
    {
        $this->objects = new ArrayCollection($objectsArray);
        $this->objectsPerPage = $objectsPerPage;
        $this->currentPage = $currentPage;

        $offset = ($this->currentPage - 1) * $this->objectsPerPage;
        $this->nbPages = count($this->objects) / $objectsPerPage;
        $this->objects = $this->objects->slice($offset, $this->objectsPerPage);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->objects);
    }

    public function getPages(){
        $pages = array();
        for($i=0 ; $i < $this->nbPages; $i++){
            $pages[$i] = $i+1;
        }
        return $pages;
    }

    public function getNbPages(){
        return $this->nbPages;
    }

    public function getCurrentPage(){
        return $this->currentPage;
    }
}