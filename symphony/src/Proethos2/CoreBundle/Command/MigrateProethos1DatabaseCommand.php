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

use Proethos2\ModelBundle\Entity\User;
use Proethos2\ModelBundle\Entity\Meeting;
use Proethos2\ModelBundle\Entity\Protocol;
use Proethos2\ModelBundle\Entity\Submission;
use Proethos2\ModelBundle\Entity\SubmissionCost;
use Proethos2\ModelBundle\Entity\SubmissionCountry;
use Proethos2\ModelBundle\Entity\SubmissionTask;
use Proethos2\ModelBundle\Entity\SubmissionUpload;
use Proethos2\ModelBundle\Entity\ProtocolComment;
use Proethos2\ModelBundle\Entity\ProtocolHistory;
use Proethos2\ModelBundle\Entity\ProtocolRevision;


class MigrateProethos1DatabaseCommand extends ContainerAwareCommand
{
    var $user_relations = array();
    var $meeting_relations = array();
    var $country_relations = array(
        13 => 4,
        18 => 9,
        20 => 11,
        24 => 16,
        31 => 23,
        25 => 17,
        26 => 18,
        55 => 48,
        59 => 52,
        63 => 57,
        76 => 69,
        78 => 71,
        104 => 95,
        110 => 101,
        139 => 131,
        152 => 145,
        182 => 176,
        // 221 =>
        238 => 826,
        240 => 840,
        243 => 858,
    );

    protected function configure()
    {
        $this
            ->setName('proethos2:migrate-proethos1-database')
            ->setDescription('Migrate all content from proethos1 to proethos2')
            ->addArgument('database', InputArgument::REQUIRED, 'Insert the database name that has proethos1 data.')
            ->addArgument('default_country', InputArgument::REQUIRED, 'Insert the default country for you users and protocols.')
            ->addArgument('old_directory', InputArgument::REQUIRED, 'Insert the document directory from old proethos installation.')
        ;
    }

    public function migrate_users($input, $output) {

        $user_repository = $this->em->getRepository('Proethos2ModelBundle:User');
        $role_repository = $this->em->getRepository('Proethos2ModelBundle:Role');
        $country_repository = $this->em->getRepository('Proethos2ModelBundle:Country');
        $mysql_connect = $this->connect_database($input);

        // mapping of roles from proethos1 => proethos2
        $LIST_ROLES = array(
            'RES' => 1,
            'SCR' => 2,
            'MEM' => 3,
            'ADC' => 4,
            'ADM' => 5,
        );

        // Migration usernames
        $query = "SELECT
                id_us as id
                ,us_country as country
                ,us_email as email
                ,us_senha as password
                ,us_nome as name
                ,us_instituition as institution
                ,us_perfil as proethos2_roles
            FROM usuario
        ";

        // making the query
        $result = mysql_query($query);
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }

        $count = 0;
        while($row = mysql_fetch_assoc($result)) {

            // making username from first part of email
            $email = explode(" ", $row['email']);
            $email = $email[0];
            $username = explode("@", $email);
            $username = $username[0];

            // if the user already exists, skip the row
            $user = $user_repository->findOneBy(array('username' => $username));
            if($user) {
                $this->user_relations[$row['id']] = $user;
                $output->writeln('<info>User '. $row['email'] .' already exists. Skipping...</info>');
                continue;
            }

            $current_country = (int) $row['country'];
            if(array_key_exists($current_country, $this->country_relations)) {
                $current_country = $country_repository->findOneBy(array('id' => $current_country));
            } else {
                $current_country = $country_repository->findOneBy(array('code' => $this->default_country ));
            }

            // initing the User object
            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setName($row['name']);
            $user->setCountry($current_country);
            $user->setInstitution($row['institution']);
            $user->setIsActive(true);
            $user->setFirstAccess(false);

            // generate the password salt from old password. In practical, the passwords won't change.
            $encoderFactory = $this->container->get('security.encoder_factory');
            $encoder = $encoderFactory->getEncoder($user);
            $salt = $user->getSalt();
            $password = $encoder->encodePassword($row['password'], $salt);
            $user->setPassword($password);

            // save the user on proethos2
            $this->em->persist($user);
            $this->em->flush();

            // adding roles to this user
            $roles = explode("#", $row['proethos2_roles']);
            $added_roles = array();
            foreach($roles as $role) {
                if(!empty($role) && array_key_exists($role, $LIST_ROLES)) {
                    $role = $LIST_ROLES[$role];
                    if(!in_array($role, $added_roles)) {
                        $added_roles[] = $role;
                        $role = $role_repository->find($role);
                        $user->addProethos2Role($role);
                    }
                }
            }
            $this->em->persist($user);
            $this->em->flush();

            $this->user_relations[$row['id']] = $user;
            $output->writeln('<info>User '. $user->getUsername() .' have been inserted.</info>');
            $count += 1;
        }

