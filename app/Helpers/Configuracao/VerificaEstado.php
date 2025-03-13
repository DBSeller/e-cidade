<?php
if (!function_exists('getEstadoInstituicao')) {
    function getEstadoInstituicao()
    {
        $municipio = InstituicaoRepository::getInstituicaoPrefeitura();
        return $municipio->getUf();
    }
}

if (!function_exists('isParaiba')) {
    function isParaiba()
    {
        return getEstadoInstituicao() === 'PB';
    }
}

if (!function_exists('isRioGrandeDoSul')) {
    function isRioGrandeDoSul()
    {
        return getEstadoInstituicao() === 'RS';
    }
}

if (!function_exists('isRioGrandeDoNorte')) {
    function isRioGrandeDoNorte()
    {
        return getEstadoInstituicao() === 'RN';
    }
}

if (!function_exists('isRioDeJaneiro')) {
    function isRioDeJaneiro()
    {
        return getEstadoInstituicao() === 'RJ';
    }
}
