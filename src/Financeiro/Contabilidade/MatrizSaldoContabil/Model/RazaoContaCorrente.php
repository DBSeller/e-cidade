<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model;


class RazaoContaCorrente
{
    /**
     * @var string
     */
    private $hashAtributos;

    /**
     * @var integer
     */
    private $reduzido;

    /**
     * @var int
     */
    private $codigoRecurso;

    /**
     * @var string
     */
    private $descricaoRecurso;

    /**
     * @var string
     */
    private $estrutural;

    /**
     * @var string
     */
    private $descricaoEstrutural;

    /**
     * @var \DBDate
     */
    private $dataMovimentacao;

    /**
     * @var float
     */
    private $movimentacaoCredito;

    /**
     * @var float
     */
    private $movimentacaoDebito;

    /**
     * @var string
     */
    private $naturezaSaldoAnterior;

    /**
     * @var float
     */
    private $saldoAnterior;

    /**
     * @var float
     */
    private $saldoFinal;

    /**
     * @var string
     */
    private $naturezaSaldoFilnal;

    /**
     * @var int
     */
    private $codigoDocumento;

    /**
     * @var string
     */
    private $descricaoDocumento;

    /**
     * @var string
     */
    private $descricaoNr;

    /**
     * @var
     */
    private $descricaoNd;

    /**
     * @var array
     */
    private $atributos = array('PO'=> null, 'FP'=> null, 'FR'=> null, 'FS'=> null, 'DC'=> null, 'NR'=> null, 'ND'=> null);

    public function __construct($reduzido, $codigoDocumento, \DBDate $dataMovimentacao, $hashAtributos)
    {
        $this->dataMovimentacao = $dataMovimentacao;
        $this->reduzido = $reduzido;
        $this->codigoDocumento = $codigoDocumento;
        $this->movimentacaoCredito = 0.0;
        $this->movimentacaoDebito = 0.0;
        $this->saldoAnterior = 0.0;
        $this->saldoFinal = 0.0;

        $this->setAtributos($hashAtributos);
        $this->gerarHashAtributos();
    }

    /**
     * @param \DBDate $dataMovimentacao
     * @param $hashAtributos
     */
    private function setAtributos($hashAtributos)
    {
        $atributos = explode(',', $hashAtributos);
        foreach ($atributos as $atributo) {
            $siglaAtributo =  explode('#', $atributo);
            $this->atributos[$siglaAtributo[0]] = $siglaAtributo[1];
        }

        //Elimina atributos que não foram inicializados
        foreach ($this->atributos as $sigla => $atributo) {
            if (is_null($atributo)) {
                unset($this->atributos[$sigla]);
            }
        }
    }

    /**
     * Gera o hash de atributos no formato: <Y-m-d>|<siglaAtributo>|<valorAtributo> ... |<codigoDocumento>
     * Ex.: 2018-01-12|PO#0201|FS#08244|FR#4566|51
     * @throws \Exception
     */
    private function gerarHashAtributos()
    {
        if (empty($this->atributos) || empty($this->dataMovimentacao) || !($this->dataMovimentacao instanceof \DBDate)) {
            throw new \Exception('Não foi possível gerar o hash de atributos da conta corrente.');
        }

        $this->hashAtributos = $this->dataMovimentacao->getDate();
        foreach ($this->atributos as $sigla => $atributo) {
            $this->hashAtributos .= "|{$sigla}#{$atributo}";
        }
        $this->hashAtributos .= "|{$this->codigoDocumento}";
    }

    /**
     * @return float
     */
    public function getMovimentacaoCredito()
    {
        return $this->movimentacaoCredito;
    }

    /**
     * @param float $valor
     */
    public function somarMovimentacaoCredito($valor)
    {
        $this->movimentacaoCredito += $valor;
    }

    /**
     * @return float
     */
    public function getMovimentacaoDebito()
    {
        return $this->movimentacaoDebito;
    }

    /**
     * @param float $valor
     */
    public function somarMovimentacaoDebito($valor)
    {
        $this->movimentacaoDebito += $valor;
    }

    /**
     * @return float
     */
    public function getSaldoAnterior()
    {
        return $this->saldoAnterior;
    }

