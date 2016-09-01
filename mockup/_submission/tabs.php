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
        <ul class="nav nav-tabs" id='new-submission-tab'>
            <li role="presentation" <?php if(strpos($_SERVER['REQUEST_URI'], 'nova-submissao.php')) print "class='active'";  ?>><a href="nova-submissao.php">Informações Preliminares</a></li>
            <li role="presentation" <?php if(strpos($_SERVER['REQUEST_URI'], 'nova-submissao-2.php')) print "class='active'";  ?>><a href="nova-submissao-2.php">Sobre o projeto</a></li>
            <li role="presentation" <?php if(strpos($_SERVER['REQUEST_URI'], 'nova-submissao-3.php')) print "class='active'";  ?>><a href="nova-submissao-3.php">Estudo clínico</a></li>
            <li role="presentation" <?php if(strpos($_SERVER['REQUEST_URI'], 'nova-submissao-4.php')) print "class='active'";  ?>><a href="nova-submissao-4.php">Cronograma e Orçamento</a></li>
            <li role="presentation" <?php if(strpos($_SERVER['REQUEST_URI'], 'nova-submissao-5.php')) print "class='active'";  ?>><a href="nova-submissao-5.php">Referências</a></li>
            <li role="presentation" <?php if(strpos($_SERVER['REQUEST_URI'], 'nova-submissao-6.php')) print "class='active'";  ?>><a href="nova-submissao-6.php">Arquivos anexados</a></li>
            <li role="presentation" <?php if(strpos($_SERVER['REQUEST_URI'], 'nova-submissao-7.php')) print "class='active'";  ?>><a href="nova-submissao-7.php">Revisão</a></li>
        </ul>
    </div>
</div>