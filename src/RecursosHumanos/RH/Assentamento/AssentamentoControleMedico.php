<?php
/**
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

namespace ECidade\RecursosHumanos\RH\Assentamento;

use Assentamento;
use AssentamentoRepository;
use BusinessException;
use DateTime;
use db_utils;
use DBDate;
use DBException;
use DBPessoal;
use ECidade\RecursosHumanos\RH\Assentamento\Model\ControleMedico;
use Exception;
use Servidor;
use ServidorRepository;

/**
 * Class AssentamentoControleMedico
 * @package ECidade\RecursosHumanos\RH\Assentamento
 */
class AssentamentoControleMedico extends Assentamento
{
    /**
     * Código da natureza do assentamento
     *
     * @var int
     */
    const CODIGO_NATUREZA = 10;

    /**
     * @var ControleMedico
     */
    private $controleMedico;

    /**
     * AssentamentoAbonoFalta constructor.
     * @param int|null $iCodigo
     * @throws Exception
     */
    public function __construct($iCodigo = null)
    {
        if (empty($iCodigo)) {
            return;
        }

        parent::__construct($iCodigo);

        $this->setControleMedico(new ControleMedico($this->getCodigo()));
    }

    /**
     * @return ControleMedico
     */
    public function getControleMedico()
    {
        return $this->controleMedico;
    }

    /**
     * @param ControleMedico $controleMedico
     */
    public function setControleMedico($controleMedico)
    {
        $this->controleMedico = $controleMedico;
    }

    public function toArray()
    {
        $servidor = ServidorRepository::getInstanciaByCodigo(
            $this->getMatricula(),
            DBPessoal::getAnoFolha(),
            DBPessoal::getMesFolha()
        );

        $exames = [];

        foreach ($this->getControleMedico()->getExames() as $exame) {
            $exames[] = [
                "descricao" => utf8_encode($exame->getDescricaoProcedimento()),
                "data" => $exame->getData(),
                "resultado" => $exame->getResultado(),
                "ordem" => $exame->getOrdem(),
                "procedimento" => $exame->getProcedimento(),
                "observacao" => utf8_encode($exame->getObservacao()),
                "codigoMonitoramentoSaude" => $exame->getCodigoMonitoramentoSaude(),
                "codigo" => $exame->getCodigo()
            ];
        }

        $dataLancamento = $this->getDataLancamento() instanceof DBDate
            ? $this->getDataLancamento()->getDate(DBDate::DATA_PTBR)
            : $this->getDataLancamento();

        $dataTermino = $this->getDataTermino() instanceof DBDate
            ? $this->getDataTermino()->getDate(DBDate::DATA_PTBR)
            : $this->getDataTermino();

        $dataConcessao = $this->getDataConcessao() instanceof DBDate
            ? $this->getDataConcessao()->getDate(DBDate::DATA_PTBR)
            : $this->getDataConcessao();

        return array(
            'codigo' => $this->getCodigo(),
            'tipo' => $this->getTipoAssentamento(),
            'natureza' => 'padrao',
            'cgm_servidor' => $servidor->getCgm()->getCodigo(),
            'nome_servidor' => utf8_encode($servidor->getCgm()->getNome()),
            'matricula' => $this->getMatricula(),
            'dataConcessao' => $dataConcessao,
            'historico' => $this->getHistorico(),
            'codigoPortaria' => $this->getCodigoPortaria(),
            'descricaoAto' => $this->getDescricaoAto(),
            'dias' => $this->getDias(),
            'percentual' => $this->getPercentual(),
            'dataTermino' => $dataTermino,
            'segundoHistorico' => $this->getSegundoHistorico(),
            'loginUsuario' => $this->getLoginUsuario(),
            'dataLancamento' => $dataLancamento,
            'convertido' => (int)$this->isConvertido(),
            'anoPortaria' => $this->getAnoPortaria(),
            'hora' => $this->getHora(),
            "dataAtestado" => $this->getControleMedico()->getDataAtestado(),
            "codigoControleMedico" => $this->getControleMedico()->getCodigo(),
            "crmMedico" => $this->getControleMedico()->getCrmMedico(),
            "crmResponsavel" => $this->getControleMedico()->getCrmResponsavel(),
            "tipoExameOcupacional" => $this->getControleMedico()->getTipoExameOcupacional(),
            "resultadoAtestado" => $this->getControleMedico()->getResultadoAtestado(),
            "nomeMedico" => $this->getControleMedico()->getNomeMedico(),
            "nomeResponsavel" => $this->getControleMedico()->getNomeResponsavel(),
            "ufCrmMedico" => $this->getControleMedico()->getUfCrm(),
            "ufCrmResponsavel" => $this->getControleMedico()->getUfCrmResponsavel(),
            "exames" => $exames
        );
    }
}
