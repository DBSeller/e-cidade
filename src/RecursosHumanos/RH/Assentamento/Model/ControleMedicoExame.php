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
namespace ECidade\RecursosHumanos\RH\Assentamento\Model;

class ControleMedicoExame
{
    const EXAMEINICIAL = 1;
    const EXAMESEQUENCIAL = 2;

    const ORDEMEXAME = [
        0 => "Não informado",
        self::EXAMEINICIAL => self::EXAMEINICIAL . " - Inicial",
        self::EXAMESEQUENCIAL => self::EXAMESEQUENCIAL . " - Sequencial"
    ];

    const RESULTADONORMAL = 1;
    const RESULTADOALTERADO = 2;
    const RESULTADOESTAVEL = 3;
    const RESULTADOAGRAVAMENTO = 4;
    const INDICACAORESULTADOS = [
        0 => "Não informado",
        self::RESULTADONORMAL => self::RESULTADONORMAL . " - Normal",
        self::RESULTADOALTERADO => self::RESULTADOALTERADO . " - Alterado",
        self::RESULTADOESTAVEL => self::RESULTADOESTAVEL . " - Estável",
        self::RESULTADOAGRAVAMENTO => self::RESULTADOAGRAVAMENTO . " - Agravamento"
    ];

    public static function getDescricaoOrdemExame()
    {
        return self::ORDEMEXAME;
    }

    public static function getDescricaoResultado()
    {
        return self::INDICACAORESULTADOS;
    }

    /**
     * @var string
     */
    private $descricaoProcedimento;

    /**
     * @return string
     */
    public function getDescricaoProcedimento()
    {
        return $this->descricaoProcedimento;
    }

    /**
     * @param string $descricaoProcedimento
     */
    public function setDescricaoProcedimento($descricaoProcedimento)
    {
        $this->descricaoProcedimento = $descricaoProcedimento;
    }

    /**
     * @var int
     */
    private $codigo;
    /**
     * @var int
     */
    private $codigoMonitoramentoSaude;
    /**
     * @var DateTime
     */
    private $data;
    /**
     * @var int
     */
    private $resultado;
    /**
     * @var string
     */
    private $procedimento;
    /**
     * @var string
     */
    private $observacao;
    /**
     * @var int
     */
    private $ordem;
    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return int
     */
    public function getCodigoMonitoramentoSaude()
    {
        return $this->codigoMonitoramentoSaude;
    }

    /**
     * @param int $codigoMonitoramentoSaude
     */
    public function setCodigoMonitoramentoSaude($codigoMonitoramentoSaude)
    {
        $this->codigoMonitoramentoSaude = $codigoMonitoramentoSaude;
    }

    /**
     * @return DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param DateTime $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * @param int $resultado
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;
    }

    /**
     * @return string
     */
    public function getProcedimento()
    {
        return $this->procedimento;
    }

    /**
     * @param string $procedimento
     */
    public function setProcedimento($procedimento)
    {
        $this->procedimento = $procedimento;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    /**
     * @return int
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * @param int $ordem
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
    }

    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $dao = new \cl_monitoramentosaudeexame();
            $where = "h27_sequencial = {$codigo} ";
            $sql = $dao->sql_query(null, "*", null, $where);
            $rs = db_query($sql);

            if (!$rs) {
                $msg = "Erro ao buscar informações de Exame Médico Código {$codigo}.";
                throw new BusinessException($msg);
            }
            if (pg_num_rows($rs) >= 1) {
                $exame = \db_utils::fieldsMemory($rs, 0);
                $this->setCodigo($exame->h27_sequencial);
                $this->setCodigoMonitoramentoSaude($exame->h27_monitoriamentosaude);
                $this->setData($exame->h27_data);
                $this->setResultado($exame->h27_resultado);
                $this->setProcedimento($exame->h27_procedimento);
                $this->setObservacao($exame->h27_observacao);
                $this->setOrdem($exame->h27_ordem);
                $this->buscaDescricaoProcedimento();
            }
        }
    }

    public function salvar()
    {
        if (empty($this->codigoMonitoramentoSaude)) {
            throw new BusinessException("Código do Controle Médico não informado.");
        }
        $exame = new \cl_monitoramentosaudeexame();

        $exame->h27_monitoriamentosaude = $this->codigoMonitoramentoSaude;
        $exame->h27_data = $this->data;
        $exame->h27_resultado = $this->resultado;
        $exame->h27_procedimento = $this->procedimento;
        $exame->h27_observacao = $this->observacao;
        $exame->h27_ordem = $this->ordem;
        $exame->incluir(null);

        if ($exame->erro_status == 2) {
            throw new \DBException($exame->erro_msg);
        }
        $this->setCodigo($exame->h27_sequencial);
        $this->buscaDescricaoProcedimento();
    }

    private function buscaDescricaoProcedimento()
    {
        $sql = "
            select
                   h28_descricao
            from
                 recursoshumanos.monitoramentosaudeprocedimento
            where
                  h28_codigo ='{$this->procedimento}'";
        $rs = db_query($sql);

        if (!$rs) {
            $msg = "Erro ao buscar informações do procedimento código {$this->procedimento}.";
            throw new BusinessException($msg);
        }
        if (pg_num_rows($rs) >= 1) {
            $procedimento = \db_utils::fieldsMemory($rs, 0)->h28_descricao;
            $this->setDescricaoProcedimento($procedimento);
        }
    }
}
