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

namespace ECidade\Patrimonial\Patrimonio\Bem\Repository;

use ECidade\Patrimonial\Patrimonio\Bem\Model\BemPlaca;
use Exception;

class BemPlacaRepository
{
    /**
     * @var Object
     */
    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function buscaBemPlacasPorIdBem($id, $colunas = array('*'))
    {
        $sql = $this->dao->sql_query_file(
            $id,
            implode(', ', $colunas),
            't41_data DESC',
            "t41_excluido = 'false' AND t41_codigo = {$id}"
        );
        
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o Bem.");
        }

        $numrows = pg_num_rows($rs);

        if ($numrows === 0) {
            return false;
        }

        $bemPlacas = array();

        for ($i = 0; $i < $numrows; $i++) {
            $bemPlacas[] = BemPlaca::fromState(pg_fetch_array($rs, $i));
        }
        
        return $bemPlacas;
    }

    public function buscaPlacasParaExclusao($idBem = null, $sequencialPlaca = null)
    {
        $sql = $this->dao->sqlQueryPlacasParaExclusao($idBem, $sequencialPlaca);
        $rs = db_query($sql);
        
        if (!$rs) {
            throw new Exception("Não foi possível buscar as placas do bem.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }
        
        $placas = [];
        
        while ($row = pg_fetch_array($rs)) {
            $placas[] = BemPlaca::fromState($row);
        }
        
        return $placas;
    }

    public function excluiPlacas($idsPlacas)
    {
        $placas = $this->validaPlacasExclusao(implode(',', $idsPlacas));
        
        if (!$placas) {
            throw new Exception('Erro ao validar as placas para exclusão.');
        }

        $placasAExcluir = [];

        foreach ($placas as $placa) {
            if ($placa['pode_excluir'] == 't') {
                $placasAExcluir[] = $placa['t41_codigo'];
            }
        }

        $ids = implode(',', $placasAExcluir);
        
        $sql = "UPDATE bensplaca SET t41_excluido = 't' WHERE bensplaca.t41_codigo IN ({$ids})";
        
        $rs = db_query($sql);

        if (!$rs) {
            return false;
        }

        return true;
    }

    private function validaPlacasExclusao($ids)
    {
        $sql = $this->dao->sqlQueryValidaPlacasExclusao($ids);
        
        $rs = db_query($sql);

        if (!$rs) {
            return false;
        }

        return pg_fetch_all($rs);
    }
}
