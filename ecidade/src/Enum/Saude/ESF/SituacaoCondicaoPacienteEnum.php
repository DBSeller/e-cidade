<?php

namespace ECidade\Enum\Saude\ESF;

use Exception;

class SituacaoCondicaoPacienteEnum extends \ECidade\Enum\Enum
{
    const DEFICIENCIA = 1;
    const HIPERTENSAO_ARTERIAL = 2;
    const DIABETES = 3;
    const GESTANTE = 4;
    const TUBERCULOSE = 5;
    const HANSENIASE = 6;

    /**
     * @return string
     * @throws Exception
     */
    public function column()
    {
        $data = [
            self::DEFICIENCIA => 'psf5_tem_deficiencia',
            self::HIPERTENSAO_ARTERIAL => 'psf5_hipertensao',
            self::DIABETES => 'psf5_diabetes',
            self::GESTANTE => 'psf5_gestante',
            self::TUBERCULOSE => 'psf5_tuberculose',
            self::HANSENIASE => 'psf5_hanseniase'
        ];

        if (empty($data[$this->getValue()])) {
            throw new Exception('Opção inválida.');
        }

        return $data[$this->getValue()];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = [
            self::DEFICIENCIA => 'Deficiência',
            self::HIPERTENSAO_ARTERIAL => 'Hipertensão Arterial',
            self::DIABETES => 'Diabetes',
            self::GESTANTE => 'Gestante',
            self::TUBERCULOSE => 'Tuberculose',
            self::HANSENIASE => 'Hanseníase'
        ];

        if (empty($data[$this->getValue()])) {
            throw new Exception('Opção inválida.');
        }

        return $data[$this->getValue()];
    }
}
