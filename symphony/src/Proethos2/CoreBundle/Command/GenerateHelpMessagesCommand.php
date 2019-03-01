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

class GenerateHelpMessagesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:generate-help-messages')
            ->setDescription('Generate help message')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $root_dir = $this->getContainer()->get('kernel')->getRootDir();

        // iterates finding all the .twig files
        $it = new \RecursiveDirectoryIterator($root_dir . '/../src');
        foreach(new \RecursiveIteratorIterator($it) as $filepath => $object) {
            if(strpos($filepath, ".twig") > -1) {
                
                // get file contents and set the new content
                $content = file_get_contents($filepath);
                $new_content = "";

                $count = 0;
                $content_by_lines = explode("\n", $content);
                foreach($content_by_lines as $line) {
                    if(strpos($line, "#modal-help") > -1) {

                        // if doesnt mapped, associate an id to this help link
                        if(strpos($line, "href='#'") > -1 or strpos($line, 'href="#"') > -1) {

                            // create a new help text
                            $help = new Help();
                            $help->setType('help');
                            $em->persist($help);
                            $em->flush();

                            $line = str_replace("href='#'", 'href="{{ path("crud_admin_help_show", {help_id: '. $help->getId() .'} ) }}"', $line);
                            $line = str_replace('href="#"', 'href="{{ path("crud_admin_help_show", {help_id: '. $help->getId() .'} ) }}"', $line);
                            $output->writeln(trim($line));
                        }
                        
                    }

                    // concat the new line on the new content.
                    $count += 1;
                    $new_content .= $line;
                    // If is the last line, doesn't print the break line
                    if(count($content_by_lines) > $count) {
                        $new_content .= "\n";
                    }
                }

                // write the new file
                file_put_contents($filepath, $new_content);
            }
        }
    }
}