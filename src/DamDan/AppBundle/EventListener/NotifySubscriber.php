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

        $servers = $eventArgs->getEntityManager()->getRepository('DamDanUserBundle:User')->findByRole(User::ROLE_SERVER);
        $emails = array_map(function(User $server){
            return $server->getEmail();
        }, $servers);


        if ($object instanceof Dish) {
            $message = \Swift_Message::newInstance()
                ->setSubject(sprintf('NEW DISH %s - {emakina food academy}', $object->__toString()))
                ->setFrom($this->sender)
                ->setTo($emails)
                ->setBody($this->templating->render('DamDanAppBundle:Emails:dish_alert.html.twig', array('dish' => $object)),
                    'text/html'
                )
                ->addPart(
                    $this->templating->render('DamDanAppBundle:Emails:dish_alert.txt.twig', array('dish' => $object)),
                    'text/plain'
                );
            $this->swiftmailer->send($message);
        }

        if($object instanceof Menu){
            $message = \Swift_Message::newInstance()
                ->setSubject(sprintf('NEW MENU %s - {emakina food academy}', $object->__toString()))
                ->setFrom($this->sender)
                ->setTo($emails)
                ->setBody($this->templating->render('DamDanAppBundle:Emails:menu_alert.html.twig', array('menu' => $object)),
                    'text/html'
                )
                ->addPart(
                    $this->templating->render('DamDanAppBundle:Emails:menu_alert.txt.twig', array('menu' => $object)),
                    'text/plain'
                );

            $this->swiftmailer->send($message);
        }
    }
}
