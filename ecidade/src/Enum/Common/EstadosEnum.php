<?php


namespace ECidade\Enum\Common;

use ECidade\Enum\Enum;
use Exception;

/**
 * Class EstadosEnum
 * @package ECidade\Enum\Common
 */
class EstadosEnum extends Enum
{
    const AC = "AC";
    const AL = "AL";
    const AP = "AP";
    const AM = "AM";
    const BA = "BA";
    const CE = "CE";
    const ES = "ES";
    const GO = "GO";
    const MA = "MA";
    const MT = "MT";
    const MS = "MS";
    const MG = "MG";
    const PA = "PA";
    const PB = "PB";
    const PR = "PR";
    const PE = "PE";
    const PI = "PI";
    const RJ = "RJ";
    const RN = "RN";
    const RS = "RS";
    const RO = "RO";
    const RR = "RR";
    const SC = "SC";
    const SP = "SP";
    const SE = "SE";
    const TO = "TO";
    const DF = "DF";
    const ESTADOS_DESCRICOES = [
        self::AC => "Acre",
        self::AL => "Alagoas",
        self::AP => "Amapá",
        self::AM => "Amazonas",
        self::BA => "Bahia",
        self::CE => "Ceará",
        self::ES => "Espírito Santo",
        self::GO => "Goiás",
        self::MA => "Maranhão",
        self::MT => "Mato Grosso",
        self::MS => "Mato Grosso do Sul",
        self::MG => "Minas Gerais",
        self::PA => "Pará",
        self::PB => "Paraíba",
        self::PR => "Paraná",
        self::PE => "Pernambuco",
        self::PI => "Piauí",
        self::RJ => "Rio de Janeiro",
        self::RN => "Rio Grande do Norte",
        self::RS => "Rio Grande do Sul",
        self::RO => "Rondônia",
        self::RR => "Roraima",
        self::SC => "Santa Catarina",
        self::SP => "São Paulo",
        self::SE => "Sergipe",
        self::TO => "Tocantins",
        self::DF => "Distrito Federal"
    ];

    const ESTADOS_SIGLAS = [
        self::AC => self::AC,
        self::AL => self::AL,
        self::AP => self::AP,
        self::AM => self::AM,
        self::BA => self::BA,
        self::CE => self::CE,
        self::ES => self::ES,
        self::GO => self::GO,
        self::MA => self::MA,
        self::MT => self::MT,
        self::MS => self::MS,
        self::MG => self::MG,
        self::PA => self::PA,
        self::PB => self::PB,
        self::PR => self::PR,
        self::PE => self::PE,
        self::PI => self::PI,
        self::RJ => self::RJ,
        self::RN => self::RN,
        self::RS => self::RS,
        self::RO => self::RO,
        self::RR => self::RR,
        self::SC => self::SC,
        self::SP => self::SP,
        self::SE => self::SE,
        self::TO => self::TO,
        self::DF => self::DF
    ];

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = self::ESTADOS_DESCRICOES;

        if (empty($data[$this->getValue()])) {
            throw new Exception('Zona de residência não encontrada.');
        }

        return $data[$this->getValue()];
    }

    /**
     * Retorna os valores com os nomes
     * @return array
     * @throws Exception
     */
    public static function toArrayWithNames()
    {
        $tipos = self::values();
        $return = array();
        foreach ($tipos as $tipo) {
            $return[] = array(
                'value' => $tipo->value(),
                'name' => $tipo->name()
            );
        }

        return $return;
    }

    public static function getDescricoes($obrigatorio = true)
    {
        if ($obrigatorio) {
            return self::ESTADOS_DESCRICOES;
        } else {
            return array_merge(["" => "Não Informado"], self::ESTADOS_DESCRICOES);
        }
    }

    public static function getSiglas($obrigatorio = true)
    {
        if ($obrigatorio) {
            return self::ESTADOS_SIGLAS;
        } else {
            return array_merge(["" => "Não Informado"], self::ESTADOS_SIGLAS);
        }
    }
}
