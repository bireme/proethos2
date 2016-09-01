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
                <h1 class="page-header">
                    Perguntas Frequentes
                    <a href='#' class='btn btn-primary' data-toggle='modal' data-target='#modal-new-faq'>Nova Pergunta</a>
                </h1>
            </div>
        </div>

        <div class='block'>
            <form class='form'>
                <div class='row'>
                    <div class="col-md-11">
                        <div class="form-group">
                            <label for="">Pesquisar por pergunta:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type='text' class='form-control'>
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
                                <th width="5%">#</th>
                                <th width="70%">Pergunta</th>
                                <th>Status</th>
                                <th>Linguagem</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        
                        <tbody id='sortable' class='sortable'>
                            <tr>
                                <td>1</td> 
                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a turpis eu diam varius auctor sit amet quis tellus. Sed nec metus semper...</td> 
                                <td><span class='label label-success'>Ativo</span></td> 
                                <td>Português</td> 
                                <td>
                                    <a href='#' data-toggle='modal' data-target='#modal-new-faq' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='#' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-trash'></i></a>
                                </td> 
                            </tr>
                            <tr>
                                <td>2</td> 
                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a turpis eu diam varius auctor sit amet quis tellus. Sed nec metus semper...</td> 
                                <td><span class='label label-success'>Ativo</span></td> 
                                <td>Inglês</td> 
                                <td>
                                    <a href='#' data-toggle='modal' data-target='#modal-new-faq' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='#' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-trash'></i></a>
                                </td> 
                            </tr>
                            <tr>
                                <td>3</td> 
                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a turpis eu diam varius auctor sit amet quis tellus. Sed nec metus semper...</td> 
                                <td><span class='label label-default'>Inativo</span></td> 
                                <td>Português</td> 
                                <td>
                                    <a href='#' data-toggle='modal' data-target='#modal-new-faq' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
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

    <!-- Modal Novo Relator -->
    <div class="modal fade" id="modal-new-faq" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Cadastrar Relator</h4>
                </div>
                <div class="modal-body">
                    <form class='form'>

                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="">Pergunta:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <input type='text' class='form-control'>
                                </div>
                            </div>
                        </div>
                        
                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="">Resposta:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <textarea class='form-control' rows="5"></textarea>
                                </div>
                            </div>
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

<script>
    $(function(){
        $( "#sortable" ).sortable();
    });
</script>