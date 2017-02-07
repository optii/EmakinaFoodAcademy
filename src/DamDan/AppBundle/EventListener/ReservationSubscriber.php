<?php
/**
 * Created by PhpStorm.
 * User: Damien
 * Date: 07/02/2017
 * Time: 10:22
 */

namespace DamDan\AppBundle\EventListener;


use DamDan\AppBundle\Entity\Reservation;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bundle\TwigBundle\TwigEngine;

class ReservationSubscriber implements EventSubscriber
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
            'postUpdate',
        );
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $object = $eventArgs->getObject();

        if ($object instanceof Reservation) {
            $customerEmail = $object->getEmail();
            if ($object->isAccepted()){
                $message = \Swift_Message::newInstance()
                    ->setSubject(sprintf('RESERVATION %s ACCEPTED - {emakina food academy}', $object->getName()))
                    ->setFrom($this->sender)
                    ->setTo($customerEmail)
                    ->setBody($this->templating->render('DamDanAppBundle:Emails:reservation_accepted_alert.html.twig'),
                        'text/html'
                    )
                    ->addPart(
                        $this->templating->render('DamDanAppBundle:Emails:reservation_accepted_alert.html.twig'),
                        'text/plain'
                    );
                $this->swiftmailer->send($message);
            }
            else if ($object->isRefused()){
                $message = \Swift_Message::newInstance()
                    ->setSubject(sprintf('RESERVATION %s REFUSED- {emakina food academy}', $object->getName()))
                    ->setFrom($this->sender)
                    ->setTo($customerEmail)
                    ->setBody($this->templating->render('DamDanAppBundle:Emails:reservation_refused_alert.html.twig'),
                        'text/html'
                    )
                    ->addPart(
                        $this->templating->render('DamDanAppBundle:Emails:reservation_refused_alert.html.twig'),
                        'text/plain'
                    );
                $this->swiftmailer->send($message);
            }
        }
    }
}