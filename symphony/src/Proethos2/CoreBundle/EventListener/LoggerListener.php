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


namespace Proethos2\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;

class LoggerListener
{
    private $securityContext;
    private $logger;
    private $container;

    public function __construct(SecurityContextInterface $securityContext, $logger, $container)
    {
        $this->container = $container;
        $this->securityContext = $securityContext;
        $this->logger = $this->container->get('monolog.logger.security');
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $this->container->get('request');
        $route = $request->getRequestUri();
        $_route = $request->get('_route');
        $pathinfo = $request->getPathInfo();
        $controller = $event->getController();
        $action = '';

        if (is_array($controller)) {
            $_controller = get_class($controller[0]).'::'.$controller[1];
            $action = $controller[1];
        } elseif (is_object($controller)) {
            $_controller = get_class($controller);
        }

        $user = '';
        $token = $this->securityContext->getToken();

        if (null !== $token) {
            $user = (string) $token->getUser();
        }

        if ( $_route and !in_array($action, array('checkHelpAction', 'checkMailAction')) ) {
            $this->logger->info('route: '.$route.' action: '.$_controller, array('username' => $user));
        }
    }
}
