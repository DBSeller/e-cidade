<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use Exception;
use Instituicao;
use stdClass;

// phpcs:disable
require_once(modification('libs/db_stdlib.php'));
// phpcs:enable
/**
 * Class LotacaoTributaria
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
class LotacaoTributaria extends ProcessamentoAbstract implements ProcessamentoInterface
{
    /**
     * @var
     */
    private $iCgm;

    /**
     * LotacaoTributaria constructor.
     * @param $iCgm
     */
    public function __construct($iCgm)
    {
        $this->iCgm = $iCgm;
    }

    /**
     * @return bool|mixed
     * @throws Exception
     */
    public function processar()
    {
        $bAlteracao = false;
        $iCgm = $this->iCgm;
        $oInstituicao = new Instituicao(db_getsession('DB_instit'));

        $oDadosESocial = new DadosESocial();
        $oDadosESocial->setReponsavelPeloPreenchimento($iCgm);
        $oDadosESocial->setInstituicao($oInstituicao);

        $oDadosPreenchimento = $oDadosESocial->getPorTipo(Tipo::LOTACAO_TRIBUTARIA);

        $oFormatter = FormatterFactory::get(Tipo::S1020);
        $aDadosLotacao = $oFormatter->formatar($oDadosPreenchimento);

        array_map(function (stdClass $oDadoLotacao) use ($iCgm, &$bAlteracao) {
            $oEvento = new Evento(Tipo::S1020, $iCgm, $oDadoLotacao->referencia, $oDadoLotacao);
            $bAlteracao = $oEvento->adicionarFila();
        }, $aDadosLotacao);

        return $bAlteracao;
    }
}
