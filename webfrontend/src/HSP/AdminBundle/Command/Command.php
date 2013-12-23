<?php
namespace HSP\AdminBundle\Command;

use HSP\AdminBundle\HSPAdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends ContainerAwareCommand
{
  protected function configure()
  {
    $this->setName('ipv6watch:createuser')
      ->setDescription('create initial user');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->writeln("Creating initial user");
    $dialog = $this->getHelperSet()->get('dialog');
    $username = $dialog->ask($output, "Please enter a username: ");
    $email = $dialog->ask($output, "Please enter an email-address for the user: ");
    $realName = $dialog->ask($output, "Please provide the users real name: ");
    $password = $dialog->askHiddenResponse($output, "Please enter a password for '$username': ", false);
    $passwordval = $dialog->askHiddenResponse($output, "Please validate password for '$username': ", false);
    if ($password !== $passwordval) $output->writeln("Password mismatch, try again"); else {
      $output->writeln("Creating user '$username'");
      $userManager = $this->getContainer()->get('fos_user.user_manager');
      $newUser = $userManager->createUser();
      $newUser->setUsername($username);
      $newUser->setUsernameCanonical($username);
      $encoder = $this->getContainer()->get('security.encoder_factory')->getEncoder($newUser);
      $newPassword = $encoder->encodePassword($password, $newUser->getSalt());
      $newUser->setPassword($newPassword);
      $newUser->setEmail($email);
      $newUser->addRole("ROLE_SUPER_ADMIN");
      $newUser->setEnabled(true);
      $newUser->setIsNotDeleteable(1);
      $newUser->setRealName($realName);
      $this->getContainer()->get('doctrine')->getManager()->persist($newUser);
      $this->getContainer()->get('doctrine')->getManager()->flush();
    }
  }
}