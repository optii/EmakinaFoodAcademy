<?php
/**
 * Created by PhpStorm.
 * User: Daniel Cole
 * Date: 03/02/2017
 * Time: 12:34
 */

namespace DamDan\AppBundle\EventListener;

use DamDan\AppBundle\Entity\Dish;
use DamDan\AppBundle\Entity\Menu;
use DamDan\UserBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bundle\TwigBundle\TwigEngine;

class NotifySubscriber implements EventSubscriber
{
    private $templating;
    private $swiftmailer;
    private $sender;


    public function __construct(TwigEngine $templating, \Swift_Mailer $swiftmailer, $sender)
    {
        $this->templating = $templating;
        $this->swiftmailer = $swiftmailer;
        $this->sender = $sender;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
        );
    }

    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $object = $eventArgs->getObject();

        if ($object instanceof Dish) {
            $message = $this->initializeMessage($eventArgs->getEntityManager()->getRepository('DamDanUserBundle:User'));
            $message
                ->setSubject(sprintf('NEW DISH %s - {emakina food academy}', $object->__toString()))
                ->setBody($this->templating->render('DamDanAppBundle:Emails:dish_alert.html.twig', array('dish' => $object)),
                    'text/html'
                )
                ->addPart(
                    $this->templating->render('DamDanAppBundle:Emails:dish_alert.txt.twig', array('dish' => $object)),
                    'text/plain'
                )
            ;

            $this->swiftmailer->send($message);
        }

        if ($object instanceof Menu) {
            $message = $this->initializeMessage($eventArgs->getEntityManager()->getRepository('DamDanUserBundle:User'));
            $message
                ->setSubject(sprintf('NEW MENU %s - {emakina food academy}', $object->__toString()))
                ->setBody($this->templating->render('DamDanAppBundle:Emails:menu_alert.html.twig', array('menu' => $object)),
                    'text/html'
                )
                ->addPart(
                    $this->templating->render('DamDanAppBundle:Emails:menu_alert.txt.twig', array('menu' => $object)),
                    'text/plain'
                )
            ;

            $this->swiftmailer->send($message);
        }
    }

    /**
     * @param EntityRepository $userRepository
     * @return \Swift_Message $message
     */
    private function initializeMessage(EntityRepository $userRepository)
    {
        $emails = $userRepository->findEmailsByRoles(User::ROLE_SERVER);
        $message = \Swift_Message::newInstance()
            ->setFrom($this->sender)
            ->setTo($emails);
        return $message;
    }
}
