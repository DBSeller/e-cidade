<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Entity\Servidor;
use ECidade\RecursosHumanos\ESocial\Service\ServidorService;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorMovimentacaoRepository;
use stdClass;
use CgmJuridico;
use DBPessoal;

/**
<<<<<<< HEAD
 * Class ProcessoTrabalhistaFormatter
=======
 * Class TributoTrabalhistaFormatter
>>>>>>> e1e1dfc7abcd16556195fabc0b95503a870d8e14
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 */
class TributoTrabalhistaFormatter extends Formatter
{

    /**
     * @param  array $dados
     * @return mixed|stdClass[]
     * @throws \BusinessException
     * @throws \DBException
     */
    public function formatar($dados)
    {
        $dadosProcessoTrabalhista = null;

        foreach ($dados as $tributoTrabalhista) {
            $dadoTributo = new stdClass();
            $dadoTributo = $tributoTrabalhista;
            $dadoTributo->inscricao_empregador = $this->getEmpregador()->getCnpj();
            $dadosProcessoTrabalhista[] = $dadoTributo;
        }
        return $dadosProcessoTrabalhista;
    }
}
