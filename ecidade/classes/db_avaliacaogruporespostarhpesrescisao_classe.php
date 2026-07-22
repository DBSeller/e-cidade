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
 * Class cl_avaliacaogruporespostarhpesrescisao
 * @property integer eso15_sequencial
 * @property integer eso15_codigorescisao
 * @property integer eso15_avaliacaogruporesposta
 * @property integer eso15_regist
 * @property integer eso15_cgmempregador
 */
class cl_avaliacaogruporespostarhpesrescisao extends DAOBasica
{
    /**
     * cl_avaliacaogruporespostarhpesrescisao constructor.
     */
    public function __construct()
    {
        parent::__construct('esocial.avaliacaogruporespostarhpesrescisao');
    }

    /**
     * @param array $fields
     * @param array $where
     * @param null $otherInstructions
     * @return string
     */
    public function sql_query_formulario($fields = array('*'), $where = array(), $groupBy)
    {

        $fields = implode(', ', $fields);
        $where  = implode(' and ', $where);
        $groupBy = implode(', ', $groupBy);
        if (!empty($where)) {
            $where = " where {$where}";
        }
        if (!empty($groupBy)) {
            $groupBy = " group by {$groupBy}";
        }
        $consulta = <<<SQL_PREENCHIMENTO_FORMULARIO
        select {$fields}
          from avaliacaogruporespostarhpesrescisao
          join cgm on cgm.z01_numcgm = avaliacaogruporespostarhpesrescisao.eso15_cgmempregador
       {$where}
       {$groupBy}
SQL_PREENCHIMENTO_FORMULARIO;

        return $consulta;
    }

    public function buscaPreenchimento(array $campos, array $where, array $order, $instit = null)
    {
        $sql = " select  " . implode(', ', $campos);
        $sql .= " from avaliacaogruporespostarhpesrescisao";
        $sql .= " join avaliacaogruporesposta on avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostarhpesrescisao.eso15_avaliacaogruporesposta ";
        $sql .= " join avaliacaogrupoperguntaresposta on avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta = avaliacaogruporesposta.db107_sequencial ";
        $sql .= " join avaliacaoresposta on avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta  ";
        $sql .= " join avaliacaoperguntaopcao on avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao ";
        $sql .= " join avaliacaopergunta on avaliacaopergunta.db103_sequencial = avaliacaoperguntaopcao.db104_avaliacaopergunta ";
        $sql .= " join avaliacaogrupopergunta on avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta ";
        $sql .= " join cgm on cgm.z01_numcgm = avaliacaogruporespostarhpesrescisao.eso15_cgmempregador";

        if($instit) {
            $sql .= " join pessoal.rhlota on cgm.z01_numcgm = rhlota.r70_numcgm and rhlota.r70_instit = {$instit}";
        }

        if (!empty($where)) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (!empty($order)) {
            $sql .= " order by " . implode(', ', $order);
        }

        return $sql;
    }

    public function buscaRespostasPorPergunta($pergunta, $preenchimento, $campos = "*", $ordem = null)
    {
        $sql  = "select {$campos}";
        $sql .= "  from avaliacaogruporespostarhpesrescisao";
        $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso15_avaliacaogruporesposta";
        $sql .= "      inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "      inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta";
        $sql .= "      inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao";
        $sql .= "      inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta";
        $sql .= " where db103_sequencial = {$pergunta}";
        $sql .= "   and db107_sequencial = {$preenchimento}";
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    public function avaliacaoPreenchida(array $campos = array('*'), array $where = array(), $outrosComandos = null)
    {
        $sql = " select " . implode(', ', $campos);
        $sql .= "  from avaliacaogruporespostarhpesrescisao";
        $sql .= "  join avaliacaogruporesposta on db107_sequencial = eso15_avaliacaogruporesposta ";
        $sql .= "  join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "  join avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  join avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  join avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  join avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  join avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql .= "  join cgm ON eso15_cgmempregador = z01_numcgm ";

        if (!empty($where)) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= " {$outrosComandos} ";
        }

        return $sql;
    }

    /**
     * @param string $campos
     * @param null $where
     * @param null $ordem
     * @return string
     */
    public function buscaServidorCargaDesligamento($campos = "*", $where = null, $ordem = null)
    {
        $anoFolha = DBPessoal::getAnoFolha();
        $mesFolha = DBPessoal::getMesFolha();
        $instituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();

        $sql  = "select {$campos}";
        $sql .= "  from avaliacaogruporespostarhpesrescisao";
        $sql .= "  join rhpesrescisao on rh05_codigorescisao = eso15_codigorescisao";
        $sql .= "  join rhpessoalmov on rh05_seqpes = rh02_seqpes";
        $sql .= "                   and rh02_anousu = {$anoFolha}";
        $sql .= "                   and rh02_mesusu = {$mesFolha}";
        $sql .= "                   and rh02_instit = {$instituicao}";
        $sql .= "  join rhpessoal on rh02_regist = rh01_regist";
        $sql .= "  join cgm on rh01_numcgm = z01_numcgm";

        if (!empty($where)) {
            $sql .= " where " . $where;
        }

        if (!empty($ordem)) {
            $sql .= " order by " . $ordem;
        }

        return $sql;
    }
}