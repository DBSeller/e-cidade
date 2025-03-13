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
namespace ECidade\RecursosHumanos\ESocial\Entity;

/**
 * Class Reintegracao
 * @package ECidade\RecursosHumanos\ESocial\Entity
 */
class Reintegracao
{
    const AVALIACAO = 3000031;
    /**
     * @var 
     */
    private $cpfTrab;
    /**
     * @var 
     */
    private $nisTrab;
    /**
     * @var 
     */
    private $matricula;
    /**
     * @var 
     */
    private $tpReint;
    /**
     * @var 
     */
    private $nrProcJud;
    /**
     * @var 
     */
    private $nrLeiAnistia;
    /**
     * @var 
     */
    private $dtEfetRetorno;
    /**
     * @var 
     */
    private $dtEfeito;
    /**
     * @var 
     */
    private $indPagtoJuizo;

    /**
     * @return mixed 
     */
    public function getCpfTrab()
    {
        return $this->cpfTrab;
    }
    /**
     * @return mixed 
     */
    public function getNisTrab()
    {
        return $this->nisTrab;
    }
    /**
     * @return mixed 
     */
    public function getMatricula()
    {
        return $this->matricula;
    }
    /**
     * @return mixed 
     */
    public function getTpReint()
    {
        return $this->tpReint;
    }
    /**
     * @return mixed 
     */
    public function getNrProcJud()
    {
        return $this->nrProcJud;
    }
    /**
     * @return mixed 
     */
    public function getNrLeiAnistia()
    {
        return $this->nrLeiAnistia;
    }
    /**
     * @return mixed 
     */
    public function getDtEfetRetorno()
    {
        return $this->dtEfetRetorno;
    }
    /**
     * @return mixed 
     */
    public function getDtEfeito()
    {
        return $this->dtEfeito;
    }
    /**
     * @return mixed 
     */
    public function getIndPagtoJuizo()
    {
        return $this->indPagtoJuizo;
    }

    /**
     * @param mixed
     */ 
    public function setCpfTrab($cpfTrab)
    {
        $this->cpfTrab = $cpfTrab;
    }
    /**
     * @param mixed
     */ 
    public function setNisTrab($nisTrab)
    {
        $this->nisTrab = $nisTrab;
    }
    /**
     * @param mixed
     */ 
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }
    /**
     * @param mixed
     */ 
    public function setTpReint($tpReint)
    {
        $this->tpReint = $tpReint;
    }
    /**
     * @param mixed
     */ 
    public function setNrProcJud($nrProcJud)
    {
        $this->nrProcJud = $nrProcJud;
    }
    /**
     * @param mixed
     */ 
    public function setNrLeiAnistia($nrLeiAnistia)
    {
        $this->nrLeiAnistia = $nrLeiAnistia;
    }
    /**
     * @param mixed
     */ 
    public function setDtEfetRetorno($dtEfetRetorno)
    {
        $this->dtEfetRetorno = $dtEfetRetorno;
    }
    /**
     * @param mixed
     */ 
    public function setDtEfeito($dtEfeito)
    {
        $this->dtEfeito = $dtEfeito;
    }
    /**
     * @param mixed
     */ 
    public function setIndPagtoJuizo($indPagtoJuizo)
    {
        $this->indPagtoJuizo = $indPagtoJuizo;
    }
}
