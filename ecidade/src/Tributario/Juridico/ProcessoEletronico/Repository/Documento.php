<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 15/06/18
 * Time: 15:31
 */

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Repository;

use ECidade\Tributario\Juridico\ProcessoEletronico\Documento as DocumentoModel;

class Documento extends \BaseClassRepository
{

    /**
     * @var Documento
     */
    protected static $oInstance;


    /**
     * @param $codigoProcesso
     * @return DocumentoModel[]
     * @throws \DBException
     */
    public static function getPorProcessoEletronico($codigoProcesso)
    {

        $daoProcessoDocumento = new \cl_integracaoprocessoeletronicoarquivo();
        $sqlDocumentos      = $daoProcessoDocumento->sql_query_file(
            null,
            "*",
            "v40_nome desc",
            "v40_integracaoprocessoeletronico = {$codigoProcesso} AND v40_tipo != 2"
        );
        $rsDocumentos = db_query($sqlDocumentos);
        if (!$rsDocumentos) {
            throw new \DBException("Erro ao pesquisar documentos do processo eletronico {$codigoProcesso}");
        }
        $instancia = self::getInstance();
        $documentos = \db_utils::makeCollectionFromRecord($rsDocumentos, function ($dados) use ($instancia) {
            return  $instancia->make($dados);
        });
        return $documentos;
    }

    /**
     * @param $codigoProcesso
     * @return DocumentoModel
     * @throws \DBException
     */
    public static function getInicialPorProcessoEletronico($codigoProcesso)
    {

        $daoProcessoDocumento = new \cl_integracaoprocessoeletronicoarquivo();
        $sqlDocumentos      = $daoProcessoDocumento->sql_query_file(
            null,
            "*",
            "v40_nome desc",
            "v40_integracaoprocessoeletronico = {$codigoProcesso} and v40_tipo = 1"
        );
        $rsDocumentos = db_query($sqlDocumentos);
        if (!$rsDocumentos) {
            throw new \DBException("Erro ao pesquisar documentos do processo eletronico {$codigoProcesso}");
        }
        $instancia = self::getInstance();
        $documentos = \db_utils::fieldsMemory($rsDocumentos, 0);
        return  $instancia->make($documentos);
    }

    /**
     * @param $dados
     * @return DocumentoModel
     */
    public function make($dados)
    {
        $documento = new DocumentoModel();
        $documento->setConteudo($dados->v40_arquivo);
        $documento->setNome($dados->v40_nome);
        $documento->setTipo($dados->v40_tipo);
        $documento->setData(new \DateTime($dados->v40_data));
        $documento->setCodigo($dados->v40_sequencial);
        $documento->setCaminho('tmp/'.$documento->getNome());
        return $documento;
    }
}
