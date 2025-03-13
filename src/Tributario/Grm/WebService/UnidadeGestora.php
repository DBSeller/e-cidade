<?php
namespace ECidade\Tributario\Grm\WebService;

use ECidade\Tributario\Grm\Repository\TipoRecolhimento as TipoRecolhimentoRepository;
use ECidade\Tributario\Grm\UnidadeGestora as UnidadeGestoraModel;
use ECidade\Tributario\Arrecadacao\Model\TaxasLancadasDinamicos;
use ECidade\Tributario\Arrecadacao\Repository\TaxasLancadasDinamicosRepository;
use ECidade\Tributario\Arrecadacao\Model\TaxasLancadas;
use ECidade\Tributario\Arrecadacao\Repository\TaxasLancadasRepository;

/**
 * Class UnidadeGestoraGrmWebService
 *
 */
class UnidadeGestora
{

    public function getUnidadesGestoras()
    {

        $oUnidadeRepository  = new \ECidade\Tributario\Grm\Repository\UnidadeGestora();
        $aUnidades = array();
        foreach ($oUnidadeRepository->getAll() as $oUnidade) {
            $oUnidadeRetorno         = new \stdClass();
            $oUnidadeRetorno->codigo = $oUnidade->getCodigo();
            $oUnidadeRetorno->nome   = $oUnidade->getNome();
            $aUnidades[]             = utf8_encode_all($oUnidadeRetorno);
        }
        return $aUnidades;
    }

  /**
   * @param $codigoUnidade
   * @return array
   */
    public function getTiposRecolhimentosUnidadeGestora($codigoUnidade)
    {

        $oUnidade                    = new UnidadeGestoraModel($codigoUnidade);
        $oUnidade->setCodigo($codigoUnidade);

        $oTipoRecolhimentoRepository = new TipoRecolhimentoRepository();
        $aRecolhimentosUnidade       = $oTipoRecolhimentoRepository->getTiposRecolhimentoDaUnidadeGestora($oUnidade);
        $aRecolhimentos = array();
        foreach ($aRecolhimentosUnidade as $oRecolhimento) {
            $oRecolhimentoRetorno = new \stdClass();
            $oRecolhimentoRetorno->codigo = $oRecolhimento->getTipoRecolhimento()->getCodigo();
            $oRecolhimentoRetorno->codigo_recolhimento = $oRecolhimento->getTipoRecolhimento()->getCodigoRecolhimento();
            $oRecolhimentoRetorno->titulo = $oRecolhimento->getTipoRecolhimento()->getNome();
            $oRecolhimentoRetorno->tipo_recolhedor = $oRecolhimento->getTipoRecolhimento()->getTipoPessoa();
            $oRecolhimentoRetorno->titulo_reduzido = $oRecolhimento->getTipoRecolhimento()->getTituloReduzido();
            $oRecolhimentoRetorno->obriga_referencia = $oRecolhimento->getTipoRecolhimento()->obrigaNumeroReferencia();
            $oRecolhimentoRetorno->informa_desconto = $oRecolhimento->getTipoRecolhimento()->informaDesconto();
            $oRecolhimentoRetorno->informa_juros = $oRecolhimento->getTipoRecolhimento()->informaJuros();
            $oRecolhimentoRetorno->informa_multa = $oRecolhimento->getTipoRecolhimento()->informaMulta();
            $oRecolhimentoRetorno->informa_outros_acrescimos = $oRecolhimento->getTipoRecolhimento()
                                                                             ->informaOutrosAcrescimos();
            $oRecolhimentoRetorno->informa_outras_deducoes   = $oRecolhimento->getTipoRecolhimento()
                                                                             ->informaOutrasDeducoes();
            $oRecolhimentoRetorno->isTaxa                    = $oRecolhimento->isTaxa;

            if ($oRecolhimento->isTaxa) {
                $oRecolhimentoRetorno->isFixa = $oRecolhimento->isFixa;
                $oRecolhimentoRetorno->valorFixo = $oRecolhimento->valorFixo;
                $oRecolhimentoRetorno->dataVencimento = $oRecolhimento->dataVencimento;
                $oRecolhimentoRetorno->isTaxaSemUg = false;

                $taxasLancadasDinamicos  = new TaxasLancadasDinamicos();
                $taxasLancadasDinamicos->setTaxaslancadas($oRecolhimento->getTipoRecolhimento()->getCodigo());

                $taxasLancadasDinamicosRepository = TaxasLancadasDinamicosRepository::getInstance();
                $aCampos = $taxasLancadasDinamicosRepository->getCampos($taxasLancadasDinamicos);

                foreach ($aCampos as $oCampos) {
                    $oAtributoRetorno = new \stdClass();
                    $oAtributoRetorno->id = $oCampos->ar47_codcam;
                    $oAtributoRetorno->nome = $oCampos->rotulo;
                    $oAtributoRetorno->tipo = $oCampos->tipogrm;
                    $oAtributoRetorno->valor = $oCampos->valorDefault;
                    $oAtributoRetorno->obrigatorio = ($oCampos->ar47_obrigatorio == "t" ? true : false);
                    $oRecolhimentoRetorno->atributos[] = $oAtributoRetorno;
                }
            } else {
                $oRecolhimentoRetorno->atributos                 = array();
                $oGrupoAtributo  = TipoRecolhimentoRepository::getAtributosDoRecolhimento(
                    $oRecolhimento->getTipoRecolhimento()
                );

                if (!empty($oGrupoAtributo)) {
                    $atributos = $oGrupoAtributo->getAtributosAtivos();
                    foreach ($atributos as $oAtributo) {
                        $oAtributoRetorno = new \stdClass();
                        $oAtributoRetorno->id = $oAtributo->getCodigo();
                        $oAtributoRetorno->nome = $oAtributo->getDescricao();
                        $oAtributoRetorno->tipo = $oAtributo->getTipo();
                        $oAtributoRetorno->valor = $oAtributo->getValorDefault();
                        $oAtributoRetorno->obrigatorio = $oAtributo->isObrigatorio();
                        $oRecolhimentoRetorno->atributos[] = $oAtributoRetorno;
                    }
                }
            }

            $aRecolhimentos[] = $oRecolhimentoRetorno;
        }
        return utf8_encode_all($aRecolhimentos);
    }

