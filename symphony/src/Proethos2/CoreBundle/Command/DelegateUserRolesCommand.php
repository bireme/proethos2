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
use Proethos2\CoreBundle\Util\Security;


class DelegateUserRolesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proethos2:delegate-user-roles')
            ->setDescription('Delegate roles for any existing user on the ProEthos system')
            ->setHelp('Usage: php app/console proethos2:delegate-user-roles <email> <roles>')
            ->addArgument('email', InputArgument::REQUIRED, 'Insert the user email.')
            ->addArgument('roles', InputArgument::REQUIRED, 'Insert the user roles ID (comma-separated): 1-investigator|2-secretary|3-member-of-committee|4-member-ad-hoc|5-administrator (e.g. 1,2,3,4,5)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');
        $translator = $container->get('translator');

        $em = $doctrine->getManager();
        $util = new Util($container, $doctrine);
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');

        $user_roles = array(
            'investigator'        => 1,
            'secretary'           => 2,
            'member-of-committee' => 3,
            'member-ad-hoc'       => 4,
            'administrator'       => 5            
        );

        $email = $input->getArgument('email');
        $list_roles = explode(',', $input->getArgument('roles'));
        $roles = array_intersect($user_roles, $list_roles);

        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $user = $user_repository->findOneBy(array('email' => $email));

        if ( !$user ) {
            $_email = Security::encrypt($email);
            $user = $user_repository->findOneBy(array('email' => $_email));
        }

        if ( $user ) {
            foreach ($roles as $role => $role_id) {
                $user->addProethos2Role($role_repository->findOneBy(array('slug' => $role)));
            }

            $user->setIsActive(true);

            $em->persist($user);
            $em->flush();

            $output_roles = implode('|', array_keys($roles));
            $output->writeln(sprintf("\n[INFO] user roles for '%s': %s\n", $email, $output_roles));
        } else {
            $output->writeln(sprintf("\n[ERROR] user email '%s' not found.\n", $email));
        }
    }
}
