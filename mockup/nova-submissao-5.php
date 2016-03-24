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
                            <h2 class='sub-header'>Referências:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Referências:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Contato com o Pesquisador:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Contato Público com o Pesquisador:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <textarea rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Aprovação no Local de Origem:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Aprovação no Local de Origem:</label> 
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                <div class='radio'>
                                    <label><input type='radio' name='radio'> Sim</label>
                                </div>
                                <div class='radio'>
                                    <label><input type='radio' name='radio'> Não</label>
                                </div>
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

<?php include 'footer.php'; ?> 
