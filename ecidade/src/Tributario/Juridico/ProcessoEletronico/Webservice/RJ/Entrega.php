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
namespace ECidade\Tributario\Juridico\ProcessoEletronico\Webservice\RJ;
use ECidade\Tributario\Juridico\ProcessoEletronico\Documento as DocumentoModel;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\Documento;
use ECidade\Tributario\Juridico\ProcessoEletronico\Webservice\RJ\TipoEntregarManifestacaoProcessual;
use ECidade\Tributario\Juridico\ProcessoEletronico\Webservice\Usuario;

/**
 * Manipulacao de XMLs no padrao do C.R.A
 */
class Entrega
{
    private $usuario;


    /**
     * Monta o xml de envio da remessa
     * @param  array $aDadosRemessa Dados a serem enviados na remesssa
     * @return mixed
     * @throws \DBException
     */

    public function entregarManifestacaoProcessual($aDadosRemessa = null)
    {

        $oOrigem = $aDadosRemessa;


        /**
         * Instancia os objetos do XSD
         */

        $oEntregar     = new TipoEntregarManifestacaoProcessual();
        $oDadosBasicos = new TipoCabecalhoProcesso();
        $oPessoa = new TipoPessoa();
        $oPessoaEndereco = new TipoEndereco();
        $oAssunto = new TipoAssuntoProcessual();
        $oAssuntoLocal = new TipoAssuntoLocal();
        $oDocumento = new TipoDocumento();


        /**
         * MANIFESTAÇÃO PROCESSUAL
         */

        $oEntregar->idManifestante = $this->getUsuario()->getUsuario();
        $oEntregar->senhaManifestante = $this->getUsuario()->getSenha();
        $oEntregar->numeroProcesso = '';

        /**
         * DADOS BÁSICOS > OUTRO PARAMETRO
         */

        $aOutroParametros = array();
        foreach ($oOrigem->certidoes as $certidao) {

            $aOutroParametros = array_merge($aOutroParametros, array(

            array(
                'nome' => 'DADOS_CDA',
                'valor' => $certidao->numero_certidao . '_' .
                    $certidao->ano_exercicio . '_' .
                    $certidao->moeda_divida . '_' .
                    $certidao->valor_divida.'_'.$certidao->ufir_divida

            ),
            array('nome' => 'NOME_DEVEDOR', 'valor'           => utf8_encode($oOrigem->nome_devedor)),
            array('nome' => 'NUMERO_INSCRICAO', 'valor'       => utf8_encode($oOrigem->numero_inscricao)),
            array('nome' => 'NATUREZA_DIVIDA', 'valor'        => utf8_encode($oOrigem->natureza_divida)),
            array('nome' => 'TIPO_LOGRADOURO', 'valor'        => utf8_encode($oOrigem->tipo_logradouro)),
            array('nome' => 'NOME_LOGRADOURO', 'valor'        => utf8_encode($oOrigem->nome_logradouro)),
            array('nome' => 'NUMERO_LOGRADOURO', 'valor'      => ($oOrigem->numero_logradouro)),
            array('nome' => 'COMPLEMENTO_LOGRADOURO', 'valor' => utf8_encode($oOrigem->complemento_logradouro)),
            array('nome' => 'BAIRRO_LOGRADOURO', 'valor'      => utf8_encode($oOrigem->bairro_logradouro)),
            array('nome' => 'CIDADE_LOGRADOURO', 'valor'      => utf8_encode($oOrigem->cidade_logradouro)),
            array('nome' => 'UF_LOGRADOURO', 'valor'          => utf8_encode($oOrigem->uf_logradouro)),
            array('nome' => 'CEP_LOGRADOURO', 'valor'         => utf8_encode($oOrigem->cep_logradouro)),
            array('nome' => 'BASE_LEGAL', 'valor'             => utf8_encode($certidao->base_legal))
            ));

        }

        /**
         * DADOS BASICOS > PESSOA
         */

        $oPessoa->outroNome = ''; // string
        $oPessoa->documento = '';
        $oPessoa->pessoaRelacionada = false;
        $oPessoa->pessoaVinculada = '';                                    // tipoPessoa ( precisa ???? )
        $oPessoa->tipoPessoa = utf8_encode($oOrigem->tipo_pessoa); // tipoQualificacaoPessoa
        $oPessoa->numeroDocumentoPrincipal = utf8_encode($oOrigem->cpf);                         // string
        $oPessoa->cidadeNatural = utf8_encode($oOrigem->cidade_natural); // string
        $oPessoa->nacionalidade = 'BR';  // string
        $oPessoa->estadoNatural = '';                                    // string
        $oPessoa->dataObito = '';                                    // string
        $oPessoa->sexo = utf8_encode($oOrigem->sexo);            // modalidadeGeneroPessoa
        $oPessoa->nome = utf8_encode($oOrigem->nome);           // string
        $oPessoa->nomeGenitor = utf8_encode($oOrigem->nome_genitor);   // string
        $oPessoa->dataNascimento = utf8_encode($oOrigem->data_nascimento);// string
        $oPessoa->nomeGenitora = utf8_encode($oOrigem->nome_genitora);  // string

        /**
         * DADOS BASICOS > PESSOA > ENDERECO
         */

        $oPessoaEndereco->logradouro = utf8_encode($oOrigem->logradouro);  // string
        $oPessoaEndereco->numero = utf8_encode($oOrigem->numero_end);  // string
        $oPessoaEndereco->complemento = utf8_encode($oOrigem->complemento); // string
        $oPessoaEndereco->bairro = utf8_encode($oOrigem->bairro);      // string
        $oPessoaEndereco->cidade = utf8_encode($oOrigem->munic);       // string
        $oPessoaEndereco->estado = utf8_encode($oOrigem->uf);          // string
        $oPessoaEndereco->pais = utf8_encode($oOrigem->pais);        // string
        $oPessoaEndereco->cep = utf8_encode($oOrigem->cep);         // string
        $oPessoa->endereco = $oPessoaEndereco;                              // tipoEndereco

        /**
         * DADOS BASICOS > POLO
         */

        // Objeto polo separado por AT (Polo Ativo) e PA (Polo Passivo)

        // AT (Polo Ativo)
        $oPoloAt = new TipoPoloProcessual();
        $oPoloAt->polo = 'AT';

        $oPoloParteAt = new TipoParte();

        $oPartePessoaAt = new TipoPessoa();
        $oPartePessoaAt->nome = utf8_encode($oOrigem->nome_at);
        $oPartePessoaAt->numeroDocumentoPrincipal = $oOrigem->cpf_at;
        $oPartePessoaAt->tipoPessoa = utf8_encode($oOrigem->tipo_pessoa_at);

        $oPessoaDocumentoAt = new TipoDocumentoIdentificacao();
        $oPessoaDocumentoAt->codigoDocumento = $oOrigem->cpf_at;
        $oPessoaDocumentoAt->emissorDocumento = 'SRFB';
        $oPessoaDocumentoAt->nome = utf8_encode($oOrigem->nome_at);
        $oPessoaDocumentoAt->tipoDocumento = '';

        $oPartePessoaAt->documento = $oPessoaDocumentoAt;

        $oPessoaEnderecoAt = new TipoEndereco();
        $oPessoaEnderecoAt->cep = $oOrigem->cep_at;
        $oPessoaEnderecoAt->logradouro = utf8_encode($oOrigem->logradouro_at);
        $oPessoaEnderecoAt->numero = $oOrigem->numero_end_at;
        $oPessoaEnderecoAt->bairro = utf8_encode($oOrigem->bairro_at);
        $oPessoaEnderecoAt->cidade = utf8_encode($oOrigem->munic_at);
        $oPessoaEnderecoAt->estado = utf8_encode($oOrigem->uf_at);
        $oPessoaEnderecoAt->pais = 'BR';

        $oPartePessoaAt->endereco = $oPessoaEnderecoAt;

        $oPoloParteAt->pessoa = $oPartePessoaAt;

        /*
        /**
         * @todo verificar o que fazer com os dados do advogado.
         */
        $oPessoaAdvogadoAt = new TipoRepresentanteProcessual();
        $oPessoaAdvogadoAt->intimacao = false;
        $oPessoaAdvogadoAt->nome = utf8_encode(mb_strtoupper($oOrigem->nome_advog));
        $oPessoaAdvogadoAt->numeroDocumentoPrincipal = $oOrigem->matricula_advogado;
        $oPessoaAdvogadoAt->tipoRepresentante = 'A';
        $oPessoaAdvogadoAt->inscricao =$oOrigem->oab_advog;

        $oAdvogadoEnderecoAt = new TipoEndereco();
        $oAdvogadoEnderecoAt->cep = $oOrigem->cep_advog;
        $oAdvogadoEnderecoAt->logradouro = utf8_encode($oOrigem->logradouro_advog);
        $oAdvogadoEnderecoAt->numero = $oOrigem->numero_advog;
        $oAdvogadoEnderecoAt->bairro = utf8_encode($oOrigem->bairro_advog);
        $oAdvogadoEnderecoAt->cidade = utf8_encode($oOrigem->cidade_advog);
        $oAdvogadoEnderecoAt->estado = $oOrigem->uf_advog;
        $oAdvogadoEnderecoAt->pais = 'BR';

        $oPessoaAdvogadoAt->endereco = $oAdvogadoEnderecoAt;

        $oPoloParteAt->advogado = $oPessoaAdvogadoAt;

        $oPoloAt->parte = $oPoloParteAt;

        // PA (Polo Passivo)
        $oPoloPa = new tipoPoloProcessual();
        $oPoloPa->polo = 'PA';

        $oPoloPartePa = new TipoParte();

        $oPartePessoaPa = new TipoPessoa();
        $oPartePessoaPa->nome = utf8_encode($oOrigem->nome);
        $oPartePessoaPa->sexo = $oOrigem->sexo;
        $oPartePessoaPa->numeroDocumentoPrincipal = $oOrigem->cpf;
        $oPartePessoaPa->tipoPessoa = utf8_encode($oOrigem->tipo_pessoa);

        $oPessoaEnderecoPa = new TipoEndereco();
        $oPessoaEnderecoPa->cep = $oOrigem->cep;
        $oPessoaEnderecoPa->logradouro = utf8_encode($oOrigem->logradouro);
        $oPessoaEnderecoPa->numero = $oOrigem->numero_end;
        $oPessoaEnderecoPa->bairro = utf8_encode($oOrigem->bairro);
        $oPessoaEnderecoPa->cidade = utf8_encode($oOrigem->munic);
        $oPessoaEnderecoPa->estado = utf8_encode($oOrigem->uf);
        $oPessoaEnderecoPa->pais = 'BR';

        $oPartePessoaPa->endereco = $oPessoaEnderecoPa;

        $oPoloPartePa->pessoa = $oPartePessoaPa;

        $oPoloPa->parte = $oPoloPartePa;

        $aPolo = array($oPoloAt, $oPoloPa);

        $oDadosBasicos->polo = $aPolo;

        /**
         * DADOS BASICOS > ASSUNTO
         */

        $oDadosBasicos->assunto = $oAssunto;                                        // tipoAssuntoProcessual
        $oAssunto->assuntoLocal = $oAssuntoLocal;                               // tipoAssuntoLocal
        $oAssuntoLocal->assuntoLocalPai = utf8_encode($oOrigem->assunto_local_pai);
        $oAssuntoLocal->descricao = utf8_encode($oOrigem->descricao);
        $oAssuntoLocal->codigoPaiNacional = utf8_encode($oOrigem->codigo_pai_nacional);
        $oAssuntoLocal->codigoAssunto = utf8_encode($oOrigem->codigo_assunto);

        $oAssunto->codigoNacional = utf8_encode($oOrigem->codigo_nacional);
        $oAssunto->principal = 'true'; //utf8_encode($oOrigem->principal);

        /*
         */

        $oDadosBasicos->magistradoAtuante = utf8_encode($oOrigem->magistrado_atuante);
        $oDadosBasicos->processoVinculado = '';
        $oDadosBasicos->prioridade = '';
        $oDadosBasicos->outroParametro = $aOutroParametros;
        $oDadosBasicos->orgaoJulgador = '';
        $oDadosBasicos->valorCausa = $oOrigem->valor_causa;
        $oDadosBasicos->outrosnumeros = '';                                       // string
        $oDadosBasicos->intervencaoMP = false;                                    //utf8_encode($oOrigem->intervencao_mp); // boolean
        $oDadosBasicos->nivelSigilo = "0";      // int
        $oDadosBasicos->dataAjuizamento = '';  // string
        $oDadosBasicos->tamanhoProcesso = null;  //utf8_encode($oOrigem->tamanho_processo); // int
        $oDadosBasicos->competencia = ($oOrigem->competencia);       // int
        $oDadosBasicos->numero = '00000000000000000000';                   //utf8_encode($oOrigem->numero); // string
        $oDadosBasicos->codigoLocalidade = ($oOrigem->codigo_localidade); // string
        $oDadosBasicos->classeProcessual = ($oOrigem->classe_processual); // int


        $oEntregar->dadosBasicos = $oDadosBasicos;  // tipoCabecalhoProcesso

        /**
         * MANIFESTACAO PROCESSUAL > DOCUMENTO
         */

        /**
         * Converte Documento em Binário e Busca o tamanho do Documento
         */
        $documentoCda = Documento::getInicialPorProcessoEletronico($oOrigem->codigo_processo_eletronico);

        $docBin    = base64_decode($documentoCda->getConteudo());
        $hash = hash('sha256', $docBin);
        $descricao = $documentoCda->getNome();
        $mimetype = 'application/pdf';

        $aOutroParametroDocumento = '';

        $documentos = Documento::getPorProcessoEletronico($oOrigem->codigo_processo_eletronico);
        foreach ($documentos as  $i => $documento) {

          if ($documento->getTipo() == \ECidade\Tributario\Juridico\ProcessoEletronico\Documento::INICIAL) {
              continue;
          }
          $aDocumentosVinculados[] = self::montaDocumento($documento);
        }

        $oDocumento->outroParametro = $aOutroParametroDocumento; // array
        $oDocumento->any = ''; // <anyXML> ????
        $oDocumento->documentoVinculado = $aDocumentosVinculados; // tipoDocumento ( idDocumentoVinculado ???? )
        $oDocumento->movimento = ''; // int
        $oDocumento->conteudo = $docBin;
        $oDocumento->nivelSigilo = 0; // int
        $oDocumento->hash = $hash; // string
        $oDocumento->tipoDocumentoLocal = 14; // string
        $oDocumento->idDocumentoVinculado = ''; // string
        $oDocumento->tipoDocumento = 58; // string
        $oDocumento->descricao = $descricao; // string
        $oDocumento->idDocumento = ''; // string
        $oDocumento->mimetype = $mimetype; // string
        $oDocumento->dataHora = date('YmdHis'); // string

        $oEntregar->documento = $oDocumento; // tipoDocumento
        $oEntregar->dataEnvio = $oOrigem->data_envio;

        $aParametros = array(array('nome' => 'MOTIVO_GRERJ_AUSENTE', 'valor' => '9'));

        $oEntregar->parametros = $aParametros; // array
        $oRetorno = $this->removeVazio($oEntregar);

        return $oRetorno;
    }

