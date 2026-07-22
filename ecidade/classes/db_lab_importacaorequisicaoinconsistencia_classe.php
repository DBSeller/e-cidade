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
 * Class cl_nomedatable
*/

class cl_lab_importacaorequisicaoinconsistencia extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('laboratorio.lab_importacaorequisicaoinconsistencia');
    }

    public function sql_query_file_inconsistencia_laboratorio_setor($idLaboratorio, $idSetor = null)
    {
        $whereSetor = '';
        if (!empty($idSetor)) {
            $whereSetor = " AND la24_i_setor = {$idSetor}";
        }

        $sql = "
            SELECT 
                lab_requiitem.la21_i_requisicao,
                lab_setorexame.la09_i_exame,
                lab_importacaorequisicaoinconsistencia.la64_inconsistencias
            FROM lab_importacaorequisicaoinconsistencia
            INNER JOIN lab_requisicao ON lab_importacaorequisicaoinconsistencia.la64_requisicao = lab_requisicao.la22_i_codigo
            INNER JOIN lab_requiitem ON lab_requiitem.la21_i_requisicao = lab_requisicao.la22_i_codigo
            INNER JOIN lab_setorexame ON lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame
            INNER JOIN lab_labsetor ON lab_labsetor.la24_i_codigo = lab_setorexame.la09_i_labsetor
            WHERE lab_labsetor.la24_i_laboratorio = {$idLaboratorio} {$whereSetor}
            ORDER BY lab_requiitem.la21_i_requisicao
        ";

        return $sql;
    }
}
