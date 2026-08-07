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

namespace ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Repository;

use Exception;
use DBException;
use cl_orcunidade;
use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model\AndamentoPreAutorizacao;
use stdClass;

/**
 * Class LiberacaoAutorizacaoUnidadeRepository
 * @package ECidade\Patrimonial\Compras\Repository
 */
class LiberacaoAutorizacaoUnidadeRepository
{
    /**
     * @var Object
     */
    private $dao;

    /**
     * Emppreautorizacaounidade constructor.
     * @param $dao \cl_emppreautorizacaounidade
     */
    public function __construct()
    {
        $this->dao = new \cl_emppreautorizacaounidade;
    }

    /**
     * @return bool
     */
    public function salvar($params)
    {
        $retorno = new stdClass();

        db_query('DELETE FROM emppreautorizacaounidade');
        $exercicio = $params->exercicio;
        foreach ($params->unidades as $orgaoUnidade) {
            $codigoPartes = explode("-", $orgaoUnidade);
            $orgao_id = $codigoPartes[0];
            $unidade_id = $codigoPartes[1];

            if (!$this->dao->incluir($exercicio, $orgao_id, $unidade_id)) {
                $retorno->message = "Ocorreu um erro ao incluir a(s) unidade(s).";
                throw new DBException("Ocorreu um erro ao incluir a(s) unidade(s).");
            }
        }
        $retorno->message = "As alterações foram salvas com sucesso.";
        return $retorno;
    }

    /**
     * @return array
     */
    public function listaUnidades()
    {
        $retorno = [];
        $orcUnidade = new cl_orcunidade();
        $campos = " o41_orgao, o41_unidade, o41_anousu, o40_descr, o41_descr, 
                        concat(emppreautorizacaounidade.unidade_id, '-',
                        emppreautorizacaounidade.orgao_id) as salvo ";
        $ordem = " o40_descr, o41_descr ";
        $sql = $orcUnidade->sql_query_unidades(db_getsession("DB_anousu"), null, null, $campos, $ordem);
        $rs = db_query($sql);
        while ($orgao = pg_fetch_array($rs)) {
            $retorno[] = [
                'orgao_id' => $orgao['o41_orgao'],
                'unidade_id' => $orgao['o41_unidade'],
                'exercicio' => $orgao['o41_anousu'],
                'orgao' => $orgao['o40_descr'],
                'unidade' => $orgao['o41_unidade']." - ".$orgao['o41_descr'],
                'salvo' => $orgao['salvo']
            ];
        }
        
        return $retorno;
    }
}
