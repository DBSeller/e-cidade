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
 *  junto com este programa; se nao, escreva para a Free Softwareb
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use DBCompetencia;
use DBPessoal;
use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Repository\BeneficioTermino as BeneficioTerminoRepository;
use stdClass;
use ParameterException;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

/**
 * Class BeneficioTermino
 * referente ao S2420
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
class BeneficioTermino extends ProcessamentoAbstract implements ProcessamentoInterface
{
    private $cgm;

    private $mes;
    private $ano;

    /**
     * @param integer $mes
     * Seta o mes da competencia informada
     */
    public function setMes($mes)
    {
        if (!empty($mes)) {
            $this->mes = $mes;
        }
    }

    /**
     * @param integer $ano
     */
    public function setAno($ano)
    {
        if (!empty($ano)) {
            $this->ano = $ano;
        }
    }

    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return bool|mixed
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     * @throws \Exception
     */
    public function processar()
    {
        $alteracao = false;

        if (empty($this->ano) || empty($this->mes)) {
            $this->ano = DBPessoal::getAnoFolha();
            $this->mes = DBPessoal::getMesFolha();
        }

        $dados = new stdClass();
        $competencia =  new DBCompetencia($this->ano, $this->mes);

        $dados->servidores = BeneficioTerminoRepository::buscarBeneficiarios($competencia, $this->servidores);
        $dados->inscricao_empregador = BeneficioTerminoRepository::buscarCNPJEmpregador($this->cgm);


        $formatter = FormatterFactory::get(Tipo::S2420);

        $dadosPreenchimentoEmpregador = $formatter->formatar($dados);

        $validaMd5 = true;
        if ($this->envioForcado) {
            $validaMd5 = false;
        }
        foreach ($dadosPreenchimentoEmpregador as $indice => $dados) {
            $evento = new Evento(TIPO::S2420, $this->cgm, $dados->referencia, $dados);
            $evento->iContador = $indice;

            if ($evento->adicionarFila(false, $validaMd5)) {
                $alteracao = true;
            }
        }
        return $alteracao;
    }
}
