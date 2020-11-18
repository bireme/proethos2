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

use Proethos2\CoreBundle\Util\Security;


class SanitizeSensitiveDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:sanitize-sensitive-data')
            ->setDescription('Encrypt/Decrypt sensitive data in ProEthos2 database')
            ->setHelp('Usage: php app/console proethos2:sanitize-sensitive-data [-r, --rollback]')
            ->addOption('rollback', 'r', InputOption::VALUE_NONE, 'Restores the database to a previously defined state')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $database_host = $this->getContainer()->getParameter('database_host');
        $database_port = $this->getContainer()->getParameter('database_port');
        $database_name = $this->getContainer()->getParameter('database_name');
        $database_user = $this->getContainer()->getParameter('database_user');
        $database_password = $this->getContainer()->getParameter('database_password');

        $pdo = new \PDO("mysql:host=$database_host;dbname=$database_name", $database_user, $database_password);

        print "-- ====================================================\n";
        print "-- TABLE: user\n";
        print "-- ====================================================\n";

        $sql = "SELECT `id`, `email`, `username`, `institution` FROM user;";

        $stmte = $pdo->prepare($sql);
        $executa = $stmte->execute();
        // die(print_r($stmte->fetchAll(\PDO::FETCH_OBJ)));

        if ( $executa ) {
            if ( $stmte->rowCount() > 0 ) {
                $count = 0;
                while($reg = $stmte->fetch(\PDO::FETCH_OBJ)){ // Para recuperar um ARRAY utilize PDO::FETCH_ASSOC
                    $sanitize = true;

                    try {
                        $email = Security::decrypt($reg->email);
                        $username = Security::decrypt($reg->username);
                        $institution = Security::decrypt($reg->institution);
                    } catch (\Exception $e) {
                        $sanitize = false;
                    }

                    if ( $input->getOption('rollback') != true ) {
                        if ( !$sanitize ) {
                            $email = Security::encrypt($reg->email);
                            $username = Security::encrypt($reg->username);
                            $institution = Security::encrypt($reg->institution);
                        } else {
                            print "-- [ERROR] Data already encrypted --\n";
                            break;
                        }
                    } else {
                        $email = Security::decrypt($reg->email);
                        $username = Security::decrypt($reg->username);
                        $institution = Security::decrypt($reg->institution);
                    }

                    $sql = "UPDATE user
                            SET email = :email, username = :username, institution = :institution
                            WHERE id = :id;";

                    $reg_stmte = $pdo->prepare($sql);
                    $reg_stmte->bindParam(":id", $reg->id, \PDO::PARAM_INT);
                    $reg_stmte->bindParam(":email", $email, \PDO::PARAM_STR);
                    $reg_stmte->bindParam(":username", $username, \PDO::PARAM_STR);
                    $reg_stmte->bindParam(":institution", $institution, \PDO::PARAM_STR);
                    $reg_executa = $reg_stmte->execute();

                    if ( $reg_executa and $reg_stmte->rowCount() > 0 ) {
                        $count++;
                    } else {
                        $error = $reg_stmte->errorInfo();
                        print "-- [ERROR] $error[2] --\n";
                        break;
                    }
                }
                print "-- [INFO] Affected lines: $count --\n";
            } else {
                print "-- [FAIL] Empty table --\n";
            }
        } else {
            $error = $stmte->errorInfo();
            print "-- [ERROR] $error[2] --\n";
        }

        print "\n-- ====================================================\n";
        print "-- TABLE: submission\n";
        print "-- ====================================================\n";

        $sql = "SELECT `id`, `funding_source`, `primary_sponsor`, `secondary_sponsor`, `sscientific_contact` FROM submission;";

        $stmte = $pdo->prepare($sql);
        $executa = $stmte->execute();
        // die(print_r($stmte->fetchAll(\PDO::FETCH_OBJ)));

        if ( $executa ) {
            if ( $stmte->rowCount() > 0 ) {
                $count = 0;
                while($reg = $stmte->fetch(\PDO::FETCH_OBJ)){ // Para recuperar um ARRAY utilize PDO::FETCH_ASSOC
                    $sanitize = true;

                    try {
                        $funding_source = Security::decrypt($reg->funding_source);
                        $primary_sponsor = Security::decrypt($reg->primary_sponsor);
                        $secondary_sponsor = Security::decrypt($reg->secondary_sponsor);
                        $sscientific_contact = Security::decrypt($reg->sscientific_contact);
                    } catch (\Exception $e) {
                        $sanitize = false;
                    }

                    if ( $input->getOption('rollback') != true ) {
                        if ( !$sanitize ) {
                            $funding_source = Security::encrypt($reg->funding_source);
                            $primary_sponsor = Security::encrypt($reg->primary_sponsor);
                            $secondary_sponsor = Security::encrypt($reg->secondary_sponsor);
                            $sscientific_contact = Security::encrypt($reg->sscientific_contact);
                        } else {
                            print "-- [ERROR] Data already encrypted --\n";
                            break;
                        }
                    } else {
                        $funding_source = Security::decrypt($reg->funding_source);
                        $primary_sponsor = Security::decrypt($reg->primary_sponsor);
                        $secondary_sponsor = Security::decrypt($reg->secondary_sponsor);
                        $sscientific_contact = Security::decrypt($reg->sscientific_contact);
                    }

                    $sql = "UPDATE submission
                            SET funding_source = :fs, primary_sponsor = :ps, secondary_sponsor = :ss, sscientific_contact = :sc
                            WHERE id = :id;";

                    $reg_stmte = $pdo->prepare($sql);
                    $reg_stmte->bindParam(":id", $reg->id, \PDO::PARAM_INT);
                    $reg_stmte->bindParam(":fs", $funding_source, \PDO::PARAM_STR);
                    $reg_stmte->bindParam(":ps", $primary_sponsor, \PDO::PARAM_STR);
                    $reg_stmte->bindParam(":ss", $secondary_sponsor, \PDO::PARAM_STR);
                    $reg_stmte->bindParam(":sc", $sscientific_contact, \PDO::PARAM_STR);
                    $reg_executa = $reg_stmte->execute();

                    if ( $reg_executa and $reg_stmte->rowCount() > 0 ) {
                        $count++;
                    } else {
                        $error = $reg_stmte->errorInfo();
                        print "-- [ERROR] $error[2] --\n";
                        break;
                    }
                }
                print "-- [INFO] Affected lines: $count --\n";
            } else {
                print "-- [FAIL] Empty table --\n";
            }
        } else {
            $error = $stmte->errorInfo();
            print "-- [ERROR] $error[2] --\n";
        }
    }
}
