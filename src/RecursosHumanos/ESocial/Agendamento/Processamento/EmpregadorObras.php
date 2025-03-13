<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 08/01/18
 * Time: 15:04
 */

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use Instituicao;

/**
 * Class EmpregadorObras
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
class EmpregadorObras extends ProcessamentoAbstract implements ProcessamentoInterface
{
    /**
     * @var
     */
    private $cgm;

    /**
     * @var
     */
    private $layout;

    /**
     * ProcessamentoInterface constructor.
     * @param $cgm
     */
    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return mixed
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param mixed $cgm
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return mixed
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param mixed $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return bool|mixed
     * @throws \Exception
     */
    public function processar()
    {
        $alteracao = false;
        $numeroCgm = $this->cgm;
        $oInstituicao = new Instituicao(db_getsession('DB_instit'));

        $oDadosESocial = new DadosESocial();
        $oDadosESocial->setReponsavelPeloPreenchimento($numeroCgm);
        $oDadosESocial->setInstituicao($oInstituicao);

        $oDadosPreenchimento = $oDadosESocial->getPorTipo(Tipo::EMPREGADOR);

        $formatter = FormatterFactory::get(Tipo::S1000);
        $formatter->setInstituicao($oInstituicao);
        $dadosEmpregador = $formatter->formatar($oDadosPreenchimento);
        $numeroProcessamentos = count($dadosEmpregador);

        for ($i = 0; $i < $numeroProcessamentos; $i++) {
            $oEventoFila = new Evento(TIPO::S1000, $numeroCgm, $numeroCgm, $dadosEmpregador[$i]);
            $alteracaoEmpregador = $oEventoFila->adicionarFila();

            if ($alteracaoEmpregador) {
                $alteracao = true;
            }
        }

        return $alteracao;
    }
}
