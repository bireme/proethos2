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

class LoadDatabaseInitialDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:load-database-initial-data')
            ->setDescription('Load initial data.')
            ->addOption('update', 'u', InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $root_dir = $this->getContainer()->get('kernel')->getRootDir() . "/..";
        $fixtures_dir = $root_dir . "/fixtures";

        $database_host = $this->getContainer()->getParameter('database_host');
        $database_port = $this->getContainer()->getParameter('database_port');
        $database_name = $this->getContainer()->getParameter('database_name');
        $database_user = $this->getContainer()->getParameter('database_user');
        $database_password = $this->getContainer()->getParameter('database_password');

        $tables = array(
            'list_clinical_trial_name',
            'list_country',
            'list_gender',
            'list_monitoring_action',
            'list_recruitment_status',
            'list_role',
            'upload_type_extension',
            'upload_type',
            'upload_type_upload_type_extension',
            'help',
            'faq',
            'ext_translations',
        );

        if($input->getOption('update') != true) {
            $tables[] = 'configuration';
            $tables[] = 'user';
            $tables[] = 'user_role';
        }

        $host = $database_host;
        if($database_port) {
            $host .= " -p$database_port";
        }

        foreach($tables as $table) {
            $output->writeln("loading $table...");
            $command = "mysql -h $host -u$database_user -p$database_password $database_name";
            $command .= " < $fixtures_dir/data_$table.sql";
            exec($command);

        }
    }
}
