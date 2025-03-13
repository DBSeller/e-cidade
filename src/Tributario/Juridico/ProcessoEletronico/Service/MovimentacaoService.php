<?php

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Service;

use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\ProcessoEletronico as ProcessoEletronicoRepository;

/**
 * Class MovimentacaoService
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Service
 */
class MovimentacaoService
{
    /**
     * @var \Instituicao $oInstituicao
     */
    private $oInstituicao;

    /**
     * @var $oProcessoEletronico
     */
    private $oProcessoEletronico;

    /**
     * @var CODIGO_VARA integer
     */
    const CODIGO_VARA = 36389;

    /**
     * MovimentacaoService constructor.
     * @param \Instituicao $oInstituicao
     * @param \ECidade\Tributario\Juridico\ProcessoEletronico\ProcessoEletronico $oProcessoEletronico
     */
    public function __construct(\Instituicao $oInstituicao, \ECidade\Tributario\Juridico\ProcessoEletronico\ProcessoEletronico $oProcessoEletronico)
    {
        $this->oInstituicao = $oInstituicao;
        $this->oProcessoEletronico = $oProcessoEletronico;
    }

    /**
     * @param \ECidade\Tributario\Juridico\ProcessoEletronico\Domain\RetornoRemessa $oRetornoRemessa
     * @return mixed
     * @throws \Exception
     */
    private function persistProcessoForo(\ECidade\Tributario\Juridico\ProcessoEletronico\Domain\RetornoRemessa $oRetornoRemessa)
    {
        //TODO
        $oProcessoForo = new \cl_processoforo;
        $oProcessoForo->v70_codforo = $oRetornoRemessa->getNumeroProcesso();
        $oProcessoForo->v70_processoforomov = "null";
        $oProcessoForo->v70_id_usuario = db_getsession("DB_id_usuario");
        $oProcessoForo->v70_vara = self::CODIGO_VARA;
        $oProcessoForo->v70_data = $oRetornoRemessa->getDataOperacao();
        $oProcessoForo->v70_valorinicial = $this->oProcessoEletronico->getInicial()->getValorAtualizadoAte(new \DateTime()); // tem que mudar
        $oProcessoForo->v70_observacao = 'PROTOCOLO TJ: ' . $oRetornoRemessa->getProtocoloRecebimento();
        $oProcessoForo->v70_anulado = 'false';
        $oProcessoForo->v70_instit = $this->oInstituicao->getCodigo();
        $oProcessoForo->v70_cartorio = $oRetornoRemessa->getCartorio();
        $oProcessoForo->incluir(null);

        if ($oProcessoForo->erro_status == "0") {
            throw new \Exception($oProcessoForo->erro_msg);
        }

        return $oProcessoForo->v70_sequencial;
    }

    /**
     * Persite ProcessoInicial
     *
     * @param $v70_sequencial
     * @param $inicial
     * @param $dataOperacao
     */
    private function persistProcessoInicial($v70_sequencial, $inicial, $dataOperacao)
    {

        //TODO
        $oProcessoForoInicial = new \cl_processoforoinicial;

        $oProcessoForoInicial->v71_id_usuario = db_getsession("DB_id_usuario");
        $oProcessoForoInicial->v71_inicial = $inicial;
        $oProcessoForoInicial->v71_processoforo = $v70_sequencial;
        $oProcessoForoInicial->v71_data = $dataOperacao;
        $oProcessoForoInicial->v71_anulado = 'false';
        $oProcessoForoInicial->incluir(null);

        if ($oProcessoForoInicial->erro_status == "0") {
            throw new \Exception($oProcessoForoInicial->erro_msg);
        }
    }

    /**
     * Incluir  a movimentação do processo
     *
     * @param \ECidade\Tributario\Juridico\ProcessoEletronico\Domain\RetornoRemessa $oRetornoRemessa
     */
    public function salvaMovimentacao(\ECidade\Tributario\Juridico\ProcessoEletronico\Domain\RetornoRemessa $oRetornoRemessa , $inicial)
    {

        //TODO
        $movimentacao = new \ECidade\Tributario\Juridico\ProcessoEletronico\Domain\Movimentacao();
        $movimentacao->setData(new \DateTime($oRetornoRemessa->getDataOperacao()));
        $movimentacao->setProtocolo($oRetornoRemessa->getProtocoloRecebimento());
        $movimentacao->setTexto($oRetornoRemessa->getMensagem());

        ProcessoEletronicoRepository::persistirMovimentacoes($this->oProcessoEletronico, $movimentacao);

        $this->oProcessoEletronico->setSituacao(\ECidade\Tributario\Juridico\ProcessoEletronico\Integracao::SITUACAO_RETORNO_ERRO);

        if ($oRetornoRemessa->getStatus() == true) {
            $this->oProcessoEletronico->setRecibo(base64_encode($oRetornoRemessa->getRecibo()));
            $this->oProcessoEletronico->setSituacao(\ECidade\Tributario\Juridico\ProcessoEletronico\Integracao::SITUACAO_COM_RECIBO);
            $codigo_foro = $this->persistProcessoForo($oRetornoRemessa);
            $this->persistProcessoInicial($codigo_foro, $inicial, $oRetornoRemessa->getDataOperacao());
        }

        ProcessoEletronicoRepository::persist($this->oProcessoEletronico);

    }
}
