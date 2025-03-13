<?php
/*
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
namespace ECidade\RecursosHumanos\RH\PontoEletronico\Validacao;

/**
 * Class ValidacaoBasica
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Validacao
 */
class ValidacaoBasica extends PontoEletronico implements InterfacePontoEletronico {

  /**
   * @var \DBDate
   */
  protected $data;

  /**
   * @var array
   */
  protected $erros = array();

  protected $lValidarAfastamento = true;

  /**
   * @return bool
   * @throws \ParameterException
   */
  public function validar() {

    if (empty($this->servidor) || !$this->servidor instanceof \Servidor) {
      throw new \ParameterException("Informe o servidor para ser validado.");
    }

    if ($this->lValidarAfastamento && $this->possuiAfastamentoNoRHNaData($this->data)) {
      $this->erros[self::POSSUI_AFASTAMENTO_NO_RH_NA_DATA] = 'Servidor com afastamento no RH para a data.';
    }

    if ( ! $this->possuiEscalaNaData($this->data)) {
      $this->erros[self::POSSUI_ESCALA_NA_DATA] = 'Servidor sem escala cadastrada para a data.';
    }

    if ( ! $this->possuiLotacaoConfiguradaNoPontoEletronico()) {
      $this->erros[self::POSSUI_LOTACAO_CONFIGURADA_NO_PONTO_ELETRONICO] = 'Servidor sem configuração de lotação no ponto eletrônico.';
    }

    if ( ! $this->possuiLotacaoConfiguradaParaServidor()) {
      $this->erros[self::POSSUI_LOTACAO_CONFIGURADA] = 'Servidor sem lotação configurada.';
    }

    return count($this->erros) === 0;
  }

  /**
   * @return array
   */
  public function getErros() {
    return $this->erros;
  }

  /**
   * @return \DBDate
   */
  public function getData() {
    return $this->data;
  }

  /**
   * @param \DBDate $data
   *
   * @return self
   */
  public function setData(\DBDate $data) {

    $this->data = $data;
    return $this;
  }

  public function validarAfastamento($lValidarAfastamento) {
    $this->lValidarAfastamento = $lValidarAfastamento;
  }
}
