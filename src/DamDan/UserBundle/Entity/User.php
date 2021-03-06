<?php

namespace DamDan\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="DamDan\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    /** User role constants */
    const ROLE_SERVER = 'ROLE_SERVER';
    const ROLE_EDITOR = 'ROLE_EDITOR';
    const ROLE_REVIEWER = 'ROLE_REVIEWER';
    const ROLE_CHEF = 'ROLE_CHEF';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity="DamDan\AppBundle\Entity\Dish", mappedBy="author")
     */
    private $dishes;

    /**
     * @ORM\OneToMany(targetEntity="DamDan\AppBundle\Entity\Menu", mappedBy="author")
     */
    private $menus;

    public function __construct()
    {
        $this->roles = [];
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get Password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set Password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }



    /**
     * Get Plain Password
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set Plain Password
     *
     * @param string $plainPassword
     * @return $this
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return "";
    }


    /**
     * Add Role
     *
     * @param $role
     * @return $this
     */
    public function addRole($role){
        if(!in_array($role, $this->getRoles())){
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * Remove Role
     *
     * @param $role
     * @return $this
     */
    public function removeRole($role){
        $key = array_search($role, $this->getRoles());
        if($key !== false){
            $roles = $this->getRoles();
            array_splice($roles, $key, 1);
            $this->roles = $roles;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
       $this->setPlainPassword(null);
    }

    /**
     * @return mixed
     */
    public function getDishes()
    {
        return $this->dishes;
    }

    /**
     * @param mixed $dishes
     */
    public function setDishes($dishes)
    {
        $this->dishes = $dishes;
    }

    /**
     * @return mixed
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * @param mixed $menus
     */
    public function setMenus($menus)
    {
        $this->menus = $menus;
    }

    /**
     * Has role
     *
     * @param $role
     * @return bool
     */
    public function hasRole($role){
        foreach($this->getRoles() as $r){
            if($role == $r){
                return true;
            }
        }

        return false;
    }

    /**
     * Get Role Names
     *
     * @return array
     */
    public function getRoleNames(){
        $roleNames = array_flip(self::getRolesArray());
        $res = [];
        foreach($this->getRoles() as $role){
            $res[] = $roleNames[$role];
        }

        return $res;
    }

    /**
     * Get roles arrray
     *
     * @return array
     */
    public static function getRolesArray(){
        return [
            'Server' => self::ROLE_SERVER,
            'Editor' => self::ROLE_EDITOR,
            'Reviewer' => self::ROLE_REVIEWER,
            'Chef' => self::ROLE_CHEF
        ];
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->roles
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->roles,
            ) = unserialize($serialized);
    }

    public function __toString()
    {
        return $this->getUsername();
    }
}

