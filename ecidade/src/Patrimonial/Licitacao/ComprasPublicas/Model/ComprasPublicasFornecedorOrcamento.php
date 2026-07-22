<?php

namespace ECidade\Patrimonial\Licitacao\ComprasPublicas\Model;

use cl_pcorcamdescla;
use stdClass;
use cl_pcorcamjulg;
use cl_pcorcamjulgamentologitem;
use cl_pcorcamval;
use Exception;

class ComprasPublicasFornecedorOrcamento
{
    private $orcamforne;
    private $orcamitem;
    private $valor;
    private $quant;
    private $obs;
    private $vlrun;
    private $vencedorCancelado  = false;
    private $validmin           = null;
    private $percentualdesconto = 0.00;
    private $bdi                = 0.00;
    private $encargossociais    = 0.00;
    private $data               = null;
    private $notatecnica        = 0.00;
    private $taxaestimada       = 0.00;
    private $taxahomologada     = 0.00;
    private $vencedor           = null;
    private $gerajulgamento     = true;

    public function __construct(
        $orcamforne,
        $orcamitem,
        $valor,
        $quant,
        $obs,
        $vlrun,
        $data = null,
        $vlrdesc = 0.00
    ) {
        $this->orcamforne          = $orcamforne;
        $this->orcamitem           = $orcamitem;
        $this->valor               = $valor;
        $this->quant               = $quant;
        $this->obs                 = $obs;
        $this->vlrun               = $vlrun;
        $this->data                = $data;
        $this->percentualdesconto  = $vlrdesc;
    }

    public function save($codigolog)
    {
        $pcorcamval                          = new cl_pcorcamval();
        $pcorcamdescla                       = new cl_pcorcamdescla();
        $pcorcamjulg                         = new cl_pcorcamjulg();
        $pcorcamjulgamentologitem            = new cl_pcorcamjulgamentologitem();

        $pcorcamval->pc23_orcamforne         = $this->orcamforne;
        $pcorcamval->pc23_orcamitem          = $this->orcamitem;
        $pcorcamval->pc23_valor              = $this->valor;
        $pcorcamval->pc23_quant              = $this->quant;
        $pcorcamval->pc23_obs                = $this->obs;
        $pcorcamval->pc23_vlrun              = $this->vlrun;
        $pcorcamval->pc23_validmin           = $this->validmin;
        $pcorcamval->pc23_percentualdesconto = $this->percentualdesconto;
        $pcorcamval->pc23_bdi                = $this->bdi;
        $pcorcamval->pc23_encargossociais    = $this->encargossociais;
        $pcorcamval->pc23_data               = $this->data;
        $pcorcamval->pc23_notatecnica        = $this->notatecnica;
        $pcorcamval->pc23_taxaestimada       = $this->taxaestimada;
        $pcorcamval->pc23_taxahomologada     = $this->taxahomologada;
        $pcorcamval->incluir($this->orcamforne, $this->orcamitem);

        if ($pcorcamval->erro_status == 0) {
            throw new Exception($pcorcamval->erro_msg);
        }
        
        if ($this->isVencedor($this->orcamforne)) {
            if ($this->gerajulgamento) {
                $pcorcamjulg->pc24_orcamitem  = $this->orcamitem;
                $pcorcamjulg->pc24_pontuacao  = 1;
                $pcorcamjulg->pc24_orcamforne = $this->orcamforne;
                $pcorcamjulg->incluir($this->orcamitem, $this->orcamforne);
                if ($pcorcamjulg->erro_status == 0) {
                    throw new Exception($pcorcamjulg->erro_msg);
                }
            
                if ($this->vencedorCancelado) {
                    $pcorcamdescla->pc32_orcamitem  = $this->orcamitem;
                    $pcorcamdescla->pc32_orcamforne = $this->orcamforne;
                    $pcorcamdescla->pc32_motivo     = "Revogado";
                    $pcorcamdescla->incluir($this->orcamitem, $this->orcamforne);
                    if ($pcorcamdescla->erro_status == 0) {
                        throw new Exception($pcorcamdescla->erro_msg);
                    }
                }

                $pcorcamjulgamentologitem->pc93_pcorcamjulgamentolog = $codigolog;
                $pcorcamjulgamentologitem->pc93_pcorcamitem          = $this->orcamitem;
                $pcorcamjulgamentologitem->pc93_pcorcamforne         = $this->orcamforne;
                $pcorcamjulgamentologitem->pc93_valorunitario        = $this->vlrun;
                $pcorcamjulgamentologitem->pc93_pontuacao            = 1;
                $pcorcamjulgamentologitem->incluir(null);
                if ($pcorcamjulgamentologitem->erro_status == 0) {
                    throw new Exception($pcorcamjulgamentologitem->erro_msg);
                }
            }
        }
    }

    public function setVencedor($orcamforne, $vencedorCancelado = false)
    {
        $this->vencedor          = $orcamforne;
        $this->vencedorCancelado = $vencedorCancelado;
    }

    public function isVencedor($orcamforne)
    {
        if ($this->vencedor == $orcamforne) {
            return true;
        }

        return false;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function setValorUnitario($valorUnitario)
    {
        $this->vlrun = $valorUnitario;
    }

    public function setPercentualDesonto($percentualDesconto)
    {
        $this->percentualDesconto = $percentualDesconto;
    }
    
    public function setGeraJulgamento($gerajulgamento)
    {
        $this->gerajulgamento = $gerajulgamento;
    }
}
