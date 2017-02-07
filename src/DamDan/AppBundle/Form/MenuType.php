<?php

namespace DamDan\AppBundle\Form;

use DamDan\AppBundle\Entity\Dish;
use DamDan\AppBundle\Entity\Menu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuType extends AbstractType
{

    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
            ->add('price')
            ->add('order')
        ;

        if($this->authorizationChecker->isGranted('ROLE_EDITOR')){
            $builder->add('status', ChoiceType::class, array('choices' => Dish::getStatusArray()));
        }

        $builder->add('dishes', EntityType::class, array(
            'class'    => 'DamDanAppBundle:Dish',
            'multiple' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Menu::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'damdan_appbundle_menu';
    }


}
