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

use BusinessException;
use ECidade\RecursosHumanos\RH\Assentamento\Model\ControleMedicoExame;

class ControleMedico
{
    /**
     * @var array ControleMedicoExame
     */
    private $exames = [];

    /**
     * @return array
     */
    public function getExames()
    {
        return $this->exames;
    }

    /**
     * @param array $exames
     */
    public function setExames($exames)
    {
        $this->exames = $exames;
    }

    /**
     * @return DateTime
     */
    public function getDataAtestado()
    {
        return $this->dataAtestado;
    }

    /**
     * @param DateTime $dataAtestado
     */
    public function setDataAtestado($dataAtestado)
    {
        $this->dataAtestado = $dataAtestado;
    }

    /**
     * @return int
     */
    public function getTipoExameOcupacional()
    {
        return $this->tipoExameOcupacional;
    }

    /**
     * @param int $tipoExameOcupacional
     */
    public function setTipoExameOcupacional($tipoExameOcupacional)
    {
        $this->tipoExameOcupacional = $tipoExameOcupacional;
    }

    /**
     * @return int
     */
    public function getResultadoAtestado()
    {
        return $this->resultadoAtestado;
    }

    /**
     * @param int $resultadoAtestado
     */
    public function setResultadoAtestado($resultadoAtestado)
    {
        $this->resultadoAtestado = $resultadoAtestado;
    }

    /**
     * @return string
     */
    public function getNomeMedico()
    {
        return $this->nomeMedico;
    }

    /**
     * @param string $nomeMedico
     */
    public function setNomeMedico($nomeMedico)
    {
        $this->nomeMedico = $nomeMedico;
    }

    /**
     * @return string
     */
    public function getCrmMedico()
    {
        return $this->crmMedico;
    }

    /**
     * @param string $crmMedico
     */
    public function setCrmMedico($crmMedico)
    {
        $this->crmMedico = $crmMedico;
    }

    /**
     * @return string
     */
    public function getUfCrm()
    {
        return $this->ufCrm;
    }

    /**
     * @param string $ufCrm
     */
    public function setUfCrm($ufCrm)
    {
        $this->ufCrm = $ufCrm;
    }

    /**
     * @return string
     */
    public function getCpfResponsavel()
    {
        return $this->cpfResponsavel;
    }

    /**
     * @param string $cpfResponsavel
     */
    public function setCpfResponsavel($cpfResponsavel)
    {
        $this->cpfResponsavel = $cpfResponsavel;
    }

    /**
     * @return string
     */
    public function getNomeResponsavel()
    {
        return $this->nomeResponsavel;
    }

    /**
     * @param string $nomeResponsavel
     */
    public function setNomeResponsavel($nomeResponsavel)
    {
        $this->nomeResponsavel = $nomeResponsavel;
    }

    /**
     * @return string
     */
    public function getCrmResponsavel()
    {
        return $this->crmResponsavel;
    }

    /**
     * @param string $crmResponsavel
     */
    public function setCrmResponsavel($crmResponsavel)
    {
        $this->crmResponsavel = $crmResponsavel;
    }

    /**
     * @return string
     */
    public function getUfCrmResponsavel()
    {
        return $this->ufCrmResponsavel;
    }

    /**
     * @param string $ufCrmResponsavel
     */
    public function setUfCrmResponsavel($ufCrmResponsavel)
    {
        $this->ufCrmResponsavel = $ufCrmResponsavel;
    }

    /**
     * @return int
     */
    public function getCodigoAssentamento()
    {
        return $this->codigoAssentamento;
    }

    /**
     * @param int $codigoAssentamento
     */
    public function setCodigoAssentamento($codigoAssentamento)
    {
        $this->codigoAssentamento = $codigoAssentamento;
    }
    /**
     * @var DateTime
     */
    private $dataAtestado;
    /**
     * @var int
     */
    private $tipoExameOcupacional;
    /**
     * @var bool
     */
    private $resultadoAtestado;
    /**
     * @var string
     */
    private $nomeMedico;
    /**
     * @var string
     */
    private $crmMedico;
    /**
     * @var string
     */
    private $ufCrm;
    /**
     * @var string
     */
    private $cpfResponsavel;
    /**
     * @var string
     */
    private $nomeResponsavel;
    /**
     * @var string
     */
    private $crmResponsavel;
    /**
     * @var string
     */
    private $ufCrmResponsavel;
    /**
     * @var int
     */
    private $codigoAssentamento;
    /**
     * @var int
     */
    private $codigo;

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    // Tipos de Exames
    const EXAMEADMISSIONAL = 0;
    const EXAMEPERIODICO = 1;
    const EXAMERETORNOTRABALHO = 2;
    const EXAMEMUDANCA = 3;
    const EXAMEMONITORACAOPONTUAL = 4;
    const EXAMEDEMISSIONAL = 9;

    /**
     * @var array
     */
    const DESCRICOES_TIPOS_EXAMES = [
        self::EXAMEADMISSIONAL => "0 - Exame médico admissional",
        self::EXAMEPERIODICO => "1 - Exame médico periódico, conforme Norma Regulamentadora 07 - NR-07 e/ou "
            . "planejamento do Programa de Controle Médico de Saúde Ocupacional - PCMSO",
        self::EXAMERETORNOTRABALHO => "2 - Exame médico de retorno ao trabalho",
        self::EXAMEMUDANCA => "3 - Exame médico de mudança de função ou de mudança de risco ocupacional",
        self::EXAMEMONITORACAOPONTUAL => "4 - Exame médico de monitoração pontual, não enquadrado nos demais casos",
        self::EXAMEDEMISSIONAL => "9 - Exame médico demissional"
    ];

    // Tipos de Resultados
    const APTO = 1;
    const INAPTO = 2;

