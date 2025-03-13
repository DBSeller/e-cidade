<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Hydrator;

use \Exception;
use \StdClass;
use \DateTime;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Filtro;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\FiltroUnica;

final class FiltroHydrator extends Service
{
    public function build(StdClass $parametros)
    {
        if (empty($parametros->anousu)) {
            throw new Exception("Erro! Parametro Ano não informado!");
        }

        if (empty($parametros->debito)) {
            throw new Exception("Erro! Parametro Debitos não informado!");
        }

        $filtro = new Filtro($parametros->quantidade, $parametros->anousu);
        $filtro->setTerceiroDigitoUnica($parametros->barrasunica);
        $filtro->setTerceiroDigitoParcela($parametros->barrasparc);

        if (!empty($parametros->listamatrics)) {
            $filtro->setMatriculas(explode(',', $parametros->listamatrics));
        }

        if (!empty($parametros->debito)) {
            $debitos = explode(",", $parametros->debito);

            if ($debitos[0] == "IPTU") {
                $filtro->setIptu(true);
                unset($debitos[0]);
            }

            if (!empty($debitos)) {
                $filtro->setTaxas($debitos);
            }
        }

        if (!empty($parametros->unica)) {
            $unicas = array();

            $array = explode('U', $parametros->unica);

            foreach ($array as $value) {
                $dados = explode('=', $value);

                $unicas[] = new FiltroUnica(
                    new DateTime($dados[0]),
                    $dados[2]
                );
            }

            $filtro->setCotaUnicas($unicas);
        }

        $filtro->setEntregaValido((bool)$parametros->entregavalido);
        $filtro->setCidadeBranco((bool)$parametros->cidadebranco);
        $filtro->setQuantidadeParcela($parametros->quantidadeparcela);

        return $filtro;
    }
}
