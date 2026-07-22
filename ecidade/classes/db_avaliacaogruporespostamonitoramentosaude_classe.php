<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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
class cl_avaliacaogruporespostamonitoramentosaude extends DAOBasica
{
    public function __construct()
    {
        parent::__construct("esocial.avaliacaogruporespostamonitoramentosaude");
    }

    public function buscaRespostasPorPergunta($pergunta, $preenchimento, $campos = "*", $ordem = null)
    {
        $sql  = <<<SQL
            select {$campos}
            from esocial.avaliacaogruporespostamonitoramentosaude
                inner join avaliacaogruporesposta on db107_sequencial = eso37_avaliacaogruporesposta
                inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial
                inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta
                inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao
                inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta
            where db103_sequencial = {$pergunta}
                and db107_sequencial = {$preenchimento}
SQL;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    public function buscaPreenchimento(array $campos, array $where, array $order, $instit = null)
    {

        $campos = implode(', ', $campos);
        $sql = <<<SQL
            select {$campos}
            from esocial.avaliacaogruporespostamonitoramentosaude
                join avaliacaogruporesposta on avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostamonitoramentosaude.eso37_avaliacaogruporesposta
                join avaliacaogrupoperguntaresposta on avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta = avaliacaogruporesposta.db107_sequencial
                join avaliacaoresposta on avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta
                join avaliacaoperguntaopcao on avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao
                join avaliacaopergunta on avaliacaopergunta.db103_sequencial = avaliacaoperguntaopcao.db104_avaliacaopergunta
                join avaliacaogrupopergunta on avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta
                left join cgm on cgm.z01_cgccpf = avaliacaogruporespostamonitoramentosaude.eso37_cpf
SQL;
        if($instit) {
            $sql .= " join pessoal.rhlota on rhlota.r70_instit = {$instit}";
        }

        $instituicaoCodigo = db_getsession("DB_instit");
        $cgmInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($instituicaoCodigo)->getCgm()->getCodigo();
        $where[] = " eso37_empregador = {$cgmInstituicao} ";

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
        $campos = implode(', ', $campos);
        $sql = <<<SQL
            select {$campos}
            from esocial.avaliacaogruporespostamonitoramentosaude
                join avaliacaogruporesposta on db107_sequencial = eso37_avaliacaogruporesposta
                join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial
                join avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta
                join avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao
                join avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta
                join avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial
                join avaliacao ON db102_avaliacao = db101_sequencial
                join cgm on eso37_empregador = z01_numcgm
SQL;
        $instituicaoCodigo = db_getsession("DB_instit");
        $cgmInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($instituicaoCodigo)->getCgm()->getCodigo();
        $where[] = " eso37_empregador = {$cgmInstituicao} ";

        if (!empty($where)) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= " {$outrosComandos} ";
        }
        return $sql;
    }
}