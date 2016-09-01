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
                            <h2 class='sub-header'>Descrição General:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Desenho do Estudo:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="4"S class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Especificações de Amostra de Participantes:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Gênero:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <select class='form-control'>
                                    <option></option>
                                    <option>Masculino</option>
                                    <option>Feminino</option>
                                    <option>Ambos</option>
                                    <option>Não se aplica</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Número de Participantes:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <input type="number" class="form-control" placeholder="" min="1" value="0">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Idade Mínina:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <input type="number" class="form-control" placeholder="" min="0" value="0">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Idade Máxima:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <input type="number" class="form-control" placeholder="" min="0" value="0">
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Critérios de Inclusão:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Critérios de Exclusão:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Data prevista para o primeiro recrutamento:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <input type='date' class='form-control'>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Países de Recrutamento:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <table class='table table-hover table-condensed'>
                                
                                <thead>
                                    <tr>
                                        <th width="50%">Nome do País</th>
                                        <th>Quantidade de Participantes</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                       <td>Brasil</td> 
                                       <td><label class='label label-default'>14</label></td> 
                                       <td><a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-trash'></i></a></td> 
                                    </tr>
                                    <tr>
                                       <td>Chile</td> 
                                       <td><label class='label label-default'>10</label></td> 
                                       <td><a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-trash'></i></a></td> 
                                    </tr>
                                </tbody>
                            </table>
                            <a href='#' class='btn btn-default' data-toggle="modal" data-target="#modal-new-country">Novo País</a>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Intervenções:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Resultado da Pesquisa:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Resultados Preliminares:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Desfecho Secundário:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Metodologia:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Metodologia da pesquisa e formas de recrutamento e abordagem:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Metodologia da análise dos dados:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Questões Éticas:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Riscos e benefícios para o participante:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='row'>
                    <div class='submission-button-controls col-md-12'>
                        <a href='nova-submissao-4.php' class='btn btn-primary'>Salvar e Avançar</a>
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

    <!-- Modal Novo País -->
    <div class="modal fade" id="modal-new-country" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Novo País</h4>
                </div>
                <div class="modal-body">
                    <form class='form'>
                        
                        <div class="form-group">
                            <label for="">País:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <select class='form-control selectpicker' data-live-search="true">
                                <option></option>
                                <option>Brasil</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Quantidade de Participantes:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type='number' class='form-control' min="1">
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
