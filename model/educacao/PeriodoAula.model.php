<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

/**
 * Representação de um período de aula
 *
 * @package    Educacao
 * @author     Andrio Costa - andrio.costa@dbseller.com.br
 * @version    $Revision: 1.1 $
 */
class PeriodoAula {

  /**
   * Periodo da escola
   * @var PeriodoEscola
   */
  private $oPeriodoEscola;

  /**
   * Disciplina no período
   * @var Disciplina
   */
  private $oDisciplina;

  /**
   * Dia da semana no padrão da ISO ISO-8601
   * @var int
   */
  private $iDiaSemena;

  public function __construct() {

  }

  /**
   * Atribui o dia da semanna no padrão da ISO ISO-8601
   * @param int $iDiaSemena
   */
  public function setDiaSemena($iDiaSemena) {
    $this->iDiaSemena = $iDiaSemena;
  }

  /**
   * Retorna  o dia da semanna no padrão da ISO ISO-8601
   * @return int
   */
  public function getDiaSemena() {
    return $this->iDiaSemena;
  }

  /**
   * Atribui a disciplina para o Período
   * @param Disciplina $oDisciplina
   */
  public function setDisciplina($oDisciplina) {
    $this->oDisciplina = $oDisciplina;
  }

  /**
   * Retona a disciplina para o Período
   * @return Disciplina
   */
  public function getDisciplina() {
    return $this->oDisciplina;
  }

  /**
   * Atribui o Periodo
   * @param PeriodoEscola $oPeriodoEscola
   */
  public function setPeriodoEscola($oPeriodoEscola) {
    $this->oPeriodoEscola = $oPeriodoEscola;
  }

  /**
   * Retorna o período
   * @return PeriodoEscola
   */
  public function getPeriodoEscola() {
    return $this->oPeriodoEscola;
  }

}