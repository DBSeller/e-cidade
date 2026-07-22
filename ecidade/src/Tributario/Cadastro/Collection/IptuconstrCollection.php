<?php

namespace ECidade\Tributario\Cadastro\Collection;

use ECidade\Tributario\Library\ModelCollection;
use ECidade\Tributario\Cadastro\Model\Iptuconstr;

final class IptuconstrCollection extends ModelCollection
{
    protected function get($index)
    {
        $object = $this->fetchRow($this->resource, $index);

        $iptuconstr = new Iptuconstr();

        $iptuconstr->setMatric($object->j39_matric);
        $iptuconstr->setIdcons($object->j39_idcons);
        $iptuconstr->setAno($object->j39_ano);
        $iptuconstr->setArea($object->j39_area);
        $iptuconstr->setAreap($object->j39_areap);
        $iptuconstr->setDtlan($object->j39_dtlan);
        $iptuconstr->setCodigo($object->j39_codigo);
        $iptuconstr->setNumero($object->j39_numero);
        $iptuconstr->setCompl($object->j39_compl);
        $iptuconstr->setDtdemo($object->j39_dtdemo);
        $iptuconstr->setIdaument($object->j39_idaument);
        $iptuconstr->setIdprinc($object->j39_idprinc);
        $iptuconstr->setHabite($object->j39_habite);
        $iptuconstr->setPavim($object->j39_pavim);
        $iptuconstr->setCodprotdemo($object->j39_codprotdemo);
        $iptuconstr->setObs($object->j39_obs);

        return $iptuconstr;
    }

    public function getValorTotalArea()
    {
        $valor = 0;

        foreach ($this as $iptuconstr) {
            $valor += $iptuconstr->getArea();
        }

        return $valor;
    }

    public function getValorTotalAreaNaoDemolida()
    {
        $valor = 0;

        foreach ($this as $iptuconstr) {

            if (empty($iptuconstr->getDtdemo())) {
                $valor += $iptuconstr->getArea();
            }
        }

        return $valor;
    }
}
