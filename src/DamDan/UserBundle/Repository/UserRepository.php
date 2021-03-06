<?php

namespace DamDan\UserBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    public function findEmailsByRoles($roles){
        if(is_string($roles)){
           $roles = [$roles];
        }

        $users = $this->findAll();
        $emails = [];

        foreach($users as $user){
            if(!empty(array_intersect($roles, $user->getRoles()))){
                $emails[] = $user->getEmail();
            }
        }

        return $emails;
    }

}