    public static function montaDocumento(DocumentoModel $documento)
    {
        $docBin    = base64_decode($documento->getConteudo());
        $hash      = hash('sha256', $docBin);
        $descricao = $documento->getNome();
        $mimetype  = 'application/pdf';

        $oDocumento = new \stdClass();

        $oDocumento->any = ''; // <anyXML> ????
        $oDocumento->movimento = ''; // int
        $oDocumento->conteudo = $docBin;
        $oDocumento->nivelSigilo = 0; // int
        $oDocumento->hash = $hash; // string
        $oDocumento->tipoDocumentoLocal = 14; // string
        $oDocumento->idDocumentoVinculado = ''; // string
        $oDocumento->tipoDocumento = 58; // string
        $oDocumento->descricao = $descricao; // string
        $oDocumento->idDocumento = ''; // string
        $oDocumento->mimetype = $mimetype; // string
        $oDocumento->dataHora = date('YmdHis'); // string
        return $oDocumento;

    }

    public function removeVazio($dados)
    {

        $return = $dados;
        foreach ($dados as $key => $value) {
            if (empty($value) || is_null($value)) {

                if (!is_int($value)) {
                    if (is_object($dados)) {

                        unset($return->$key);
                    } elseif (is_array($dados)) {

                        unset($return[$key]);
                    }

                    continue;
                }

            } else {
                if (is_object($value) || is_array($value)) {

                    if (is_object($dados)) {

                        $arr = (array)$this->removeVazio($value);
                        if (empty($arr)) {
                            unset($return->$key);
                            continue;
                        }

                        $return->$key = $this->removeVazio($value);

                    } elseif (is_array($dados)) {

                        $return[$key] = $this->removeVazio($value);
                    }
                } else {

                    if (is_object($dados)) {

                        $return->$key = $value;

                    } elseif (is_array($dados)) {

                        $return[$key] = $value;
                    }
                }
            }
        }

        return $return;
    }

    /**
     * Usuario para envio do processo
     * @param Usuario $usuario
     */
    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

}
