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

namespace ECidade\Saude\Ambulatorial\Repository;

use ECidade\Saude\Ambulatorial\Model\Prontuario;
use Exception;

/**
 * Class AutorizacaoEmpenhoRepository
 * @package ECidade\Patrimonial\Compras\Repository
 */
class ProntuarioRepository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @var Object
     */
    private $dao;

    /**
     * ProntuarioRepository constructor.
     * @param $dao \cl_prontuarios
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param Prontuario $prontuario
     * @return Prontuario
     * @throws Exception
     */
    public function salvar(Prontuario $prontuario)
    {
        $this->dao->sd24_i_codigo = $prontuario->getCodigo();
        $this->dao->sd24_i_ano = $prontuario->getAno();
        $this->dao->sd24_i_mes = $prontuario->getMes();
        $this->dao->sd24_i_seq = $prontuario->getSequencia();
        $this->dao->sd24_i_unidade = $prontuario->getUnidade();
        $this->dao->sd24_i_numcgs = $prontuario->getCgs() ? $prontuario->getCgs()->getCodigo() : '';
        $this->dao->sd24_v_motivo = $prontuario->getMotivo();
        $this->dao->sd24_d_cadastro = $prontuario->getDataCadastro();
        $this->dao->sd24_c_cadastro = $prontuario->getHoraCadastro();
        $this->dao->sd24_i_cid = $prontuario->getCid();
        $this->dao->sd24_v_pressao = $prontuario->getPressao();
        $this->dao->sd24_f_peso = $prontuario->getPeso();
        $this->dao->sd24_f_temperatura = $prontuario->getTemperatura();
        $this->dao->sd24_i_profissional = $prontuario->getProfissional();
        $this->dao->sd24_t_diagnostico = $prontuario->getDiagnostico();
        $this->dao->sd24_i_siasih = $prontuario->getSiasih();
        $this->dao->sd24_c_digitada = $prontuario->getDigitada();
        $this->dao->sd24_i_login = $prontuario->getLogin();
        $this->dao->sd24_i_motivo = $prontuario->getMotivoAtendimento();
        $this->dao->sd24_i_tipo = $prontuario->getTipo();
        $this->dao->sd24_i_acaoprog = $prontuario->getAcaoProgramatica();
        $this->dao->sd24_setorambulatorial = $prontuario->getSetorAmbulatorial();
        $this->dao->sd24_idadegestacional = $prontuario->getIdadeGestacional();
        $this->dao->sd24_dum = $prontuario->getDum();

        if (!$prontuario->getCodigo()) {
            $this->dao->incluir(null);
        } else {
            $this->dao->alterar($prontuario->getCodigo());
        }
        if ($this->dao->erro_status === '0') {
            throw new Exception($this->dao->erro_msg);
        }

        return $prontuario;
    }

    public function buscaDadosGestante($codigo)
    {
        $rs = $this->dao->sql_record(
            $this->dao->sql_query_file(
                null,
                "sd24_idadegestacional,sd24_dum",
                '',
                "sd24_i_codigo= $codigo"
            )
        );

        if (!$rs) {
            return false;
        }

        return pg_fetch_all($rs);
    }

    public function buscaProntuario($codigo)
    {
        $rs = $this->dao->sql_record(
            $this->dao->sql_query_file($codigo)
        );

        if (!$rs) {
            return false;
        }

        return pg_fetch_all($rs);
    }
}
