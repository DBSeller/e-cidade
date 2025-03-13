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

namespace ECidade\Patrimonial\Ouvidoria\Externa\WebService;

use DBDate;
use DBDepartamentoRepository;
use ECidade\Patrimonial\Ouvidoria\Externa\Model\PreProcesso as PreProcessoModel;
use ECidade\Patrimonial\Ouvidoria\Externa\Repository\PreProcesso as PreProcessoRepository;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Model\TipoProcesso;
use InstituicaoRepository;
use UsuarioSistemaRepository;

class PreProcesso
{
    /**
     * @var string
     */
    private $observacao;

    /**
     * @var string
     */
    private $metadados;

    /**
     * @var int
     */
    private $instituicao;

    /**
     * @var int
     */
    private $departamento;

    /**
     * @var int
     */
    private $tipoProcesso;

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = urldecode($observacao);
    }

    /**
     * @return string
     */
    public function getMetadados()
    {
        return $this->metadados;
    }

    /**
     * @param string $metadados
     */
    public function setMetadados($metadados)
    {
        $this->metadados = $metadados;
    }

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param int $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return int
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param int $departamento
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    /**
     * @return int
     */
    public function getTipoProcesso()
    {
        return $this->tipoProcesso;
    }

    /**
     * @param int $tipoProcesso
     */
    public function setTipoProcesso($tipoProcesso)
    {
        $this->tipoProcesso = $tipoProcesso;
    }

    /**
     * @return int
     */
    public function getRequerenteNome()
    {
        return $this->requerenteNome;
    }

    /**
     * @param int $requerenteNome
     */
    public function setRequerenteNome($requerenteNome)
    {
        $this->requerenteNome = urldecode($requerenteNome);
    }

    /**
     * @return int
     */
    public function getRequerenteCpf()
    {
        return $this->requerenteCpf;
    }

    /**
     * @param int $requerenteCpf
     */
    public function setRequerenteCpf($requerenteCpf)
    {
        $this->requerenteCpf = $requerenteCpf;
    }

    /**
     * @throws \ParameterException
     * @throws \Exception
     */
    public function salvar()
    {
        $instituicao = InstituicaoRepository::getInstituicaoByCodigo($this->instituicao);
        $departamento = DBDepartamentoRepository::getDBDepartamentoByCodigo($this->departamento);

        $tipoProcesso = new TipoProcesso();
        $tipoProcesso->setCodigo($this->tipoProcesso);

        $preProcessoModel = new PreProcessoModel();
        $preProcessoModel->setData(new DBDate(date('Y-m-d')));
        $preProcessoModel->setUsuario(UsuarioSistemaRepository::getUsuarioSessao());
        $preProcessoModel->setCgm($instituicao->getCgm());
        $preProcessoModel->setRequerente($this->requerenteNome);
        // Usando o campo despacho do preprocesso para salvar o cpf do requerente
        $preProcessoModel->setDespacho($this->requerenteCpf);
        $preProcessoModel->setDepartamento($departamento);
        $preProcessoModel->setObservacao($this->observacao);
        $preProcessoModel->setHora(date('H:i'));
        $preProcessoModel->setInterno(false);
        $preProcessoModel->setPublico(false);
        $preProcessoModel->setInstituicao($instituicao);
        $preProcessoModel->setAno(date('Y'));
        $preProcessoModel->setMetadados($this->metadados);
        $preProcessoModel->setTipoProcesso($tipoProcesso);

        $preProcessoRepository = PreProcessoRepository::getInstancia();
        $preProcessoModel = $preProcessoRepository->salvar($preProcessoModel);

        return $preProcessoModel->getSequencial();
    }
}
