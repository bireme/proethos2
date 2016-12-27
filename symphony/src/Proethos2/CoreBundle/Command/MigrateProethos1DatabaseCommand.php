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
use Proethos2\ModelBundle\Entity\Protocol;
use Proethos2\ModelBundle\Entity\Submission;
use Proethos2\ModelBundle\Entity\SubmissionCost;
use Proethos2\ModelBundle\Entity\SubmissionCountry;
use Proethos2\ModelBundle\Entity\SubmissionTask;


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
        ";

        $result = mysql_query($query);
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }

        $count = 0;
        while($row = mysql_fetch_assoc($result)) {

            $email = explode(" ", $row['email']);
            $email = $email[0];
            $username = explode("@", $email);
            $username = $username[0];

            // if the user already exists, skip the row
            $user = $user_repository->findOneBy(array('username' => $username));
            if($user) {
                $this->user_relations[$row['id']] = $user;
                $output->writeln('<info>User '. $row['email'] .' already exists. Skipping...</info>');
                continue;
            }

            // initing the User object
            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setName($row['name']);
            $user->setCountry($country_repository->findOneBy(array('code' => $this->default_country )));
            $user->setInstitution($row['institution']);
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

            $this->user_relations[$row['id']] = $user;
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
            $meeting = $meeting_repository->findOneBy(array('date' => $date, 'subject' => $row['subject']));
            if($meeting) {
                $this->meeting_relations[$row['id']] = $meeting->getId();
                $output->writeln('<info>Meeting '. $row['subject'] .' already exists. Skipping...</info>');
                continue;
            }

            $meeting = new Meeting();
            $meeting->setDate($date);
            $meeting->setSubject($row['subject']);
            $meeting->setContent($row['content']);

            $this->em->persist($meeting);
            $this->em->flush();

            $this->meeting_relations[$row['id']] = $meeting->getId();
            $output->writeln('<info>Meeting '. $row['subject'] .' have been inserted.</info>');
            $count += 1;
        }

        $output->writeln('<info>Added '. $count .' meeting in system.</info>');
    }

    public function migrate_protocols($input, $output) {

        $protocol_repository = $this->em->getRepository('Proethos2ModelBundle:Protocol');
        $submission_repository = $this->em->getRepository('Proethos2ModelBundle:Submission');
        $gender_repository = $this->em->getRepository('Proethos2ModelBundle:Gender');
        $recruitment_status_repository = $this->em->getRepository('Proethos2ModelBundle:RecruitmentStatus');

        $mysql_connect = $this->connect_database($input);

        $query = "SELECT
                id_doc as id
                ,doc_research_main as owner
                ,doc_1_titulo_public as publicTitle
                ,doc_1_titulo as scientificTitle
                ,doc_acronym as titleAcronym
                ,doc_clinic as is_clinical_trial
            FROM cep_submit_documento

        ";

        $result = mysql_query($query);
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }

        $count = 0;
        while($row = mysql_fetch_assoc($result)) {

            // var_dump($row);

            $owner = (int) $row['owner'];
            if(!array_key_exists($owner, $this->user_relations)) {
                $output->writeln('<error>ERROR: Protocol "'. $row['publicTitle'] .'" skipped because main investigator (id: '. $row['owner'] .') wansn\'t found.</error>');
                continue;
            }

            $protocol = new Protocol();
            $submission = new Submission();

            $protocol->setMainSubmission($submission);
            $protocol->setOwner($this->user_relations[$owner]);
            // $protocol->setStatus();

            $submission->setProtocol($protocol);
            $submission->setOwner($this->user_relations[$owner]);
            $submission->setNumber(1);
            $submission->setPublicTitle($row['publicTitle']);
            $submission->setScientificTitle($row['scientificTitle']);
            $submission->setTitleAcronym($row['titleAcronym']);
            $submission->setIsClinicalTrial($row['is_clinical_trial']);

            $field_relations = array(
                // TODO: Team (dúvida: o que é ct_type? C e N)
                '00002' => 'setAbstract',
                '00008' => 'setKeywords',
                '00004' => 'setIntroduction',
                '00030' => 'setJustification',
                '00011' => 'setGoals',
                '00010' => 'setStudyDesign',
                '00001' => 'setHealthCondition',
                '00069' => 'setGender',
                '00070' => 'setMinimumAge',
                '00071' => 'setMaximumAge',
                '00027' => 'setInclusionCriteria',
                '00028' => 'setExclusionCriteria',
                '00022' => 'setRecruitmentInitDate',
                '00042' => 'setRecruitmentStatus',
                // TODO: countries
                '00020' => 'setInterventions',
                '00017' => 'setPrimaryOutcome',
                '00018' => 'setSecondaryOutcome',
                '00031' => 'setGeneralProcedures',
                '00032' => 'setAnalysisPlan',
                '00023' => 'setEthicalConsiderations',
                // TODO: clinical trials (problema: não há padronização nas datas do campo cep_submit_register_unit)
                '00033' => 'setFundingSource',
                '00040' => 'setPrimarySponsor',
                '00041' => 'setSecondarySponsor',
                '00024' => 'setBibliography',
                '00056' => 'setScientificTitle',
                '00083' => 'setPriorEthicalApproval',
                --------------------- PAREI AQUI ---------------------
                // TODO: uploads
            );

            // text fields
            foreach($field_relations as $p1_field => $p2_method) {
                $sql = "SELECT spc_content as value FROM cep_submit_documento_valor WHERE CAST(spc_projeto AS UNSIGNED) = ". $row['id'] ." AND spc_codigo = '". $p1_field . "'";
                $result2 = mysql_query($sql) or die(mysql_error());
                $object = mysql_fetch_assoc($result2);
                $value = $object['value'];

                // set gender
                if($p1_field == '00069') {
                    if($value == '#women') {
                        $value = $gender_repository->findOneBy(array('slug' => 'female'));
                    } elseif($value == '#men') {
                        $value = $gender_repository->findOneBy(array('slug' => 'male'));
                    } elseif($value == '#both') {
                        $value = $gender_repository->findOneBy(array('slug' => 'both'));
                    } else {
                        $value = $gender_repository->findOneBy(array('slug' => 'n-a'));
                    }

                // set recruitment status
                } elseif($p1_field == '00042') {
                    $value = $recruitment_status_repository->findOneBy(array('slug' => $value));
                }

                $submission->{$p2_method}($value);
            }

            // submission cost
            $sql = "SELECT sorca_descricao as description, sorca_unid as quantity, sorca_valor as unit_cost FROM cep_submit_orca WHERE CAST(sorca_protocol AS UNSIGNED) = ". $row['id'];
            $result2 = mysql_query($sql) or die(mysql_error());
            while($row2 = mysql_fetch_assoc($result2)) {

                $item = new SubmissionCost();
                $item->setSubmission($submission);
                $item->setDescription($row2['description']);
                $item->setQuantity($row2['quantity']);
                $item->setUnitCost($row2['unit_cost']);
                // TODO: salvar
            }

            // submission task
            $sql = "SELECT scrono_descricao as description, scrono_date_start as init, scrono_date_end as end FROM cep_submit_crono WHERE CAST(scrono_protocol AS UNSIGNED) = ". $row['id'];
            $result2 = mysql_query($sql) or die(mysql_error());
            while($row2 = mysql_fetch_assoc($result2)) {
                $item = new SubmissionTask();
                $item->setSubmission($submission);
                $item->setDescription($row2['description']);
                $item->setInit(\DateTime::createFromFormat('Ym', $row2['init']));
                $item->setEnd(\DateTime::createFromFormat('Ym', $row2['end']));
                // TODO: salvar
            }

            $output->writeln('<info>Protocol '. $submission->getPublicTitle() .' have been inserted.</info>');
            $count += 1;
        }

        $output->writeln('<info>Added '. $count .' protocols in system.</info>');
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

        mysql_set_charset('utf8', $mysql_connect);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container = $this->getContainer();
        $this->doctrine = $this->container->get('doctrine');
        $translator = $this->container->get('translator');
        $this->em = $this->doctrine->getManager();

        $users = $this->migrate_users($input, $output);
        $users = $this->migrate_meetings($input, $output);
        $users = $this->migrate_protocols($input, $output);
    }
}
