<?php

namespace ECidade\Integracao\Sped\API\Service;

use CgmFactory;
use ECidade\Integracao\Sped\API\Formatter\ConsultaFormatter;
use ECidade\Integracao\Sped\API\Relatorio\ConsultaRelatorio;
use JSON;

class ConsultaService
{
    /**
     * @var \stdClass
     */
    private $parametros;

    /**
     * ConsultaService constructor.
     * @param \stdClass $parametros
     */
    public function __construct(\stdClass $parametros)
    {
        $this->parametros = $parametros;
    }

    /**
     * @throws \Exception
     */
    public function gerar()
    {
        if (empty($this->parametros->data)) {
            throw new \Exception('É necessário informar os dados para gerar o relatório.');
        }

        if (empty($this->parametros->integracao)) {
            throw new \Exception('É necessário informar o tipo de integração.');
        }

        if (empty($this->parametros->cgmResponsavel)) {
            throw new \Exception('É necessário informar o CGM do responsável pelas informações.');
        }

        if (empty($this->parametros->competencia)) {
            throw new \Exception('É necessário informar a competência.');
        }

        $cgmResponsavel = CgmFactory::getInstanceByCgm($this->parametros->cgmResponsavel);
        $eventos = JSON::create()->parse($this->parametros->data);
        $dados = array();
        foreach ($eventos as $evento) {
            foreach ($evento->totalizadores as $layout => $totalizador) {
                $formatter = new ConsultaFormatter($totalizador, $layout);
                $dados[] = $formatter->format();
            }
        }

        if (empty($dados)) {
            throw new \Exception("Não foi possível gerar o relatório.\nContate o suporte.");
        }

        $relatorio = new ConsultaRelatorio($dados, $cgmResponsavel, $this->parametros);
        
        return $relatorio->gerar();
    }

     /**
     * @throws \Exception
     */
    public function gerarCSV()
    {
        if (empty($this->parametros->data)) {
            throw new \Exception('É necessário informar os dados para gerar o relatório.');
        }

        if (empty($this->parametros->integracao)) {
            throw new \Exception('É necessário informar o tipo de integração.');
        }

        if (empty($this->parametros->cgmResponsavel)) {
            throw new \Exception('É necessário informar o CGM do responsável pelas informações.');
        }

        if (empty($this->parametros->competencia)) {
            throw new \Exception('É necessário informar a competência.');
        }

        $cgmResponsavel = CgmFactory::getInstanceByCgm($this->parametros->cgmResponsavel);
        $eventos = JSON::create()->parse($this->parametros->data);
        $dados = array();
        foreach ($eventos as $evento) {
            foreach ($evento->totalizadores as $layout => $totalizador) {
                $formatter = new ConsultaFormatter($totalizador, $layout);
                $dados[] = $formatter->format();
            }
        }

        if (empty($dados)) {
            throw new \Exception("Não foi possível gerar o relatório.\nContate o suporte.");
        }

        $relatorio = new ConsultaRelatorio($dados, $cgmResponsavel, $this->parametros);
        
        return $relatorio->gerarCSV();
    }
}
