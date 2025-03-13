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
namespace ECidade\Financeiro\Contabilidade\ContaCorrente\Repository;


use mysql_xdevapi\Exception;

/**
 * Class Visao
 * @package ECidade\Financeiro\Contabilidade\ContaCorrente\Repository
 */
class Visao extends \BaseClassRepository
{


    /**
     * @var \ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao
     */
    protected static $oInstance;

    /**
     * @var \ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao[]
     */
    protected $aColecao;


    /**
     * @param $codigo
     * @return bool|\ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao
     * @throws \DBException
     */
    public static function getPorCodigo($codigo)
    {

        if (!empty(self::getInstance()->aColecao[$codigo])) {
            return self::getInstance()->aColecao[$codigo];
        }

        $daoVisaoContaCorrente = new \cl_visaocontacorrente();
        $buscaVisao = $daoVisaoContaCorrente->sql_query_file($codigo);
        $buscaVisao = db_query($buscaVisao);
        if (!$buscaVisao) {
            throw new \DBException("Ocorreu um erro ao executar a consulta da visão com código {$codigo}.");
        }

        if (pg_num_rows($buscaVisao) === 0) {
            return false;
        }

        $visaoContaCorrente = self::getInstance()->make(\db_utils::fieldsMemory($buscaVisao, 0));
        return $visaoContaCorrente;
    }


    /**
     * @return \ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao[]
     * @throws \DBException
     */
    public static function getTodos()
    {
        $daoVisaoContaCorrente = new \cl_visaocontacorrente();
        $buscaVisoes = $daoVisaoContaCorrente->sql_query_file();
        $buscaVisoes = db_query($buscaVisoes);
        if (!$buscaVisoes) {
            throw new \DBException("Ocorreu um erro ao executar as visões cadastradas.");
        }

        self::getInstance()->aColecao = array();
        for ($row = 0; $row < pg_num_rows($buscaVisoes); $row++) {
            $visao = self::getInstance()->make(\db_utils::fieldsMemory($buscaVisoes, $row));
        }

        return self::getInstance()->aColecao;
    }

    /**
     * @param \ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao $visao
     * @return \ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao
     * @throws \DBException
     */
    public static function salvar(\ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao $visao)
    {

        $daoVisao = new \cl_visaocontacorrente();
        $daoVisao->c131_sequencial   = $visao->getCodigo();
        $daoVisao->c131_db_itensmenu = $visao->getCodigoItemMenu();
        $daoVisao->c131_nome         = $visao->getNome();
        $daoVisao->c131_filtros      = pg_escape_string($visao->getFiltrosJson());

        if (empty($daoVisao->c131_sequencial)) {
            $daoVisao->incluir($daoVisao->c131_sequencial);
        } else {
            $daoVisao->alterar($daoVisao->c131_sequencial);
        }

        if ($daoVisao->erro_status === '0') {
            throw new \DBException("Ocorreu um erro ao salvar os dados da visão do conta corrente.");
        }
        $visao->setCodigo($daoVisao->c131_sequencial);
        return $visao;
    }

    /**
     * @param $dados
     * @return \ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao
     */
    protected function make($dados)
    {

        $visao = new \ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao();
        $visao->setCodigo($dados->c131_sequencial);
        $visao->setCodigoItemMenu($dados->c131_db_itensmenu);
        $visao->setNome($dados->c131_nome);
        $visao->setFiltrosJson($dados->c131_filtros);
        $this->aColecao[$dados->c131_sequencial] = $visao;
        return $this->aColecao[$dados->c131_sequencial];
    }


    /**
     * @param $codigo
     */
    public static function excluir($codigo)
    {

        $daoVisao = new \cl_visaocontacorrente();
        $daoVisao->excluir($codigo);
        if ($daoVisao->erro_status === "0") {
            throw new Exception("Ocorreu um erro para excluir a visão do conta corrente.");
        }
    }
}
