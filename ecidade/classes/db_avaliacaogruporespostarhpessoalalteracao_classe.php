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
 * Class cl_avaliacaogruporespostarhpessoalalteracao
 * @property integer eso17_sequencial
 * @property integer eso17_avaliacaogruporesposta
 * @property integer eso17_rhpessoal
 */
class cl_avaliacaogruporespostarhpessoalalteracao extends DAOBasica
{
    /**
     * cl_avaliacaogruporespostarhpessoalalteracao constructor.
     */
    public function __construct()
    {
        parent::__construct('esocial.avaliacaogruporespostarhpessoalalteracao');
    }


    /**
     * @param array $campos
     * @param array $where
     * @param string $outrosComandos
     * @return string
     */
    public function sql_query_avaliacao_alteracao_servidor( $campos = array("*"), $where = array(), $outrosComandos = null )
    {
        $sql  = "select ".implode(', ', $campos);
        $sql .= "  from avaliacaogruporespostarhpessoalalteracao ";
        $sql .= "  join avaliacaogruporesposta on db107_sequencial = eso17_avaliacaogruporesposta";
        $sql .= "  join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "  join avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  join avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  join avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  join avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  join avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql .= "  join rhpessoalmov ON rhpessoalmov.rh02_regist = avaliacaogruporespostarhpessoalalteracao.eso17_rhpessoal ";
        $sql .= "  join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota and rhlota.r70_instit = rhpessoalmov.rh02_instit";

        if (!empty($where)) {
            $sql .= ' where '.implode(' and ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= ' '.$outrosComandos;
        }
        
        return $sql;
    }

    /***
     *
     * Busca os dados preechidos
     * @param null $iCodigoPergunta
     * @param null $iMatricula
     * @param string $campos
     * @param null $ordem
     * @param string $dbwhere
     * @return string
     */
    public function buscaRespostasPorPerguntaMatricula ($iCodigoPergunta = null, $iMatricula = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = "select {$campos}";
        $sql .= "  from avaliacaogruporespostarhpessoalalteracao ";
        $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso17_avaliacaogruporesposta";
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
                $aWhere[] = "eso17_rhpessoal = {$iMatricula}";
                $aWhere[] = "eso17_avaliacaogruporesposta = (select max(eso17_avaliacaogruporesposta)
                      from avaliacaogruporespostarhpessoalalteracao
                        inner join avaliacaogruporesposta on db107_sequencial = eso17_avaliacaogruporesposta
                        inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial
                        inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta
                        inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao
                        inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta
                    where db103_sequencial = {$iCodigoPergunta} and eso17_rhpessoal = {$iMatricula})";
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

}