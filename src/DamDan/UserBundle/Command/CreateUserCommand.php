<?php
/**
 * Created by PhpStorm.
 * User: Daniel Cole
 * Date: 31/01/2017
 * Time: 14:33
 */

namespace DamDan\UserBundle\Command;

use DamDan\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class CreateUserCommand
 * @package DamDan\UserBundle\Command
 * @author Daniel Cole <daniel@dancole.fr>
 */
class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dandam:user:create')
            ->setDescription('Creates new users.')
            ->setHelp("This command allows you to create users...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');
        $questions = [
            'username' => new Question('username: '),
            'plainPassword' => new Question('password: '),
            'email' => new Question('email: ')
        ];

        $user = new User();

        foreach($questions as $key => $question){
            $user->{'set'.ucfirst($key)}($helper->ask($input, $output, $question));
        }

        $password = $this->getContainer()->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $validator = $this->getContainer()->get('validator');

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            foreach($errors as $error){
                $output->writeln($error->getMessage());
            }
        } else {
            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->persist($user);
            $em->flush($user);
            $output->writeln('User created');
        }
    }
}
