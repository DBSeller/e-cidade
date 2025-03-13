<?php


namespace App\Domain\Configuracao\Services;

use Illuminate\Support\Facades\DB;

class AssinaturaService
{
    private $idInstituicao;

    public function __construct($idInstituicao)
    {
        $this->idInstituicao = $idInstituicao;
    }

    /**
     * @param $documento
     * @return mixed
     */
    private function get($documento)
    {
        $dados = DB::select("
            select db04_ordem, db02_texto
              from db_documento
              join db_docparag on db03_docum = db04_docum
              join db_paragrafo on db04_idparag = db02_idparag
             where db03_tipodoc = {$documento}
               and db03_instit = {$this->idInstituicao}
             order by db04_ordem
        ");
        return array_map(function ($dado) {
            return $dado->db02_texto;
        }, $dados);
    }


    private function assinatura($documento, $default)
    {
        $assinatura = $this->get($documento);
        if (!empty($assinatura)) {
            return implode("\n", $assinatura);
        }

        return $this->blank($default);
    }

    public function assinaturaPrefeito()
    {
        return $this->assinatura(1000, 'Prefeito');
    }

    public function assinaturaTesoureiro()
    {
        return $this->assinatura(1004, 'Tesoureiro');
    }

    public function assinaturaSecretarioFazenda()
    {
        return $this->assinatura(1002, 'Secretaria da Fazenda');
    }

    public function assinaturaContador()
    {
        return $this->assinatura(1005, 'Contador');
    }

    private function blank($string)
    {
        return "__________________________________\n $string";
    }
}
