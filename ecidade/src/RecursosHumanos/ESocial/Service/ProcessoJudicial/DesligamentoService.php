<?php
/*
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

namespace ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Desligamento;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\DesligamentoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\EstatutarioRepository;

use Exception;
use stdClass;
use DBDate;

class DesligamentoService
{
    /**
     * @var
     */
    private $desligamentoRepository;

    /**
     * unicidadeService constructor.
    */
    public function __construct()
    {
        $this->desligamentoRepository = new DesligamentoRepository();
    }

    /**
     * @param Desligamento
     * @return Desligamento
     * @throws Exception
     */
    public function salvar(Desligamento $desligamento)
    {
        $complemento = 'em Informações do desligamento.';

        $sequencialProcessoVinculo = $desligamento->getSequencialprocessovinculo();
        $nomeServidor = $desligamento->getNomeServidor();
        $matriculaServidor = $desligamento->getMatriculaServidor();

        $estatutarioRepository = new EstatutarioRepository();
        $estatutario = $estatutarioRepository
            ->scopeSequencialVinculo($sequencialProcessoVinculo)
            ->get();

        $grupoObrigatorio = false;
        if (!empty($estatutario)) {
            if (!empty($estatutario[0]->getTipoInscricao()) ||
                !empty($estatutario[0]->getInscricao() ||
                !empty($estatutario[0]->getDataTransferencia()))) {
                    $grupoObrigatorio = true;
            }
        }

        $validar = false;
        if ($grupoObrigatorio &&
            (!empty($desligamento->getDataDesligamento()) ||
            !empty($desligamento->getMotivoDesligamento()))) {
                $validar = true;
        }

        if ($validar && empty($desligamento->getDataDesligamento())) {
            throw new Exception("É necessário informar a 'Data de desligamento'. " .
                $complemento .
                "Servidor <strong>{$matriculaServidor}-{$nomeServidor}</strong>. " .
                "Favor revisar.");
        }

        if ($validar && empty($desligamento->getMotivoDesligamento())) {
            throw new Exception("É necessário informar a 'Código de motivo do desligamento' " .
                $complemento .
                "Servidor <strong>{$matriculaServidor}-{$nomeServidor}</strong>. " .
                "Favor revisar.");
        }

        if (!empty($desligamento->getPensaoAlimenticia()) || !empty($desligamento->getValorPensao())) {
            if (empty($desligamento->getPensaoAlimenticia())) {
                throw new Exception("É necessário informar o " .
                    "<strong>'Indicativo de pensão alimentícia para fins de retenção de FGTS'</strong> '" .
                    $complemento .
                    "Servidor <strong>{$matriculaServidor}-{$nomeServidor}</strong>. " .
                    "Favor revisar.");
            }
            if (empty($desligamento->getValorPensao())) {
                throw new Exception("É necessário informar o " .
                    "<strong>'Valor da pensão alimentícia'</strong> '" .
                    $complemento .
                    "Servidor <strong>{$matriculaServidor}-{$nomeServidor}</strong>. " .
                    "Favor revisar.");
            }
        }

        if ($desligamento->getTipoRegimeTrabalho() == 1) {
            $dataSetencaAcodo = \DateTime::createFromFormat('Y-m-d', $desligamento->getDataSentencaAcordo());
            $dataExibicao =  date('d/m/Y', strtotime($desligamento->getDataSentencaAcordo()));
            $dataeSocial = \DateTime::createFromFormat('Y-m-d', '2024-01-22');
            if ($dataSetencaAcodo > $dataeSocial) {
                throw new Exception("É necessário informar o " .
                    "<strong>'Indicativo de pensão alimentícia para fins de retenção de FGTS'</strong> '." .
                    "O 'Tipo de Regime do Trabalho' é igual a 1 (CLT) e a data de sentança/acordo " .
                    $dataExibicao . " é maior que 22/01/2024 " .
                    $complemento .
                    "Servidor <strong>{$matriculaServidor}-{$nomeServidor}</strong>. " .
                    "Favor revisar.");
            }
        }

        return $this->desligamentoRepository->save($desligamento);
    }

    /**
     * @param Desligamento
     * @return Desligamento
     * @throws Exception
     */
    public function excluir(Desligamento $desligamento)
    {
        return $this->desligamentoRepository->delete($desligamento);
    }
}
