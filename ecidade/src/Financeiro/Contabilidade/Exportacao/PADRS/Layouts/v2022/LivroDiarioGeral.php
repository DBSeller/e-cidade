<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2022;

use ECidade\Core\Mappers\ParseArray;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\LayoutPad;

class LivroDiarioGeral extends ParseArray implements LayoutPad
{
    protected $dePara = [
        "Codigo da Conta do Balancete de Verificacao" => "codigoContabalanceteVerificao",
        "Codigo do Orgao + Unidade Orcamentaria" => "orgaoUnidade",
        "Reservado para uso Futuro" => "campoReservado",
        "Número do Lancamento" => "lancamento",
        "Número do Lote" => "numeroLote",
        "Número do Documento" => "numeroDocumento",
        "Data do Lancamento" => "dataLancamento",
        "Valor" => "valor",
        "Tipo de Lancamento" => "tipoLancamento",
        "Número de Arquivamento" => "numeroArquivamento",
        "Histórico" => "historico",
        "Tipo Documento" => "tipoDocumento",
        "Natureza da Informacao" => "natureza",
        "Indicador de Superavit Financeiro" => "indicadorSuperavitFinanceiro",
        "Código do Recurso Vinculado" => "fonteRecurso",
        "Complemento do Recurso" => "complemento",
        "Codigo da Fonte de Recurso" => "fonteRecursoSiconfi",
        "Codigo de Execucao Orcamentaria - CO" => "complementoSiconfi",
    ];

    protected $codigoContabalanceteVerificao;
    protected $orgaoUnidade;
    protected $campoReservado = '0000';
    protected $lancamento;
    protected $numeroLote;
    protected $numeroDocumento;
    protected $dataLancamento;
    protected $valor;
    protected $tipoLancamento;
    protected $numeroArquivamento = '000000000000';
    protected $historico;
    protected $tipoDocumento;
    protected $natureza;
    protected $indicadorSuperavitFinanceiro;
    protected $fonteRecurso;
    protected $complemento;
    protected $fonteRecursoSiconfi = '0000';
    protected $complementoSiconfi = '0000';

    /**
     * @return mixed
     */
    public function getCodigoContabalanceteVerificao()
    {
        return $this->codigoContabalanceteVerificao;
    }

