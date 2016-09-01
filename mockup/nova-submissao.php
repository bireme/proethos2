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

<?php include 'header.php'; ?> 
    
    <div class="container-fluid main-content">
        <div class="row">
            <div class='col-md-12'>
                <h1 class="page-header">Nova Submissão</h1>
            </div>
        </div>

        <?php include '_submission/tabs.php'; ?>

        <div id='new-submission-tab-content' class='new-submission-tab-content'>
            
            <?php include '_submission/meta.php'; ?>
            
            <div class='row'>
                <div class='col-md-12'>
                    <form class='form'>
                        <fieldset>
                            <legend>Estudo Clínico:</legend>
                            <div class="form-group">
                                <label for="">Estudo Clínico?</label>
                                <div class="radio">
                                    <label><input type="radio" name="blankRadio" id="" value="" aria-label="..."> Sim</label>
                                    <label><input type="radio" name="blankRadio" id="" value="" aria-label="..."> Não</label>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>Título do Projeto:</legend>
                            
                            <div class="form-group">
                                <label for="">Título Científico do Projeto:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <input type="text" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label for="">Título Público:</label>
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <input type="text" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label for="">Acrônimo do Título:</label>
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </fieldset>

                        <div class='submission-button-controls'>
                            <a href='nova-submissao-2.php' class='btn btn-primary'>Salvar e Avançar</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal HELP -->
    <div class="modal fade" id="modal-help" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ajuda</h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?> 