    public function getAllTaxas()
    {
        $taxasLancadasRepository = TaxasLancadasRepository::getInstance();
        $sWhere = " (TO_CHAR(ar44_datavigencia, 'YYYY-MM-DD')::date >= '".date("Y-m-d")."'::date";
        $sWhere .= " OR ar44_datavigencia IS NULL)";
        $sWhere .= " AND ar44_receita IS NOT NULL";

        $aTaxas = $taxasLancadasRepository->getTaxas($sWhere);

        $oTipoRecolhimentoRepository = new TipoRecolhimentoRepository();

        foreach ($aTaxas as $oTaxa) {
            $oRecolhimentoTaxa = $oTipoRecolhimentoRepository->makeTaxas($oTaxa);

            $oRecolhimentoRetorno = new \stdClass();
            $oRecolhimentoRetorno->codigo = $oRecolhimentoTaxa->getCodigo();
            $oRecolhimentoRetorno->codigo_recolhimento = $oRecolhimentoTaxa->getCodigoRecolhimento();
            $oRecolhimentoRetorno->titulo = $oRecolhimentoTaxa->getNome();
            $oRecolhimentoRetorno->tipo_recolhedor = $oRecolhimentoTaxa->getTipoPessoa();
            $oRecolhimentoRetorno->titulo_reduzido = $oRecolhimentoTaxa->getTituloReduzido();
            $oRecolhimentoRetorno->obriga_referencia = $oRecolhimentoTaxa->obrigaNumeroReferencia();
            $oRecolhimentoRetorno->informa_desconto = $oRecolhimentoTaxa->informaDesconto();
            $oRecolhimentoRetorno->informa_juros = $oRecolhimentoTaxa->informaJuros();
            $oRecolhimentoRetorno->informa_multa = $oRecolhimentoTaxa->informaMulta();
            $oRecolhimentoRetorno->informa_outros_acrescimos = $oRecolhimentoTaxa->informaOutrosAcrescimos();
            $oRecolhimentoRetorno->informa_outras_deducoes   = $oRecolhimentoTaxa->informaOutrasDeducoes();
            $oRecolhimentoRetorno->isTaxa                    = true;
            $oRecolhimentoRetorno->isTaxaSemUg               = true;
            $oRecolhimentoRetorno->isFixa = ($oTaxa->ar44_tipo == 0);
            $oRecolhimentoRetorno->valorFixo = $oTaxa->i02_valor;
            $oRecolhimentoRetorno->dataVencimento = date("d/m/Y", strtotime("+{$oTaxa->ar44_diasvencimento} days"));
            $oRecolhimentoRetorno->tipoOrigem = $oTaxa->ar44_origem;

            $taxasLancadasDinamicos  = new TaxasLancadasDinamicos();
            $taxasLancadasDinamicos->setTaxaslancadas($oRecolhimentoTaxa->getCodigo());

            $taxasLancadasDinamicosRepository = TaxasLancadasDinamicosRepository::getInstance();
            $aCampos = $taxasLancadasDinamicosRepository->getCampos($taxasLancadasDinamicos);

            foreach ($aCampos as $oCampos) {
                $oAtributoRetorno = new \stdClass();
                $oAtributoRetorno->id = $oCampos->ar47_codcam;
                $oAtributoRetorno->nome = $oCampos->rotulo;
                $oAtributoRetorno->tipo = $oCampos->tipogrm;
                $oAtributoRetorno->valor = $oCampos->valorDefault;
                $oAtributoRetorno->obrigatorio = ($oCampos->ar47_obrigatorio == "t" ? true : false);
                $oRecolhimentoRetorno->atributos[] = $oAtributoRetorno;
            }

            $aRecolhimentos[] = $oRecolhimentoRetorno;
        }

        return utf8_encode_all($aRecolhimentos);
    }
}
