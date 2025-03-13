<?php

namespace ECidade\Enum\Common;

use ECidade\Enum\Enum;

/**
 * Classe responsável por enumerar as Faixa Etária
 */
class FaixaEtariaEnum extends Enum
{
    const MENOS_DE_UM = 1;
    const UM = 2;
    const DOIS = 3;
    const TRES = 4;
    const QUATRO = 5;
    const CINCO_NOVE = 6;
    const DEZ_QUATORZE = 7;
    const QUINZE_DEZENOVE = 8;
    const VINTE_VINTEQUATRO = 9;
    const VINTECINCO_VINTENOVE = 10;
    const TRINTA_TRINTAQUATRO = 11;
    const TRINTACINCO_TRINTANOVE = 12;
    const QUARENTA_QUARENTAQUATRO = 13;
    const QUARENTACINCO_QUARENTANOVE = 14;
    const CINQUENTA_CINQUENTAQUATRO = 15;
    const CINQUENTACINCO_CINQUENTANOVE = 16;
    const SESSENTA_SESSENTAQUATRO = 17;
    const SESSENTACINCO_SESSENTANOVE = 18;
    const SETENTA_SETENTAQUATRO = 19;
    const SETENTACINCO_SETENTANOVE = 20;
    const OITENTA_MAIS = 21;

    /**
     * Retorna a descrição da faixa Etária
     * @return string $descricao
     */
    public function name()
    {
        $data = [
            self::MENOS_DE_UM => 'Menos de 01 ano',
            self::UM => '01 ano',
            self::DOIS => '02 anos',
            self::TRES => '03 anos',
            self::QUATRO => '04 anos',
            self::CINCO_NOVE => '05 a 09 anos',
            self::DEZ_QUATORZE => '10 a 14 anos',
            self::QUINZE_DEZENOVE => '15 a 16 anos',
            self::VINTE_VINTEQUATRO => '20 a 24 anos',
            self::VINTECINCO_VINTENOVE => '25 a 29 anos',
            self::TRINTA_TRINTAQUATRO => '30 a 34 anos',
            self::TRINTACINCO_TRINTANOVE => '35 a 39 anos',
            self::QUARENTA_QUARENTAQUATRO => '40 a 44 anos',
            self::QUARENTACINCO_QUARENTANOVE => '45 a 49 anos',
            self::CINQUENTA_CINQUENTAQUATRO => '50 a 54 anos',
            self::CINQUENTACINCO_CINQUENTANOVE => '55 a 59 anos',
            self::SESSENTA_SESSENTAQUATRO => '60 a 64 anos',
            self::SESSENTACINCO_SESSENTANOVE => '65 a 69 anos',
            self::SETENTA_SETENTAQUATRO => '70 a 74 anos',
            self::SETENTACINCO_SETENTANOVE => '75 a 79 anos',
            self::OITENTA_MAIS => '80 anos ou mais'
        ];

        if (empty($data[$this->getValue()])) {
            throw new \Exception('Opção inválida! Selecione uma faixa etária válida.');
        }

        return $data[$this->getValue()];
    }

    /**
     * Retorna a faixa etária
     * @return array $faixaEtaria
     */
    public function getFaixaEtaria()
    {
        $data = [
            self::MENOS_DE_UM => [0, 0],
            self::UM => [1, 1],
            self::DOIS => [2, 2],
            self::TRES => [3, 3],
            self::QUATRO => [4, 4],
            self::CINCO_NOVE => [5, 9],
            self::DEZ_QUATORZE => [10, 14],
            self::QUINZE_DEZENOVE => [15, 19],
            self::VINTE_VINTEQUATRO => [20, 24],
            self::VINTECINCO_VINTENOVE => [25, 29],
            self::TRINTA_TRINTAQUATRO => [30, 34],
            self::TRINTACINCO_TRINTANOVE => [35, 39],
            self::QUARENTA_QUARENTAQUATRO => [40, 44],
            self::QUARENTACINCO_QUARENTANOVE => [45, 49],
            self::CINQUENTA_CINQUENTAQUATRO => [50, 54],
            self::CINQUENTACINCO_CINQUENTANOVE => [55, 59],
            self::SESSENTA_SESSENTAQUATRO => [60, 64],
            self::SESSENTACINCO_SESSENTANOVE => [65, 69],
            self::SETENTA_SETENTAQUATRO => [70, 74],
            self::SETENTACINCO_SETENTANOVE => [75, 79],
            self::OITENTA_MAIS => [80, 120]
        ];

        if (empty($data[$this->getValue()])) {
            throw new \Exception('Opção inválida! Selecione uma faixa etária válida.');
        }

        return $data[$this->getValue()];
    }
}
