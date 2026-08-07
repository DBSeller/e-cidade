<?php

use ECidade\Educacao\Escola\Resource\ComponenteCurricularResource;
use ECidade\Educacao\Secretaria\BNCC\Model\DisciplinaEquivalente;
use ECidade\Educacao\Secretaria\BNCC\Registry\DisciplinaRegistry;
use ECidade\Educacao\Secretaria\BNCC\Registry\EtapaRegistry;
use ECidade\Educacao\Secretaria\BNCC\Resource\DisciplinaResource;
use ECidade\Educacao\Secretaria\BNCC\Resource\EtapaResource as EtapaResourceBNCC;
use ECidade\Educacao\Secretaria\BNCC\Service\EquivalenciaDisciplinasService;
use ECidade\Educacao\Secretaria\BNCC\Service\EquivalenciaEtapasService;
use ECidade\Enum\Educacao\BNCC\EnsinoEnum;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];
try {
    db_inicio_transacao();
    switch ($parametros->acao) {
        case 'buscarEtapasBNCC':
            if (empty($parametros->ensino)) {
                throw new Exception("Informe o ensino da BNCC.");
            }
            $ensinoEnum = new EnsinoEnum($parametros->ensino);
            $service = new EquivalenciaEtapasService();
            $retorno->etapasBNCC = EtapaResourceBNCC::toArray($service->getEtapasBNCC($ensinoEnum));
            break;
        case 'buscarEtapasEcidade':
            if (empty($parametros->ensino)) {
                throw new Exception("Informe o Ensino da BNCC.");
            }
            if (empty($parametros->codigoEtapa)) {
                throw new Exception("Informe uma Etapa da BNCC.");
            }

            $ensinoEnum = new EnsinoEnum($parametros->ensino);
            $service = new EquivalenciaEtapasService();
            $retorno->ensinos = array_values(
                $service->getEtapasEquivalente($ensinoEnum, EtapaRegistry::get($parametros->codigoEtapa))
            );
            break;
        case 'salvarDeParaEtapas':
            if (empty($parametros->etapa_bncc)) {
                throw new Exception("Informe uma Etapa da BNCC.");
            }
            if (empty($parametros->etapas)) {
                throw new Exception("Selecione as etapas equivalentes.");
            }

            $etapa = EtapaRegistry::get($parametros->etapa_bncc);
            $service = new EquivalenciaEtapasService();
            $service->salvarEquivalencia($etapa, $parametros->etapas);
            $retorno->mensagem = "Equivalência salva com sucesso.";
            break;
        case 'buscarDisciplinas':
            $service = new EquivalenciaDisciplinasService();
            $retorno->disciplinasBncc = DisciplinaResource::toArray($service->getDisciplinasBNCC());
            $retorno->disciplinasEcidade = ComponenteCurricularResource::toArray($service->getDisciplinasEcidade());
            break;

        case 'buscarEquivalentes':
            if (empty($parametros->disciplina)) {
                throw new Exception("Informe uma disciplina da BNCC.");
            }

            $disciplina = DisciplinaRegistry::get($parametros->disciplina);
            $service = new EquivalenciaDisciplinasService();
            $equivalencias = $service->equivalenciasDisciplinaBNCC($disciplina);

            $retorno->equivalencias = array_map(function (DisciplinaEquivalente $disciplinaEquivalente) {
                return $disciplinaEquivalente->getDisciplinaEcidade()->getCodigo();
            }, $equivalencias);
            break;

        case 'salvarDeParaDisciplinas':
            if (empty($parametros->disciplina_bncc)) {
                throw new Exception("Informe a Disciplina da BNCC");
            }
            if (empty($parametros->disciplinas)) {
                throw new Exception("Informe as Disciplinas cadastradas no E-cidade");
            }

            $disciplina = DisciplinaRegistry::get($parametros->disciplina_bncc);
            $componentes = array_map(function ($codigo) {
                return \ECidade\Educacao\Escola\Registry\ComponenteCurricularRegistry::get($codigo);
            }, $parametros->disciplinas);

            $service = new EquivalenciaDisciplinasService();
            $service->salvarEquivalencia($disciplina, $componentes);
            $retorno->mensagem = "Equivalência salva com sucesso.";
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
