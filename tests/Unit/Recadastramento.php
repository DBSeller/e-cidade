<?php

namespace Tests\Unit;

use ECidade\RecursosHumanos\RH\Recadastramento\conversorJson\Formatter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Recadastramento extends TestCase
{

    protected $json;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $json = file_get_contents("tests/files/resposta.json");
        $this->json = new Formatter($json);
    }

    public function testGetSecaoExiste()
    {
        $this->secao = $this->json->getSecao("dados_pessoais");
        $this->assertEquals("dados_pessoais", $this->secao->getNome());
    }

    public function testCampoExiste()
    {
        $this->secao = $this->json->getSecao("dados_pessoais");
        $campo = $this->secao->getCampo("nome_funcionario");
        $this->assertEquals("nome_funcionario", $campo->getNome());
    }

    public function testCampoValor()
    {
        $this->secao = $this->json->getSecao("dados_pessoais");
        $campo = $this->secao->getCampo("nome_funcionario");
        $this->assertEquals("PATRICIA FILGUEIRAS DOS REIS", $campo->getResposta());
    }

    public function testSecaoTipoTabela()
    {
        $this->secao = $this->json->getSecao("dependentes_ativos");
        $this->assertEquals("tabela", $this->secao->getTipo());
    }

    public function testSecaoTipoTabelaResposta()
    {
        $this->secao = $this->json->getSecao("dependentes_ativos");
        $this->assertTrue(is_array($this->secao->getResposta()));
    }
}
