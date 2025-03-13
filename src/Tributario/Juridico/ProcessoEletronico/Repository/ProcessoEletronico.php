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

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Repository;

use ECidade\Tributario\Juridico\Inicial\Repository\Inicial;
use ECidade\Tributario\Juridico\ProcessoEletronico\Documento;
use ECidade\Tributario\Juridico\ProcessoEletronico\Domain\Movimentacao;
use ECidade\Tributario\Juridico\ProcessoEletronico\ProcessoEletronico as ProcessoEletronicoModel;

/**
 * Class ProcessoEletronico
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Repository
 */
class ProcessoEletronico extends \BaseClassRepository
{

    /**
     * @var ProcessoEletronico
     */
    protected static $oInstance;

    /**
     * Persiste os dados da inicial
     * @param ProcessoEletronicoModel $processoEletronico
     * @throws \BusinessException
     */
    public static function persist(ProcessoEletronicoModel $processoEletronico)
    {
        $daoProcessoEletronico = new \cl_integracaoprocessoeletronico;

        $daoProcessoEletronico->v38_inicial = $processoEletronico->getInicial()->getCodigo();
        $daoProcessoEletronico->v38_parte = $processoEletronico->getParte()->getCodigo();
        $daoProcessoEletronico->v38_situacao = $processoEletronico->getSituacao();
        $daoProcessoEletronico->v38_datacalculo = $processoEletronico->getDataCalculo()->format('Y-m-d');
        $daoProcessoEletronico->v38_recibo = $processoEletronico->getRecibo() == '' ? 'null' : $processoEletronico->getRecibo();
        $daoProcessoEletronico->v38_sequencial = $processoEletronico->getCodigo();
        if ($processoEletronico->getCodigo() == '') {

            $daoProcessoEletronico->incluir(null);
            $processoEletronico->setCodigo($daoProcessoEletronico->v38_sequencial);
            $instance = self::getInstance();
            $instance->add($processoEletronico);
        } else {
            $daoProcessoEletronico->alterar($processoEletronico->getCodigo());
        }
        if ($daoProcessoEletronico->erro_status == 0) {

            $mensagemErro = "Não foi possível salvar dados do processo eletrônico.Erro encontrado:\n";
            $mensagemErro .= $daoProcessoEletronico->erro_msg;
            throw new \BusinessException($mensagemErro);
        }

    }

    /**
     * Persiste as movimentacoes de um processo eletrônico
     * @throws \BusinessException
     */
    public static function persistirMovimentacoes(
        ProcessoEletronicoModel $processoEletronico,
        Movimentacao $movimentacao
    ) {

        $daoProcessoEletronicoMovimentacoes = new \cl_integracaoprocessoeletronicomovimentacao();
        $daoProcessoEletronicoMovimentacoes->v39_integracaoprocessoeletronico = $processoEletronico->getCodigo();
        $daoProcessoEletronicoMovimentacoes->v39_protocolo = $movimentacao->getProtocolo();
        $daoProcessoEletronicoMovimentacoes->v39_dataenvio = $movimentacao->getData()->format("Y-m-d");
        $daoProcessoEletronicoMovimentacoes->v39_retorno = $movimentacao->getTexto();
        $daoProcessoEletronicoMovimentacoes->incluir(null);
        if ($daoProcessoEletronicoMovimentacoes->erro_status == 0) {

            $mensagemErro = "Não foi possível salvar dados do processo eletrônico.Erro encontrado:\n";
            $mensagemErro .= $daoProcessoEletronicoMovimentacoes->erro_msg;
            throw new \BusinessException($mensagemErro);
        }

        $processoEletronico->adicionarMovimentacoes($movimentacao);

    }


