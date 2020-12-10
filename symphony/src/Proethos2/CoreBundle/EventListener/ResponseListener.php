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
 
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
 
class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->set('X-Frame-Options', 'deny');
        $event->getResponse()->headers->set('X-Content-Type-Options', 'nosniff');
        $event->getResponse()->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $event->getResponse()->headers->set('X-XSS-Protection', '0');
    }
}