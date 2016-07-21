<?php

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
            'upload_type',
            'help',
            'user',
        );

        $host = $database_host;
        if($database_port) {
            $host .= ":$database_port";
        }

        foreach($tables as $table) {
            $output->writeln("loading $table...");
            $command = "mysql -h $host -u$database_user -p$database_password $database_name";
            $command .= " < $fixtures_dir/data_$table.sql";
            exec($command);

        }
    }
}