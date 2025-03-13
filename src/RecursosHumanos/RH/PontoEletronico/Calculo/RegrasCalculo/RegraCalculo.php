<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;

/**
 * Classe com a representação básica
 * de uma regra de cálculo de horas do ponto
 */
abstract class RegraCalculo
{
    const EXTRA_DIA_TRABALHO = 1;
    const HORA_EVENTO = 2;
    const HORA_TRABALHADA = 3;

    /**
     * Propriedade com as informações
     * necessárias para o cálculo
     *
     * @var $diaTrabalho
     */
    protected $diaTrabalho;

    /**
     * Propriedade com as informações
     * referente à jornada do servidor
     *
     * @var $jornada
     */
    protected $jornada;

    /**
     * Configurações de lotação do servidor
     *
     * @var $configuracoesLotacao
     */
    protected $configuracoesLotacao;

    /**
     * Horário de Início da HoraNoturna
     *
     * @var $horaNoturnaInicio
     */
    protected $horaNoturnaInicio;

    /**
     * Horário de Fim da HoraNoturna
     *
     * @var $horaNoturnaFim
     */
    protected $horaNoturnaFim;

    /**
     * Horário de Fim da HoraNoturna
     *
     * @var $horaNoturnaFimNoMesmoDia
     */
    protected $horaNoturnaFimNoMesmoDia;

    /**
     * Construtor da classe
     *
     * @param DiaTrabalho $diaTrabalho
     */
    public function __construct($diaTrabalho = null)
    {
        if (!empty($diaTrabalho)) {
            $this->diaTrabalho = $diaTrabalho;
            $this->jornada = $diaTrabalho->getJornada();
            $this->configuracoesLotacao = $this->diaTrabalho->getConfiguracoesLotacao();

            $data = $this->diaTrabalho->getData()->getDate();

            $this->horaNoturnaInicio = new \DateTime($data . ' 22:00');
            $this->horaNoturnaFim = new \DateTime($data . ' 05:00');
            $this->horaNoturnaFimNoMesmoDia = new \DateTime($data . ' 05:00');

            $this->horaNoturnaFim->modify('+1 day');
        }
    }

    /**
     * Método que será sempre invocado
     * em cada implementação de regra
     *
     * @param \DateTime $hora
     */
    abstract public function processar(\DateTime $hora);

    /**
     * @return DiaTrabalho
     */
    public function getDiaTrabalho()
    {
        return $this->diaTrabalho;
    }

    /**
     * @param DiaTrabalho $diaTrabalho
     * @return self
     */
    public function setDiaTrabalho($diaTrabalho)
    {
        $this->diaTrabalho = $diaTrabalho;

        return $this;
    }
}
