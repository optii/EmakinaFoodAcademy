<?php

namespace DamDan\AppBundle\Form;

use DamDan\AppBundle\Entity\Dish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DishType extends AbstractType
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
            ->add('description')
            ->add('price')
            ->add('file', FileType::class)
        ;

        if ($this->authorizationChecker->isGranted('ROLE_EDITOR')) {
            $builder->add('status', ChoiceType::class, array('choices' => Dish::getStatusArray()));
        }

        $builder
            ->add('category', ChoiceType::class, array('choices' => Dish::getCategoriesArray()))
            ->add('homeMade');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DamDan\AppBundle\Entity\Dish',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'damdan_appbundle_dish';
    }


}
