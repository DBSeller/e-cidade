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
class EstabelecimentoObras extends ProcessamentoAbstract implements ProcessamentoInterface
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

        $oDadosPreenchimento = $oDadosESocial->getPorTipo(Tipo::OBRAS);
        $formatter = FormatterFactory::get(Tipo::S1005);
        $dadosObras = $formatter->formatar($oDadosPreenchimento);
        $numeroProcessamentos = count($dadosObras);
        for ($i = 0; $i < $numeroProcessamentos; $i++) {
            $oEventoFila = new Evento(TIPO::S1005, $numeroCgm, $dadosObras[$i]->referencia, $dadosObras[$i]);
            $alteracaoObras = $oEventoFila->adicionarFila();

            if ($alteracaoObras) {
                $alteracao = true;
            }
        }

        return $alteracao;
    }
}
