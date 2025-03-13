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

namespace ECidade\Patrimonial\Patrimonio\Bem\Service;

use ECidade\Patrimonial\Patrimonio\Bem\Repository\BemRepository;
use ECidade\Patrimonial\Patrimonio\Bem\Repository\BemPlacaRepository;

class BemService
{
    private $repositorio;
    private $bemPlacaRepositorio;

    /**
     * BemService constructor.
     * @param BemRepository $repositorio
     */
    public function __construct(BemRepository $repositorio, BemPlacaRepository $bemPlacaRepositorio)
    {
        $this->repositorio = $repositorio;
        $this->bemPlacaRepositorio = $bemPlacaRepositorio;
    }

    public function busca($id)
    {
        try {
            return $this->repositorio->busca($id);
        } catch (Exception $e) {
            throw new Exception('Bem não encontrado.');
        }
    }

    public function buscaPlacasParaExclusao($idBem = null, $sequencialPlaca = null)
    {
        if (empty($idBem) && empty($sequencialPlaca)) {
            throw new Exception('Não foram informados os dados para realização do filtro.');
        }

        try {
            return $this->bemPlacaRepositorio->buscaPlacasParaExclusao((int) $idBem, (int) $sequencialPlaca);
        } catch (Exception $e) {
            throw new Exception('Não há placas para exclusão.');
        }
    }

    public function excluiPlacas($idsPlacas)
    {
        try {
            return $this->bemPlacaRepositorio->excluiPlacas($idsPlacas);
        } catch (Exception $e) {
            throw new Exception('Erro ao tentar excluir as placas.');
        }
    }
}
