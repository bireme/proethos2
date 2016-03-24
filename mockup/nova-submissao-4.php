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
                            <h2 class='sub-header'>Orçamento:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <table class='table table-hover table-condensed'>
                                
                                <thead>
                                    <tr>
                                        <th width="50%">Descrição da Despesa</th>
                                        <th>Valor Unitário</th>
                                        <th>Quantidade</th>
                                        <th>Subtotal</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                       <td>Tubos de Ensaio</td> 
                                       <td><label class='label label-default'>R$ 15,00</label></td> 
                                       <td><label class='label label-default'>14</label></td> 
                                       <td><label class='label label-primary'>R$ 210,00</label></td> 
                                       <td><a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-trash'></i></a></td> 
                                    </tr>
                                </tbody>
                            </table>
                            <a href='#' class='btn btn-default' data-toggle="modal" data-target="#modal-new-expend">Nova Despesa</a>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Origem do Financiamento:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Patrocinador Oficial:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Outros Patrocinadores:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Cronograma:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <table class='table table-hover table-condensed'>
                                
                                <thead>
                                    <tr>
                                        <th width="50%">Descrição da Atividade</th>
                                        <th>Início</th>
                                        <th>Fim</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                       <td>Preparação</td> 
                                       <td>15/04/2016</td> 
                                       <td>15/05/2016</td> 
                                       <td><a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-trash'></i></a></td> 
                                    </tr>
                                    <tr>
                                       <td>Execução</td> 
                                       <td>16/05/2016</td> 
                                       <td>15/06/2016</td> 
                                       <td><a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-trash'></i></a></td> 
                                    </tr>
                                </tbody>
                            </table>
                            <a href='#' class='btn btn-default' data-toggle="modal" data-target="#modal-new-task">Nova Tarefa</a>
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

    <!-- Modal Nova Despesa -->
    <div class="modal fade" id="modal-new-expend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nova Despesa</h4>
                </div>
                <div class="modal-body">
                    <form class='form'>
                        
                        <div class="form-group">
                            <label for="">Descrição:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type='text' class='form-control'>
                        </div>

                        <div class="form-group">
                            <label for="">Quantidade:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type='number' class='form-control' min="1">
                        </div>

                        <div class="form-group">
                            <label for="">Valor Unitário:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type="tel" class='form-control' required="required" maxlength="15" name="valor" pattern="([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$" />
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

    <!-- Modal Nova Tarefa -->
    <div class="modal fade" id="modal-new-task" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nova tarefa</h4>
                </div>
                <div class="modal-body">
                    <form class='form'>
                        
                        <div class="form-group">
                            <label for="">Descrição:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type='text' class='form-control'>
                        </div>

                        <div class="form-group">
                            <label for="">Início:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type="date" class='form-control' required="required" maxlength="10" name="date" pattern="[0-9]{2}\/[0-9]{2}\/[0-9]{4}$" />
                        </div>

                        <div class="form-group">
                            <label for="">Fim:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type="date" class='form-control' required="required" maxlength="10" name="date" pattern="[0-9]{2}\/[0-9]{2}\/[0-9]{4}$" />
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
