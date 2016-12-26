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
use Proethos2\ModelBundle\Entity\Meeting;


class MigrateProethos1DatabaseCommand extends ContainerAwareCommand
{
    var $user_relations = array();
    var $meeting_relations = array();

    protected function configure()
    {
        $this
            ->setName('proethos2:migrate-proethos1-database')
            ->setDescription('Migrate all content from proethos1 to proethos2')
            ->addArgument('database', InputArgument::REQUIRED, 'Insert the database name that has proethos1 data.')
            ->addArgument('default_country', InputArgument::REQUIRED, 'Insert the default country for you users and protocols.')
        ;
    }

    public function migrate_users($input, $output) {

        $user_repository = $this->em->getRepository('Proethos2ModelBundle:User');
        $role_repository = $this->em->getRepository('Proethos2ModelBundle:Role');
        $country_repository = $this->em->getRepository('Proethos2ModelBundle:Country');

        $mysql_connect = $this->connect_database($input);

        $LIST_ROLES = array(
            'RES' => 1,
            'SCR' => 2,
            'MEM' => 3,
            'ADC' => 4,
            'ADM' => 5,
        );

        // Migration usernames
        $query = "SELECT
                id_us as id
                ,us_email as email
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
            $user = $user_repository->findOneBy(array('username' => $row['email']));
            if($user) {
                $this->user_relations[$row['id']] = $user->getId();
                $output->writeln('<info>User '. $row['email'] .' already exists. Skipping...</info>');
                continue;
            }

            // initing the User object
            $user = new User();
            $user->setEmail($row['email']);
            $user->setUsername($row['email']);
            $user->setName(utf8_encode($row['name']));
            $user->setCountry($country_repository->findOneBy(array('code' => $this->default_country )));
            $user->setInstitution(utf8_encode($row['institution']));
            $user->setIsActive(true);
            $user->setFirstAccess(false);

            $encoderFactory = $this->container->get('security.encoder_factory');
            $encoder = $encoderFactory->getEncoder($user);
            $salt = $user->getSalt();
            $password = $encoder->encodePassword($row['password'], $salt);
            $user->setPassword($password);

            $this->em->persist($user);
            $this->em->flush();

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
            $this->em->persist($user);
            $this->em->flush();

            $this->user_relations[$row['id']] = $user->getId();
            $output->writeln('<info>User '. $user->getUsername() .' have been inserted.</info>');
            $count += 1;
        }

        $output->writeln('<info>Added '. $count .' users in system.</info>');
    }

    public function migrate_meetings($input, $output) {

        $meeting_repository = $this->em->getRepository('Proethos2ModelBundle:Meeting');

        $mysql_connect = $this->connect_database($input);

        $query = "SELECT
                id_cal as id
                ,cal_date as date
                ,cal_description as subject
                ,cal_description as content
            FROM calender
            WHERE
                cal_ativo > 0
        ";

        $result = mysql_query($query);
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }

        $count = 0;
        while($row = mysql_fetch_assoc($result)) {

            $date = \DateTime::createFromFormat('Ymd', $row['date']);

            // if the user already exists, skip the row
            $meeting = $meeting_repository->findOneBy(array('date' => $date, 'subject' => utf8_encode(utf8_encode($row['subject']))));
            if($meeting) {
                $this->meeting_relations[$row['id']] = $meeting->getId();
                $output->writeln('<info>Meeting '. $row['subject'] .' already exists. Skipping...</info>');
                continue;
            }

            $meeting = new Meeting();
            $meeting->setDate($date);
            $meeting->setSubject(utf8_encode($row['subject']));
            $meeting->setContent(utf8_encode($row['content']));

            $this->em->persist($meeting);
            $this->em->flush();

            $this->meeting_relations[$row['id']] = $meeting->getId();
            $output->writeln('<info>Meeting '. $row['subject'] .' have been inserted.</info>');
            $count += 1;
        }

        $output->writeln('<info>Added '. $count .' meeting in system.</info>');
    }

    public function connect_database($input) {
        $database_host = $this->getContainer()->getParameter('database_host');
        $database_port = $this->getContainer()->getParameter('database_port');
        $database_name = $input->getArgument('database');
        $database_user = $this->getContainer()->getParameter('database_user');
        $database_password = $this->getContainer()->getParameter('database_password');

        $this->default_country = -$input->getArgument('default_country');

        $mysql_connect = mysql_connect($database_host, $database_user, $database_password);
        $mysql_database = mysql_select_db($database_name, $mysql_connect);
        if (!$mysql_database) {
            die ('Can\'t use foo : ' . mysql_error());
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container = $this->getContainer();
        $this->doctrine = $this->container->get('doctrine');
        $translator = $this->container->get('translator');
        $this->em = $this->doctrine->getManager();

        $users = $this->migrate_users($input, $output);
        $users = $this->migrate_meetings($input, $output);
    }
}
