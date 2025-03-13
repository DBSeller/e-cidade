<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository;

use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Imovel;

final class ParcelaPagaRepository extends DataBaseRepository
{
    public function find()
    {
        // $sql = "
        //     select proprietario.j01_tipoimp,
        //            proprietario.j01_matric,
        //            iptumatzonaentrega.j86_iptucadzonaentrega,
        //            proprietario.j34_zona,
        //            lotesetorfiscal.j91_codigo,
        //            proprietario.j34_setor,
        //            proprietario.j34_quadra,
        //            proprietario.j34_lote
        //       from proprietario
        //            left join iptumatzonaentrega on iptumatzonaentrega.j86_matric = proprietario.j01_matric
        //            left join lotesetorfiscal on lotesetorfiscal.j91_idbql = proprietario.j01_idbql
        //      where proprietario.j01_matric = $matricula
        // ";
        
        // $result = $this->dataBase->execute($sql);

        // $object = $this->dataBase->fetchRow($result);

        // $imovel = new Imovel();

        // $tipoImovelCodigo = 1;
        // if ($object->j01_tipoimp == "Predial") {
        //     $tipoImovelCodigo = 2;
        // }

        // if (empty($object->j91_codigo)) {
        //     $object->j91_codigo = 0;
        // }

        // $imovel->setTipoImovelCodigo($tipoImovelCodigo);
        // $imovel->setTipoImovelDescricao($object->j01_tipoimp);
        // $imovel->setMatricula($object->j01_matric);
        // $imovel->setExercicio($ano);
        // $imovel->setNotificacao(0);
        // $imovel->setZonaEntrega($object->j86_iptucadzonaentrega);
        // $imovel->setZonaFiscalLote($object->j34_zona);
        // $imovel->setSetorFiscal($object->j91_codigo);
        // $imovel->setSetorCartografica($object->j34_setor);
        // $imovel->setQuadraCartografica($object->j34_quadra);
        // $imovel->setLoteCartografica($object->j34_lote);
        // $imovel->setSublote(0);

        // return $imovel;
    }
}
