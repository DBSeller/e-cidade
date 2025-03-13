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

use ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Atributo as AtributoModel;
use ECidade\Financeiro\Contabilidade\ContaCorrente\Model\ContaCorrente as ContaCorrenteModel;

/**
 * Class Atributo
 * @package ECidade\Financeiro\Contabilidade\ContaCorrente\Model
 */
class Atributo extends \BaseClassRepository
{
    /**
     * @var Atributo
     */
    protected static $oInstance;



    /**
     * Retorna todos os atributos de um conta corrente pela conplano e reduz
     * @param ContaCorrenteModel $contaCorrente
     *
     * @return AtributoModel[]|bool
     */
    public static function getByConPlano($conta, $reduzido = "")
    {

        $instance = self::getInstance();
        $anoSessao = db_getsession('DB_anousu');
        $instituicaoSessao = db_getsession('DB_instit');
        $oDaoConplanoAtributos = new \cl_conplanoatributos();
        $aWhere = array();

        $aWhere[] = "c120_anousu = {$anoSessao}";
        $aWhere[] = "c61_instit = {$instituicaoSessao}";
        $aWhere[] = "c61_codcon in ({$conta})";
        $aWhere[] = "c120_conplanosistema = 1";
        $aWhere[] = "c61_reduz = {$reduzido}";

        $campos = "conplanoinfocomplementar.*";

        $sWhere = implode(" and ", $aWhere);

        $sqlBuscaInformacoes = $oDaoConplanoAtributos->sql_query_atributosPorReduzido(
            $campos,
            $sWhere,
            null,
            "c121_descricao"
        );

        $rsAtributos = $oDaoConplanoAtributos->sql_record($sqlBuscaInformacoes);
        $totalAtributos = $oDaoConplanoAtributos->numrows;
        if ($totalAtributos == 0) {
            return false;
        }

        $atributos = array();
        for ($row = 0; $row < $totalAtributos; $row++) {
            $stdDados = \db_utils::fieldsMemory($rsAtributos, $row);
            $atributos[] = $instance->make($stdDados);
        }

        return $atributos;
    }




    /**
     * Retorna todos os atributos de um conta corrente
     * @param ContaCorrenteModel $contaCorrente
     *
     * @return AtributoModel[]|bool
     */
    public static function getByContaCorrente(ContaCorrenteModel $contaCorrente)
    {

        $instance = self::getInstance();

        $daoAtributos = new \cl_conplanosistemaatributos();
        $where        = "c129_conplanosistema = {$contaCorrente->getCodigo()}";
        $campos = "conplanoinfocomplementar.*";
        $sqlAtributos = $daoAtributos->sql_query(null, "$campos", "c129_ordem", $where);

        $rsAtributos = db_query($sqlAtributos);
        $totalAtributos = pg_num_rows($rsAtributos);
        if ($totalAtributos == 0) {
            return false;
        }

        $atributos = array();
        for ($row = 0; $row < $totalAtributos; $row++) {
            $stdDados = \db_utils::fieldsMemory($rsAtributos, $row);
            //if (!empty($instance->aColecao[$stdDados->c121_sequencial])) {
            //    return $instance->aColecao[$stdDados->c121_sequencial];
            //}
            $atributos[] = $instance->make($stdDados);
        }
        return $atributos;
    }

    /**
     * @param $dados
     *
     *
     * @return AtributoModel
     */
    protected function make($dados)
    {

        $atributo = new AtributoModel();
        $atributo->setCodigo($dados->c121_sequencial);
        $atributo->setNome($dados->c121_descricao);
        $atributo->setSigla($dados->c121_sigla);
        $atributo->setAjuda($dados->c121_ajuda);
        $atributo->setRegra($dados->c121_sql);
        $atributo->setValorPadrao($dados->c121_valorpadrao);
        self::getInstance()->aColecao[$dados->c121_sequencial] = $atributo;
        return $atributo;
    }
}
