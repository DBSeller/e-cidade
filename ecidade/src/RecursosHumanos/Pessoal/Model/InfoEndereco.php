<?php

namespace ECidade\RecursosHumanos\Pessoal\Model;

class InfoEndereco
{
    private $codigoIbge;

    private $tipoLogradouro;



    public function __construct($codigo, $nomeRua)
    {
        if (!empty($codigo)) {
            $sql = "
                select db125_codigosistema
                from configuracoes.cadendermunicipiosistema
                inner join  configuracoes.cadendermunicipio
                on db72_sequencial = db125_cadendermunicipio
                where db125_db_sistemaexterno  = 4
                and db72_sequencial = {$codigo};";
            $rsIbge = \db_query($sql);
            // if (!$rsIbge) {
            //     throw new \DBException("Houve um erro ao buscar o código {$codigo}.");
            // }
            // if (pg_num_rows($rsIbge) == 0) {
            //     throw new \BusinessException("código {$codigo} não encontrado.");
            // }

            $municipio = \db_utils::fieldsMemory($rsIbge, 0);
            $this->setCodigoIbge($municipio->db125_codigosistema);
        }
        if (!empty($nomeRua)) {
            $sql = "
                    select j88_sigla
                    from cadastro . ruastipo
                    inner join configuracoes . cadenderruaruastipo
                    on j88_codigo = db85_ruastipo
                    inner join configuracoes . cadenderrua
                    on db74_sequencial = db85_cadenderrua
                    where db74_descricao = '{$nomeRua}' and db74_cadendermunicipio = $codigo;";
            $rsLogr = \db_query($sql);
            if (!$rsLogr) {
                throw new \DBException("Houve um erro ao buscar a rua {$nomeRua} {$codigo}.");
            }
            // if (pg_num_rows($rsLogr) == 0) {
            //     throw new \BusinessException("rua {$nomeRua} não encontrado.");
            // }

            $tipoLogr = \db_utils::fieldsMemory($rsLogr, 0);
            $this->setTipoLogradouro($tipoLogr->j88_sigla);
        }
    }

    public function setTipoLogradouro($tipo)
    {
        $this->tipoLogradouro = $tipo;
    }

    public function setCodigoIbge($codigoIbge)
    {
        $this->codigoIbge = $codigoIbge;
    }

    /**
     * @return mixed
     */
    public function getCodigoIbge()
    {
        return $this->codigoIbge;
    }

    /**
     * @return mixed
     */
    public function getTipoLogradouro()
    {
        return $this->tipoLogradouro;
    }
}
