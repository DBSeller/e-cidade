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

namespace ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Repository;

use cl_orcreservasol;
use Exception;
use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model\AndamentoPreAutorizacao;
use db_utils;

/**
 * Class AutorizacaoEmpenhoRepository
 * @package ECidade\Patrimonial\Compras\Repository
 */
class AndamentoPreAutorizacaoRepository
{
    /**
     * @var Object
     */
    private $dao;

    private $daoAutorizada;

    private $daoEmpAut;

    private $daoOrcReservaAut;

    private $daoOrcReserva;

    private $daoEmpAutDot;

    const STATUS_AGUARDANDO_LIBERACAO = 1;
    const STATUS_EM_ANALISE = 2;
    const STATUS_AUTORIZADO = 3;
    const STATUS_NAO_AUTORIZADO = 4;
    const STATUS_REVISAR_PENDENCIAS = 5;

    /**
     * AndamentoPreAutorizacao constructor.
     * @param $dao
     */
    public function __construct($dao)
    {
        $this->dao = $dao;

        $this->daoAutorizada = new \cl_empautorizacaoautorizada;
        $this->daoEmpAut = new \cl_empautoriza;
        $this->daoOrcReserva = new \cl_orcreserva;
        $this->daoOrcReservaAut = new \cl_orcreservaaut;
        $this->daoEmpAutDot = new \cl_empautidot;
    }

    public function salvar(AndamentoPreAutorizacao $liberacao)
    {
        $this->dao->id  = $liberacao->getCodigoAndamento();
        $this->dao->empautoriza_id  = $liberacao->getCodigoAutorizacao();
        $this->dao->status_id  = $liberacao->getIdStatus();
        $this->dao->observacao  = $liberacao->getObservacao();
        $this->dao->id_usuario  = $liberacao->getIdUsuario();
        $this->dao->data = date("Y/m/d");

        if (empty($this->dao->id)) {
            $this->dao->incluir();
        } elseif ($this->existeStatusAndamento()) {
            $this->dao->alterar($this->dao->id);
            if ($this->dao->erro_status == '0') {
                throw new Exception("Não foi possível alterar as informações.\nContate o suporte.");
            }
        } else {
            $this->dao->incluir();
        }

        if ($this->dao->erro_status == '0') {
            throw new Exception("Não foi possível salvar as informações.\nContate o suporte.");
        }

        $this->avaliaAutorizacao();
    }

    public function avaliaAutorizacao()
    {
        /**
         * Bloco da anulacao da autorizacao
         */
        if ($this->dao->status_id != $this::STATUS_NAO_AUTORIZADO && $this->temAnulacao()) {
            $this->cancelaAnulacao();
        } elseif ($this->dao->status_id == $this::STATUS_NAO_AUTORIZADO) {
            $this->anulaAutorizacao();
        }

        /**
         * Bloco da reserva de saldo
         */

        $orcReserva = $this->existeReservaSaldo();
        if ($this->dao->status_id == $this::STATUS_AUTORIZADO && !empty($orcReserva->o80_codres)) {
            $this->alterarReserva($orcReserva->o80_codres);
        }
        if ($this->dao->status_id != $this::STATUS_AUTORIZADO && !empty($orcReserva->o80_codres)) {
            $this->excluiReservaSaldo($orcReserva->o80_codres);
        } elseif ($this->dao->status_id == $this::STATUS_AUTORIZADO && empty($orcReserva->o80_codres)) {
            $this->geraReservaSaldo();
        }

        /**
         * Bloco da autorizacao
         */
        if ($this->dao->status_id != $this::STATUS_AUTORIZADO &&
            $this->daoAutorizada->estaAutorizada($this->dao->empautoriza_id)
        ) {
            $this->cancelaAutorizada();
        } elseif ($this->dao->status_id == $this::STATUS_AUTORIZADO &&
            !$this->daoAutorizada->estaAutorizada($this->dao->empautoriza_id)
        ) {
            $this->autoriza();
        }
    }

    public function temAnulacao()
    {
        $sqlBuscaAnulacao = $this->daoEmpAut->sql_query_file($this->dao->empautoriza_id, $campos = "e54_anulad");
        $rsBuscaAnulacao = db_query($sqlBuscaAnulacao);
        $anulado = db_utils::fieldsMemory($rsBuscaAnulacao, 0)->e54_anulad;

        if (!empty($anulado)) {
            return true;
        }
        return false;
    }

