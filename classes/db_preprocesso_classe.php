<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2019  DBSeller Servicos de Informatica
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
 * Class cl_categoriatipoproc
 */
class cl_preprocesso extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('protocolo.preprocesso');
    }

    /**
     * @param array $campos
     * @param array $where
     * @return string
     */
    public function sqlPreProcessoTipoProcesso($campos = array(), $where = array())
    {
        $campos = empty($campos) ? '*' : implode(', ', $campos);
        $where = empty($where) ? '' : ' where ' . implode(' AND ', $where);

        $sql = "select {$campos} ";
        $sql .= " from preprocesso ";
        $sql .= "      inner join tipoproc on p51_codigo = p106_tipoprocesso";
        $sql .= " {$where}";

        return $sql;
    }
}
