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

/**
 * Class cl_avaliacaogruporespostaexclusaoeventos
 */
class cl_avaliacaogruporespostaexclusaoeventos extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('esocial.avaliacaogruporespostaexclusaoeventos');
    }

    /**
     * Busca as respostas do preenchimento
     * @param array $campos 
     * @param array $where 
     * @param string $outrosComandos 
     * @return string
     */
    public function buscarRespostasPreenchimento(array $campos = array('*'), array $where = array(), $outrosComandos = null)
    {
        $sql = " SELECT distinct " . implode(', ', $campos);
        $sql .= "  FROM avaliacaogruporespostaexclusaoeventos";
        $sql .= "  INNER JOIN avaliacaogruporesposta ON db107_sequencial = eso14_avaliacaogruporesposta ";
        $sql .= "  INNER JOIN avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "  INNER JOIN avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  INNER JOIN avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  INNER JOIN avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  INNER JOIN avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  INNER JOIN avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql .= "  INNER JOIN cgm ON z01_numcgm = eso14_cgm";

        if (!empty($where)) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= " {$outrosComandos} ";
        }

        return $sql;
    }
}