    public function cancelaAnulacao()
    {
        $this->daoEmpAut->e54_autori = $this->dao->empautoriza_id;
        $this->daoEmpAut->e54_anulad = null;
        $this->daoEmpAut->alterar($this->dao->empautoriza_id);
        if ($this->daoEmpAut->erro_status == '0') {
            throw new Exception('Ocorreu um erro ao cancelar a anulação da autorização.');
        }
    }

    public function anulaAutorizacao()
    {
        $this->daoEmpAut->e54_autori = $this->dao->empautoriza_id;
        $this->daoEmpAut->e54_anulad = date("Y/m/d");
        $this->daoEmpAut->alterar($this->daoEmpAut->e54_autori);
        if ($this->daoEmpAut->erro_status == '0') {
            throw new Exception('Ocorreu um erro ao anular a autorização.');
        }
    }

    public function autoriza()
    {
        $this->daoAutorizada->empautoriza_id = $this->dao->empautoriza_id;
        $this->daoAutorizada->incluir();
        if ($this->daoAutorizada->erro_status == '0') {
            throw new Exception('Ocorreu um erro ao incluir ao liberar a autorização.');
        }
    }

    public function cancelaAutorizada()
    {
        $where = "empautoriza_id = ".$this->dao->empautoriza_id;
        $this->daoAutorizada->excluir(null, $where);
        if ($this->daoAutorizada->erro_status == '0') {
            throw new Exception('Ocorreu um erro ao excluir a liberação da autorização.');
        }
    }

    public function existeStatusAndamento()
    {
        $where = "id = ".$this->dao->id;
        $where .= " and status_id = ".$this->dao->status_id;

        $sqlAndamento = $this->dao->sql_query_file(null, 'id', null, $where);
        $rsAndamento = db_query($sqlAndamento);
        if (pg_num_rows($rsAndamento) > 0) {
            return true;
        }
        return false;
    }

    public function existeReservaSaldo()
    {
        $sqlOrcReservaAut = $this->daoOrcReservaAut->sql_query_file(
            null,
            "o83_codres as o80_codres",
            "",
            "o83_autori=".$this->dao->empautoriza_id
        );
        $rsOrcReservaAut = db_query($sqlOrcReservaAut);
        $orcReservaAut  = db_utils::fieldsMemory($rsOrcReservaAut, 0);

        return $orcReservaAut;
    }

    public function geraReservaSaldo()
    {

        $campos = "e56_coddot, e54_valor";
        $where = " empautidot.e56_autori = ".$this->dao->empautoriza_id;
        $where .= " and empautidot.e56_anousu = ".db_getsession("DB_anousu");
        $sqlDaoEmpAutDot = $this->daoEmpAutDot->sql_query_dotacao(null, $campos, null, $where);

        $rsEmpAutDot = db_query($sqlDaoEmpAutDot);
        $empAutDot = null;
        if (pg_numrows($rsEmpAutDot) > 0) {
            $empAutDot = db_utils::fieldsMemory($rsEmpAutDot, 0);
        }

        $this->daoOrcReserva->o80_anousu = db_getsession("DB_anousu");
        $this->daoOrcReserva->o80_coddot = $empAutDot->e56_coddot;
        $this->daoOrcReserva->o80_dtfim = date('Y', db_getsession('DB_datausu')) . "-12-31";
        $this->daoOrcReserva->o80_dtini = date('Y-m-d', db_getsession('DB_datausu'));
        $this->daoOrcReserva->o80_dtlanc = date('Y-m-d', db_getsession('DB_datausu'));
        $this->daoOrcReserva->o80_valor = $empAutDot->e54_valor;
        $this->daoOrcReserva->o80_descr = "Reserva da autorização".$this->dao->empautoriza_id;

        $o80_codres = '';
        $this->daoOrcReserva->incluir($o80_codres);
        if ($this->daoOrcReserva->erro_status == '0') {
            throw new Exception('Ocorreu um erro ao incluir a reserva de saldo.');
        }
        $o80_codres = $this->daoOrcReserva->o80_codres;

        $this->daoOrcReservaAut->o83_codres = $o80_codres;
        $this->daoOrcReservaAut->o83_autori = $this->dao->empautoriza_id;
        $this->daoOrcReservaAut->incluir($o80_codres);
        if ($this->daoOrcReservaAut->erro_status == '0') {
            throw new Exception('Ocorreu um erro ao incluir o vínculo com a reserva de saldo.');
        }
    }

