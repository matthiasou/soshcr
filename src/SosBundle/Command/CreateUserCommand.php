<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SosBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use FOS\UserBundle\Command\CreateUserCommand as BaseCommand;


/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Luis Cordova <cordoval@gmail.com>
 */
class CreateUserCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('fos:user:create')
            ->setDescription('Create a user.')
            ->setDefinition(array(
                new InputArgument('nom', InputArgument::REQUIRED, 'The nom'),
                new InputArgument('prenom', InputArgument::REQUIRED, 'The prenom'),
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('telephone', InputArgument::REQUIRED, 'The telephone'),
                new InputArgument('dateNaissance', InputArgument::REQUIRED, 'The dateNaissance'),
                new InputArgument('cursusScolaire', InputArgument::REQUIRED, 'The cursusScolaire'),
                new InputArgument('niveauAnglais', InputArgument::REQUIRED, 'The niveauAnglais'),
                new InputArgument('score', InputArgument::REQUIRED, 'The score'),
                new InputArgument('adresse', InputArgument::REQUIRED, 'The adresse'),
                new InputArgument('rayonEmploi', InputArgument::REQUIRED, 'The rayonEmploi'),
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
                new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
            ))
            ->setHelp(<<<'EOT'
The <info>fos:user:create</info> command creates a user:

  <info>php %command.full_name% matthieu</info>

This interactive shell will ask you for an email and then a password.

You can alternatively specify the email and password as the second and third arguments:

  <info>php %command.full_name% matthieu matthieu@example.com mypassword</info>

You can create a super admin via the super-admin flag:

  <info>php %command.full_name% admin --super-admin</info>

You can create an inactive user (will not be able to log in):

  <info>php %command.full_name% thibault --inactive</info>

EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $nom = $input->getArgument('nom');
        $prenom = $input->getArgument('prenom');
        $telephone = $input->getArgument('telephone');
        $dateNaissance = $input->getArgument('dateNaissance');
        $score = $input->getArgument('score');
        $niveauAnglais = $input->getArgument('niveauAnglais');
        $cursusScolaire = $input->getArgument('cursusScolaire');
        $adresse = $input->getArgument('adresse');
        $rayonEmploi = $input->getArgument('rayonEmploi');

        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $inactive = $input->getOption('inactive');
        $superadmin = $input->getOption('super-admin');

        $manipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
        $manipulator->create($username, $password, $email, !$inactive, $superadmin, $nom, $prenom, $telephone, $dateNaissance, $score, $niveauAnglais, $cursusScolaire, $adresse, $rayonEmploi);

        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('nom')) {
            $question = new Question('Sélectionner un nom:');
            $question->setValidator(function ($nom) {
                if (empty($nom)) {
                    throw new \Exception('Nom can not be empty');
                }

                return $nom;
            });
            $questions['nom'] = $question;
        }
        if (!$input->getArgument('prenom')) {
            $question = new Question('Sélectionner un prénom:');
            $question->setValidator(function ($prenom) {
                if (empty($prenom)) {
                    throw new \Exception('Prénom can not be empty');
                }

                return $prenom;
            });
            $questions['prenom'] = $question;
        }
        if (!$input->getArgument('telephone')) {
            $question = new Question('Please choose a telephone:');
            $question->setValidator(function ($telephone) {
                if (empty($telephone)) {
                    throw new \Exception('telephone can not be empty');
                }

                return $telephone;
            });
            $questions['telephone'] = $question;
        }
        if (!$input->getArgument('dateNaissance')) {
            $question = new Question('Please choose a dateNaissance:');
            $question->setValidator(function ($dateNaissance) {
                if (empty($dateNaissance)) {
                    throw new \Exception('dateNaissance can not be empty');
                }

                return $dateNaissance;
            });
            $questions['dateNaissance'] = $question;
        }

        if (!$input->getArgument('cursusScolaire')) {
            $question = new Question('Please choose a cursusScolaire:');
            $question->setValidator(function ($cursusScolaire) {
                if (empty($cursusScolaire)) {
                    throw new \Exception('cursusScolaire can not be empty');
                }

                return $cursusScolaire;
            });
            $questions['cursusScolaire'] = $question;
        }

        if (!$input->getArgument('score')) {
            $question = new Question('Please choose a score:');
            $question->setValidator(function ($score) {
                if (empty($score)) {
                    throw new \Exception('score can not be empty');
                }

                return $score;
            });
            $questions['score'] = $question;
        }

        if (!$input->getArgument('niveauAnglais')) {
            $question = new Question('Please choose a niveauAnglais:');
            $question->setValidator(function ($niveauAnglais) {
                if (empty($niveauAnglais)) {
                    throw new \Exception('niveauAnglais can not be empty');
                }

                return $niveauAnglais;
            });
            $questions['niveauAnglais'] = $question;
        }

        if (!$input->getArgument('adresse')) {
            $question = new Question('Please choose a adresse:');
            $question->setValidator(function ($adresse) {
                if (empty($adresse)) {
                    throw new \Exception('adresse can not be empty');
                }

                return $adresse;
            });
            $questions['adresse'] = $question;
        }

        if (!$input->getArgument('rayonEmploi')) {
            $question = new Question('Please choose a rayonEmploi:');
            $question->setValidator(function ($rayonEmploi) {
                if (empty($rayonEmploi)) {
                    throw new \Exception('rayonEmploi can not be empty');
                }

                return $rayonEmploi;
            });
            $questions['rayonEmploi'] = $question;
        }

        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email can not be empty');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