    /**
     * @param mixed $codigoContabalanceteVerificao
     * @return LivroDiarioGeral
     */
    public function setCodigoContabalanceteVerificao($codigoContabalanceteVerificao)
    {
        $this->codigoContabalanceteVerificao = $codigoContabalanceteVerificao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrgaoUnidade()
    {
        return $this->orgaoUnidade;
    }

    /**
     * @param mixed $orgaoUnidade
     * @return LivroDiarioGeral
     */
    public function setOrgaoUnidade($orgaoUnidade)
    {
        $this->orgaoUnidade = $orgaoUnidade;
        return $this;
    }

    /**
     * @return string
     */
    public function getCampoReservado()
    {
        return $this->campoReservado;
    }

    /**
     * @param string $campoReservado
     * @return LivroDiarioGeral
     */
    public function setCampoReservado($campoReservado)
    {
        $this->campoReservado = $campoReservado;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLancamento()
    {
        return $this->lancamento;
    }

    /**
     * @param mixed $lancamento
     * @return LivroDiarioGeral
     */
    public function setLancamento($lancamento)
    {
        $this->lancamento = $lancamento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroLote()
    {
        return $this->numeroLote;
    }

    /**
     * @param mixed $numeroLote
     * @return LivroDiarioGeral
     */
    public function setNumeroLote($numeroLote)
    {
        $this->numeroLote = $numeroLote;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroDocumento()
    {
        return $this->numeroDocumento;
    }

    /**
     * @param mixed $numeroDocumento
     * @return LivroDiarioGeral
     */
    public function setNumeroDocumento($numeroDocumento)
    {
        $this->numeroDocumento = $numeroDocumento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataLancamento()
    {
        return $this->dataLancamento;
    }

    /**
     * @param mixed $dataLancamento
     * @return LivroDiarioGeral
     */
    public function setDataLancamento($dataLancamento)
    {
        $this->dataLancamento = $dataLancamento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     * @return LivroDiarioGeral
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoLancamento()
    {
        return $this->tipoLancamento;
    }

    /**
     * @param mixed $tipoLancamento
     * @return LivroDiarioGeral
     */
    public function setTipoLancamento($tipoLancamento)
    {
        $this->tipoLancamento = $tipoLancamento;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumeroArquivamento()
    {
        return $this->numeroArquivamento;
    }

    /**
     * @param string $numeroArquivamento
     * @return LivroDiarioGeral
     */
    public function setNumeroArquivamento($numeroArquivamento)
    {
        $this->numeroArquivamento = $numeroArquivamento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHistorico()
    {
        return $this->historico;
    }

    /**
     * @param mixed $historico
     * @return LivroDiarioGeral
     */
    public function setHistorico($historico)
    {
        $this->historico = $historico;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * @param mixed $tipoDocumento
     * @return LivroDiarioGeral
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNatureza()
    {
        return $this->natureza;
    }

    /**
     * @param mixed $natureza
     * @return LivroDiarioGeral
     */
    public function setNatureza($natureza)
    {
        $this->natureza = $natureza;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndicadorSuperavitFinanceiro()
    {
        return $this->indicadorSuperavitFinanceiro;
    }

    /**
     * @param mixed $indicadorSuperavitFinanceiro
     * @return LivroDiarioGeral
     */
    public function setIndicadorSuperavitFinanceiro($indicadorSuperavitFinanceiro)
    {
        $this->indicadorSuperavitFinanceiro = $indicadorSuperavitFinanceiro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFonteRecurso()
    {
        return $this->fonteRecurso;
    }

    /**
     * @param mixed $fonteRecurso
     * @return LivroDiarioGeral
     */
    public function setFonteRecurso($fonteRecurso)
    {
        $this->fonteRecurso = $fonteRecurso;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param mixed $complemento
     * @return LivroDiarioGeral
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFonteRecursoSiconfi()
    {
        return $this->fonteRecursoSiconfi;
    }

    /**
     * @param mixed $fonteRecursoSiconfi
     * @return LivroDiarioGeral
     */
    public function setFonteRecursoSiconfi($fonteRecursoSiconfi)
    {
        $this->fonteRecursoSiconfi = $fonteRecursoSiconfi;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComplementoSiconfi()
    {
        return $this->complementoSiconfi;
    }

    /**
     * @param mixed $complementoSiconfi
     * @return LivroDiarioGeral
     */
    public function setComplementoSiconfi($complementoSiconfi)
    {
        $this->complementoSiconfi = $complementoSiconfi;
        return $this;
    }

    public function toArray()
    {
        return [
            "codigoContabalanceteVerificao" => $this->getCodigoContabalanceteVerificao(),
            "orgaoUnidade" => $this->getOrgaoUnidade(),
            "campoReservado" => $this->getCampoReservado(),
            "lancamento" => $this->getLancamento(),
            "numeroLote" => $this->getNumeroLote(),
            "numeroDocumento" => $this->getNumeroDocumento(),
            "dataLancamento" => $this->getDataLancamento(),
            "valor" => $this->getValor(),
            "tipoLancamento" => $this->getTipoLancamento(),
            "numeroArquivamento" => $this->getNumeroArquivamento(),
            "historico" => $this->getHistorico(),
            "tipoDocumento" => $this->getTipoDocumento(),
            "natureza" => $this->getNatureza(),
            "indicadorSuperavitFinanceiro" => $this->getIndicadorSuperavitFinanceiro(),
            "fonteRecurso" => $this->getFonteRecurso(),
            "complemento" => $this->getComplemento(),
            "fonteRecursoSiconfi" => $this->getFonteRecursoSiconfi(),
            "complementoSiconfi" => $this->getComplementoSiconfi(),
        ];
    }
}
