<?php

namespace Proethos2\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDatabaseStructureCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:generate-database-structure')
            ->setDescription('Generate help message')
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

        $host = $database_host;
        if($database_port) {
            $host .= ":$database_port";
        }

        $command = "mysqldump -h $host -u$database_user -p$database_password $database_name -d | sed 's/ AUTO_INCREMENT=[0-9]*\b//'";
        $command .= " > $fixtures_dir/structure.sql";
        exec($command);
    }
}