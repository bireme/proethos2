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
?>

<div class='row'>
    <div class='col-md-12'>
        <ul class="nav nav-tabs" id='tab-comite'>
            <li <?php $link = 'comite-em-analise.php'; if(strpos($_SERVER['REQUEST_URI'], $link)) print "class='active'";  ?>>
                <a href="<?= $link; ?>#action">Análise</a>
            </li>
            <li <?php $link = 'comite-avaliacao-do-comite.php'; if(strpos($_SERVER['REQUEST_URI'], $link)) print "class='active'";  ?>>
                <a href="<?= $link; ?>#action">Avaliação do Comitê</a>
            </li>
            <li <?php $link = 'comite-pesquisa-do-comite.php'; if(strpos($_SERVER['REQUEST_URI'], $link)) print "class='active'";  ?>>
                <a href="<?= $link; ?>#action">Pesquisa do Comitê</a>
            </li>
            <li <?php $link = 'comite-agendado-para-reuniao.php'; if(strpos($_SERVER['REQUEST_URI'], $link)) print "class='active'";  ?>>
                <a href="<?= $link; ?>#action">Agendado para Reunião</a>
            </li>
            <li <?php $link = 'comite-analise-finalizada.php'; if(strpos($_SERVER['REQUEST_URI'], $link)) print "class='active'";  ?>>
                <a href="<?= $link; ?>#action">Análise Finalizada</a>
            </li>
            <li <?php $link = 'comite-pesquisa-em-andamento.php'; if(strpos($_SERVER['REQUEST_URI'], $link)) print "class='active'";  ?>>
                <a href="<?= $link; ?>#action">Pesquisa em andamento</a>
            </li>
            
        </ul>
    </div>
</div>