<?php


namespace App\Domain\Financeiro\Contabilidade\Factories;

use App\Domain\Configuracao\RelarorioLegal\Model\Template;
use Exception;

/**
 * Class TemplateFactory
 * @package App\Domain\Financeiro\Contabilidade\Factories
 */
class TemplateFactory
{
    const MODELO_PADRAO = 0;
    const MODELO_IN_RS = 1;
    const MODELO_PORTO_VELHO = 2;
    const MODELO_MDF = 3;

    /**
     * @param $idRelatorio
     * @param $idPeriodo
     * @param int $modelo
     * @return mixed
     * @throws Exception
     */
    public static function getTemplate($idRelatorio, $idPeriodo, $modelo = 0)
    {
        $template = Template::query()
            ->where('c138_orcparamrel', '=', $idRelatorio)
            ->where('c138_periodo', '=', $idPeriodo)
            ->where('c138_modelo', '=', $modelo)
            ->first();

        if (is_null($template)) {
            $msg = sprintf(
                '%s\n%s',
                'Não foi encontrado o template para o relatório no período.',
                'Entre em contato com o suporte para que seja atualizado.'
            );

            throw new Exception($msg, 403);
        }

        return $template->c138_path;
    }
}
