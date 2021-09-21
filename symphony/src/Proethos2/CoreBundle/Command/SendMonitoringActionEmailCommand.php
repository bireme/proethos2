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

use Proethos2\CoreBundle\Util\Util;


class SendMonitoringActionEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:send-monitoring-action-email')
            ->setDescription('Send email to monitoring actions with date closes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');
        $translator = $container->get('translator');

        $em = $doctrine->getManager();
        $util = new Util($container, $doctrine);
        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');
        // $help = $help_repository->findBy(array("id" => {id}, "type" => "mail"));
        // $translations = $trans_repository->findTranslations($help[0]);

        $protocols_to_send = array();
        $today = new \DateTime();
        $next_7_days = new \DateTime();
        $next_30_days = new \DateTime();

        $next_7_days = $next_7_days->add(new \DateInterval('P7D'));
        $next_30_days = $next_30_days->add(new \DateInterval('P30D'));

        // next 30 days
        $protocols = $protocol_repository->createQueryBuilder('p')
            ->andWhere('p.monitoring_action_next_date > :date_start')
            ->andWhere('p.monitoring_action_next_date < :date_end')
            ->setParameter('date_start', $next_30_days->format('Y-m-d 00:00:00'))
            ->setParameter('date_end', $next_30_days->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getResult();

        foreach($protocols as $protocol) {
            $code = $protocol->getCode();
            if(!array_key_exists($code, $protocols_to_send)) {
                $protocols_to_send[$code] = $protocol;
            }
        }

        // next 7 days
        $protocols = $protocol_repository->createQueryBuilder('p')
            ->andWhere('p.monitoring_action_next_date > :date_start')
            ->andWhere('p.monitoring_action_next_date < :date_end')
            ->setParameter('date_start', $next_7_days->format('Y-m-d 00:00:00'))
            ->setParameter('date_end', $next_7_days->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getResult();

        foreach($protocols as $protocol) {
            $code = $protocol->getCode();
            if(!array_key_exists($code, $protocols_to_send)) {
                $protocols_to_send[$code] = $protocol;
            }
        }

        if ( $protocols_to_send ) {
            foreach($protocols_to_send as $protocol) {

                $date = $protocol->getMonitoringActionNextDate()->format("d/m/Y");
                $email = $protocol->getMainSubmission()->getOwner()->getEmail();
                $code = $protocol->getCode();
                $translator->setLocale($protocol->getMainSubmission()->getLanguage());

                $help = $help_repository->find(223);
                $translations = $trans_repository->findTranslations($help);
                $text = $translations[$protocol->getMainSubmission()->getLanguage()];
                $body = ( $text ) ? $text['message'] : $help->getMessage();
                $body = str_replace("%monitoring_date%", $date, $body);
                $body = str_replace("%protocol_code%", $protocol->getCode(), $body);
                $body = str_replace("\r\n", "<br />", $body);
                $body .= "<br /><br />";
                $body = $util->linkify($body);

                $message = \Swift_Message::newInstance()
                ->setSubject("[proethos2] " . $translator->trans("You have a pending monitoring action to %monitoring_date%", array("%monitoring_date%" => $date)))
                ->setFrom($util->getConfiguration('committee.email'))
                ->setTo($email)
                ->setBody(
                    $body
                    ,
                    'text/html'
                );

                $send = $container->get('mailer')->send($message);
                $output->writeln(sprintf("[%s] remaind sent to '%s'.", $code, $email));
            }
        } else {
            $output->writeln("[INFO] No protocols with pending monitoring action.");
        }
    }
}
