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
 

namespace Proethos2\CoreBundle\Util;

class Util {

    var $container;
    var $doctrine;
    var $em;

    public function __construct($container, $doctrine) {
        $this->container = $container;
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
    }

    public function getConfiguration($key) {
        $configuration_repository = $this->em->getRepository('Proethos2ModelBundle:Configuration');

        if ( 'committee.contact' == $key ) {
            $configuration = $configuration_repository->findOneByKey('committee.email');

            if($configuration and !empty($configuration->getValue())) {
                $value = $configuration->getValue();
                $committee_contact = explode("|", $value);
                
                if( $committee_contact && count($committee_contact) == 2 ) {
                    return $committee_contact[1];
                }
                
                return $configuration->getValue(); 
            }

            $configuration = $this->container->getParameter('committee.email');
            if(!empty($configuration)) {
                return $configuration; 
            }
        } else {
            $configuration = $configuration_repository->findOneByKey($key);

            if($configuration and !empty($configuration->getValue())) {
                if ( 'committee.email' == $key ) {
                    $value = $configuration->getValue();
                    $committee_email = explode("|", $value);
                    
                    if( $committee_email ) {
                        return $committee_email[0];
                    }
                }

                return $configuration->getValue(); 
            }

            $configuration = $this->container->getParameter($key);
            if(!empty($configuration)) {
                return $configuration; 
            }
        }

        return NULL;
    }

    public function linkify($string) {
        // regex filter
        $reg_pattern = '!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?&_/=]+!';

        // make the urls to hyperlinks
        return preg_replace($reg_pattern, "<a href=\"\\0\" target=\"_blank\" rel=\"noopener noreferrer\">\\0</a>", $string);
    }
}