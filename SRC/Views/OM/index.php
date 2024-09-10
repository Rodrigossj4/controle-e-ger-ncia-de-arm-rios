    <?php

    /** @var Marinha\Mvc\Models\OM[] $TipoOmList */
    ?>
    <?php require_once __DIR__ . "../../topo.php" ?>
    <div class="container">
        <div class="container">
            <div class="bg-body-tertiary rounded-3 row">
                <div class="col-md-8 order-md-1" id="modCadOM">
                    <div class="tituloModulo">Cadastro de OM</div>
                    <form method="post" id="formCadOM" action="/cadastrarOM">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="col-form-label" for="codOM">Código OM: </label>
                                <input class="form-control form-control-sm" type="text" name="codOM" id="codOM">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="col-form-label" for="sigla">Sigla: </label>
                                <input class="form-control form-control-sm" type="text" name="sigla" id="sigla">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="col-form-label" for="nomeOM">Nome OM: </label>
                                <input class="form-control form-control-sm" type="text" name="nomeOM" id="nomeOM">
                            </div>
                            <br>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <input type="button" id="btnCadOM" value="Cadastrar" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                    <span class="alerta"></span>
                </div>
            </div>
            <hr class="mb-4">
        </div>


        <div class="container">
            <div class="bg-body-tertiary rounded-3 row">
                <div class="col-md-8 order-md-1 form-control-padronizado">
                    <h3>Gerenciamento de OM</h3>
                    <div class="container">
                        <div class="row">
                            <table class="table table-striped" id="listPaginas">
                                <thead>
                                    <tr>
                                        <th scope="col">Código</th>
                                        <th scope="col">Sigla</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col" colspan="2" style="text-align: center;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id='gradeListaOM'>
                                    <?php foreach ($OmList  as $om) : ?>
                                        <tr>
                                            <td><?= $om['CodOM']; ?></td>
                                            <td><?= $om['NomeAbreviado']; ?></td>
                                            <td><?= $om['NomOM']; ?></td>
                                            <td>
                                                <button class="btn btn-warning btnAlterarOm" data-bs-toggle="modal" data-bs-target="#AlteraOm" data-codom="<?= $om['CodOM']; ?>" data-sigla="<?= $om['NomeAbreviado']; ?>" data-nomeom="<?= $om['NomOM']; ?>">Editar</button>
                                            </td>
                                            <td>
                                                <form method="post" id="formExcluirOm<?= $om['CodOM']; ?>" action="">
                                                    <input type="hidden" id="CodOMExcluir" name="CodOMExcluir" value="<?= $om['CodOM']; ?>">
                                                    <button class="btn btn-danger excluirOm" data-bs-toggle="modal" data-bs-target="#modexcluirOm" data-codom="<?= $om['CodOM']; ?>" type="button">Excluir</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="modal fade" id="AlterarOm" tabindex="-1" aria-labelledby="AlterarOm" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alterar OM</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formAlterarOm" action="/alterarOM">
                        <div class="form-group">
                            <input type="hidden" name="codOMAlter" id="codOMAlter">
                            <label class="col-form-label">Sigla: </label>
                            <input type="text" class="form-control form-control-sm" name="siglaAlter" id="siglaAlter">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Nome OM: </label>
                            <input type="text" class="form-control form-control-sm" name="nomeOMAlter" id="nomeOMAlter">
                        </div>

                        <br>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <input type="button" class="btn btn-primary" id="exibConfirmaAlteracaoOm" value="Alterar">
                            </div>
                        </div>
                        <div class="form-group row opcoesConfirmacao">
                            <hr class="linha-alert">
                            <span>Deseja realmente alterar essas informações?</span>
                            <div class="col-sm-3">
                                <input type="button" id="btnConfirmaAlteracaoOm" value="Sim" class="btn btn-success">
                            </div>
                            <div class="col-sm-3">
                                <input type="button" id="btnNaoConfirmaAlteracaoOm" value="Não" class="btn btn-danger">
                            </div>
                        </div>
                    </form>
                    <span class="alerta"></span>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modexcluirOm" tabindex="-1" aria-labelledby="modexcluirOm" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <span>Deseja realmente excluir essa OM?</span>
                        <div class="col-sm-3">
                            <form id="formExcluirOm">
                                <input type="hidden" name="codOMExcluir" id="codOMExcluir">
                            </form>
                            <input type="button" id="btnConfirmaExcluirOn" data-id="" value="Sim" class="btn btn-success btnConfirmaExcluirOm">
                        </div>
                        <div class="col-sm-3">
                            <input type="button" id="btnNaoConfirmaExcluirOm" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaExcluirOm">
                        </div>
                    </div>
                    <span class="alerta"></span>
                </div>
            </div>
        </div>
    </div>

    </div>
    </body>

    </html>

    <?php require_once __DIR__ . "../../rodape.php" ?>