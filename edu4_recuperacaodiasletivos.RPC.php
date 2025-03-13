<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

use ECidade\Educacao\Escola\Model\RecuperacaoDiasLetivos as RecuperacaoDiasLetivosModel;
use ECidade\Educacao\Escola\Repository\RecuperacaoDiasLetivos as RecuperacaoDiasLetivosRepository;

$post = db_utils::postMemory($_REQUEST);
$post->json = str_replace("\\", "", $post->json);
$parametro = JSON::create()->parse($post->json);
$retorno = (object)array('erro' => false, 'mensagem' => '');

$recuperacaoDiasLetivosRepository = RecuperacaoDiasLetivosRepository::getInstance();
try {
    db_inicio_transacao();

    switch ($parametro->executa) {
        case 'salvar':
            if ($parametro->turno == null) {
                throw new \ParameterException('Turno não informado.');
            }

            if ($parametro->periodos == null) {
                throw new \ParameterException('Nenhum período informado.');
            }

            if ($parametro->rechumano == null) {
                throw new \ParameterException('Regente não informado.');
            }

            if ($parametro->data == null) {
                throw new \ParameterException('Data não informada.');
            }

            if ($parametro->regencia == null) {
                throw new \ParameterException('Disciplina não informada.');
            }

            $recuperacaoDiasLetivosModel = new RecuperacaoDiasLetivosModel();
            $recuperacaoDiasLetivosModel->setTurno(TurnoRepository::getTurnoByCodigo($parametro->turno));
            $recuperacaoDiasLetivosModel->setRegencia(RegenciaRepository::getRegenciaByCodigo($parametro->regencia));
            $recuperacaoDiasLetivosModel->setRechumano($parametro->rechumano);
            $recuperacaoDiasLetivosModel->setData(new \DBDate($parametro->data));

            foreach ($parametro->periodos as $periodo) {
                $recuperacaoDiasLetivosModel->adicionarPeriodo(PeriodoEscolaRepository::getByCodigo($periodo));
            }

            $recuperacaoDiasLetivosRepository->salvar($recuperacaoDiasLetivosModel);

            $retorno->mensagem = "Períodos salvos com sucesso.";

            break;

        case 'buscarRegistros':
            if (empty($parametro->turma)) {
                throw new \ParameterException('Turma não informada.');
            }

            if (empty($parametro->etapa)) {
                throw new \ParameterException('Etapa não informada.');
            }

            $retorno->registros = $recuperacaoDiasLetivosRepository->getRecuperacaoDiasLetivosPorTurma(
              TurmaRepository::getTurmaByCodigo($parametro->turma),
              EtapaRepository::getEtapaByCodigo($parametro->etapa)
            );

            break;

        case 'excluir':
            if (empty($parametro->regenciasHorario)) {
                throw new ParameterException('Períodos a serem excluídos não informados.');
            }

            $recuperacaoDiasLetivosRepository->excluir($parametro->regenciasHorario);

            $retorno->mensagem = "Períodos excluídos com sucesso.";

            break;

        case 'buscarDataFeriadoLetivoPorCalendario':
            if (empty($parametro->calendario)) {
                throw new ParameterException('Calendário não informado.');
            }

            $retorno->datas = $recuperacaoDiasLetivosRepository->buscarDataFeriadoLetivoPorCalendario($parametro->calendario);
            break;

    }

    db_fim_transacao();
} catch (Exception $erro) {
    db_fim_transacao(true);

    $retorno->erro = true;
    $retorno->mensagem = $erro->getMessage();
}

echo JSON::create()->stringify($retorno);
