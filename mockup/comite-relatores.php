<?php include 'header.php'; ?> 
    
    <div class="container-fluid main-content submissions">
        <div class="row">
            <div class='col-md-12'>
                <h1 class="page-header">
                    Relatores
                    <a href='#' class='btn btn-primary' data-toggle='modal' data-target='#modal-criar-relator'>Cadastrar Relator</a>
                </h1>
            </div>
        </div>

        <div class='block'>
                    
    
            <form class='form'>
                <div class='row'>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="">Pesquisar por pesquisadores:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type='text' class='form-control'>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Tipo:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <select class='form-control selectpicker' multiple>
                                <option selected>Todos</option>
                                <option>Interno</option>
                                <option>Externo</option>
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
                                <th width="30%">Nome</th>
                                <th>Tipo</th>
                                <th>Insituição</th>
                                <th>E-mail</th>
                                <th>País</th>
                                <th>Recrutamento</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr>
                                <td>Moacir Moda</td> 
                                <td>Interno</td> 
                                <td>BIREME/PAHO/OMS</td> 
                                <td><a href='mailto:moa.moda@gmail.com'>moa.moda@gmail.com</a></td> 
                                <td>Brazil</td> 
                                <td>25/04/2016</td> 
                                <td><span class='label label-default'>Inativo</span></td> 
                                <td>
                                    <a href='#' data-toggle='modal' data-target='#modal-criar-relator' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='#' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-trash'></i></a>
                                </td> 
                            </tr>
                            <tr>
                                <td>Renato Murasaki</td> 
                                <td>Interno</td> 
                                <td>BIREME/PAHO/OMS</td> 
                                <td><a href='mailto:moa.moda@gmail.com'>murasaki@gmail.com</a></td> 
                                <td>Brazil</td> 
                                <td>25/04/2016</td> 
                                <td><span class='label label-success'>Ativo</span></td> 
                                <td>
                                    <a href='#' data-toggle='modal' data-target='#modal-criar-relator' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='#' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-trash'></i></a>
                                </td> 
                            </tr>
                            <tr>
                                <td>Tania Flores</td> 
                                <td>Externo</td> 
                                <td>PAHO/OMS</td> 
                                <td><a href='mailto:moa.moda@gmail.com'>tania@gmail.com</a></td> 
                                <td>Colombia</td> 
                                <td>25/04/2016</td> 
                                <td><span class='label label-success'>Ativo</span></td> 
                                <td>
                                    <a href='#' data-toggle='modal' data-target='#modal-criar-relator' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-pencil'></i></a>
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
    <div class="modal fade" id="modal-criar-relator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                    <label for="">Nome Completo:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <input type='text' class='form-control'>
                                </div>
                            </div>
                        </div>
                                
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="">E-mail:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <input type='text' class='form-control'>
                                </div>
                            </div>

                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="">E-mail alternativo:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <input type='text' class='form-control'>
                                </div>
                            </div>
                        </div>
                        
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="">Senha:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <input type='text' class='form-control'>
                                </div>
                            </div>

                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label for="">Senha:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <input type='text' class='form-control'>
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="">Endereço:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <textarea rows="4" class='form-control'></textarea>
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="">Instituição:</label> 
                                    <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                                    <input type='text' class='form-control'>
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
