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
 *  junto com este programa; se nao, escreva para a Free Softwareb
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\ESocial\Repository;

use cl_avaliacaogruporespostalotacao;
use db_utils;
use Exception;
use ParameterException;

/**
 * Class Empregador
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class Empregador
{
    /**
     * @param $inscricaoEmpregador
     * @return null
     * @throws Exception
     */
    public static function buscarCodigoLotacaoPelaInscricao($inscricaoEmpregador)
    {
        if(empty($inscricaoEmpregador)) {
            throw new ParameterException("Inscrição do empregador não informada.");
        }

        $daoAvaliacaoGrupoRespostaLotacao = new cl_avaliacaogruporespostalotacao();
        $order = "eso04_avaliacaogruporesposta desc limit 1";

        $where = "      db103_sequencial = 3000860 ";
        $where .= " AND eso04_cgm in (SELECT DISTINCT z01_numcgm  ";
        $where .= "                     FROM cgm  ";
        $where .= "                          INNER JOIN rhlota ON rhlota.r70_numcgm = cgm.z01_numcgm ";
        $where .= "                    WHERE z01_cgccpf = '{$inscricaoEmpregador}') ";

        $sql = $daoAvaliacaoGrupoRespostaLotacao->buscaRespostasPorPergunta(3000860, null, 'db106_resposta', $order, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar o código da lotação tributária do empregador.");
        }

        $codigoLotacao = null;

        if (pg_num_rows($rs) > 0) {
            $codigoLotacao = db_utils::fieldsMemory($rs, 0)->db106_resposta;
        }

        return $codigoLotacao;
    }
}