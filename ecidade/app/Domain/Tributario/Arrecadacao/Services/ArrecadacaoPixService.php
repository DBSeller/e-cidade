<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use App\Domain\Patrimonial\Protocolo\Repository\CgmRepository;
use App\Domain\Tributario\Arrecadacao\Models\Arretipo;
use App\Domain\Patrimonial\Protocolo\Model\Cgm;
use App\Domain\Tributario\Arrecadacao\Models\Arretipopix;
use App\Domain\Tributario\Arrecadacao\Models\Modcarnepadraopix;
use App\Domain\Tributario\Arrecadacao\Models\Recibobarpix;
use App\Domain\Tributario\Arrecadacao\Models\Recibopaga;
use App\Domain\Tributario\Arrecadacao\Pix\Bancos\BancoDoBrasil;
use App\Domain\Tributario\Arrecadacao\Pix\Bancos\Banrisul;
use App\Domain\Tributario\Arrecadacao\Repositories\RecibobarpixRepository;
use Exception;
use recibo;
use regraEmissao;
use convenio;

final class ArrecadacaoPixService
{
    protected $codigo_arrecadacao;
    protected $parcela_arrecadacao = 0;
    protected $convenio;
    protected $modelo;
    protected $parcelainicio;
    protected $parcelafim;
    protected $tipo_debito;
    protected $vencimento;
    protected $config;

    private $instit  = null;
    private $ip      = null;
    private $datausu = null;

    public function seInstit($value)
    {
        $this->instit = $value;
    }

    public function setIp($value)
    {
        $this->ip = $value;
    }

    public function setDatausu($value)
    {
        $this->datausu = $value;
    }

    public function gerarPix()
    {
        if (empty($this->getCodigoArrecadacao())) {
            throw new Exception("Recibo não informado!");
        }

        $regraemissao = new regraEmissao(
            $this->getTipoDebito(),
            $this->getModelo(),
            $this->instit,
            date("Y-m-d", $this->datausu),
            $this->ip,
            true,
            false,
            $this->getParcelaInicio(),
            $this->getParcelaFim()
        );


        if (!self::validaEmissaoPix(
            $this->getTipoDebito(),
            $regraemissao,
            $this->getCodigoArrecadacao()
        )) {
            return false;
        }

        $recibo = new recibo(
            null,
            null,
            1,
            $this->getCodigoArrecadacao()
        );


        $repositoryCgm = new CgmRepository();
        $cgm = $repositoryCgm->getByNumcgm($recibo->getCgm());

        $tipoDebito = Arretipo::where(
            'k00_tipo',
            $this->getTipoDebito()
        )->first();

        $valorRecibo = $recibo->getTotalRecibo();
        $valorCodigoBarras = str_pad(
            number_format((float)$valorRecibo, 2, '', ''),
            11,
            0,
            STR_PAD_LEFT
        );

        $convenio = new convenio(
            $regraemissao->getConvenio(),
            $recibo->getNumpreRecibo(),
            0,
            $valorRecibo,
            $valorCodigoBarras,
            $this->getVencimento(),
            $tipoDebito->k00_tercdigrecnormal
        );

        $this->geraPixBanco($convenio, $cgm, $valorRecibo);
    }

    public function geraPixCarne($convenio, $numpre, $numpar, $numcgm, $valor, $codigoBarras = null)
    {
        $repositoryCgm = new CgmRepository();
        $cgm = $repositoryCgm->getByNumcgm($numcgm);

        $this->setCodigoArrecadacao($numpre);
        $this->setParcelaArrecadacao($numpar);

        $this->geraPixBanco($convenio, $cgm, $valor, $codigoBarras);
    }

    /**
     * @throws \BusinessException
     * @throws Exception
     */
    protected function geraPixBanco($convenio, Cgm $cgm, $valor, $codigoBarras = null)
    {
        if ($this->validaEmissaoReciboBarPix($codigoBarras ? $codigoBarras : $convenio->getCodigoBarra())) {
            return;
        }

        $arretipopix = Arretipopix::query()->where("k00_tipo", $this->getTipoDebito())->first();

        $arretipopixbancogeracaoService = new ArretipopixbancogeracaoService();
        $bankCode = $arretipopixbancogeracaoService->chooseBankToGeneratePix($arretipopix, true, false);

        switch ($bankCode) {
            case BancoDoBrasil::BANK_CODE:
                $banco = new BancoDoBrasil();
                break;
            case Banrisul::BANK_CODE:
                $banco = new Banrisul();
                break;
            default:
                throw new \BusinessException("Verifique as configurações do PIX, banco {$bankCode} não configurado.");
        }

        $banco->setConvenio($convenio);
        $banco->setCodigoBarras($codigoBarras ? $codigoBarras : $convenio->getCodigoBarra());
        $banco->setCgm($cgm);
        $banco->setValor($valor);
        $banco->setCodigoArrecadacao($this->getCodigoArrecadacao());
        $banco->setParcela($this->getParcelaArrecadacao());
        $banco->setVencimento($this->getVencimento());
        $banco->gerarPix();
    }

    protected function validaEmissaoReciboBarPix($codigoBarras)
    {
        $repository = new RecibobarpixRepository();
        return $repository->getByCodBar($codigoBarras);
    }

    public function setCodigoArrecadacao($codigo_arrecadacao)
    {
        $this->codigo_arrecadacao = $codigo_arrecadacao;
    }

    public function getCodigoArrecadacao()
    {
        return $this->codigo_arrecadacao;
    }

    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;
    }

    public function getConvenio()
    {
        return $this->convenio;
    }

    public function setModelo($modelo)
    {
        $this->modelo = $modelo;
    }

    public function getModelo()
    {
        return $this->modelo;
    }

    public function setParcelaInicio($parcelainicio)
    {
        $this->parcelainicio = $parcelainicio;
    }

    public function getParcelaInicio()
    {
        return $this->parcelainicio;
    }

    public function setParcelaFim($parcelafim)
    {
        $this->parcelafim = $parcelafim;
    }

    public function getParcelaFim()
    {
        return $this->parcelafim;
    }

    public function setTipoDebito($tipo_debito)
    {
        $this->tipo_debito = $tipo_debito;
    }

    public function getTipoDebito()
    {
        return $this->tipo_debito;
    }

    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;
    }

    public function getVencimento()
    {
        return $this->vencimento;
    }

    public function getParcelaArrecadacao()
    {
        return $this->parcela_arrecadacao;
    }

    public function setParcelaArrecadacao($parcela_arrecadacao)
    {
        $this->parcela_arrecadacao = $parcela_arrecadacao;
    }

    public static function validaEmissaoPix($tipoDebito, regraEmissao $regraemissao, $numpre)
    {
        $codigoModelo = $regraemissao->getModCarnePadrao();

        $regraEmissaoPix = Modcarnepadraopix::where([
            'k48_sequencial' => $codigoModelo,
            'k48_ammpix' =>  true
        ])->first();

        $tipoDebito = Arretipopix::where([
            'k00_tipo' => $tipoDebito,
            'modsistema' => true
        ])->first();

        if ($tipoDebito && $regraEmissaoPix) {
            if (!empty($tipoDebito->dtini) && !empty($tipoDebito->dtfim)) {
                $dadosReciboPaga = Recibopaga::where(["k00_numnov" => $numpre])
                ->where("k00_dtvenc", "<", "'{$tipoDebito->dtini}'")
                ->where("k00_dtvenc", ">", "'{$tipoDebito->dtfim}'")
                ->get();

                if (!$dadosReciboPaga->isEmpty()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
