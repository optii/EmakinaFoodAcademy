<?php

namespace DamDan\AppBundle\Repository;

/**
 * MenuRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MenuRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllWithoutDishes(){
        return $this->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.dishes', 'd')
            ->having('count(d.id) = 0')
            ->groupBy('m.id')
           ->getQuery()->getResult();
    }
}
