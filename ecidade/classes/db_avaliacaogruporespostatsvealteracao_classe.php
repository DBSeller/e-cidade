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

/**
 * Class cl_avaliacaogruporespostatsvealteracao
 * @property integer eso23_sequencial
 * @property integer eso23_avaliacaogruporesposta
 * @property integer eso23_rhpessoal
 */
class cl_avaliacaogruporespostatsvealteracao extends DAOBasica
{

    /**
     * cl_avaliacaogruporespostatsvealteracao constructor.
     */
    public function __construct()
    {
        parent::__construct('esocial.avaliacaogruporespostatsvealteracao');
    }

    public function sql_query_busca_avaliacao(array $campos = array('*'), array $where = array(), $outrosComandos = null)
    {
        $sql  = " select ".implode(', ', $campos);
        $sql .= "   from avaliacaogruporespostatsvealteracao ";
        $sql .= "        inner join avaliacaogruporesposta ON db107_sequencial = eso23_avaliacaogruporesposta ";
        $sql .= "        inner join avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "        inner join avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "        inner join avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "        inner join avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "        inner join avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "        inner join avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql .= "        inner join rhpessoal ON rhpessoal.rh01_regist = avaliacaogruporespostatsvealteracao.eso23_rhpessoal ";
        $sql .= "        inner join rhpessoalmov ON rhpessoalmov.rh02_regist = rhpessoal.rh01_regist ";
        $sql .= "        inner join cgm ON z01_numcgm = rh01_numcgm ";
        $sql .= "        inner join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota ";
        $sql .= "                         and rhlota.r70_instit = rhpessoalmov.rh02_instit ";

        if (!empty($where)) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= $outrosComandos;
        }

        return $sql;
    }

    public function buscarRespostasPreenchimento(array $campos, array $where)
    {
        return $this->sql_query_busca_avaliacao($campos, $where);
    }

    public function sql_avaliacao_preenchida( $eso23_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "" )
    {
        $sql  = "select {$campos} ";
        $sql .= "  from avaliacaogruporespostatsvealteracao ";
        $sql .= "  join avaliacaogruporesposta on db107_sequencial = eso23_avaliacaogruporesposta";
        $sql .= "  join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "  join avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  join avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  join avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  join avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  join avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql .= "  join rhpessoalmov ON rhpessoalmov.rh02_regist = eso23_rhpessoal ";
        $sql .= "  join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota and rhlota.r70_instit = rhpessoalmov.rh02_instit";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso23_sequencial)){
                $sql2 .= " where avaliacaogruporespostarhpessoal.eso23_sequencial = {$eso23_sequencial} ";
            }
        } else if (!empty($dbwhere)) {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }
}