    /**
     * @param ProcessoEletronicoModel $processoEletronico
     * @param Documento[] $documentos
     * @throws \BusinessException
     */
    public static function persistirDocumentos(ProcessoEletronicoModel $processoEletronico, array $documentos)
    {
        $daoProcessoEletronicoDocumento = new \cl_integracaoprocessoeletronicoarquivo();
        foreach ($documentos as $documento) {

            $daoProcessoEletronicoDocumento->v40_sequencial = null;
            $daoProcessoEletronicoDocumento->v40_integracaoprocessoeletronico = $processoEletronico->getCodigo();
            $daoProcessoEletronicoDocumento->v40_data = $documento->getData()->format('Y-m-d');
            $daoProcessoEletronicoDocumento->v40_arquivo = $documento->getConteudo();
            $daoProcessoEletronicoDocumento->v40_tipo = $documento->getTipo();
            $daoProcessoEletronicoDocumento->v40_nome = $documento->getNome();
            $daoProcessoEletronicoDocumento->incluir(null);
            if ($daoProcessoEletronicoDocumento->erro_status == 0) {
                throw new \BusinessException("Erro ao salvar dados do documento {$documento->getNome()} no processo eletronico da inicial {$processoEletronico->getInicial()->getCodigo()}.\n{$daoProcessoEletronicoDocumento->erro_msg} ");
            }
        }
    }

    /**
     * @param $processo
     * @return ProcessoEletronicoModel
     * @throws \Exception
     */
    protected function make($processo)
    {

        $inicialRepository = Inicial::getInstance();
        $inicialRepository->setReturnFullItem(true);

        $processoEletronico = new ProcessoEletronicoModel();
        $processoEletronico->setCodigo($processo->v38_sequencial);
        $processoEletronico->setSituacao($processo->v38_situacao);
        $processoEletronico->setRecibo($processo->v38_recibo);
        $processoEletronico->setDataCalculo(new \DateTime($processo->v38_datacalculo));
        $processoEletronico->setInicial($inicialRepository->getByCode($processo->v38_inicial));
        $processoEletronico->setParte(\CgmRepository::getByCodigo($processo->v38_parte));
        return $processoEletronico;
    }

    /**
     * @return ProcessoEletronico
     */
    public static function getInstance()
    {
        return parent::getInstance();

    }

    /**
     * @param $codigo
     * @return ProcessoEletronicoModel|bool
     * @throws \DBException
     * @return ProcessoEletronicoModel
     */
    public static function getByCodigo($codigo)
    {

        $daoIntegracaoProcessoEletronico = new \cl_integracaoprocessoeletronico();
        $dadosProcesso = $daoIntegracaoProcessoEletronico->findBydId($codigo);
        if (empty($dadosProcesso)) {
            return false;
        }

        return self::getInstance()->make($dadosProcesso);
    }

    public static function getListaProcessos($listProcessos)
    {

        $daoIntegracaoProcessoEletronico = new \cl_integracaoprocessoeletronico();
        $dadosProcesso = $daoIntegracaoProcessoEletronico->findAllByIds($listProcessos);
        
       
        if (empty($dadosProcesso)) {
            return false;
        }

        $list = array();   
        foreach ($dadosProcesso as $value) {
           $obj   =  (object) $value;
           $list[] =  self::getInstance()->make($obj);
        }

        return  $list;

    }

    public static function getByLista($lista)
    {

    }

    /**
     * remove os documentos do processo eletronico informado
     * @param ProcessoEletronicoModel $processoEletronico
     * @throws \BusinessException
     */
    public static function removerDocumentos(ProcessoEletronicoModel $processoEletronico)
    {
        $daoProcessoEletronicoDocumento = new \cl_integracaoprocessoeletronicoarquivo();
        $daoProcessoEletronicoDocumento->excluir(null,
            "v40_integracaoprocessoeletronico = {$processoEletronico->getCodigo()}");
        if ($daoProcessoEletronicoDocumento->erro_status == 0) {
            throw new \BusinessException("Erro ao remover dados do documento no processo.\n{$daoProcessoEletronicoDocumento->erro_msg}");
        }
    }

}