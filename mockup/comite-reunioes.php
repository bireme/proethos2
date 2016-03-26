<?php include 'header.php'; ?> 
    
    <div class="container-fluid main-content submissions">
        <div class="row">
            <div class='col-md-12'>
                <h1 class="page-header">
                    Reuniões
                    <a href='#' class='btn btn-primary' data-toggle='modal' data-target='#modal-new-meeting'>Nova Reunião</a>
                </h1>
            </div>
        </div>

        <div class='block'>
            <form class='form'>
                <div class='row'>
                    <div class="col-md-11">
                        <div class="form-group">
                            <label for="">Pesquisar por pauta de reunião:</label> 
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
                                <th width="10%">Data</th>
                                <th width="85%">Título</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr>
                                <td>20/05/2016</td> 
                                <td>Reunião de decisão de comitê #13</td> 
                                <td>
                                    <a href='#' data-toggle='modal' data-target='#modal-new-meeting' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='#' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-trash'></i></a>
                                </td> 
                            </tr>
                            <tr>
                                <td>25/05/2016</td> 
                                <td>Reunião de decisão de comitê #14</td> 
                                <td>
                                    <a href='#' data-toggle='modal' data-target='#modal-new-meeting' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='#' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-trash'></i></a>
                                </td> 
                            </tr>
                            <tr>
                                <td>20/06/2016</td> 
                                <td>Reunião de decisão de comitê #15</td> 
                                <td>
                                    <a href='#' data-toggle='modal' data-target='#modal-new-meeting' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
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
    <div class="modal fade" id="modal-new-meeting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nova Reunião</h4>
                </div>
                <div class="modal-body">
                    <form class='form'>

                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="">Data:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <input type='date' class='form-control'>
                                </div>
                            </div>
                        </div>
                        
                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="">Titulo:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <input type='text' class='form-control'>
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="">Pauta:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <textarea rows="5" class='form-control'></textarea>
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