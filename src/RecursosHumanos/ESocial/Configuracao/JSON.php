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
namespace ECidade\RecursosHumanos\ESocial\Configuracao;

/**
 * Class JSON
 * @package ECidade\RecursosHumanos\ESocial\Configuracao
 */
abstract class JSON
{

    /**
     * Caminho do arquivo de configuração
     * @var string
     */
    const CAMINHO_ARQUIVO_CONFIGURACAO = 'config/esocial/config.json';

    /**
     * StdClass representando o arquivo JSON
     * @var \stdClass
     */
    protected $arquivoConfiguracao;

    /**
     * Codigo do arquivo.
     * @var string
     */
    protected $codigoArquivo;

    /**
     * @return \stdClass
     */
    private function getArquivoConfiguracao()
    {
        $this->arquivoConfiguracao = \JSON::create()->parse(file_get_contents(self::CAMINHO_ARQUIVO_CONFIGURACAO));
        return $this->arquivoConfiguracao;
    }

    /**
     * @param \stdClass $data
     */
    protected function escrever(\stdClass $data)
    {

        $this->getArquivoConfiguracao();
        foreach ($this->arquivoConfiguracao as $indice => $stdArquivoJson) {

            if ($stdArquivoJson->arquivo === $this->codigoArquivo) {
                $this->arquivoConfiguracao[$indice] = $data;
                $this->arquivoConfiguracao[$indice]->arquivo = $this->codigoArquivo;
            }
        }
        file_put_contents(self::CAMINHO_ARQUIVO_CONFIGURACAO, \JSON::create()->stringify($this->arquivoConfiguracao));
    }


    /**
     * Retorna uma propriedade do objeto json referente ao arquivo desejado
     * @param $propriedade
     * @return bool|string|integer
     */
    public function getPropriedade($propriedade)
    {
        $arquivoJson = $this->getPropriedadeArquivo($this->codigoArquivo);
        if (empty($arquivoJson->{$propriedade})) {
            return false;
        }
        return $arquivoJson->{$propriedade};
    }

    /**
     * Retorna o objeto referente ao arquivo desejado
     * @param $arquivo
     * @return \stdClass|bool
     */
    protected function getPropriedadeArquivo($arquivo) {

        $this->getArquivoConfiguracao();
        foreach ($this->arquivoConfiguracao as $stdDadosArquivo) {

            if ($stdDadosArquivo->arquivo === $arquivo) {
                return $stdDadosArquivo;
            }
        }
        return false;
    }
}