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

use Proethos2\ModelBundle\Entity\Help;

class GenerateDatabaseTranslationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:generate-database-translations')
            ->setDescription('Generate database traslations from database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $root_dir = $this->getContainer()->get('kernel')->getRootDir();

        $fixtures_dir = $root_dir . "/fixtures";

        $database_host = $this->getContainer()->getParameter('database_host');
        $database_port = $this->getContainer()->getParameter('database_port');
        $database_name = $this->getContainer()->getParameter('database_name');
        $database_user = $this->getContainer()->getParameter('database_user');
        $database_password = $this->getContainer()->getParameter('database_password');

        $object_classes = array('Help', 'ClinicalTrialName', "Country", 'Gender', 'MonitoringAction', 'RecruitmentStatus', "Role", 'UploadType', 'Faq');
        $languages = array('en', 'es_ES', 'pt_BR', 'fr_FR');

        $pdo = new \PDO("mysql:host=$database_host;dbname=$database_name", $database_user, $database_password);

        print "DELETE FROM ext_translations;\n";
        print "\n";

        foreach($languages as $language) {
            print "\n-- ====================================================\n";
            print "-- $language\n";
            print "-- ====================================================\n";
            foreach($object_classes as $object_class) {

                $full_object_class = "Proethos2\\ModelBundle\\Entity\\" . $object_class;

                $sql = "SELECT
                        `locale`, `object_class`, `field`, `foreign_key`, `content`, CAST(`foreign_key` AS UNSIGNED) as fkeyint
                    FROM
                        ext_translations
                    WHERE
                        object_class = :object_class
                        AND locale = :locale
                    ORDER BY fkeyint ASC
                    ;";

                $stmte = $pdo->prepare($sql);
                $stmte->bindParam(":object_class", $full_object_class, \PDO::PARAM_STR);
                $stmte->bindParam(":locale", $language, \PDO::PARAM_STR);
                $executa = $stmte->execute();

                if($executa and $stmte->rowCount() > 0){
                    print "\n-- ---------------------\n";
                    print "-- $object_class\n";
                    print "-- ---------------------\n";
                    while($reg = $stmte->fetch(\PDO::FETCH_OBJ)){ /* Para recuperar um ARRAY utilize PDO::FETCH_ASSOC */
                        print "INSERT INTO `ext_translations` (`locale`, `object_class`, `field`, `foreign_key`, `content`) VALUES ('". $reg->locale ."', '". addslashes($reg->object_class) ."', '". $reg->field ."', '". $reg->foreign_key ."', '". addslashes(utf8_encode($reg->content)) ."');\n";
                    }
                }
            }
        }
    }
}
