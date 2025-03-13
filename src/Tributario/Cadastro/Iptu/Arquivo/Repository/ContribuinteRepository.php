<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository;

use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Contribuinte;

final class ContribuinteRepository extends DataBaseRepository
{
    public function find($matricula)
    {
        $sql = "
            select proprietario.z01_nome,
                   cgm.z01_numcgm,
                   proprietario.proprietario,
                   proprietario.z01_cgmpri,
                   proprietario.codpri,
                   proprietario.tipopri,
                   proprietario.nomepri,
                   proprietario.j39_numero,
                   proprietario.j39_compl,
                   proprietario.j13_descr,
                   proprietario.z01_ender,
                   cgm.z01_numero,
                   cgm.z01_compl,
                   cgm.z01_munic,
                   proprietario.z01_cep,
                   cgm.z01_uf,
                   cgm.z01_cgccpf,
                   iptuender.j23_ender,
                   iptuender.j23_numero,
                   iptuender.j23_compl,
                   iptuender.j23_bairro,
                   iptuender.j23_munic,
                   iptuender.j23_uf,
                   iptuender.j23_cep,
                   iptuender.j23_cxpostal,
                   iptuender.j23_dest 
              from iptubase
                   inner join lote on lote.j34_idbql = iptubase.j01_idbql
                   inner join cgm on cgm.z01_numcgm = iptubase.j01_numcgm
                   inner join (select proprietario.j01_matric,
                                      proprietario.z01_cgmpri,
                                      proprietario.proprietario,
                                      proprietario.codpri,
                                      proprietario.tipopri,
                                      proprietario.nomepri,
                                      proprietario.j39_numero,
                                      proprietario.j39_compl,
                                      proprietario.j13_descr,
                                      proprietario.z01_ender,
                                      proprietario.z01_cep,
                                      proprietario.z01_nome
                                 from proprietario
                                where proprietario.j01_matric = $matricula
                   ) as proprietario on proprietario.j01_matric = iptubase.j01_matric
                   inner join (select $matricula as matric,
                                      substr(fc_iptuender, 001, 40) as j23_ender,
                                      substr(fc_iptuender, 042, 10) as j23_numero,
                                      substr(fc_iptuender, 053, 20) as j23_compl,
                                      substr(fc_iptuender, 074, 40) as j23_bairro,
                                      substr(fc_iptuender, 115, 40) as j23_munic,
                                      substr(fc_iptuender, 156, 02) as j23_uf,
                                      substr(fc_iptuender, 159, 08) as j23_cep,
                                      substr(fc_iptuender, 168, 20) as j23_cxpostal,
                                      substr(fc_iptuender, 189, 40) as j23_dest 
                                 from fc_iptuender($matricula)
                   ) as iptuender on iptuender.matric = iptubase.j01_matric
             where iptubase.j01_matric = $matricula
        ";

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        $nome = $object->z01_nome;
        $primitente = $object->z01_nome;

        if ($object->z01_cgmpri == $object->z01_numcgm) {
            $nome = $object->proprietario;
            $primitente = "";
        }

        $ender = $object->j23_ender;

        if (trim($object->j23_cxpostal) != "" && $object->j23_cxpostal > 0) {
            $ender = "CAIXA POSTAL: $j23_cxpostal";
        }

        $entregaLogradouro = $ender;
        $entregaNumero = $object->j23_numero;
        $entregaComplemento = $object->j23_compl;

        if (trim($ender) == "") {

            $entregaLogradouro = $object->nomepri;
            $entregaNumero = $object->j39_numero;
            $entregaComplemento = $object->j39_compl;
        }

        $contribuinte = new Contribuinte();
        $contribuinte->setNome($nome);
        $contribuinte->setPromitente($primitente);
        $contribuinte->setProprietario($object->proprietario);
        $contribuinte->setProprietarioEndereco($object->z01_ender);
        $contribuinte->setProprietarioNumero($object->z01_numero);
        $contribuinte->setProprietarioComplemento($object->z01_compl);
        $contribuinte->setProprietarioMunicipio($object->z01_munic);
        $contribuinte->setProprietarioCep($object->z01_cep);
        $contribuinte->setProprietarioUf($object->z01_uf);
        $contribuinte->setProprietarioCnpjcpf($object->z01_cgccpf);
        $contribuinte->setImovelCodigoLogradouro($object->codpri);
        $contribuinte->setImovelTipoLogradouro($object->tipopri);
        $contribuinte->setImovelNomeLogradouro($object->nomepri);
        $contribuinte->setImovelNumero($object->j39_numero);
        $contribuinte->setImovelComplemento($object->j39_compl);
        $contribuinte->setImovelBairro($object->j13_descr);
        $contribuinte->setEntregaLogradouro($entregaLogradouro);
        $contribuinte->setEntregaNumero($entregaNumero);
        $contribuinte->setEntregaComplemento($entregaComplemento);
        $contribuinte->setEntregaBairro($object->j23_bairro);
        $contribuinte->setEntregaCidade($object->j23_munic);
        $contribuinte->setEntregaUf($object->j23_uf);
        $contribuinte->setEntregaCep($object->j23_cep);
        $contribuinte->setEntregaCaixaPostal($object->j23_cxpostal);
        $contribuinte->setEntregaDestinatario($object->j23_dest);

        return $contribuinte;
    }
}
