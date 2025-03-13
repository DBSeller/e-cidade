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
 * Class cl_avaliacaogruporespostatertrabasemvinc
 * @property integer eso24_sequencial
 * @property integer eso24_avaliacaogruporesposta
 * @property integer eso24_rhpessoal
 */
class cl_avaliacaogruporespostatertrabasemvinc extends DAOBasica
{
    /**
     * cl_avaliacaogruporespostatertrabasemvinc constructor.
     */
    public function __construct()
    {
        parent::__construct('esocial.avaliacaogruporespostatertrabasemvinc');
    }


    public function buscaRespostasPorPerguntaMatricula($iCodigoPergunta = null, $iMatricula = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql  = "select {$campos}";
        $sql .= "  from avaliacaogruporespostatertrabasemvinc ";
        $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso24_avaliacaogruporesposta";
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
                $aWhere[] = "eso24_rhpessoal = {$iMatricula}";
                $aWhere[] = "eso24_avaliacaogruporesposta = (select max(eso24_avaliacaogruporesposta)
                      from avaliacaogruporespostatertrabasemvinc
                        inner join avaliacaogruporesposta on db107_sequencial = eso24_avaliacaogruporesposta
                        inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial
                        inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta
                        inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao
                        inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta
                    where db103_sequencial = {$iCodigoPergunta} and eso24_rhpessoal = {$iMatricula})";
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


    /**
     * @param array $campos
     * @param array $where
     * @param string $outrosComandos
     * @return string
     */
    public function sql_query_avaliacao_servidor_sem_vinculo($campos = array("*"), $where = array(), $outrosComandos = null)
    {
        $sql = "select " . implode(', ', $campos);
        $sql .= "  from avaliacaogruporespostatertrabasemvinc ";
        $sql .= "  join cgm on cgm.z01_numcgm = avaliacaogruporespostatertrabasemvinc.eso24_cgmempregador";


        if (!empty($where)) {
            $sql .= ' where ' . implode(' and ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= ' ' . $outrosComandos;
        }

        return $sql;
    }

    /**
     * Metodo para buscar o ultimo preecimento
     *
     * @param array $campos
     * @param array $where
     * @param array $order
     * @param null $instit
     * @return string
     */
    public function buscaPreenchimento(array $campos, array $where, array $order, $instit = null)
    {
        $sql = " select  " . implode(', ', $campos);
        $sql .= " from avaliacaogruporespostatertrabasemvinc";
        $sql .= " join avaliacaogruporesposta on avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostatertrabasemvinc.eso24_avaliacaogruporesposta ";
        $sql .= " join avaliacaogrupoperguntaresposta on avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta = avaliacaogruporesposta.db107_sequencial ";
        $sql .= " join avaliacaoresposta on avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta  ";
        $sql .= " join avaliacaoperguntaopcao on avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao ";
        $sql .= " join avaliacaopergunta on avaliacaopergunta.db103_sequencial = avaliacaoperguntaopcao.db104_avaliacaopergunta ";
        $sql .= " join avaliacaogrupopergunta on avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta ";
        $sql .= " join cgm on cgm.z01_numcgm = avaliacaogruporespostatertrabasemvinc.eso24_cgmempregador";

        if ($instit) {
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

    /**
     * Método para buscar o servidores que possuem carga
     *
     * @param array $campos
     * @param array $order
     * @param array $where
     * @return string
     */
    public function buscaServidorCargaDesligamento(array $campos, array $order, array $where)
    {
        $sql  = " select " . implode(", ", $campos);
        $sql .= " from avaliacaogruporespostatertrabasemvinc ";
        $sql .= "  inner join rhpessoal on rhpessoal.rh01_regist = avaliacaogruporespostatertrabasemvinc.eso24_rhpessoal ";
        $sql .= "  inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm";

        if (!empty($where)) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (!empty($order)) {
            $sql .= " order by " . implode(', ', $order);
        }

        return $sql;
    }
}