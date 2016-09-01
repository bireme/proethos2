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
        <?php include '_submission/meta.php'; ?>    
        
        <div class='new-submission-content'>
            <form class='form'>

                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Equipe:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <table class='table table-hover table-condensed'>
                                
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="50%">Nome</th>
                                        <th>E-mail</th>
                                        <th>País</th>
                                        <th>Principal?</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                       <th>01</th>
                                       <td>Moacir Moda</td> 
                                       <td><a href='mailto:moa.moda@gmail.com'>moa.moda@gmail.com</a></td> 
                                       <td>Brazil</td> 
                                       <td><label class='label label-success'>Sim</label></td> 
                                       <td><a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-trash'></i></a></td> 
                                    </tr>
                                    <tr>
                                       <th>02</th>
                                       <td>Renato Murasaki</td> 
                                       <td><a href='mailto:murasaki@paho.org'>murasaki@paho.org</a></td> 
                                       <td>Brazil</td> 
                                       <td><label class='label label-default'>Não</label></td> 
                                       <td><a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-trash'></i></a></td> 
                                    </tr>
                                </tbody>
                            </table>

                            <a href='#' class='btn btn-default' data-toggle="modal" data-target="#modal-new-investigator">Novo Investigador</a>
                        </div>
                    </div>
                </div>

                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Resumo e Palavras-chave:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Resumo:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Palavras-chave:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Introdução:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Introdução:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="6" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Justificativa:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Objetivos:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='row'>
                    <div class='submission-button-controls col-md-12'>
                        <a href='nova-submissao-3.php' class='btn btn-primary'>Salvar e Avançar</a>
                    </div>
                </div>
            </form>
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

    <!-- Modal Novo Investigador -->
    <div class="modal fade" id="modal-new-investigator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Novo Investigador</h4>
                </div>
                <div class="modal-body">
                    <form class='form'>
                        <div class="form-group">
                            <label for="">Nome:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type='text' class='form-control'>
                        </div>
                        
                        <div class="form-group">
                            <label for="">Email:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type='email' class='form-control'>
                        </div>
                        
                        <div class="form-group">
                            <label for="">País:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <select class='form-control selectpicker' data-live-search="true">
                                <option></option>
                                <option>Brasil</option>
                            </select>
                        </div>

                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>



<?php include 'footer.php'; ?> 