        $output->writeln('<info>Added '. $count .' users in system.</info>');
    }

    public function migrate_meetings($input, $output) {

        $meeting_repository = $this->em->getRepository('Proethos2ModelBundle:Meeting');
        $mysql_connect = $this->connect_database($input);

        // Meetings query
        $query = "SELECT
                id_cal as id
                ,cal_date as date
                ,cal_description as subject
                ,cal_description as content
            FROM calender
            WHERE
                cal_ativo > 0
        ";

        // making the query
        $result = mysql_query($query);
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }

        $count = 0;
        while($row = mysql_fetch_assoc($result)) {

            // creating date of this meeting
            $date = \DateTime::createFromFormat('Ymd', $row['date']);

            // if the meeting already exists, skip the row
            $meeting = $meeting_repository->findOneBy(array('date' => $date, 'subject' => $row['subject']));
            if($meeting) {
                $this->meeting_relations[$row['id']] = $meeting->getId();
                $output->writeln('<info>Meeting '. $row['subject'] .' already exists. Skipping...</info>');
                continue;
            }

            // setting the meeting
            $meeting = new Meeting();
            $meeting->setDate($date);
            $meeting->setSubject($row['subject']);
            $meeting->setContent($row['content']);

            // saving this meeting
            $this->em->persist($meeting);
            $this->em->flush();

            $this->meeting_relations[$row['id']] = $meeting->getId();
            $output->writeln('<info>Meeting '. $row['subject'] .' have been inserted.</info>');
            $count += 1;
        }

        $output->writeln('<info>Added '. $count .' meeting in system.</info>');
    }

    public function migrate_protocols($input, $output) {

        $protocol_repository = $this->em->getRepository('Proethos2ModelBundle:Protocol');
        $submission_repository = $this->em->getRepository('Proethos2ModelBundle:Submission');
        $gender_repository = $this->em->getRepository('Proethos2ModelBundle:Gender');
        $recruitment_status_repository = $this->em->getRepository('Proethos2ModelBundle:RecruitmentStatus');
        $upload_type_repository = $this->em->getRepository('Proethos2ModelBundle:UploadType');
        $country_repository = $this->em->getRepository('Proethos2ModelBundle:Country');

        // relations of upload types from proethos1 to proethos2
        $UPLOAD_TYPE_RELATIONS = array(
            'TCLE' => 'informed-consent',
            'outhe' => 'others',
            'PROJ' => 'protocol',
            'DICT' => 'decision',
            'REEAD' => 'adverse-event-report',
            'DICTA' => 'estimation',
            'SEGU' => 'insurance-policy',
        );

        // relations of protocol status from proethos1 to proethos2
        // TODO: Dúvida: usar o status do protocolo ou da submissão?
        $PROTOCOL_STATUS_RELATIONS = array(
            '@' => 'D',
            // 'A' => 'S',
            // 'B' => 'I',
            // 'C' => 'I',
            // 'D' => 'H',
            // '$' => 'R',
            // 'X' => 'D', // TODO: neste caso ele é cancelado pelo pesquisador. Pular este protocolo no nomento da migração
            // 'Z' => 'A',
            'A' => 'S',
            'B' => 'S',
            'C' => 'S',
            'D' => 'S',
            '$' => 'S',
            'X' => 'S', // TODO: neste caso ele é cancelado pelo pesquisador. Pular este protocolo no nomento da migração
            'Z' => 'S',
        );

        $mysql_connect = $this->connect_database($input);

        $query = "SELECT
                id_doc as id
                ,doc_research_main as owner
                ,doc_1_titulo_public as publicTitle
                ,doc_1_titulo as scientificTitle
                ,doc_acronym as titleAcronym
                ,doc_clinic as is_clinical_trial
                ,doc_status as status
            FROM cep_submit_documento

        ";

        $result = mysql_query($query);
        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }

        $count = 0;
        while($row = mysql_fetch_assoc($result)) {

            // if the meeting already exists, skip the row
            $protocol = $protocol_repository->findOneBy(array('migrated_id' => $row['id']));
            if($protocol) {
                $output->writeln('<info>Protocol '. $protocol->getId() .' already migrated. Skipping...</info>');
                continue;
            }

            $protocol = new Protocol();
            $submission = new Submission();

            $owner = (int) $row['owner'];
            if(!array_key_exists($owner, $this->user_relations)) {
                $output->writeln('<error>ERROR: Protocol "'. $row['publicTitle'] .'" skipped because main investigator (id: '. $row['owner'] .') wansn\'t found.</error>');
                continue;
            }

            // setting protocol fields
            $protocol->setOwner($this->user_relations[$owner]);
            $protocol->setMigratedId($row['id']);

            // trying to set status
            if(!array_key_exists($row['status'], $PROTOCOL_STATUS_RELATIONS)) {
                $output->writeln('<error>ERROR: Protocol "'. $row['publicTitle'] .'" skipped because status "'. $row['status'] .' "doesn\'t had been mapped.</error>');
                continue;
            } else {
                $protocol->setStatus($PROTOCOL_STATUS_RELATIONS[$row['status']]);
            }

            // maaping other fields to insert in protocol
            $sql = "SELECT
                cep_caae as code, cep_dictamen as opinion_required, cep_data as date_informed, cep_atualizado as updated_in,
                cep_dt_ciencia as revised_in, cep_dt_liberacao as decision_in
            FROM cep_protocolos WHERE CAST(cep_protocol AS UNSIGNED) = ". $row['id'];

            $result2 = mysql_query($sql) or die(mysql_error());
            while($row2 = mysql_fetch_assoc($result2)) {
                $protocol->setCode($row2['code']);
                $protocol->setOpinionRequired($row2['opinion_required']);
                $protocol->setDateInformed(\DateTime::createFromFormat('Ymd', $row2['date_informed']));
                $protocol->setUpdatedIn(\DateTime::createFromFormat('Ymd', $row2['updated_in']));
                $protocol->setRevisedIn(\DateTime::createFromFormat('Ymd', $row2['revised_in'])); // TODO: revised_in creio estar errada. Tirar dúvida com o Rene.
                $protocol->setDecisionIn(\DateTime::createFromFormat('Ymd', $row2['decision_in'])); // TODO: decision_in creio estar errada. Tirar dúvida com o Rene.
            }

            // preventing erros from too long titles
            if(strlen($row['publicTitle']) > 510) {
                $output->writeln('<error>ERROR: Protocol ID "'. $row['id'] .'" skipped because title is too long.</error>');
                continue;
            }

            // saving this protocol
            $this->em->persist($protocol);
            $this->em->flush();

            // analisar status por exemplo do 000165

            // setting submission fields
            $submission->setProtocol($protocol);
            $submission->setOwner($this->user_relations[$owner]);
            $submission->setNumber(1);
            $submission->setPublicTitle($row['publicTitle']);
            $submission->setScientificTitle($row['scientificTitle']);
            $submission->setTitleAcronym($row['titleAcronym']);
            $submission->setIsClinicalTrial($row['is_clinical_trial']);

            $field_relations = array(
                '00002' => 'setAbstract',
                '00008' => 'setKeywords',
                '00004' => 'setIntroduction',
                '00030' => 'setJustification',
                '00011' => 'setGoals',
                '00010' => 'setStudyDesign',
                '00001' => 'setHealthCondition',
                '00069' => 'setGender',
                '00070' => 'setMinimumAge',
                '00071' => 'setMaximumAge',
                '00027' => 'setInclusionCriteria',
                '00028' => 'setExclusionCriteria',
                '00022' => 'setRecruitmentInitDate',
                '00042' => 'setRecruitmentStatus',
                // TODO: Os SubmissionCountry terão que ser inseridos a mão pela falta de relação entre os códigos dos paises
                '00020' => 'setInterventions',
                '00017' => 'setPrimaryOutcome',
                '00018' => 'setSecondaryOutcome',
                '00031' => 'setGeneralProcedures',
                '00032' => 'setAnalysisPlan',
                '00023' => 'setEthicalConsiderations',
                // TODO: clinical trials (problema: não há padronização nas datas do campo cep_submit_register_unit)
                '00033' => 'setFundingSource',
                '00040' => 'setPrimarySponsor',
                '00041' => 'setSecondarySponsor',
                '00024' => 'setBibliography',
                '00083' => 'setPriorEthicalApproval',
            );

            // setting text fields on submission
            foreach($field_relations as $p1_field => $p2_method) {
                $sql = "SELECT spc_content as value FROM cep_submit_documento_valor WHERE CAST(spc_projeto AS UNSIGNED) = ". $row['id'] ." AND spc_codigo = '". $p1_field . "'";
                $result2 = mysql_query($sql) or die(mysql_error());
                $object = mysql_fetch_assoc($result2);
                $value = $object['value'];

                // set gender
                if($p1_field == '00069') {
                    if($value == '#women') {
                        $value = $gender_repository->findOneBy(array('slug' => 'female'));
                    } elseif($value == '#men') {
                        $value = $gender_repository->findOneBy(array('slug' => 'male'));
                    } elseif($value == '#both') {
                        $value = $gender_repository->findOneBy(array('slug' => 'both'));
                    } else {
                        $value = $gender_repository->findOneBy(array('slug' => 'n-a'));
                    }

                // set recruitment status
                } elseif($p1_field == '00042') {
                    $value = $recruitment_status_repository->findOneBy(array('slug' => $value));

                // fixing minimum or maximum age
                } elseif($p1_field == '00070' or $p1_field == '00071') {
                    if(!is_numeric($value)) {
                        $value = 0;
                    }

                // set recruitment init date
                } elseif($p1_field == '00022') {

                    // if size != means that not a valid date format
                    if(strlen($value) != 10 or !is_numeric($value)) {
                        continue;
                    }
                    $value = \DateTime::createFromFormat('d/m/Y', $value);

                // set prior ethical approval
                } elseif($p1_field == '00083') {
                    $value = false;
                    if($value == "#YES") {
                        $value = true;
                    }
                }

                $submission->{$p2_method}($value);
            }

            $this->em->persist($submission);
            $this->em->flush();

            // associating submission to protocol
            $protocol->setMainSubmission($submission);

            // setting all submission costs
            $sql = "SELECT sorca_descricao as description, sorca_unid as quantity, sorca_valor as unit_cost FROM cep_submit_orca WHERE CAST(sorca_protocol AS UNSIGNED) = ". $row['id'];
            $result2 = mysql_query($sql) or die(mysql_error());
            while($row2 = mysql_fetch_assoc($result2)) {

                $item = new SubmissionCost();
                $item->setSubmission($submission);
                $item->setDescription($row2['description']);
                $item->setQuantity($row2['quantity']);
                $item->setUnitCost($row2['unit_cost']);

                $this->em->persist($item);
                $this->em->flush();

                $submission->addBudget($item);
            }

            // setting all submission country
            $sql = "SELECT ctr_protocol as submission, ctr_country as country, ctr_target as participants FROM `cep_submit_country` WHERE CAST(ctr_protocol AS UNSIGNED) = ". $row['id'];
            $result2 = mysql_query($sql) or die(mysql_error());
            while($row2 = mysql_fetch_assoc($result2)) {

                $item = new SubmissionCountry();
                $current_country = (int) $row2['country'];
                if(array_key_exists($current_country, $this->country_relations)) {
                    $current_country = $country_repository->findOneBy(array('id' => $current_country));
                } else {
                    $output->writeln('<error>ERROR: Country from protocol ID "'. $row['id'] .'" skipped because country has not been mapped.</error>');
                    continue;
                }

                $item->setSubmission($submission);
                $item->setCountry($current_country);
                $item->setParticipants($row2['participants']);

                $this->em->persist($item);
                $this->em->flush();

                $submission->addCountry($item);
            }

            // setting all submission task
            $sql = "SELECT scrono_descricao as description, scrono_date_start as init, scrono_date_end as end FROM cep_submit_crono WHERE CAST(scrono_protocol AS UNSIGNED) = ". $row['id'];
            $result2 = mysql_query($sql) or die(mysql_error());
            while($row2 = mysql_fetch_assoc($result2)) {
                $item = new SubmissionTask();
                $item->setSubmission($submission);
                $item->setDescription($row2['description']);
                $item->setInit(\DateTime::createFromFormat('Ym', $row2['init']));
                $item->setEnd(\DateTime::createFromFormat('Ym', $row2['end']));

                $this->em->persist($item);
                $this->em->flush();

                $submission->addSchedule($item);
            }

            // setting all submission upload
            $sql = "SELECT doc_data as date, doc_hora as time, doc_tipo as upload_type, doc_filename as filename, doc_arquivo as filepath  FROM cep_ged_documento WHERE CAST(doc_dd0 AS UNSIGNED) = ". $row['id'];
            $result2 = mysql_query($sql) or die(mysql_error());
            while($row2 = mysql_fetch_assoc($result2)) {

                $date = \DateTime::createFromFormat('YmdH:i', $row2['date'] . $row2['time']);

                $item = new SubmissionUpload();
                $item->setCreated($date);
                $item->setUpdated($date);
                $item->setUser($this->user_relations[$owner]); // TODO: Informar que o owner do arquivo será sempre o owner do protocolo
                $item->setSubmission($submission);
                $item->setUploadType($upload_type_repository->findOneBy(array('slug' => $row2['upload_type'])));
                $item->setMigratedFile($row2['filepath']);// TODO: Criar script depois que slugifica todos os arquivos importados, pq aqui salva slugificado
                $item->setSubmissionNumber(1); // TODO: Não temos mapeado, então tem que cravar 1.

                $old_filepath = $this->old_directory . "/" . $row2['filepath'];
                if(!file_exists($old_filepath)) {
                    $output->writeln('<error>ERROR: File "'. $row['id'] .'" skipped because we can\'t find in old installation.</error>');
                    continue;
                }
                $item->setSimpleFile($old_filepath, $need_real_copy = true);
                $this->em->persist($item);
                $this->em->flush();

                $submission->addAttachment($item);
            }

            $this->em->persist($submission);
            $this->em->flush();

            // TODO: Importar revisões.
            // setting all submission comments
            $sql = "SELECT pp_avaliador as member, pp_data as date, pp_hora as time, pp_abe_01 as social_value,
                pp_abe_02 as sscientific_validity, pp_abe_03 as fair_participant_selection, pp_abe_04 as favorable_balance,
                pp_abe_05 as informed_consent, pp_abe_06 as respect_for_participants, pp_abe_07 as other_comments FROM cep_dictamen WHERE CAST(pp_protocolo AS UNSIGNED) = ". $row['id'];
            $result2 = mysql_query($sql) or die(mysql_error());
            while($row2 = mysql_fetch_assoc($result2)) {

                $item = new ProtocolRevision();
                $date = \DateTime::createFromFormat('YmdH:i:s', $row2['date'] . $row2['time']);
                if($date) {
                    $item->setCreated($date);
                    $item->setUpdated($date);
                }

                $item->setProtocol($protocol);
                $item->setMember($this->user_relations[(int)$row2['member']]);
                $item->setSocialValue($row2['social_value']);
                $item->setSscientificValidity($row2['sscientific_validity']);
                $item->setFairParticipantSelection($row2['fair_participant_selection']);
                $item->setFavorableBalance($row2['favorable_balance']);
                $item->setInformedConsent($row2['informed_consent']);
                $item->setRespectForParticipants($row2['respect_for_participants']);
                $item->setOtherComments($row2['other_comments']);

                // TODO: Achar forma de preencher answer
                // TODO: Preencher final decision
                //
                $this->em->persist($item);
                $this->em->flush();

                $protocol->addRevision($item);
            }

            // TODO: Descobrir quais são os campos Decisão e Sugestão, nas revisões enviadas pelos revisores. Verificar en el proethos.curso.
            // TODO: Remover os prints de debug.

            // setting all submission comments
            $sql = "SELECT cepc_data as date, cepc_hora as time, cepc_user as owner, cepc_comment as message FROM cep_comment WHERE CAST(cepc_codigo AS UNSIGNED) = ". $row['id'];
            $result2 = mysql_query($sql) or die(mysql_error());
            while($row2 = mysql_fetch_assoc($result2)) {

                $date = \DateTime::createFromFormat('YmdH:i:s', $row2['date'] . $row2['time']);

                $item = new ProtocolComment();
                $item->setCreated($date);
                $item->setUpdated($date);
                $item->setMessage($row2['message']);
                $item->setProtocol($protocol);

                $this->em->persist($item);
                $this->em->flush();

                $protocol->addComment($item);
            }

            // setting all submission histories
            $sql = "SELECT his_data as date, his_time as time, his_comment as message  FROM cep_protocolos_historic WHERE CAST(his_protocol AS UNSIGNED) = ". $row['id'];
            $result2 = mysql_query($sql) or die(mysql_error());
            while($row2 = mysql_fetch_assoc($result2)) {

                $date = \DateTime::createFromFormat('YmdH:i', $row2['date'] . $row2['time']);
                $item = new ProtocolHistory();
                $item->setCreated($date);
                $item->setUpdated($date);
                $item->setProtocol($protocol);
                $item->setMessage($row2['message']);

                $this->em->persist($item);
                $this->em->flush();

                $protocol->addHistory($item);
            }

            $this->em->persist($protocol);
            $this->em->flush();

            // TODO: pelo o que entendi, o monitoreo nada mais é que alterar os campos originais e anexar um novo PDF. Ou seja, não dá pra migrar.

            $output->writeln('<info>Protocol '. $submission->getPublicTitle() .' have been inserted.</info>');
            $count += 1;
        }

        $output->writeln('<info>Added '. $count .' protocols in system.</info>');
    }

    public function connect_database($input) {
        $database_host = $this->getContainer()->getParameter('database_host');
        $database_port = $this->getContainer()->getParameter('database_port');
        $database_name = $input->getArgument('database');
        $database_user = $this->getContainer()->getParameter('database_user');
        $database_password = $this->getContainer()->getParameter('database_password');

        $this->default_country = -$input->getArgument('default_country');
        $this->old_directory = $input->getArgument('old_directory');

        $mysql_connect = mysql_connect($database_host, $database_user, $database_password);
        $mysql_database = mysql_select_db($database_name, $mysql_connect);
        if (!$mysql_database) {
            die ('Can\'t use foo : ' . mysql_error());
        }

        mysql_set_charset('utf8', $mysql_connect);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container = $this->getContainer();
        $this->doctrine = $this->container->get('doctrine');
        $translator = $this->container->get('translator');
        $this->em = $this->doctrine->getManager();

        $users = $this->migrate_users($input, $output);
        $users = $this->migrate_meetings($input, $output);
        $users = $this->migrate_protocols($input, $output);
    }
}
