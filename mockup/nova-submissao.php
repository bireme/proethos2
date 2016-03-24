<?php include 'header.php'; ?> 
    
    <div class="container-fluid main-content">
        <div class="row">
            <div class='col-md-12'>
                <h1 class="page-header">Nova Submissão</h1>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-12'>
                <ul class="nav nav-tabs" id='new-submission-tab'>
                    <li role="presentation" class='active'><a href="nova-submissao.php">Informações Preliminares</a></li>
                    <li role="presentation"><a href="nova-submissao-2.php">Sobre o projeto</a></li>
                    <li role="presentation"><a href="nova-submissao-2.php">Estudo clínico</a></li>
                    <li role="presentation"><a href="nova-submissao-2.php">Dados adicionais</a></li>
                    <li role="presentation"><a href="nova-submissao-2.php">Anexo, cronograma e orçamento</a></li>
                    <li role="presentation"><a href="nova-submissao-2.php">Arquivos anexados</a></li>
                    <li role="presentation"><a href="nova-submissao-2.php">Revisão</a></li>
                </ul>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-12'>
                <div id='new-submission-tab-content' class='new-submission-tab-content'>
                    <img src='/mockup/static/img/icon-submission.png'>
                    
                    <div class='row'>
                        <div class='submission-meta'>

                            <div class='col-md-12'>
                                <div class='block'>
                                    <div class='label'>Título do Projeto</div>
                                    <h2>Projeto de Exemplo do Proethos 2.0</h2>
                                </div>
                            </div>

                            <div class='col-md-4'>
                                <div class='block'>
                                    <div class='label'>Autor Principal do Projeto</div>
                                    <h3>Moacir Moda</h3>
                                </div>
                            </div>

                            <div class='col-md-4'>
                                <div class='block'>
                                    <div class='label'>Tipo Principal</div>
                                    <h3>-</h3>
                                </div>
                            </div>

                            <div class='col-md-4'>
                                <div class='block'>
                                    <div class='label'>Atualizado em</div>
                                    <h3>05/04/2016</h3>
                                </div>
                            </div>                        

                        </div>
                    </div>
                    
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
                                    <button class='btn btn-primary'>Salvar e Avançar</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
