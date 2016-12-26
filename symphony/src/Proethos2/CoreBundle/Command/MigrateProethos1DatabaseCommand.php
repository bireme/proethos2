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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Proethos2\ModelBundle\Entity\User;


class MigrateProethos1DatabaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:migrate-proethos1-database')
            ->setDescription('Migrate all content from proethos1 to proethos2')
            ->addArgument('database', InputArgument::REQUIRED, 'Insert the database name that has proethos1 data.')
            ->addArgument('default_country', InputArgument::REQUIRED, 'Insert the default country for you users and protocols.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');
        $translator = $container->get('translator');
        $em = $doctrine->getManager();

        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');
        $country_repository = $em->getRepository('Proethos2ModelBundle:Country');

        $database_host = $this->getContainer()->getParameter('database_host');
        $database_port = $this->getContainer()->getParameter('database_port');
        $database_name = $input->getArgument('database');
        $database_user = $this->getContainer()->getParameter('database_user');
        $database_password = $this->getContainer()->getParameter('database_password');
        $default_country = -$input->getArgument('default_country');

        $mysql_connect = mysql_connect($database_host, $database_user, $database_password);
        $mysql_database = mysql_select_db($database_name, $mysql_connect);
        if (!$mysql_database) {
            die ('Can\'t use foo : ' . mysql_error());
        }

        $LIST_ROLES = array(
            'RES' => 1,
            'SCR' => 2,
            'MEM' => 3,
            'ADC' => 4,
            'ADM' => 5,
        );

        // Migration usernames
        $query = "SELECT
                 us_email as email
                ,us_senha as password
                ,us_nome as name
                ,us_instituition as institution
                ,us_perfil as proethos2_roles
            FROM usuario
            WHERE
                us_perfil <> ''
                AND us_ativo > 0
        ";


        $result = mysql_query($query);
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }

        $count = 0;
        while($row = mysql_fetch_assoc($result)) {

            // if the user already exists, skip the row
            $user = $user_repository->findBy(array('username' => $row['email']));
            if($user) {
                $output->writeln('<info>User '. $row['email'] .' already exists. Skipping...</info>');
                continue;
            }

            // initing the User object
            $user = new User();
            $user->setEmail($row['email']);
            $user->setUsername($row['email']);
            $user->setName(utf8_encode($row['name']));
            $user->setCountry($country_repository->findOneBy(array('code' => $default_country )));
            $user->setInstitution(utf8_encode($row['institution']));
            $user->setIsActive(true);
            $user->setFirstAccess(false);

            $encoderFactory = $container->get('security.encoder_factory');
            $encoder = $encoderFactory->getEncoder($user);
            $salt = $user->getSalt();
            $password = $encoder->encodePassword($row['password'], $salt);
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $roles = explode("#", $row['proethos2_roles']);
            $added_roles = array();
            foreach($roles as $role) {
                if(!empty($role) && array_key_exists($role, $LIST_ROLES)) {
                    $role = $LIST_ROLES[$role];
                    if(!in_array($role, $added_roles)) {
                        $added_roles[] = $role;
                        $role = $role_repository->find($role);
                        $user->addProethos2Role($role);
                    }
                }
            }
            $em->persist($user);
            $em->flush();

            $output->writeln('<info>User '. $user->getUsername() .' have been inserted.</info>');
            $count += 1;
        }

        $output->writeln('<info>Added '. $count .' users in system.</info>');
    }
}
