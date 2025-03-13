<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));

use ECidade\Educacao\Escola\Registry\BaseCurricularRegistry;
use ECidade\Educacao\Escola\Repository\BaseCurricularDisciplinaRepository;
use ECidade\Educacao\Escola\Repository\BaseCurricularRepository;
use ECidade\Educacao\Escola\Resource\BaseCurricularResource;
use ECidade\Educacao\Escola\Resource\BaseDisciplinaResource;
use ECidade\Educacao\Escola\Resource\ProcedimentoAvaliacao\ProcedimentoResource;
use ECidade\Educacao\Escola\Service\BaseCurricularService;


$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];
try {
    db_inicio_transacao();
    switch ($parametros->acao) {
        case 'buscarBases':
            $codigoEscola = db_getsession('DB_coddepto');
            $baseCurricular = new BaseCurricularRepository;
            $baseCurricular->scopeEscola(new Escola($codigoEscola));
            $retorno->bases = BaseCurricularResource::toArray($baseCurricular->get());
            break;

        case 'buscarProcedimentos':
            $codigoEscola = db_getsession('DB_coddepto');
            $procedimentos = ProcedimentoAvaliacaoRepository::getProcedimentoEscola(new Escola($codigoEscola));
            $retorno->procedimentos = ProcedimentoResource::toArray($procedimentos);
            break;

        case 'buscarEtapas':
            $codigoBase = $parametros->codigoBase;
            $etapas = EtapaRepository::getEtapasBase($codigoBase);
            $retorno->etapas = $etapas;
            break;

        case 'buscarDisciplinas':
            $base = BaseCurricularRegistry::get($parametros->codigoBase);
            $etapa = EtapaRepository::getEtapaByCodigo($parametros->codigoEtapa);

            $baseCurricularService = new BaseCurricularService();
            $disciplinas = $baseCurricularService->getDisciplinasEtapa($base, $etapa);

            $retorno->disciplinas = BaseDisciplinaResource::toArray($disciplinas);
            break;

        case 'atualizarProcedimentoDisciplina':
            $disciplinas = JSON::create()->parse($parametros->disciplinas);
            foreach ($disciplinas as $disciplina) {
                $baseCurricularDisciplinaRepository = new BaseCurricularDisciplinaRepository;
                $baseCurricularDisciplina = $baseCurricularDisciplinaRepository::find($disciplina->codigo);
                $procedimento = null;
                if (!empty($disciplina->procedimentoEscolhido)) {
                    $procedimento = ProcedimentoAvaliacaoRepository::getProcedimentoByCodigo(
                        $disciplina->procedimentoEscolhido
                    );
                }
                $baseCurricularDisciplina->setProcedimento($procedimento);
                $baseCurricularDisciplinaRepository->salvar($baseCurricularDisciplina);
            }
            $retorno->mensagem = "Disciplinas salvas com sucesso.";
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
