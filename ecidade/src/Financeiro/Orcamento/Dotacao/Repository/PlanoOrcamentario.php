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

namespace ECidade\Financeiro\Orcamento\Dotacao\Repository;

use ECidade\Financeiro\Orcamento\Dotacao\Model\LinhaDePacto as LinhaDePactoModel;
use ECidade\Financeiro\Orcamento\Dotacao\Model\PlanoOrcamentario as PlanoOrcamentarioModel;
use ECidade\Financeiro\Orcamento\Dotacao\Repository\LinhaDePacto as LinhaDePactoRepository;

/**
 * Class PlanoOrcamentario
 * @package ECidade\Financeiro\Orcamento\Dotacao\Repository
 */
class PlanoOrcamentario extends \BaseClassRepository
{

    /**
     * @var PlanoOrcamentario
     */
    protected static $oInstance;

    /**
     * @var PlanoOrcamentarioModel[]
     */
    protected $aColecao = array();

    /**
     * Busca por código
     * @param integer $codigo
     *
     * @return bool|PlanoOrcamentarioModel
     * @throws \DBException
     */
    public static function getPorCodigo($codigo)
    {

        $daoPlano = new \cl_orcdotacaoplanoorcamentario();
        $buscaPlano = $daoPlano->sql_query_file($codigo);
        $buscaPlano = db_query($buscaPlano);
        if (!$buscaPlano) {
            throw new \DBException("Ocorreu um erro ao consultar os dados o plano orçamentário.");
        }

        if (pg_num_rows($buscaPlano) == 0) {
            return false;
        }

        self::getInstance()->make(\db_utils::fieldsMemory($buscaPlano, 0));
        return self::getInstance()->aColecao[$codigo];
    }

    /**
     * @param \Dotacao $dotacao
     * @return PlanoOrcamentarioModel[]
     * @throws \DBException
     */
    public static function getPorDotacao(\Dotacao $dotacao)
    {

        $daoPlano = new \cl_orcdotacaoplanoorcamentario();
        $where = "o155_coddot = {$dotacao->getCodigo()} and o155_anousu = {$dotacao->getAno()}";
        $buscaPlano = $daoPlano->sql_query_file(null, "*", "1", $where);
        $buscaPlano = db_query($buscaPlano);
        if (!$buscaPlano) {
            throw new \DBException("Ocorreu um erro ao consultar os planos orçamentários por dotação.");
        }
        $totalRegistros = pg_num_rows($buscaPlano);
        for ($row = 0; $row < $totalRegistros; $row++) {
            self::getInstance()->make(\db_utils::fieldsMemory($buscaPlano, $row));
        }
        return self::getInstance()->aColecao;
    }

    /**
     * @param $stdPlano
     * @return PlanoOrcamentarioModel|void
     */
    protected function make($stdPlano)
    {

        $plano = new PlanoOrcamentarioModel();
        $plano->setCodigo($stdPlano->o155_sequencial);
        $plano->setTitulo($stdPlano->o155_titulo);
        $plano->setValor($stdPlano->o155_valor);
        $plano->setDotacao(\DotacaoRepository::getDotacaoPorCodigoAno($stdPlano->o155_coddot, $stdPlano->o155_anousu));
        self::getInstance()->aColecao[$plano->getCodigo()] = $plano;
    }

    /**
     * @param PlanoOrcamentarioModel $plano
     * @throws \DBException
     */
    public static function persist(PlanoOrcamentarioModel $plano)
    {

        $daoPlano = new \cl_orcdotacaoplanoorcamentario();
        $daoPlano->o155_sequencial = $plano->getCodigo();
        $daoPlano->o155_coddot = $plano->getDotacao()->getCodigo();
        $daoPlano->o155_anousu = $plano->getDotacao()->getAno();
        $daoPlano->o155_titulo = $plano->getTitulo();
        $daoPlano->o155_valor = $plano->getValor();
        if (empty($daoPlano->o155_sequencial)) {
            $daoPlano->incluir($daoPlano->o155_sequencial);
            $linhaPacto = new LinhaDePactoModel();
            $linhaPacto->setDescricao('N/A');
            $linhaPacto->setCodigoPlano($daoPlano->o155_sequencial);
            $linhaPacto->setValor($plano->getValor());
            $linhaPacto->setCodigoLinha('0');
            LinhaDePactoRepository::persist($linhaPacto);
        } else {
            $daoPlano->alterar($daoPlano->o155_sequencial);
        }

        if ($daoPlano->erro_status === "0") {
            throw new \DBException("Ocorreu um erro ao persistir os dados do plano orçamentário.");
        }

        $plano->setCodigo($daoPlano->o155_sequencial);

        foreach ($plano->getLinhasPacto() as $linhaPacto) {
            $linhaPacto->setCodigoPlano($plano->getCodigo());
            LinhaDePactoRepository::persist($linhaPacto);
        }

        self::getInstance()->aColecao[$plano->getCodigo()] = $plano;
    }

    /**
     * @param PlanoOrcamentarioModel $planoOrcamentario
     * @throws \DBException|\Exception
     */
    public static function excluir(PlanoOrcamentarioModel $planoOrcamentario)
    {

        LinhaDePactoRepository::excluirPorPlano($planoOrcamentario);
        $daoPlano = new \cl_orcdotacaoplanoorcamentario();
        $daoPlano->excluir($planoOrcamentario->getCodigo());
        if ($daoPlano->erro_status === "0") {
            $mensagem  = "Este plano orçamentário possui movimentação nas linhas de pacto vinculadas. ";
            $mensagem .= "Deste modo não é possível realizar a exclusão.";
            throw new \DBException($mensagem);
        }
    }

    /**
     *
     * @param \Dotacao $dotacao
     * @return float
     * @throws \DBException
     */
    public static function getValorTotalLinhasDaDotacao(\Dotacao $dotacao)
    {

        $daoPlano = new \cl_orcdotacaoplanoorcamentario();
        $where = "o155_coddot = {$dotacao->getCodigo()} and o155_anousu = {$dotacao->getAno()}";
        $sqlTotalPlano = $daoPlano->sql_query_file(null, "coalesce(sum(o155_valor), 0) as total", null, $where);
        $rstotalPlano = db_query($sqlTotalPlano);
        if (!$rstotalPlano) {
            throw new \DBException("não foi possível verificar  o valor total das linhas de pacto da dotação.");
        }
        $valorTotal = (float)\db_utils::fieldsMemory($rstotalPlano, 0)->total;
        return $valorTotal;
    }
}