    public function excluiReservaSaldo($o80_codres)
    {
        $this->daoOrcReservaAut->o83_codres = $o80_codres;
        $this->daoOrcReservaAut->excluir($o80_codres);
        if ($this->daoOrcReservaAut->erro_status == '0') {
            throw new Exception('Ocorreu um erro ao excluir o vínculo com a reserva de saldo.');
        }



        $this->daoOrcReserva->o80_codres = $o80_codres;
        $this->daoOrcReserva->excluir($o80_codres);
        if ($this->daoOrcReserva->erro_status == '0') {
            throw new Exception('Ocorreu um erro ao excluir a reserva de saldo.');
        }
    }

    public function listarAndamentosPreAutorizacao($dataInicial = null, $dataFinal = null, $status = null, $modo = null)
    {
        $sqlUltimoLancamento = $this->dao->sqlUltimoAndamentoAutorizacao();

        $campos = " e54_autori,
        e54_anousu,
        e54_valor,
        e54_emiss,
        data,
        z01_nome,
        andamentoemppreautorizacaostatus.status as status_descricao,
        andamentoemppreautorizacaostatus.id as status_id,
        andamentoemppreautorizacao.observacao as observacao,
        andamentoemppreautorizacao.id as andamento_id";

        $where = " e54_anousu = ".db_getsession("DB_anousu");

        $orderBy = "";
        $limit = "";
        if ($modo == 'consulta') {
            $orderBy = " order by andamentoemppreautorizacao.empautoriza_id, andamentoemppreautorizacao.id ";
            $where .= " and empautoriza.e54_logincriador = ".db_getsession("DB_id_usuario");
        }

        $where .= " and andamentoemppreautorizacao.id in (".$sqlUltimoLancamento.")";

        if (!empty($dataInicial) && !empty($dataFinal)) {
            $where .= " and data between '$dataInicial' and '$dataFinal'";
            $limit = "";
        }

        $statusWhere = " and status_id <> ".$this::STATUS_AUTORIZADO; // Não trazendo autorizados (pesquisa por todos)
        if (!empty($status) && $status != "0") {
            $statusWhere = " and status_id = ".$status;
        }

        $where .= $statusWhere;

        $sqlAndamentoPreAut =  $this->dao->sql_query(null, $campos, null, $where);
        $sqlAndamentoPreAut .= $orderBy;
        $sqlAndamentoPreAut .= $limit;

        $rsAndamentoPreAut = db_query($sqlAndamentoPreAut);
        $andamentoPreAut = db_utils::getCollectionByRecord($rsAndamentoPreAut);

        return $andamentoPreAut;
    }

    public function listarAndamentoPorAutorizacao($empatoriza_id)
    {
        $campos = " e54_autori,
        e54_anousu,
        e54_valor,
        e54_emiss,
        data,
        z01_nome,
        andamentoemppreautorizacaostatus.status as status_descricao,
        andamentoemppreautorizacaostatus.id as status_id,
        andamentoemppreautorizacao.observacao as observacao,
        andamentoemppreautorizacao.id as andamento_id";

        $where = " e54_autori = ".$empatoriza_id;
        $orderBy = " order by andamentoemppreautorizacao.empautoriza_id, andamentoemppreautorizacao.id ";

        $sqlAndamentoPreAut =  $this->dao->sql_query(null, $campos, null, $where);
        $sqlAndamentoPreAut .= $orderBy;
        $rsAndamentoPreAut = db_query($sqlAndamentoPreAut);
        $andamentoPreAut = db_utils::getCollectionByRecord($rsAndamentoPreAut);

        return $andamentoPreAut;
    }

    /**
     * @param $codres
     * @throws Exception
     * funcao para alterar a descr da orcreserva e deletar elementos da tabela orcreservasol
     */
    public function alterarReserva($codres)
    {
        $this->daoOrcReserva->o80_descr = 'Reserva da autorização'.$this->dao->empautoriza_id;
        $this->daoOrcReserva->o80_codres = $codres;
        $this->daoOrcReserva->alterar($codres);
        $this->apagarReservaSol($codres);
    }

    public function apagarReservaSol($codres)
    {
        $daoOrcReservaSol = new cl_orcreservasol();
        $daoOrcReservaSol->excluir(null, "o82_codres = $codres");
        if ($this->daoOrcReservaSol->erro_status == '0') {
            throw new Exception('Ocorreu um erro ao excluir o vínculo com a reserva de saldo.');
        }
    }
}
