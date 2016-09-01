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
                            <h2 class='sub-header'>Verificação:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <table class='table table-hover table-condensed table-bordered'>
                                
                                <thead>
                                    <tr>
                                        <th class='center'>Descrição do campo</th>
                                        <th class='center'>Verificação</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                        <td>Equipe de pesquisa (2 Membros)</td>
                                        <td class='center'><i class='glyphicon glyphicon-ok'></i></td>
                                    </tr>
                                    <tr>
                                        <td>files_submited (1 Arquivos)</td>
                                        <td class='center'><i class='glyphicon glyphicon-ok'></i></td>
                                    </tr>
                                    <tr>
                                        <td>Resumo</td>
                                        <td class='center'><i class='glyphicon glyphicon-ok'></i></td>
                                    </tr>
                                    <tr>
                                        <td>palavras-chave</td>
                                        <td class='center'><i class='glyphicon glyphicon-ok'></i></td>
                                    </tr>
                                    <tr>
                                        <td>Introdução</td>
                                        <td class='center'><i class='glyphicon glyphicon-ok'></i></td>
                                    </tr>
                                    <tr>
                                        <td>justificativa</td>
                                        <td class='center'><i class='glyphicon glyphicon-ok'></i></td>
                                    </tr>
                                    <tr>
                                        <td>Objetivos</td>
                                        <td class='center'><i class='glyphicon glyphicon-ok'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>desenho do estudo</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Gênero</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Idade mínima</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Idade máxima</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Critérios de inclusão</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Critérios de exclusão</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Data prevista para o primeiro recrutamento
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Intervenções</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Resultados preliminares</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Origem do financiamento</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Patrocinador principal</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Referências</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>scientific_contact</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                    <tr class='error'>
                                        <td>Aprovação no local de origem</td>
                                        <td class='center'><i class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class='block'>
                    <div class='row'>
                        <div class="col-md-12">
                            <h2 class='sub-header'>Termos de Utilização:</h2>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type='checkbox'>Aceito os termos de utilização.</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='row'>
                    <div class='submission-button-controls col-md-12'>
                        <a href='nova-submissao-3.php' class='btn btn-primary'>Salvar e Finalizar</a>
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
