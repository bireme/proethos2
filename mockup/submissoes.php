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
    
    <div class="container-fluid main-content submissions">
        <div class="row">
            <div class='col-md-12'>
                <h1 class="page-header">Submissão</h1>
            </div>
        </div>

        <div class='block'>
                    
    
            <form class='form'>
                <div class='row'>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="">Pesquisar por submissões:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type='text' class='form-control'>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Estado:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <select class='form-control selectpicker' data-live-search="true">
                                <option>Todos</option>
                                <option>Em Análise</option>
                                <option>Aprovado</option>
                                <option>Em Revisão</option>
                                <option>Reprovados</option>
                                <option>Decisão Emitida</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-1 search-button">
                        <div class="form-group">
                            <button class='btn btn-primary'>Buscar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class='block'>
            <div class='row'>
                <div class="col-md-12">
                    <h2 class='sub-header'>Resultados:</h2>
                </div>
            </div>

            <div class='row'>
                <div class="col-md-12">
                    <table class='table table-hover table-condensed'>
                        
                        <thead>
                            <tr>
                                <th width="10%">Identificação</th>
                                <th width="50%">Título</th>
                                <th>Tipo</th>
                                <th>Última Atualização</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr>
                                <th>PAHO.0001.02</th>
                                <td>Título da Submissão</td> 
                                <td><span class='label label-default'>Investigação</span></td> 
                                <td>22/04/2016</td>
                                <td>Pendente</td> 
                                <td>
                                    <a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='ver-submissao.php' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-eye-open'></i></a>
                                    <a href='#' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-trash'></i></a>
                                </td> 
                            </tr>
                            <tr>
                                <th>PAHO.0001.02</th>
                                <td>Título da Submissão</td> 
                                <td><span class='label label-default'>Investigação</span></td> 
                                <td>22/04/2016</td>
                                <td>Pendente</td> 
                                <td>
                                    <a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-eye-open'></i></a>
                                    <a href='#' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-trash'></i></a>
                                </td> 
                            </tr>
                            <tr>
                                <th>PAHO.0001.02</th>
                                <td>Título da Submissão</td> 
                                <td><span class='label label-default'>Investigação</span></td> 
                                <td>22/04/2016</td>
                                <td>Pendente</td> 
                                <td>
                                    <a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-eye-open'></i></a>
                                    <a href='#' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-trash'></i></a>
                                </td> 
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class='row'>
                <div class='col-md-12' id="pagination">
                    <ul class="pagination">
                        <li><a href="#">«</a></li>
                        <li><a href="#">‹</a></li>
                        
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li class='active'><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        
                        <li><a href="#">›</a></li>
                        <li><a href="#">»</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?> 
