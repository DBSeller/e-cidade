<?php

namespace ECidade\Enum\Saude\ESF;

use ECidade\Enum\Enum;

class DoseEnum extends Enum
{
    const PRIMEIRA_DOSE = 1;
    const SEGUNDA_DOSE = 2;
    const TERCEIRA_DOSE = 3;
    const QUARTA_DOSE = 4;
    const QUINTA_DOSE = 5;
    const PRIMEIRO_REFORCO = 6;
    const SEGUNDO_REFORCO = 7;
    const DOSE = 8;
    const UNICA = 9;
    const REVACINACAO = 10;
    const TRATAMENTO_COM_UMA_DOSE = 11;
    const TRATAMENTO_COM_DUAS_DOSES = 12;
    const TRATAMENTO_COM_TRES_DOSES = 13;
    const TRATAMENTO_COM_QUATRO_DOSES = 14;
    const TRATAMENTO_COM_CINCO_DOSES = 15;
    const TRATAMENTO_COM_SEIS_DOSES = 16;
    const TRATAMENTO_COM_SETE_DOSES = 17;
    const TRATAMENTO_COM_OITO_DOSES = 18;
    const TRATAMENTO_COM_NOVE_DOSES = 19;
    const TRATAMENTO_COM_DEZ_DOSES = 20;
    const TRATAMENTO_COM_ONZE_DOSES = 21;
    const TRATAMENTO_COM_DOZE_DOSES = 22;
    const TRATAMENTO_COM_TREZE_DOSES = 23;
    const TRATAMENTO_COM_QUATORZE_DOSES = 24;
    const TRATAMENTO_COM_QUINZE_DOSES = 25;
    const TRATAMENTO_COM_DEZESSEIS_DOSES = 26;
    const TRATAMENTO_COM_DEZESSETE_DOSES = 27;
    const TRATAMENTO_COM_DEZOITO_DOSES = 28;
    const TRATAMENTO_COM_DEZENOVE_DOSES = 29;
    const TRATAMENTO_COM_VINTE_DOSES = 30;
    const TRATAMENTO_COM_VINTE_QUATRO_DOSES = 31;
    const PRIMEIRA_DOSE_REVACINACAO = 32;
    const SEGUNDA_DOSE_REVACINACAO = 33;
    const TERCEIRA_DOSE_REVACINACAO = 34;
    const QUARTA_DOSE_REVACINACAO = 35;
    const DOSE_INICIAL = 36;
    const DOSE_ADICIONAL = 37;
    const REFORCO = 38;
    const TERCEIRO_REFORCO = 39;
    const QUARTO_REFORCO = 40;
    const QUINTO_REFORCO = 41;
    const SEXTO_REFORCO = 42;

    /**
     * @return string
     */
    public function name()
    {
        $data = [
            self::PRIMEIRA_DOSE => '1ª Dose',
            self::SEGUNDA_DOSE => '2ª Dose',
            self::TERCEIRA_DOSE => '3ª Dose',
            self::QUARTA_DOSE => '4ª Dose',
            self::QUINTA_DOSE => '5ª Dose',
            self::PRIMEIRO_REFORCO => '1º Reforço',
            self::SEGUNDO_REFORCO => '2º Reforço',
            self::DOSE => 'Dose',
            self::UNICA => 'Única',
            self::REVACINACAO => 'Revacinação',
            self::TRATAMENTO_COM_UMA_DOSE => 'Tratamento com uma dose',
            self::TRATAMENTO_COM_DUAS_DOSES => 'Tratamento com duas doses',
            self::TRATAMENTO_COM_TRES_DOSES => 'Tratamento com três doses',
            self::TRATAMENTO_COM_QUATRO_DOSES => 'Tratamento com quatro doses',
            self::TRATAMENTO_COM_CINCO_DOSES => 'Tratamento com cinco doses',
            self::TRATAMENTO_COM_SEIS_DOSES => 'Tratamento com seis doses',
            self::TRATAMENTO_COM_SETE_DOSES => 'Tratamento com sete doses',
            self::TRATAMENTO_COM_OITO_DOSES => 'Tratamento com oito doses',
            self::TRATAMENTO_COM_NOVE_DOSES => 'Tratamento com nove doses',
            self::TRATAMENTO_COM_DEZ_DOSES => 'Tratamento com dez doses',
            self::TRATAMENTO_COM_ONZE_DOSES => 'Tratamento com onze doses',
            self::TRATAMENTO_COM_DOZE_DOSES => 'Tratamento com doze doses',
            self::TRATAMENTO_COM_TREZE_DOSES => 'Tratamento com treze doses',
            self::TRATAMENTO_COM_QUATORZE_DOSES => 'Tratamento com quatorze doses',
            self::TRATAMENTO_COM_QUINZE_DOSES => 'Tratamento com quinze doses',
            self::TRATAMENTO_COM_DEZESSEIS_DOSES => 'Tratamento com dezesseis doses',
            self::TRATAMENTO_COM_DEZESSETE_DOSES => 'Tratamento com dezessete doses',
            self::TRATAMENTO_COM_DEZOITO_DOSES => 'Tratamento com dezoito doses',
            self::TRATAMENTO_COM_DEZENOVE_DOSES => 'Tratamento com dezenove doses',
            self::TRATAMENTO_COM_VINTE_DOSES => 'Tratamento com vinte doses',
            self::TRATAMENTO_COM_VINTE_QUATRO_DOSES => 'Tratamento com vinte quatro doses',
            self::PRIMEIRA_DOSE_REVACINACAO => '1ª Dose Revacinação',
            self::SEGUNDA_DOSE_REVACINACAO => '2ª Dose Revacinação',
            self::TERCEIRA_DOSE_REVACINACAO => '3ª Dose Revacinação',
            self::QUARTA_DOSE_REVACINACAO => '4ª Dose Revacinação',
            self::DOSE_INICIAL => 'Dose Inicial',
            self::DOSE_ADICIONAL => 'Dose Adicional',
            self::REFORCO => 'Reforço',
            self::TERCEIRO_REFORCO => '3º Reforço',
            self::QUARTO_REFORCO => '4º Reforço',
            self::QUINTO_REFORCO => '5º Reforço',
            self::SEXTO_REFORCO => '6º Reforço'
        ];

        if (empty($data[$this->getValue()])) {
            throw new \Exception('Opção inválida.');
        }

        return $data[$this->getValue()];
    }
}
