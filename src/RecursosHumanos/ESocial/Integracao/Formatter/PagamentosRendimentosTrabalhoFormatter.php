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

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Service\PagamentosRendimentosTrabalhoService;
use ECidade\RecursosHumanos\ESocial\Repository\PagamentosRendimentosTrabalho as PagamentosRendTrabalhoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ESocialRubricasRepository;
use ECidade\RecursosHumanos\Pessoal\Service\DataPagamentoFolhaService;
use InstituicaoRepository;
use stdClass;
use DBCompetencia;
use DBDate;

class PagamentosRendimentosTrabalhoFormatter extends Formatter
{
    /**
     * @var PagamentosRendimentosTrabalhoService
     */
    private $pagamentosService;
    /**
     * @var int
     */
    private $anoCompetencia;
    /**
     * @var int
     */
    private $mesCompetencia;
    /**
     * @var string
     */
    private $inscricaoEmpregador;
    /**
     * @var string
     */
    private $rubricaPensaoAlimenticia;
    /**
     *
     */
    private $rubricas;

    /**
     * @var string
     */
    private $perApur;

    private $isDecimoTerceiro = false;

    /**
     * @var [Servidor]
     */
    private $servidores = [];

    /**
     * @return mixed
     */
    public function getServidores()
    {
        return $this->servidores;
    }

    /**
     * @param mixed $servidores
     */
    public function setServidores($servidores)
    {
        $this->servidores = $servidores;
    }

    private $cgmNaoIncluidos = [];

    /**
     * @param array $dados
     * @return array|stdClass[]
     * @throws \DBException
     */
    public function formatar($dados)
    {
        $dados = (object)$dados;

        $this->inscricaoEmpregador = $dados->inscricao_empregador;
        $this->anoCompetencia = $dados->anoCompetencia;
        $this->mesCompetencia = $dados->mesCompetencia;
        $this->rubricaPensaoAlimenticia = PagamentosRendTrabalhoRepository::buscarParametroRubricaPensaoAlimenticia(
            new DBCompetencia(
                $this->anoCompetencia,
                $this->mesCompetencia
            )
        );

        $rubricasEsocialRepository = new ESocialRubricasRepository();
        $this->rubricas = $rubricasEsocialRepository->validarRubricas('1210');

        $dadosFormatados = array();
        foreach ($dados->cgms as $indice => $cgm) {
            if ($cgm->cgm instanceof \CgmFisico) {
                $dadoFormatado = $this->dadosECidade($cgm);
                if (!empty($dadoFormatado)) {
                    $dadosFormatados[] = $dadoFormatado;
                } else {
                    $this->cgmNaoIncluidos[] = $cgm;
                }
            } else {
                $this->cgmNaoIncluidos[] = $cgm;
            }
        }
        return $dadosFormatados;
    }

    /**
     * Busca os dados de pagamentos do CGM e retornamos formatados.
     * @param $cgm
     * @return stdClass|null
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    private function dadosECidade($dado)
    {
        $this->perApur = "{$this->anoCompetencia}-{$this->mesCompetencia}";
        $this->pagamentosService = new PagamentosRendimentosTrabalhoService();
        $this->pagamentosService->setAnoCompetencia($this->anoCompetencia);
        $this->pagamentosService->setMesCompetencia($this->mesCompetencia);
        $this->pagamentosService->setRubricasValidas($this->rubricas);

        if ($this->isDecimoTerceiro) {
            $this->pagamentosService->setDecimoTerceiro();
        }
        $pagamentosRendimentosTrabalho = $this->pagamentosService->buscarPorCGM(
            $dado->cgm,
            $dado->evento,
            $this->servidores
        );
        if (empty($pagamentosRendimentosTrabalho)) {
            return null;
        }

        $dadoFormatado = new stdClass();
        $dadoFormatado->referencia = "{$dado->cgm->getCodigo()}_"
            . "{$this->pagamentosService->getAnoCompetencia()}{$this->pagamentosService->getMesCompetencia()}";
        $dadoFormatado->inscricao_empregador = $this->inscricaoEmpregador;
        $dadoFormatado->ideBenef = new stdClass();
        $dadoFormatado->ideBenef->cpfBenef = $pagamentosRendimentosTrabalho->getCPFBeneficiente();
        $dadoFormatado->ideBenef->infoPgto = array_values($pagamentosRendimentosTrabalho->getPagamentos());
        $dadoFormatado->perApur = $this->geraPerApur();
        return $dadoFormatado;
    }

    public function setDecimoTerceiro()
    {
        $this->isDecimoTerceiro = true;
    }

    public function getCgmsNaoEnviados()
    {
        return $this->cgmNaoIncluidos;
    }

    public function setTipoDataPagamento($tipoDataPagamento)
    {
        $this->tipoDataPagamento = $tipoDataPagamento;
    }

    private function geraPerApur()
    {
        $dataPagamentoService = new DataPagamentoFolhaService();
        $dataPagamentoService->setAnoCompetencia($this->anoCompetencia);
        $dataPagamentoService->setMesCompetencia($this->mesCompetencia);
        $stdParametros = new stdClass();
        $stdParametros->instituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();

        $dataPagamentos = $dataPagamentoService->buscarDataPagamentoInstituicaoCompetencia($stdParametros);
        return $dataPagamentos[0]->getDataPagamento()->getAno() . "-"
            . $dataPagamentos[0]->getDataPagamento()->getMes();
    }
}
