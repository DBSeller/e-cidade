<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use Exception;
use Instituicao;
use stdClass;

require_once(modification('libs/db_stdlib.php'));

/**
 * Class AvisoPrevio
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
class AvisoPrevio extends ProcessamentoAbstract implements ProcessamentoInterface
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

        $oDadosPreenchimento = $oDadosESocial->getPorTipo(Tipo::AVISO_PREVIO);

        $oFormatter = FormatterFactory::get(Tipo::S2250);
        $aAvisoPrevio = $oFormatter->formatar($oDadosPreenchimento);

        array_map(function (stdClass $oDadosAvisoPrevio) use ($iCgm, &$bAlteracao) {
            $oEvento = new Evento(Tipo::S2250, $iCgm, $oDadosAvisoPrevio->referencia, $oDadosAvisoPrevio);

            $bAlteracaoRetorno = $oEvento->adicionarFila();

            if (!$bAlteracao) {
                $bAlteracao = $bAlteracaoRetorno;
            }
        }, $aAvisoPrevio);

        return $bAlteracao;
    }
}

