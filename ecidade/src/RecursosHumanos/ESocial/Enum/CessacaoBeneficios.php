<?php
namespace ECidade\RecursosHumanos\ESocial\Enum;

/**
 * Tipos de cessação de benefícios do Esocial
 * Class CessacaoBeneficios
 * @package Ecidade\RecursosHumanos\ESocial\Enum
 */

class CessacaoBeneficios
{

    private $tiposCessacao = array();

    /**
     *
     * CessacaoBeneficios constructor.
     */
    public function __construct()
    {
        $cd1  = "01 Óbito";
        $cd2  = "02 Reversão";
        $cd3  = "03 Por decisão judicial";
        $cd4  = "04 Cassação";
        $cd5  = "05 Término do prazo do benefício";
        $cd6  = "06 Extinção de quota";
        $cd7  = "07 Não homologado pelo Tribunal de Contas";
        $cd8  = "08 Renúncia expressa";
        $cd9  = "09 Transferência de órgão administrador";
        $cd10 = "10 Mudança de CPF do beneficiário";
        $cd11 = "11 Não recadastramento";

        $this->tiposCessacao = array(
            1  => $cd1,
            2  => $cd2,
            3  => $cd3,
            4  => $cd4,
            5  => $cd5,
            6  => $cd6,
            7  => $cd7,
            8  => $cd8,
            9  => $cd9,
            10 => $cd10,
            11 => $cd11,
        );
    }

    /**
     * Retorna todos os tipos de cessação de benefícios
     * @return array
     */
    public function getTiposCessacao()
    {
        return $this->tiposCessacao;
    }
}
