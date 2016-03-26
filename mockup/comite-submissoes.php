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
                                    <a href='comite-em-analise.php' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-eye-open'></i></a>
                                </td> 
                            </tr>
                            <tr>
                                <th>PAHO.0001.02</th>
                                <td>Título da Submissão</td> 
                                <td><span class='label label-default'>Ensaio Clínico</span></td> 
                                <td>22/04/2016</td>
                                <td>Pendente</td> 
                                <td>
                                    <a href='comite-em-analise.php' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-eye-open'></i></a>
                                </td> 
                            </tr>
                            <tr>
                                <th>PAHO.0001.02</th>
                                <td>Título da Submissão</td> 
                                <td><span class='label label-default'>Investigação</span></td> 
                                <td>22/04/2016</td>
                                <td>Pendente</td> 
                                <td>
                                    <a href='comite-em-analise.php' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-eye-open'></i></a>
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
