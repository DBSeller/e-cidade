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

class cl_avaliacaogruporespostatsveinicial extends DAOBasica
{
    function __construct()
    {
        parent::__construct("esocial.avaliacaogruporespostatsveinicial");
    }

    public function buscaRespostasPorPerguntaMatricula($iCodigoPergunta = null, $iMatricula = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql  = "select {$campos}";
        $sql .= "  from avaliacaogruporespostatsveinicial ";
        $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso16_avaliacaogruporesposta";
        $sql .= "      inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "      inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta";
        $sql .= "      inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao";
        $sql .= "      inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta";
        $sql2 = "";
        if (empty($dbwhere)) {

            $sql2 .=" where ";
            $aWhere = array();

            if (!empty($iCodigoPergunta)) {
                $aWhere[] = " db103_sequencial = {$iCodigoPergunta} ";
            }
            if(!empty($iMatricula)){
                $aWhere[] = "eso16_rhpessoal = {$iMatricula}";
                $aWhere[] = "eso16_avaliacaogruporesposta = (select max(eso16_avaliacaogruporesposta)
                      from avaliacaogruporespostatsveinicial
                        inner join avaliacaogruporesposta on db107_sequencial = eso16_avaliacaogruporesposta
                        inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial
                        inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta
                        inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao
                        inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta
                    where db103_sequencial = {$iCodigoPergunta} and eso16_rhpessoal = {$iMatricula})";
            }
            $sql2 .= implode("and ", $aWhere);

        } else if (!empty($dbwhere)) {
            $sql2 = " where {$dbwhere}";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    public function sql_avaliacao_preenchida($campos = array("*"), $ordem = array(), $where = array(), $groupBy = array())
    {
        $sql  = "select " . implode(', ', $campos);
        $sql .= "  from avaliacaogruporespostatsveinicial
        join cgm on cgm.z01_numcgm = avaliacaogruporespostatsveinicial.eso16_empregador
        ";

        if (count($where) > 0) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (count($groupBy) > 0) {
            $sql .= " group by " . implode(', ', $groupBy);
        }

        if (count($ordem) > 0) {
            $sql .= " order by " . implode(', ', $ordem);
        }
        return $sql;
    }
}