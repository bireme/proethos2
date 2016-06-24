<?php 

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

        $configuration = $configuration_repository->findOneByKey($key);
        if($configuration) {
           return $configuration->getValue(); 
        }

        $configuration = $this->container->getParameter($key);
        if($configuration) {
           return $configuration; 
        }

        return NULL;
    }
}