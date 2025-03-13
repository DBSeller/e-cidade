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

namespace ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Services;

use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Repository\AndamentoPreAutorizacaoRepository;
use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model\AndamentoPreAutorizacao;
use DateTime;
use Exception;

class AndamentoPreAutorizacaoService
{

    private $repositorio;

    private $andamento ;

    /**
     * AutorizacaoService constructor.
     * @param AndamentoPreAutorizacaoRepository $repositorio
     */
    public function __construct($dao)
    {
        $this->repositorio = new AndamentoPreAutorizacaoRepository($dao);
    }

    public function salvar($idAndamento, $empautoriza_id, $status_id, $observacao, $id_usuario)
    {
        $this->andamento = new AndamentoPreAutorizacao(
            $idAndamento,
            $empautoriza_id,
            $status_id,
            $observacao,
            $id_usuario
        );

        try {
            $this->repositorio->salvar($this->andamento);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function listarAndamentosPreAutorizacao($dataInicial, $dataFinal, $status, $modo = null)
    {
        return $this->repositorio->listarAndamentosPreAutorizacao($dataInicial, $dataFinal, $status, $modo);
    }
    
    public function obterAndamentoPorAutorizacao($empautoriza_id)
    {
        return $this->repositorio->listarAndamentoPorAutorizacao($empautoriza_id);
    }
}