    /**
     * @param float $saldoAnterior
     */
    public function setSaldoAnterior($saldoAnterior)
    {
        $this->saldoAnterior = $saldoAnterior;
    }


    /**
     * @return float
     */
    public function getSaldoFinal()
    {
        return $this->saldoFinal;
    }

    /**
     * @param $saldoFinal
     */
    public function setSaldoFinal($saldoFinal)
    {
        $this->saldoFinal = $saldoFinal;
    }

    /**
     * @return array
     */
    public function getAtributos()
    {
        return $this->atributos;
    }

    /**
     * @return int
     */
    public function getReduzido()
    {
        return $this->reduzido;
    }

    /**
     * @return int
     */
    public function getCodigoDocumento()
    {
        return $this->codigoDocumento;
    }

    /**
     * @param $codigoDocumento
     */
    public function setCodigoDocumento($codigoDocumento)
    {
        $this->codigoDocumento = $codigoDocumento;
    }

    /**
     * @param string $descricaoDocumento
     */
    public function setDescricaoDocumento($descricaoDocumento)
    {
        $this->descricaoDocumento = $descricaoDocumento;
    }

    /**
     * @return string
     */
    public function getDescricaoDocumento()
    {
        return $this->descricaoDocumento;
    }

    /**
     * @return string
     */
    public function getEstrutural()
    {
        return $this->estrutural;
    }

    /**
     * @param string $estrutural
     */
    public function setEstrutural($estrutural)
    {
        $this->estrutural = $estrutural;
    }

    /**
     * @return string
     */
    public function getHashAtributos()
    {
        return $this->hashAtributos;
    }

    /**
     * @return \DBDate
     */
    public function getDataMovimentacao()
    {
        return $this->dataMovimentacao;
    }

    /**
     * @return string
     */
    public function getNaturezaSaldoAnterior()
    {
        return $this->naturezaSaldoAnterior;
    }

    /**
     * @param string $naturezaSaldoAnterior
     */
    public function setNaturezaSaldoAnterior($naturezaSaldoAnterior)
    {
        $this->naturezaSaldoAnterior = $naturezaSaldoAnterior;
    }

    /**
     * @return string
     */
    public function getDescricaoNr()
    {
        return $this->descricaoNr;
    }

    /**
     * @param string $descricaoNr
     */
    public function setDescricaoNr($descricaoNr)
    {
        $this->descricaoNr = $descricaoNr;
    }

    /**
     * @return string
     */
    public function getDescricaoNd()
    {
        return $this->descricaoNd;
    }

    /**
     * @param string $descricaoNd
     */
    public function setDescricaoNd($descricaoNd)
    {
        $this->descricaoNd = $descricaoNd;
    }

    /**
     * @return bool
     */
    public function existeAtributoNd()
    {
        return empty($this->atributos['ND']) ? false : true;
    }

    /**
     * @return bool
     */
    public function existeAtributoNr()
    {
        return empty($this->atributos['NR']) ? false : true;
    }

    /**
     * @return string
     */
    public function getNaturezaSaldoFinal()
    {
        return $this->naturezaSaldoFilnal;
    }

    /**
     * @param string $naturezaSaldoFilnal
     */
    public function setNaturezaSaldoFilnal($naturezaSaldoFilnal)
    {
        $this->naturezaSaldoFilnal = $naturezaSaldoFilnal;
    }

    /**
     * @return string
     */
    public function getDescricaoEstrutural()
    {
        return $this->descricaoEstrutural;
    }

    /**
     * @param string $descricaoEstrutural
     */
    public function setDescricaoEstrutural($descricaoEstrutural)
    {
        $this->descricaoEstrutural = $descricaoEstrutural;
    }

    /**
     * @return string
     */
    public function getDescricaoRecurso()
    {
        return $this->descricaoRecurso;
    }

    /**
     * @param string $descricaoRecurso
     */
    public function setDescricaoRecurso($descricaoRecurso)
    {
        $this->descricaoRecurso = $descricaoRecurso;
    }

    /**
     * @return int
     */
    public function getCodigoRecurso()
    {
        return $this->codigoRecurso;
    }

    /**
     * @param int $codigoRecurso
     */
    public function setCodigoRecurso($codigoRecurso)
    {
        $this->codigoRecurso = $codigoRecurso;
    }
}
