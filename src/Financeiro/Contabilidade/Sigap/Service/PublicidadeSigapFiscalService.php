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

namespace ECidade\Financeiro\Contabilidade\Sigap\Service;

use DBDate;
use ECidade\Financeiro\Contabilidade\Sigap\Model\PublicidadeSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Repository\MeioComunicacaoRespository;
use ECidade\Financeiro\Contabilidade\Sigap\Repository\PublicidadeSigapFiscalRepository;
use Exception;
use Instituicao;
use Periodo;
use stdClass;

/**
 * Class PublicidadeSigapFiscalService
 * @package ECidade\Financeiro\Contabilidade\Sigap\Service
 */
class PublicidadeSigapFiscalService
{
    /**
     * @var Instituicao
     */
    private $instituicao;

    public function __construct(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @param stdClass $parametros
     * @return PublicidadeSigapFiscal
     * @throws Exception
     */
    public function salvar($parametros)
    {
        if (empty($parametros->tipoRelatorio)) {
            throw new \Exception('Informe o "Tipo de Relatorio"');
        }
        if (empty($parametros->periodo)) {
            throw new \Exception('Informe o "Per?odo"');
        }
        if (empty($parametros->descricao)) {
            throw new \Exception('Informe a "Descricao"');
        }
        if (empty($parametros->dataPublicacao)) {
            throw new \Exception('Informe a "Data de Publica??o"');
        }
        if (empty($parametros->meioComunicacao)) {
            throw new \Exception('Informe o "Meio de Comunica??o"');
        }

        $ano = date('Y');
        if (!empty($parametros->ano)) {
            $ano = $parametros->ano;
        }

        $publicidade = new PublicidadeSigapFiscal();
        $publicidade->setCodigo($parametros->codigo);
        $publicidade->setAno($ano);
        $publicidade->setDescricao($parametros->descricao);
        $publicidade->setCodigoTipoRelatorio($parametros->tipoRelatorio);
        $publicidade->setDataPublicacao(new DBDate($parametros->dataPublicacao));
        $publicidade->setMeioComunicacao(MeioComunicacaoRespository::find($parametros->meioComunicacao));
        $publicidade->setPeriodo(new Periodo($parametros->periodo));
        $publicidade->setLink($parametros->link);
        $publicidade->setLocalPublicacao($parametros->localPublicacao);
        $publicidade->setInstituicao($this->instituicao);

        $repository = new PublicidadeSigapFiscalRepository();
        $publicidade = $repository->salvar($publicidade);
        return $publicidade;
    }

    /**
     * @param $ano
     * @return PublicidadeSigapFiscal[]
     * @throws Exception
     */
    public function getPublicidadesPorAno($ano)
    {
        $ano = empty($ano) ? date('Y'): $ano;
        $repository = new PublicidadeSigapFiscalRepository();
        return $repository->scopeAno($ano)
            ->scopeInstituicao($this->instituicao)
            ->get();
    }

    /**
     * @param $codigo
     * @return bool
     * @throws Exception
     */
    public function remover($codigo)
    {
        $repository = new PublicidadeSigapFiscalRepository();
        $repository->delete($codigo);
        return true;
    }
}
