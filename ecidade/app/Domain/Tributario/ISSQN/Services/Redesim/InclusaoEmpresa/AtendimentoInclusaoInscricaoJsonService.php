<?php

namespace App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Ouvidoria\Model\TipoProcessoFormaReclamacao;
use App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\SessoesJson\AtividadesService;
use App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\SessoesJson\DadosContadorService;
use App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\SessoesJson\DadosEmpresaService;
use App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\SessoesJson\EnderecoMunicipioService;
use App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\SessoesJson\SociosService;

/**
 * Class AtendimentoInclusaoInscricaoJsonService
 * @package App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa
 */
class AtendimentoInclusaoInscricaoJsonService
{
    /**
     * Descrição do JSON
     * @var string
     */
    const DESCRICAO = "ALVARÁ";

    /**
     * Ação do JSON
     * @var string
     */
    const ACAO = "gerarAlvara";

    /**
     * @var object
     */
    private $oDados;

    /**
     * @var array
     */
    private $aJson;

    /**
     * @param object $oDados
     */
    public function setDados($oDados)
    {
        $this->oDados = $oDados;

        return $this;
    }

    private function setSecao($oSessao)
    {
        if (!array_key_exists("secoes", $this->aJson)) {
            $this->aJson["secoes"] = [];
        }

        $this->aJson["secoes"][] = $oSessao;
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode(DBJsonResponse::convertFromLatin1ToUTF8Recursively($this->aJson));
    }

    /**
     * @return AtendimentoInclusaoInscricaoJsonService
     * @throws \Exception
     */
    public function build()
    {
        $this->aJson["tipo_processo"] = AtendimentoInclusaoInscricaoJsonService::getTipoProcesso();
        $this->aJson["descricao"] = AtendimentoInclusaoInscricaoJsonService::DESCRICAO;
        $this->aJson["acao"] = AtendimentoInclusaoInscricaoJsonService::ACAO;

        $dadosEmpresaService = new DadosEmpresaService();
        $this->setSecao($dadosEmpresaService->setDados($this->oDados)->build());

        $dadosContadorServices = new DadosContadorService();
        $this->setSecao($dadosContadorServices->setDados($this->oDados)->build());

        $enderecoMunicipioService = new EnderecoMunicipioService();
        $this->setSecao($enderecoMunicipioService->setDados($this->oDados)->build());

        $atividadesService = new AtividadesService();
        $this->setSecao($atividadesService->setDados($this->oDados)->build());

        $sociosService = new SociosService();
        $this->setSecao($sociosService->setDados($this->oDados)->build());

        return $this;
    }

    public static function data($iData, $dbPattern = false)
    {
        if (empty($iData)) {
            return null;
        }

        $sData = trim($iData);

        $iDia = substr($sData, 6, 2);
        $iMes = substr($sData, 4, 2);
        $iAno = substr($sData, 0, 4);

        if ($dbPattern) {
            return "{$iAno}-{$iMes}-{$iDia}";
        }

        return "{$iDia}/{$iMes}/{$iAno}";
    }

    /**
     * @throws \Exception
     */
    public static function getTipoProcesso()
    {
        $codigoFormaReclamacao = 10;
        $tipoProcessoFormaReclamacao = TipoProcessoFormaReclamacao::query()
                                                                  ->where("p43_formareclamacao", $codigoFormaReclamacao)
                                                                  ->first();

        if (!$tipoProcessoFormaReclamacao) {
            return null;
        }

        return $tipoProcessoFormaReclamacao->p43_tipoproc;
    }
}
