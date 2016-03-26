<?php include 'header.php'; ?> 
    
    <div class="container-fluid main-content see-submission">
        <div class="row">
            <div class='col-md-12'>
                <h1 class="page-header">Análise de Protocolo</h1>
            </div>
        </div>

        <?php include '_submission/tabs-comite.php'; ?>

        <!-- ptrocol information -->
        <div class='block meta'>

            <div class='row'>
                <div class="col-md-12">
                    <h2 class='sub-header titulo'>Título do Protocolo <small>(ensaio clínico)</small></h2>
                </div>
            </div>
            
            <div class='row'>
                <div class='col-md-2'>
                    <div class='label'>Indentificação</div>
                    <h3>PAHO.0001.4</h3>
                </div>
                
                <div class='col-md-2'>
                    <div class='label'>Protocolo</div>
                    <h3>0011176</h3>
                </div>
                
                <div class='col-md-4'>
                    <div class='label'>Tipo de Protocolo</div>
                    <h3>Investigação</h3>
                </div>

                <div class='col-md-4'>
                    <div class='label'>Estado</div>
                    <h3>Em avaliação</h3>
                </div>
                
            </div>

            <div class='row'>
                <div class='col-md-5'>
                    <div class='label'>Instituição</div>
                    <h3>Organização Panamericana de Saúde</h3>
                </div>

                <div class='col-md-4'>
                    <div class='label'>País</div>
                    <h3>Brasil</h3>
                </div>

                <div class='col-md-3'>
                    <div class='label'>Resultado</div>
                    <h3>Isento</h3>
                </div>
            </div>
                
            <div class='row'>
                <div class='col-md-3'>
                    <div class='label'>Aceito em</div>
                    <h3>18/03/2016</h3>
                </div>
                
                <div class='col-md-3'>
                    <div class='label'>Atualizado em</div>
                    <h3>18/03/2016</h3>
                </div>
                
                <div class='col-md-3'>
                    <div class='label'>Revisão</div>
                    <h3>18/03/2016</h3>
                </div>

                <div class='col-md-3'>
                    <div class='label'>Decisão</div>
                    <h3>18/03/2016</h3>
                </div>

            </div>    

            <div class='row'>
                <div class='col-md-3'>
                    <div class='label'>Informado em</div>
                    <h3>18/03/2016</h3>
                </div>

                <div class='col-md-3'>
                    <div class='label'>Recrutamento</div>
                    <h3>18/03/2016</h3>
                </div>

                <div class='col-md-3'>
                    <div class='label'>Finalizado</div>
                    <h3>18/03/2016</h3>
                </div>

                <div class='col-md-3'>
                    <div class='label'>Monitoramento</div>
                    <h3>-</h3>
                </div>
            </div>
        </div>

        <!-- comunication -->
        <div class='block'>
            <div class='row'>
                <div class="col-md-12">
                    <h2 class='sub-header'>Comunicação:</h2>
                </div>
            </div>

            <div class='row'>
                <div class="col-md-12">
                    <table class='table table-hover table-condensed'>
                        
                        <thead>
                            <tr>
                                <th width="10%">Data e Hora</th>
                                <th width="10%">Autor</th>
                                <th>Comentário</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                         
                        <tbody>
                            <tr>
                               <th>20/05/2016 11:42</th>
                               <td>Proethos Admin</td> 
                               <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas consequat eros sed dictum consectetur. Cras sollicitudin laoreet nulla. Sed auctor tincidunt pharetra. Donec sed lectus iaculis, interdum ligula vitae, aliquam urna. Sed eget ornare eros. Cras aliquam blandit enim in aliquam. Phasellus id felis consequat, luctus felis in, hendrerit mi. Proin enim nibh, eleifend vel consectetur sed, auctor non libero. Aenean dapibus, ipsum ut dignissim eleifend, justo lectus interdum felis, in cursus purus ligula vel enim. Donec ullamcorper lorem orci, non fermentum felis ullamcorper vitae. Duis condimentum magna quis hendrerit scelerisque. Nulla ac dictum urna, ut consequat orci. Aliquam erat volutpat. Suspendisse potenti. Suspendisse sit amet aliquam libero. Nam at velit est.</td> 
                               <td>-</td> 
                            </tr>
                            <tr>
                               <th>20/05/2016 11:45</th>
                               <td>Other User</td> 
                               <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas consequat eros sed dictum consectetur. Cras sollicitudin laoreet nulla. Sed auctor tincidunt pharetra. Donec sed lectus iaculis, interdum ligula vitae, aliquam urna. Sed eget ornare eros. Cras aliquam blandit enim in aliquam. Phasellus id felis consequat, luctus felis in, hendrerit mi. Proin enim nibh, eleifend vel consectetur sed, auctor non libero. Aenean dapibus, ipsum ut dignissim eleifend, justo lectus interdum felis, in cursus purus ligula vel enim. Donec ullamcorper lorem orci, non fermentum felis ullamcorper vitae. Duis condimentum magna quis hendrerit scelerisque. Nulla ac dictum urna, ut consequat orci. Aliquam erat volutpat. Suspendisse potenti. Suspendisse sit amet aliquam libero. Nam at velit est.</td> 
                               <td><a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-trash'></i></a></td> 
                            </tr>
                        </tbody>
                    </table>

                    <a href='#' class='btn btn-default' data-toggle="modal" data-target="#modal-new-comment">Novo Comentário</a>
                </div>
            </div>
        </div>

        <!-- team -->
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

        <!-- uploads -->
        <div class='block'>
            <div class='row'>
                <div class="col-md-12">
                    <h2 class='sub-header'>Submissão de Documentos:</h2>
                </div>
            </div>

            <div class='row'>
                <div class="col-md-12">
                    <table class='table table-hover table-condensed'>
                        
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Nome</th>
                                <th>Tamanho</th>
                                <th>Data de Envio</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr>
                               <th>Questionário</th>
                               <td>1122-questionario-12.doc</td> 
                               <td>442 kBs</td> 
                               <td>22/04/2016</td> 
                               <td><a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-trash'></i></a></td> 
                            </tr>
                        </tbody>
                    </table>

                    <a href='#' class='btn btn-default' data-toggle="modal" data-target="#modal-new-file">Novo Anexo</a>
                </div>
            </div>
        </div>

        <!-- history -->
        <div class='block'>
            <div class='row'>
                <div class="col-md-12">
                    <h2 class='sub-header'>Histórico:</h2>
                </div>
            </div>

            <div class='row'>
                <div class="col-md-12">
                    <table class='table table-hover table-condensed'>
                        
                        <thead>
                            <tr>
                                <th>Data e Hora</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr>
                               <th>20/05/2016 11:42</th>
                               <td>Submissão inicial do documento</td> 
                            </tr>
                            <tr>
                               <th>20/05/2016 11:45</th>
                               <td>Alteração do campo Documentos</td> 
                            </tr>
                            <tr>
                               <th>20/05/2016 11:49</th>
                               <td>Aceite para validação de dados</td> 
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- comments -->
        <div class='block'>
            <div class='row'>
                <div class="col-md-12">
                    <h2 class='sub-header'>Comentários:</h2>
                </div>
            </div>

            <div class='row'>
                <div class="col-md-12">
                    <table class='table table-hover table-condensed'>
                        
                        <thead>
                            <tr>
                                <th width="10%">Data e Hora</th>
                                <th width="10%">Autor</th>
                                <th>Comentário</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                         
                        <tbody>
                            <tr>
                               <th>20/05/2016 11:42</th>
                               <td>Proethos Admin</td> 
                               <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas consequat eros sed dictum consectetur. Cras sollicitudin laoreet nulla. Sed auctor tincidunt pharetra. Donec sed lectus iaculis, interdum ligula vitae, aliquam urna. Sed eget ornare eros. Cras aliquam blandit enim in aliquam. Phasellus id felis consequat, luctus felis in, hendrerit mi. Proin enim nibh, eleifend vel consectetur sed, auctor non libero. Aenean dapibus, ipsum ut dignissim eleifend, justo lectus interdum felis, in cursus purus ligula vel enim. Donec ullamcorper lorem orci, non fermentum felis ullamcorper vitae. Duis condimentum magna quis hendrerit scelerisque. Nulla ac dictum urna, ut consequat orci. Aliquam erat volutpat. Suspendisse potenti. Suspendisse sit amet aliquam libero. Nam at velit est.</td> 
                               <td>-</td> 
                            </tr>
                            <tr>
                               <th>20/05/2016 11:45</th>
                               <td>Other User</td> 
                               <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas consequat eros sed dictum consectetur. Cras sollicitudin laoreet nulla. Sed auctor tincidunt pharetra. Donec sed lectus iaculis, interdum ligula vitae, aliquam urna. Sed eget ornare eros. Cras aliquam blandit enim in aliquam. Phasellus id felis consequat, luctus felis in, hendrerit mi. Proin enim nibh, eleifend vel consectetur sed, auctor non libero. Aenean dapibus, ipsum ut dignissim eleifend, justo lectus interdum felis, in cursus purus ligula vel enim. Donec ullamcorper lorem orci, non fermentum felis ullamcorper vitae. Duis condimentum magna quis hendrerit scelerisque. Nulla ac dictum urna, ut consequat orci. Aliquam erat volutpat. Suspendisse potenti. Suspendisse sit amet aliquam libero. Nam at velit est.</td> 
                               <td><a href='#' class='btn btn-default btn-xs'><i class='glyphicon glyphicon-trash'></i></a></td> 
                            </tr>
                        </tbody>
                    </table>

                    <a href='#' class='btn btn-default' data-toggle="modal" data-target="#modal-new-comment">Novo Comentário</a>
                </div>
            </div>
        </div>

        <!-- action -->
        <div class='block action'>
            <a name='action'></a>
            <div class='row'>
                <div class="col-md-12">
                    <h2 class='sub-header'>Ações:</h2>
                </div>
            </div>

            <div class='row'>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class='radio'>
                            <label for="">
                                <input type='radio' id='radio-rejeitar' name='radio-acao' /> Rejeitar Protocolo
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            </label> 
                        </div>
                    </div>
                    
                    <div class="form-group" id='rejeitar-group'>
                        <label for="">Motivo da Rejeição:</label> 
                        <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                        <textarea class="form-control"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class='radio'>
                            <label for="">
                                <input type='radio' id='radio-aceitar' name='radio-acao' /> Aceitar Protocolo
                                <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            </label> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='submission-button-controls col-md-12'>
                <a href='#' class='btn btn-danger' id='rejeitar-submit'>Salvar e Devolver</a>
                <a href='#' class='btn btn-primary' id='aceitar-comite-decide'>Salvar e enviar para comitê decidir</a>
                <a href='#' class='btn btn-primary' id='aceitar-requer-revisao'>Salvar e enviar para revisão ética</a>
            </div>
        </div>
    </div>

    <!-- Modal Novo Comentário -->
    <div class="modal fade" id="modal-new-comment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Novo Comentário</h4>
                </div>
                <div class="modal-body">
                    <form class='form'>
                        <div class="form-group">
                            <label for="">Comentário:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <textarea class='form-control' rows='4'></textarea>
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
        $("[id^=rejeitar]").hide();
        $("[id^=aceitar]").hide();
        
        $("#radio-rejeitar").change(function(){
            if ($(this).is(':checked')) {
                $("[id^=rejeitar]").show();
                $("[id^=aceitar]").hide();
            }
        });

        $("#radio-aceitar").change(function(){
            if ($(this).is(':checked')) {
                $("[id^=aceitar]").show();
                $("[id^=rejeitar]").hide();
            }
        });
    });
</script>
