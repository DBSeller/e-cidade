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

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Repository;


use cl_escola;
use cl_escoladadoscenso;
use db_utils;
use Escola;

class Registro10Repository
{
    /**
     * @param Escola $escola
     * @return array
     */
    public function buscarDadosAbaInfraEstrutura(Escola $escola)
    {
        $dao = new cl_escoladadoscenso();
        $rs = db_query($dao->sql_censo($escola->getCodigo()));
        if (!$rs || pg_num_rows($rs) === 0) {
            throw new \Exception("Não foi possível buscar os dados da infraestrutura da escola.");
        }

        $dados = array();
        db_utils::makeCollectionFromRecord($rs, function ($dado) use (&$dados) {

            $respota = (object) array(
                "opcao" => $dado->opcao,
                "valor_resposta" => $dado->db104_valorresposta,
                "resposta" => $dado->db106_resposta
            );
            $dados[$dado->grupo][$dado->pergunta][$dado->opcao][] = $respota;
        });

        return $dados;
    }

    public function buscarLinguaIndigena(Escola $escola)
    {
        $dao = new cl_escola();
        $rs = db_query($dao->sqlLinguaIndigena($escola->getCodigo()));

        if (!$rs) {
            throw new Exception("Erro ao buscar dados de lingua indigena.");
        }

        return pg_fetch_array($rs);
    }
}
