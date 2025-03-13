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
 * @property integer eso07_sequencial
 * @property integer eso07_avaliacaogruporesposta
 * @property integer eso07_empregador
 * @property integer eso07_regist
 */
class cl_avaliacaogruporespostaavisoprevio extends DAOBasica
{
    public function __construct()
    {
        parent::__construct("esocial.avaliacaogruporespostaavisoprevio");
    }

    public function buscaRespostasPorPergunta($pergunta, $preenchimento, $campos = "*", $ordem = null)
    {
        $sql  = "select {$campos}";
        $sql .= "  from avaliacaogruporespostaavisoprevio";
        $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso07_avaliacaogruporesposta";
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

    public function buscaPreenchimento(array $campos, array $where, array $order, $instit = null)
    {
        $sql = " select  " . implode(', ', $campos);
        $sql .= " from avaliacaogruporespostaavisoprevio";
        $sql .= " join avaliacaogruporesposta on avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostaavisoprevio.eso07_avaliacaogruporesposta ";
        $sql .= " join avaliacaogrupoperguntaresposta on avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta = avaliacaogruporesposta.db107_sequencial ";
        $sql .= " join avaliacaoresposta on avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta  ";
        $sql .= " join avaliacaoperguntaopcao on avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao ";
        $sql .= " join avaliacaopergunta on avaliacaopergunta.db103_sequencial = avaliacaoperguntaopcao.db104_avaliacaopergunta ";
        $sql .= " join avaliacaogrupopergunta on avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta ";
        $sql .= " join cgm on cgm.z01_numcgm = avaliacaogruporespostaavisoprevio.eso07_empregador";

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

    public function avaliacaoPreenchida(array $campos = array('*'), array $where = array(), $outrosComandos = null)
    {
        $sql = " select " . implode(', ', $campos);
        $sql .= "  from avaliacaogruporespostaavisoprevio";
        $sql .= "  join avaliacaogruporesposta on db107_sequencial = eso07_avaliacaogruporesposta ";
        $sql .= "  join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "  join avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  join avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  join avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  join avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  join avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql .= "  join cgm ON eso07_empregador = z01_numcgm ";

        if (!empty($where)) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= " {$outrosComandos} ";
        }

        return $sql;
    }

    public function avisoPrevioPorEnvio($envio)
    {
        $sql  = " select avaliacaogruporespostaavisoprevio.eso07_regist as regist, ";
        $sql .= " from avaliacaogruporespostaavisoprevio ";
        $sql .= " join esocialenvio on rh213_responsavelpreenchimento = eso07_avaliacaogruporesposta::varchar ";
        $sql .= " where rh213_sequencial = {$envio}";

        return $sql;
    }
}