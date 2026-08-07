<?php

namespace ECidade\RecursosHumanos\RH\ConcessaoDireitos\Controllers;

use ECidade\Lib\Request\Storage\Curl\Autenticacao;
use ECidade\Lib\Request\Storage\Curl\Post;
use ECidade\Lib\Request\Storage\File as FilePostStorage;
use App\Domain\Configuracao\Helpers\StorageHelper;
use BusinessException;
use cl_arquivoestorage;
use cl_arquivoestorageassinaturas;
use cl_assinaturasdocumento;
use cl_documentoportaria;
use cl_portaria;
use cl_portariaassentasituacao;
use db_utils;
use DBException;
use Exception;
use JSON;
use ServidorRepository;
use stdClass;

class Processassinaturadigital
{
    public $retorno;
    public function __construct()
    {
        $this->retorno = new stdClass();
        require_once(modification("classes/db_db_relatorio_classe.php"));
        require_once(modification("classes/db_db_geradorrelatoriotemplate_classe.php"));
    }

    public function gerarArquivo($parametros, $signers = null)
    {
        
            /**
         * @todo refatorar isto, fazer requisicao corretamente para isto
         */
        ob_start();
        $_POST = $parametros;
        require 'sys4_processarelatorioRPC.php';
        $processamentoRelatorio = ob_get_clean();
        $_POST = null;

        $processamentoRelatorio = JSON::create()->parse(urldecode($processamentoRelatorio));

        if ($processamentoRelatorio->erro) {
            throw new BusinessException($processamentoRelatorio->sMsg);
        }

        db_inicio_transacao();

        $path     = $processamentoRelatorio->sMsg;

        $parametros->aParametros = JSON::create()->parse(urldecode($parametros->aParametros));
        $parametrosPortaria = current($parametros->aParametros);
        $nroPortaria = $parametrosPortaria->sValor;
        $parametrosPortaria = next($parametros->aParametros);
        $nroPortaria .= '/' . $parametrosPortaria->sValor;
        $idEstorage = null;

        try {
            $response = $this->salvarDocumentoEstorage(
                $path,
                $nroPortaria,
                $parametros->iCodigoPortaria,
                null,
                $signers,
                $idEstorage
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        db_fim_transacao();

        $configStorage = StorageHelper::getStorageConfig();

        $this->retorno->url        = $configStorage->url;
        $this->retorno->url       .= '/' . $response->data->url;
        $this->retorno->path       = preg_replace('/.*?(tmp\/.*...*)$/', "$1", $path);

        return $this->retorno;
    }
    private function salvarDocumentoEstorage(
        $path,
        $nroPortaria,
        $iCodigoPortaria,
        $assinante = null,
        $signers = null,
        $idEstorage = null,
        $father_id = null
    ) {
        $file = new FilePostStorage();
        $file->realPath($path);
        $file->clientOriginalName(preg_replace('/.*\/(.*?\..*)$/', "$1", $path));
        $file->visibility('public');



        $arrayPortaria = explode('/', $nroPortaria);

        $clPortaria = new cl_portaria;
        $rsAssentamento = pg_fetch_assoc(db_query($clPortaria->sql_query_assentamento_servidor($iCodigoPortaria)));

        $metadata = new stdClass();

        $metadata->tipo_documento = 'portaria';
        $metadata->numero = "{$arrayPortaria[0]}/{$arrayPortaria[1]}";
        $metadata->matricula_servidor = $rsAssentamento['h16_regist'];
        $metadata->id_assentamento = $rsAssentamento['h16_codigo'];

        $file->metadata($metadata);

        if (!empty($signers)) {
            $file->signersRequired(true);
            $file->signers($signers);
        }

        if ($father_id != null) {
            $file->fileFather($father_id);
        }

        $post = new Post(Autenticacao::getInstance());
        try {
            $response = $post->execute($file);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $arquivoestorage = new cl_arquivoestorage();
        $arquivoestorage->db177_idestorage                 = $response->data->id;
        $arquivoestorage->db177_descricao                  = "Portaria número: {$nroPortaria}";
        $arquivoestorage->db177_datadocumento              = date('Y-m-d');
        $arquivoestorage->db177_url                        = $response->data->url;
        $arquivoestorage->db177_idestorage_arquivoanterior = null;

        if (!$arquivoestorage->incluir()) {
            throw new DBException($arquivoestorage->erro_msg);
        }

        $documentoportaria = new cl_documentoportaria();
        $documentoportaria->rh235_portaria   = $iCodigoPortaria;
        $documentoportaria->rh235_documento  = $response->data->id;

        if (!$documentoportaria->incluir()) {
            throw new DBException($documentoportaria->erro_msg);
        }

        if (!empty($assinante)) {
            $nomeAssinante = preg_replace('/^(.*?):.*/', "$1", $assinante);
            $cpfAssinante  = preg_replace(
                array(
                    '/.*?:(.*)$/',
                    '/\D/'
                ),
                array(
                    "$1",
                    ""
                ),
                $assinante
            );

            $assinaturasdocumento = new cl_assinaturasdocumento();

            $aWhereAssinantes = array(
                "db178_cpf = '{$cpfAssinante}'"
            );
            $whereAssinantes  = implode(" AND ", $aWhereAssinantes);
            $sqlAssinantes    = $assinaturasdocumento->sql_query_file(null, "db178_sequencial", null, $whereAssinantes);
            $rsAssinantes     = db_query($sqlAssinantes);

            if (!$rsAssinantes) {
                throw new DBException("Ocorreu um erro ao consultar assinantes.\n" . pg_last_error());
            }

            $idAssinante = db_utils::fieldsMemory($rsAssinantes, 0)->db178_sequencial;

            if (empty($idAssinante)) {
                $assinaturasdocumento->db178_nome      = $nomeAssinante;
                $assinaturasdocumento->db178_cpf       = $cpfAssinante;
                $assinaturasdocumento->db178_imagem    = null;
                $assinaturasdocumento->db178_metadados = 'null';

                if (!empty($cpfAssinante)) {
                    $servidor = ServidorRepository::getByCPF($cpfAssinante);

                    if (!empty($servidor)) {
                        $metadadosAssinante = (object)array(
                            'matricula' => $servidor->getMatricula(),
                            'cgm'       => $servidor->getCgm()->getCodigo()
                        );
                        $assinaturasdocumento->db178_metadados = JSON::create()->stringify($metadadosAssinante);
                    }
                }

                if (!$assinaturasdocumento->incluir()) {
                    throw new DBException($assinaturasdocumento->erro_msg);
                }

                $idAssinante = $assinaturasdocumento->db178_sequencial;
            }

            $arquivoestorageassinaturas = new cl_arquivoestorageassinaturas();

            $arquivoestorageassinaturas->db179_arquivo         = $response->data->id;
            $arquivoestorageassinaturas->db179_assinatura      = $idAssinante;
            $arquivoestorageassinaturas->db179_dataassinatura  = date('Y-m-d');

            if (!$arquivoestorageassinaturas->incluir()) {
                throw new DBException($arquivoestorageassinaturas->erro_msg);
            }

            $assinaturasarquivo = new cl_arquivoestorageassinaturas();
            $aWhereAssinaturasarquivo = array("h31_sequencial = {$iCodigoPortaria}");
            $sqlAssinaturasarquivo    = $assinaturasarquivo->sql_query_limite_assinaturas(
                null,
                array(
                    "portaria.h31_sequencial",
                    "portaria.h31_numero",
                    "portaria.h31_anousu",
                    "count(distinct arquivoestorageassinaturas.db179_assinatura) as totalAssinaturas",
                    "count(distinct assinaturadocumentodesignacao.db59_usuario) as limiteAssinaturasDocumento"
                ),
                null,
                $aWhereAssinaturasarquivo
            );
            $rsAssinaturasarquivo     = db_query($sqlAssinaturasarquivo);

            if (!$rsAssinaturasarquivo) {
                throw new DBException("Ocorreu um erro ao consultar quem assina os documentos.\n" . pg_last_error());
            }

            if (pg_num_rows($rsAssinaturasarquivo) > 0) {
                $assinaturasdocumentoportaria = db_utils::fieldsMemory($rsAssinaturasarquivo, 0);

                $totalAssinaturas           = $assinaturasdocumentoportaria->totalassinaturas;
                $limiteAssinaturasDocumento = $assinaturasdocumentoportaria->limiteassinaturasdocumento;

                if ($totalAssinaturas == $limiteAssinaturasDocumento) {
                    $portariaassentasituacao = new cl_portariaassentasituacao();

                    $portariaassentasituacao->rh236_portariaassenta = "(SELECT h33_sequencial
                                                                      FROM portariaassenta 
                                                                     WHERE h33_portaria = {$iCodigoPortaria})";
                    $portariaassentasituacao->rh236_situacao        = 'S';
                    $portariaassentasituacao->rh236_momento         = time();

                    if (!$portariaassentasituacao->incluir()) {
                        throw new DBException($portariaassentasituacao->erro_msg);
                    }
                }
            }
        }

        return $response;
    }
}
