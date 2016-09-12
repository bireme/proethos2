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
// https://github.com/bireme/proethos2/blob/master/doc/license.md


namespace Proethos2\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateLocaleFilesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:generate-locale-files')
            ->setDescription('Generate all the locale files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        exec("php app/console translation:extract pt_BR --dir=./src/ --output-dir=./app/Resources/translations");
        exec("php app/console translation:extract es_ES --dir=./src/ --output-dir=./app/Resources/translations");
        exec("php app/console translation:extract fr_FR --dir=./src/ --output-dir=./app/Resources/translations");
    }
}