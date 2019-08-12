<?php
namespace AdminBundle\Command;

use AdminBundle\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class FirstUserCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('definima:create-first')
                ->setDescription('Crée le premier administrateur')
                ->addArgument('email', InputArgument::REQUIRED, 'Email du compte')
                ->addArgument('nom', InputArgument::OPTIONAL, 'Le nom de l\'admin')
                ->addArgument('prenom', InputArgument::OPTIONAL, 'Le prénom de l\'admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();

        $admins = $em->getRepository('AdminBundle:Admin')->findAll();

        if(!$admins){
            $admin = new Admin();

            $email = $input->getArgument('email');
            $nom = ($input->getArgument('nom'))? $input->getArgument('nom'): 'Definima';
            $prenom = ($input->getArgument('prenom'))? $input->getArgument('prenom'): 'Admin';

            $password = "definima233";
            $roles = array('ROLE_ADMIN');

            $discriminator = $this->getContainer()->get('pugx_user.manager.user_discriminator');
            $discriminator->setClass('AdminBundle\Entity\Admin');
            $userManager = $this->getContainer()->get('pugx_user_manager');

            $admin->setEmail($email);
            $admin->setUsername($email);
            $admin->setPlainPassword($password);
            $admin->setRoles($roles);

            $admin->setEnabled(true);
            $admin->setNom($nom);
            $admin->setPrenom($prenom);

            $userManager->updateUser($admin, true);

            $output->writeln('L\'administrateur à été créée');
        }else{
            $output->writeln('Des admins existe déja');
        }
    }
}