    const DESCRICOES_TIPO_RESULTADOS = [
        0 => "Não informado",
        self::APTO => "1 - Apto",
        self::INAPTO => "2 - Inapto"
    ];

    public static function getTiposExames()
    {
        return ControleMedico::DESCRICOES_TIPOS_EXAMES;
    }

    public static function getTiposResultados()
    {
        return ControleMedico::DESCRICOES_TIPO_RESULTADOS;
    }

    /**
     * Construtor
     */
    public function __construct($codigoAssentamento = null)
    {
        if (!empty($codigoAssentamento)) {
            $dao = new \cl_monitoramentosaude();
            $where = "h26_assenta = {$codigoAssentamento} ";
            $sql = $dao->sql_query(null, "*", null, $where);
            $rs = db_query($sql);

            if (!$rs) {
                $msg = "Erro ao buscar informações de controle Médico do Assentamento Código {$codigoAssentamento}.";
                throw new BusinessException($msg);
            }
            if (pg_num_rows($rs) >= 1) {
                $controleMedico = \db_utils::fieldsMemory($rs, 0);
                $this->setCodigo($controleMedico->h26_sequencial);
                $this->setCodigoAssentamento($controleMedico->h26_assenta);
                $this->setTipoExameOcupacional($controleMedico->h26_tipoexameocupacional);
                $this->setDataAtestado($controleMedico->h26_dataatestado);
                $this->setResultadoAtestado($controleMedico->h26_resultadoatestado);
                $this->setNomeMedico($controleMedico->h26_nomemedico);
                $this->setCrmMedico($controleMedico->h26_crmmedico);
                $this->setUfCrm($controleMedico->h26_ufcrm);
                $this->setCpfResponsavel($controleMedico->h26_cpfresponsavel);
                $this->setNomeResponsavel($controleMedico->h26_nomeresponsavel);
                $this->setCrmResponsavel($controleMedico->h26_crmresponsavel);
                $this->setUfCrmResponsavel($controleMedico->h26_ufcrmresponsavel);
                $this->buscaExames();
            }
        }
    }

    public function salvar()
    {
        if (empty($this->codigoAssentamento)) {
            throw new BusinessException("Código de Assentamento não informado.");
        }
        $dao = new \cl_monitoramentosaude();
        $where = "h26_assenta = {$this->codigoAssentamento} ";
        $sql = $dao->sql_query(null, "h26_sequencial", null, $where);
        $rs = db_query($sql);
        if (!$rs) {
            $msg = "Erro ao buscar informações de controle Médico do Assentamento Código {$this->codigoAssentamento}.";
            throw new BusinessException($msg);
        }

        if (pg_num_rows($rs) >= 1) {
            $controleMedico = \db_utils::fieldsMemory($rs, 0);
            if ($this->getCodigo() != $controleMedico->h26_sequencial) {
                throw new BusinessException("Código de controle incompativel com a alteração.");
            }
        }

        $dao = new \cl_monitoramentosaude();

        $dao->h26_sequencial = $this->codigo;
        $dao->h26_assenta = $this->codigoAssentamento;
        $dao->h26_tipoexameocupacional = $this->tipoExameOcupacional;
        $dao->h26_dataatestado = $this->dataAtestado;
        $dao->h26_resultadoatestado = $this->resultadoAtestado;
        $dao->h26_nomemedico = $this->nomeMedico;
        $dao->h26_crmmedico = $this->crmMedico;
        $dao->h26_ufcrm = $this->ufCrm;
        $dao->h26_cpfresponsavel = $this->cpfResponsavel;
        $dao->h26_nomeresponsavel = $this->nomeResponsavel;
        $dao->h26_crmresponsavel = $this->crmResponsavel;
        $dao->h26_ufcrmresponsavel = $this->ufCrmResponsavel;

        if (empty($this->codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($this->codigo);
        }

        if ($dao->erro_status == 2) {
            throw new \DBException($dao->erro_msg);
        }
        $this->setCodigo($dao->h26_sequencial);
        $this->salvarExames();
    }

    public function addExame(ControleMedicoExame $exame)
    {
        $this->exames[] = $exame;
    }

    public function salvarExames()
    {
        if (empty($this->codigo)) {
            throw new \BusinessException("O Controle médico não está salvo no momento.");
        }
        // deletamos todos os exames e re-adicionamos
        $sql = "delete from recursoshumanos.monitoramentosaudeexame where h27_monitoriamentosaude = {$this->codigo}";
        $rs = \db_query($sql);
        if (!$rs) {
            throw new \DBException("Houve algum problema ao excluir os exames.");
        }
        foreach ($this->exames as $exame) {
            $exame->setCodigoMonitoramentoSaude($this->codigo);
            $exame->salvar();
        }
    }

    private function buscaExames()
    {
        $sql = "
            select
                h27_sequencial
            from
                recursoshumanos.monitoramentosaudeexame
            where
                  h27_monitoriamentosaude = {$this->getCodigo()}";
        $rs = \db_query($sql);

        if (!$rs) {
            $msg = "Houve um erro ao buscar os exames do Assentamento Código:{$this->getCodigoAssentamento()} .";
            throw new \DBException($msg);
        }

        if (pg_num_rows($rs) == 0) {
            $msg = "Nenhum exame encontrado para o Assentamento Código:{$this->getCodigoAssentamento()} .";
            throw new \BusinessException($msg);
        }
        $totalExames = pg_num_rows($rs);
        for ($i = 0; $i < $totalExames; $i++) {
            $codigoExame = \db_utils::fieldsMemory($rs, $i)->h27_sequencial;
            $exame = new ControleMedicoExame($codigoExame);
            $this->addExame($exame);
        }
    }
}
