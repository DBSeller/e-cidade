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

/**
 * Class LinhaDePacto
 * @package ECidade\Financeiro\Orcamento\Dotacao\Repository
 */
class LinhaDePacto extends \BaseClassRepository
{

    /**
     * @var LinhaDePacto
     */
    protected static $oInstance;

    /**
     * @var LinhaDePactoModel[]
     */
    protected $aColecao = array();


    /**
     * @param LinhaDePactoModel $linhaPacto
     * @throws \DBException
     */
    public static function persist(LinhaDePactoModel $linhaPacto)
    {

        $daoLinha = new \cl_planoorcamentariolinhapacto();
        $daoLinha->o156_sequencial = $linhaPacto->getCodigo();
        $daoLinha->o156_orcdotacaoplanoorcamentario = $linhaPacto->getCodigoPlano();
        $daoLinha->o156_linhaspacto = $linhaPacto->getCodigoLinha();
        $daoLinha->o156_valor = $linhaPacto->getValor();
        if (empty($daoLinha->o156_sequencial)) {
            $daoLinha->incluir($daoLinha->o156_sequencial);
        } else {
            $daoLinha->alterar($daoLinha->o156_sequencial);
        }

        if ($daoLinha->erro_status === '0') {
            throw new \DBException("Ocorreu um erro ao incluir as linhas de pacto para o plano orçamentário.");
        }
        $linhaPacto->setCodigo($daoLinha->o156_sequencial);
        self::getInstance()->aColecao[$linhaPacto->getCodigo()] = $linhaPacto;
    }

    /**
     * @param LinhaDePactoModel $linhaPacto
     * @throws \Exception
     */
    public static function excluir(LinhaDePactoModel $linhaPacto)
    {

        $sqlDeleteSaldo = "
            Delete 
              from linhapactosaldomovimentacao 
             where o162_linhapacto = {$linhaPacto->getCodigo()} 
               and o162_tipo = 7";
        $rsdeleteSaldo = db_query($sqlDeleteSaldo);
        $daoLinha = new \cl_planoorcamentariolinhapacto();
        $daoLinha->excluir($linhaPacto->getCodigo());
        if ($daoLinha->erro_status === "0") {
            $mensagem = "Esta linha de pacto possui movimentação. Deste modo não é possível realizar a exclusão";
            throw new \Exception($mensagem);
        }
        unset(self::getInstance()->aColecao[$linhaPacto->getCodigo()]);
    }

    /**
     * @param PlanoOrcamentarioModel $planoOrcamentario
     * @throws \Exception
     */
    public static function excluirPorPlano(PlanoOrcamentarioModel $planoOrcamentario)
    {
        $daoLinha = new \cl_planoorcamentariolinhapacto();
        $daoLinha->excluir(null, "o156_orcdotacaoplanoorcamentario = {$planoOrcamentario->getCodigo()}");
        if ($daoLinha->erro_status === "0") {
            $mensagem = "Esta linha de pacto possui movimentação. Deste modo não é possível realizar a exclusão";
            throw new \Exception($mensagem);
        }
        self::getInstance()->aColecao = array();
    }

    /**
     * @param PlanoOrcamentarioModel $planoOrcamentario
     * @return LinhaDePactoModel[]
     * @throws \DBException
     */
    public static function getPorPlano(PlanoOrcamentarioModel $planoOrcamentario)
    {

        $daoLinha = new \cl_planoorcamentariolinhapacto();
        $where = "o156_orcdotacaoplanoorcamentario = {$planoOrcamentario->getCodigo()}";
        $buscaLinhas = $daoLinha->sql_query(null, "*", "1", $where);
        $buscaLinhas = db_query($buscaLinhas);
        if (!$buscaLinhas) {
            $mensagem  = "Ocorreu um erro para consultar as linhas vinculadas ";
            $mensagem .= "ao plano {$planoOrcamentario->getTitulo()}.";
            throw new \DBException($mensagem);
        }

        $totalRegistros = pg_num_rows($buscaLinhas);
        for ($row = 0; $row < $totalRegistros; $row++) {
            self::getInstance()->make(\db_utils::fieldsMemory($buscaLinhas, $row));
        }

        return self::getInstance()->aColecao;
    }

    /**
     * @param \stdClass $stdDados
     */
    public function make($stdDados)
    {
        $linha = new LinhaDePactoModel();
        $linha->setCodigo($stdDados->o156_sequencial);
        $linha->setCodigoLinha($stdDados->o156_linhaspacto);
        $linha->setCodigoPlano($stdDados->o156_orcdotacaoplanoorcamentario);
        $linha->setDescricao($stdDados->c07_titulo);
        $linha->setValor($stdDados->o156_valor);
        self::getInstance()->aColecao[$linha->getCodigo()] = $linha;
    }
}
