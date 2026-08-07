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

use DBLargeObject;

class ImportacaoQualificacaoCadastral
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $data;

    /**
     * @var \Instituicao
     */
    private $instituicao;

    /**
     * @var string
     */
    private $nomeArquivo;

    /**
     * @var boolean
     */
    private $processado;

    /**
     * @var integer
     */
    private $arquivoOid;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ImportacaoQualificacaoCadastral
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \DateTime $data
     * @return ImportacaoQualificacaoCadastral
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return \Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param \Instituicao $instituicao
     * @return ImportacaoQualificacaoCadastral
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;

        return $this;
    }

    /**
     * @return string
     */
    public function getNomeArquivo()
    {
        return $this->nomeArquivo;
    }

    /**
     * @param string $nomeArquivo
     * @return ImportacaoQualificacaoCadastral
     */
    public function setNomeArquivo($nomeArquivo)
    {
        $this->nomeArquivo = $nomeArquivo;

        return $this;
    }

    /**
     * @return bool
     */
    public function isProcessado()
    {
        return $this->processado;
    }

    /**
     * @param bool $processado
     * @return ImportacaoQualificacaoCadastral
     */
    public function setProcessado($processado)
    {
        $this->processado = $processado;

        return $this;
    }

    /**
     * @return int
     */
    public function getArquivoOid()
    {
        return $this->arquivoOid;
    }

    public function getPathArquivo()
    {
        $arquivo = 'tmp/'.$this->nomeArquivo;
        if (!DBLargeObject::leitura($this->arquivoOid, $arquivo)) {
            throw new \Exception("Erro ao escrever arquivo em disco.");
        }

        return $arquivo;
    }

    /**
     * @param int $arquivoOid
     * @return ImportacaoQualificacaoCadastral
     */
    public function setArquivoOid($arquivoOid)
    {
        $this->arquivoOid = $arquivoOid;
        return $this;
    }

    /**
     * Retorna as propriedades da entidade como um stdClass.
     * @return \stdClass
     */
    public function getPropriedadesStdClass()
    {
        $std = new \stdClass();
        $std->id = $this->id;
        $std->data = $this->data->format('d/m/Y');
        $std->hora = $this->data->format('H:i:s');
        $std->instituicao = $this->instituicao;
        $std->nomeArquivo = $this->nomeArquivo;
        $std->processado = $this->processado;
        $std->arquivoOid = $this->arquivoOid;
        return $std;
    }

}