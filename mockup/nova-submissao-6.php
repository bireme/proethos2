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

    <!-- Modal Novo Investigador -->
    <div class="modal fade" id="modal-new-file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Novo Anexo</h4>
                </div>
                <div class="modal-body">
                    <form class='form'>
                        <div class="form-group">
                            <label for="">Tipo:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <select class='form-control selectpicker' data-live-search="true">
                                <option></option>
                                <option value="auto">Autorización institucional</option><option value="CIENT">Aval científico</option><option value="BIBLI">Bibliografía</option><option value="TCLE">Consentimiento informado</option><option value="CUES">Cuestionario</option><option value="DIC">Dictamen</option><option value="DICT">Dictamen emitido por otro comité</option><option value="Asent">Documento de asentimiento</option><option value="ENC">Encuesta</option><option value="DISPE">Justificación de dispensa del consentimiento</option><option value="reclu">Material de reclutamiento / información al partic</option><option value="OUTHE">Otros</option><option value="SEGU">Póliza de seguro</option><option value="DICTA">Presupuesto</option><option value="PROJ">Protocolo</option><option value="PRANI">Protocolo para animales</option><option value="REEAD">Reporte de eventos adversos</option><option value="RESUM">Resumen ejecutivo</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="">Arquivo:</label> 
                            <a href='#' data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>
                            <input type='file' class='form-control'>
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
