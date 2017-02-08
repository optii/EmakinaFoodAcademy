<?php

namespace DamDan\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class AddRoleCommand
 * @package DamDan\UserBundle\Command
 * @author Daniel Cole <daniel@dancole.fr>
 */
class AddRoleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dandam:role:add')
            ->setDescription('Adds a role to an user.')
            ->setHelp("This command allows add a role to a user...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $helper = $this->getHelper('question');
        $usernameQuestion = new Question('username: ');
        $roleQuestion = new Question('role: ');

        $username = $helper->ask($input, $output, $usernameQuestion);

        $user = $em->getRepository('DamDanUserBundle:User')->findOneByUsername($username);

        if (!$user) {
            $output->writeln('The user doesn\'t exist');
            return;
        }

        $role = $helper->ask($input, $output, $roleQuestion);
        $user->addRole($role);
        $em->flush($user);
        $output->writeln('role: '.$role.' has been added to '.$user);
    }
}
