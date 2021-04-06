<?php

// This file is part of the ProEthos Software.
//
// Copyright 2013, PAHO. All rights reserved. You can redistribute it and/or modify
// ProEthos under the terms of the ProEthos License as published by PAHO, which
// restricts commercial use of the Software.
//
// ProEthos is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details.
//
// You should have received a copy of the ProEthos License along with the ProEthos
// Software. If not, see
// https://github.com/bireme/proethos2/blob/master/LICENSE.txt


namespace Proethos2\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Proethos2\ModelBundle\Entity\User;
use Proethos2\CoreBundle\Util\Util;
use Proethos2\CoreBundle\Util\Security;

use Cocur\Slugify\Slugify;


class CreateSuperUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:createsuperuser')
            ->setDescription('Create ProEthos default admin user')
            ->setHelp('Usage: php app/console proethos2:createsuperuser --email=EMAIL --username=USERNAME [--password=PASSWORD]')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Insert the email.')
            ->addOption('username', null, InputOption::VALUE_REQUIRED, 'Insert the username.')
            ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'Insert the password.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');
        $translator = $container->get('translator');

        $em = $doctrine->getManager();
        $util = new Util($container, $doctrine);
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');

        $slugify = new Slugify();
        $io = new SymfonyStyle($input, $output);

        $email = $input->getOption('email');
        $username = $slugify->slugify($input->getOption('username'));
        $password = ( $input->getOption('password') ) ? $input->getOption('password') : substr(md5(date("YmdHis")), 0, 8);

        if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            $io->error('Invalid email.');
            exit;
        }

        if ( strlen($username) < 5 ) {
            $io->error('Username must be at least 5 characters.');
            exit;
        }

        if ( strlen($password) < 8 ) {
            $io->error('Password must be at least 8 characters.');
        }

        $roles = array(
            'investigator',
            'secretary',
            'member-of-committee',
            'member-ad-hoc',
            'administrator'        
        );

        $user = $user_repository->findOneBy(array('email' => $email));
        if ( $user ) {
            $io->warning('User already exists.');
            exit;
        } else {
            $_email = Security::encrypt($email);
            $user = $user_repository->findOneBy(array('email' => $_email));
            if ( $user ) {
                $io->warning('User already exists.');
                exit;
            }
        }

        $user = $user_repository->findOneBy(array('username' => $username));
        if ( !$user ) {
            $_username = Security::encrypt($username);
            $user = $user_repository->findOneBy(array('username' => $_username));
        }

        if ( !$user ) {
            $user = new User();
            $user->setName($username);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setInstitution(NULL);
            $user->setFirstAccess(false);
            $user->setIsActive(true);

            // adding user role
            foreach ($roles as $role) {
                $user->addProethos2Role($role_repository->findOneBy(array('slug' => $role)));
            }

            $encoderFactory = $container->get('security.encoder_factory');
            $encoder = $encoderFactory->getEncoder($user);
            $salt = $user->getSalt();
            $hash = $encoder->encodePassword($password, $salt);
            $user->setPassword($hash);

            $user->cleanHashcode();

            $em->persist($user);
            $em->flush();

            $io->success(array(
                'User created successfully!',
                sprintf("Username: '%s'", $username),
                sprintf("Password: '%s'", $password),
            ));
        } else {
            $io->warning('User already exists.');
            // throw new \RuntimeException('Admin user already exists.');
        }
    }
}
