<?php

namespace DamDan\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EmptyMenuCommand
 * @package DamDan\UserBundle\Command
 * @author Daniel Cole <daniel@dancole.fr>
 */
class MenuCheckCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('damdan:menu:check')
            ->setDescription('Checks if any menus don\'t have dishes and sends an email to notify.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('[INFO] Starting check for empty menus...');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $menus = $em->getRepository('DamDanAppBundle:Menu')->findAllWithoutDishes();

        if (count($menus) > 0) {
            $output->writeln(sprintf('[INFO] %s menus found with no dishes...', count($menus)));
            $message = \Swift_Message::newInstance()
                ->setSubject(sprintf('%s EMPTY MENUS - {emakina food academy}', count($menus)))
                ->setFrom($this->getContainer()->getParameter('dandam_app.administrator.email'))
                ->setTo($this->getContainer()->getParameter('dandam_app.administrator.email'))
                ->setBody(
                    $this->getContainer()->get('templating')->render(
                        'DamDanAppBundle:Emails:menu_check.html.twig',
                        array('menus' => $menus)
                    ),
                    'text/html'
                )
                ->addPart(
                    $this->getContainer()->get('templating')->render(
                        'DamDanAppBundle:Emails:menu_check.txt.twig',
                        array('menus' => $menus)
                    ),
                    'text/plain'
                );

            $this->getContainer()->get('mailer')->send($message);
            $output->writeln('[INFO] Email sent to administrator');
        }

        $output->writeln('[SUCCESS] Check for empty menus completed');
    }
}