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

class GenerateDatabaseInitialDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:generate-database-initial-data')
            ->setDescription('Generate initial data from development environment')
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
            'configuration',
            'list_clinical_trial_name',
            'list_country',
            'list_gender',
            'list_monitoring_action',
            'list_recruitment_status',
            'list_role',
            'upload_type',
            'help',
            'user',
            'user_role',
            'faq',
        );

        $host = $database_host;
        if($database_port) {
            $host .= ":$database_port";
        }

        foreach($tables as $table) {
            $output->writeln("extracting $table...");

            $command = "mysqldump -h $host -u$database_user -p$database_password $database_name --replace --no-create-info -t $table --complete-insert --compact --extended-insert=FALSE";
            $command .= " > $fixtures_dir/data_$table.sql";
            exec($command);
        }

        exec("cd $fixtures_dir && python ../../tools/reorder-fixtures-help.py data_help.sql");
    }
}
