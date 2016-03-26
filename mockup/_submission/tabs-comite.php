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