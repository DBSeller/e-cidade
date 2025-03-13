<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Contrato\Model;

use BusinessException;
use DBException;
use Exception;

class ContratoJornada
{

    //Tipos de jornadas
    const DOZE_POR_TRINTA_SEIS = 2;
    const FIXO_FOLGA_VARIAVEL = 3;
    const FIXO_FOLGA_DOMINGO = 4;
    const FIXO_FOLGA_NAO_DOMINGO = 5;
    const FIXO_FOLGA_SEMANA_ADICIONAL = 6;
    const ININTERRUPTO_REVEZAMENTO = 7;
    const DEMAIS_TIPOS = 9;

    /**
     * @var array
     */
    const DESCRICOES_JORNADAS = [
        self::DEMAIS_TIPOS => self::DEMAIS_TIPOS . " - Demais tipos de jornada",
        self::DOZE_POR_TRINTA_SEIS => self::DOZE_POR_TRINTA_SEIS . "- Jornada 12 x 36 (12 horas de trabalho seguidas 
            de 36 horas ininterruptas descanso)",
        self::FIXO_FOLGA_VARIAVEL => self::FIXO_FOLGA_VARIAVEL . " - Jornada com horário diário fixo e folga 
            variável",
        self::FIXO_FOLGA_DOMINGO => self::FIXO_FOLGA_DOMINGO . " - Jornada com horário diário fixo e folga fixa 
            (no domingo)",
        self::FIXO_FOLGA_NAO_DOMINGO => self::FIXO_FOLGA_NAO_DOMINGO . " - Jornada com horário diário fixo e folga 
            fixa (exceto no domingo)",
        self::FIXO_FOLGA_SEMANA_ADICIONAL => self::FIXO_FOLGA_SEMANA_ADICIONAL . " - Jornada com horário diário fixo e 
            folga fixa (em outro dia da semana), com folga adicional periódica no domingo",
        self::ININTERRUPTO_REVEZAMENTO => self::ININTERRUPTO_REVEZAMENTO . " - Turno ininterrupto de revezamento"
    ];

    //Tipode contrato em tempo parcial
    const NAO_CONTRATO = 0;
    const VINTE_CINCO_H_SEMANAL = 1;
    const TRINTA_H_SEMANAL = 2;
    const VINTE_SEIS_H_SEMANAL = 3;

    /**
     * @var array
     */
    const CONTRATOS_PARCIAIS = [
        self::NAO_CONTRATO => " Não é contrato em tempo parcial",
        self::VINTE_CINCO_H_SEMANAL => self::VINTE_CINCO_H_SEMANAL . "  - Limitado a 25 horas semanais",
        self::TRINTA_H_SEMANAL => self::TRINTA_H_SEMANAL . " - Limitado a 30 horas semanais",
        self::VINTE_SEIS_H_SEMANAL => self::VINTE_SEIS_H_SEMANAL . " - Limitado a 26 horas semanais"
    ];

    /**
     * @var int
     */
    private $codigo;

    /**
     * @var int
     */
    private $matricula;

    /**
     * @var int
     */
    private $codigoInstituicao;

    /**
     * @var int
     */
    private $tipoJornada;

    /**
     * @var int
     */
    private $tempoParcial;

    /**
     * @var string
     */
    private $horarioNoturno;

    /**
     * @var string
     */
    private $descricaoJornada;

    /**
     * @var array
     */
    private $simNao = ['S' => 'Sim','N' => 'Não'];

    public function __construct($matricula = null, $codigoInstituicao = null)
    {
        if ($matricula && $codigoInstituicao) {
            $dao =new \cl_rhservidorcontratojornada();
            $campos = ["rh254_sequencial",
                       "rh254_instit",
                       "rh254_matricula",
                       "rh254_tipojornada",
                       "rh254_tempoparcial",
                       "rh254_horarionoturno",
                       "rh254_descricaojornada"
                       ];
            $camposQuery = implode(',', $campos);
            $where = "rh254_matricula = {$matricula} and rh254_instit = {$codigoInstituicao} ";
            $sql = $dao->sql_query(null, $camposQuery, null, $where);
            $rs = db_query($sql);
            if (!$rs) {
                throw new DBException("Erro ao buscar informações.");
            }
            if (pg_numrows($rs)>0) {
                $dao = \db_utils::fieldsMemory($rs, 0);
                $this->setCodigo((int)$dao->rh254_sequencial);
                $this->setCodigoInstituicao((int)$dao->rh254_instit);
                $this->setMatricula((int)$dao->rh254_matricula);
                $this->setTipoJornada((int)$dao->rh254_tipojornada);
                $this->setTempoParcial((int)$dao->rh254_tempoparcial);
                $this->setHorarioNoturno($dao->rh254_horarionoturno);
                $this->setDescricaoJornada($dao->rh254_descricaojornada);
            }
        }
    }

    public function salvar()
    {
        $this->validar();
        $dao = new \cl_rhservidorcontratojornada();
        $dao->rh254_sequencial = $this->getCodigo();
        $dao->rh254_matricula = $this->getMatricula();
        $dao->rh254_instit = $this->getCodigoInstituicao();
        $dao->rh254_tipojornada = $this->getTipoJornada();
        $dao->rh254_tempoparcial = $this->getTempoParcial();
        $dao->rh254_horarionoturno = $this->getHorarioNoturno();
        $dao->rh254_descricaojornada = $this->getDescricaoJornada();
        try {
            if (!empty($this->codigo)) {
                $dao->alterar($this->codigo);
            } else {
                $dao->incluir();
                $this->codigo = $dao->rh254_sequencial;
            }
        } catch (Exception $e) {
            throw new DBException($e->getMessage());
        }
    }

    private function validar()
    {
        if (empty($this->matricula)) {
            throw new BusinessException("Não informada a matrícula.");
        }
        if (empty($this->codigoInstituicao)) {
            throw new BusinessException("Não informado código da instituição.");
        }
        if (empty(self::DESCRICOES_JORNADAS[$this->getTipoJornada()])) {
            throw new BusinessException("Tipo de jornada não válida.");
        }
        if (empty(self::CONTRATOS_PARCIAIS[$this->getTempoParcial()])) {
            throw new BusinessException("Tipo de contrato em tempo parcial não é válido.");
        }
        if (empty($this->simNao[$this->getHorarioNoturno()])) {
            throw new BusinessException("Valores válidos para se possui horário notuno são 'S' e 'N' somente.");
        }
    }
  

    /**
     * Get the value of codigo
     *
     * @return  int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set the value of codigo
     *
     * @param  int  $codigo
     *
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Get the value of matricula
     *
     * @return  int
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set the value of matricula
     *
     * @param  int  $matricula
     *
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * Get the value of codigoInstituicao
     *
     * @return  int
     */
    public function getCodigoInstituicao()
    {
        return $this->codigoInstituicao;
    }

    /**
     * Set the value of codigoInstituicao
     *
     * @param  int  $codigoInstituicao
     *
     */
    public function setCodigoInstituicao($codigoInstituicao)
    {
        $this->codigoInstituicao = $codigoInstituicao;
    }

    /**
     * Get the value of tipoJornada
     *
     * @return  int
     */
    public function getTipoJornada()
    {
        return $this->tipoJornada;
    }

    /**
     * Set the value of tipoJornada
     *
     * @param  int  $tipoJornada
     *
     */
    public function setTipoJornada($tipoJornada)
    {
        $this->tipoJornada = $tipoJornada;
    }


    /**
     * Get the value of horarioNoturno
     *
     * @return  string
     */
    public function getHorarioNoturno()
    {
        return $this->horarioNoturno;
    }

    /**
     * Set the value of horarioNoturno
     *
     * @param  string  $horarioNoturno
     *
     */
    public function setHorarioNoturno($horarioNoturno)
    {
        $this->horarioNoturno = $horarioNoturno;
    }

    /**
     * Get the value of descricaoJornada
     *
     * @return  string
     */
    public function getDescricaoJornada()
    {
        return $this->descricaoJornada;
    }

    /**
     * Set the value of descricaoJornada
     *
     * @param  string  $descricaoJornada
     *
     */
    public function setDescricaoJornada($descricaoJornada)
    {
        $this->descricaoJornada = $descricaoJornada;
    }

    /**
     * Get the value of tempoParcial
     *
     * @return  int
     */
    public function getTempoParcial()
    {
        return $this->tempoParcial;
    }

    /**
     * Set the value of tempoParcial
     *
     * @param  int  $tempoParcial
     *
     * @return  self
     */
    public function setTempoParcial($tempoParcial)
    {
        $this->tempoParcial = $tempoParcial;
    }
}
