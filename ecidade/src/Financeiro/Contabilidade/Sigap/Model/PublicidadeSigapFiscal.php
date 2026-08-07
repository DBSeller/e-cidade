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

namespace ECidade\Financeiro\Contabilidade\Sigap\Model;

use DBDate;
use ECidade\Financeiro\Contabilidade\Sigap\Repository\MeioComunicacaoRespository;
use Exception;
use Instituicao;
use InstituicaoRepository;
use Periodo;

/**
 * Class PublicidadeSigapFiscal
 * @package ECidade\Financeiro\Contabilidade\Sigap\Model
 */
class PublicidadeSigapFiscal
{
    const RELATORIO_RREO = 1;
    const RELATORIO_RGF = 2;

    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var integer
     */
    private $ano;
    /**
     * @var integer
     */
    private $codigoTipoRelatorio;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var DBDate
     */
    private $dataPublicacao;
    /**
     * @var MeioComunicacao
     */
    private $meioComunicacao;
    /**
     * @var Periodo
     */
    private $periodo;

    /**
     * @var string
     */
    private $link;
    /**
     * @var string
     */
    private $local_publicacao;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var string[]
     */
    private static $tiposRelatorio = [
        self::RELATORIO_RREO => 'RREO',
        self::RELATORIO_RGF => 'RGF',
    ];


    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return PublicidadeSigapFiscal
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param int $ano
     * @return PublicidadeSigapFiscal
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     * @return PublicidadeSigapFiscal
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoTipoRelatorio()
    {
        return $this->codigoTipoRelatorio;
    }

    /**
     * @param int $codigoTipoRelatorio
     * @return PublicidadeSigapFiscal
     */
    public function setCodigoTipoRelatorio($codigoTipoRelatorio)
    {
        $this->codigoTipoRelatorio = $codigoTipoRelatorio;
        return $this;
    }

    /**
     * @return DBDate
     */
    public function getDataPublicacao()
    {
        return $this->dataPublicacao;
    }

    /**
     * @param DBDate $dataPublicacao
     * @return PublicidadeSigapFiscal
     */
    public function setDataPublicacao(DBDate $dataPublicacao)
    {
        $this->dataPublicacao = $dataPublicacao;
        return $this;
    }

    /**
     * @return MeioComunicacao
     */
    public function getMeioComunicacao()
    {
        return $this->meioComunicacao;
    }

    /**
     * @param MeioComunicacao $meioComunicacao
     * @return PublicidadeSigapFiscal
     */
    public function setMeioComunicacao(MeioComunicacao $meioComunicacao)
    {
        $this->meioComunicacao = $meioComunicacao;
        return $this;
    }

    /**
     * @return Periodo
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * @param Periodo $periodo
     * @return PublicidadeSigapFiscal
     */
    public function setPeriodo(Periodo $periodo)
    {
        $this->periodo = $periodo;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return PublicidadeSigapFiscal
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalPublicacao()
    {
        return $this->local_publicacao;
    }

    /**
     * @param string $local_publicacao
     * @return PublicidadeSigapFiscal
     */
    public function setLocalPublicacao($local_publicacao)
    {
        $this->local_publicacao = $local_publicacao;
        return $this;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     * @return PublicidadeSigapFiscal
     */
    public function setInstituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
        return $this;
    }

    /**
     * @param array $state
     * @return PublicidadeSigapFiscal
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('c136_codigo', $state)) {
            $self->setCodigo($state['c136_codigo']);
        }
        if (array_key_exists('c136_ano', $state)) {
            $self->setAno($state['c136_ano']);
        }
        if (array_key_exists('c136_descricao', $state)) {
            $self->setDescricao($state['c136_descricao']);
        }
        if (array_key_exists('c136_tipo_relatorio', $state)) {
            $self->setCodigoTipoRelatorio($state['c136_tipo_relatorio']);
        }
        if (array_key_exists('c136_data_publicacao', $state)) {
            $self->setDataPublicacao(new DBDate($state['c136_data_publicacao']));
        }
        if (array_key_exists('c136_meio_comunicacao', $state)) {
            $self->setMeioComunicacao(MeioComunicacaoRespository::find($state['c136_meio_comunicacao']));
        }
        if (array_key_exists('c136_periodo', $state)) {
            $self->setPeriodo(new Periodo($state['c136_periodo']));
        }
        if (array_key_exists('c136_link', $state)) {
            $self->setLink($state['c136_link']);
        }
        if (array_key_exists('c136_local_publicacao', $state)) {
            $self->setLocalPublicacao($state['c136_local_publicacao']);
        }
        if (array_key_exists('c136_instituicao', $state)) {
            $self->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($state['c136_instituicao']));
        }

        return $self;
    }

    /**
     * @return string
     */
    public function getDescricaoTipoRelatorio()
    {
        return self::$tiposRelatorio[$this->getCodigoTipoRelatorio()];
    }
}
