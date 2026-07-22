<?php
/**
 *  E-cidade Software Publico para Gestao Municipal
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

namespace ECidade\Tributario\Integracao\Civitas\Arquivo;

/**
 * Classe responsável pela manipulação do arquivo de exportação do civitas
 * @author Alysson Zanette <alysson.zanette@dbseller.com.br>
 */
class Exportacao
{
    const DIR = 'tmp/';

    /**
     * @var \SplFileObject
     */
    private $oArquivo;

    /**
     * @var array
     */
    private $aDados;

    /**
     * @var string
     */
    private $sNomeArquivo;

    /**
     * @var
     */
    private $oHeader;

    /**
     * @param array $aDadosExportacao
     * @throws \ParameterException
     */
    public function __construct($aDadosExportacao)
    {
        if (empty($aDadosExportacao)) {
            throw new \ParameterException("Dados para exportacao não infromados.");
        }

        $this->sNomeArquivo = self::DIR . 'ArquivoExportacaoCivitas-' . date('Y-m-d') . '-' . time() . '.csv';
        $this->aDados       = $this->organizarDados($aDadosExportacao);
        $this->oArquivo = fopen($this->sNomeArquivo, 'w');
        $this->gerarArquivoCSV();

        fclose($this->oArquivo);
    }

    /**
     * Retorna o conteúdo do arquivo gerado.
     * @return string
     */
    public function getArquivo()
    {
        return file_get_contents($this->sNomeArquivo);
    }

    public function getFilePath()
    {
        return $this->sNomeArquivo;
    }

    /**
     * Retorna o conteúdo do arquivo gerado com base64.
     * @return string
     */
    public function getArquivoBase64()
    {
        return base64_encode($this->getArquivo());
    }

    /**
     * Gera o contéúdo do arquivo csv.
     * @return void
     */
    private function gerarArquivoCSV()
    {
        //Adiciona header no arquivo
        $this->adicionarLinhaArquivo(Exportacao::getHeader());

        //Adiciona corpo do arquivo
        foreach ($this->aDados as $aLinha) {
            $this->adicionarLinhaArquivo($aLinha);
        }
    }

    /**
     * Adiciona uma linha no arquivo
     * @param array $aLinha
     */
    private function adicionarLinhaArquivo($aLinha)
    {
        fputs($this->oArquivo, implode('|', (array) $aLinha)."\n");
    }

    /**
     * Organiza os dados de uma matriz para as colunas ficarem na mesma posição do cabeçalho do arquivo.
     * @param array $aDados
     */
    private function organizarDados($aDados)
    {
        $header = Exportacao::getHeader();
        $aDadosOrganizados = array();
        foreach ($aDados as $aDadosIndex => $aLinha) {
            foreach ($header as $headerIndex => $valorHeader) {

                if (isset($aLinha[$valorHeader])) {
                    $aDadosOrganizados[$aDadosIndex][$valorHeader] = $aLinha[$valorHeader];
                } else {
                    $aDadosOrganizados[$aDadosIndex][$valorHeader] = ''; //cria a coluna vazia se não haver
                }
            }
        }

        return $aDadosOrganizados;
    }

    /**
     * Retorna o cabeçalho do arquivo.
     * @return array
     */
    public static function getHeader()
    {
        $aHeader = array(
            "matricula",
            "referencia_anterior",
            "codigo_setor",
            "codigo_quadra",
            "codigo_lote",
            "idbql",
            "proprietario",
            "promitente",
            "rua_tipo_sigla_testada",
            "testadas_lote",
            "globalid",
            "rua_codigo",
            "rua_nome",
            "construcao_numero",
            "construcao_complemento",
            "bairro_codigo",
            "bairro_descricao",
            "rua_cep",
            "lote_codigo_loteamento",
            "lote_descricao_loteamento",
            "lote_area",
            "codigo_construcao",
            "construcao_area",
            "ano_construcao",
            "valor_testada_lote",
            "num_habite",
            "data_habite",
            "data_demolicao",
            "valor_venal_terreno",
            "valor_venal_construcao",
            "valor_iptu_terreno",
            "valor_iptu_construcao",
            "isencao_codigo",
            "isencao_descricao",
            "endereco_entrega",
            "endereco_entrega_numero",
            "endereco_entrega_complemento",
            "endereco_entrega_bairro",
            "endereco_entrega_municipio",
            "endereco_entrega_uf",
            "endereco_entrega_cep",
            "endereco_entrega_caixapostal"

        );

        for ($i=1; $i < 10; $i++) {
            $aHeader[] = "lote_tipo_{$i}";
            $aHeader[] = "lote_id_grupo_{$i}";
            $aHeader[] = "lote_grupo_{$i}";
            $aHeader[] = "lote_id_{$i}";
            $aHeader[] = "lote_descricao_{$i}";
            $aHeader[] = "lote_pontos_{$i}";
        }

        for ($i=1; $i < 21; $i++) {
            $aHeader[] = "construcao_tipo_{$i}";
            $aHeader[] = "construcao_id_grupo_{$i}";
            $aHeader[] = "construcao_grupo_{$i}";
            $aHeader[] = "construcao_id_{$i}";
            $aHeader[] = "construcao_descricao_{$i}";
            $aHeader[] = "construcao_pontos_{$i}";
        }

        $aHeader[] = "situacao_iptu";
        $aHeader[] = "incide_taxa";
        $aHeader[] = "valor_taxa";
        $aHeader[] = "valor_isencao_taxa";
        $aHeader[] = "atividades_economicas";
        $aHeader[] = "profundidade_padrao";
        $aHeader[] = "area_lote_vila";
        $aHeader[] = "area_da_vila";
        $aHeader[] = "tipo_imovel_2";
        $aHeader[] = "vlr_metro_linear_testada_v0";
        $aHeader[] = "valor_venal_forcado";
        $aHeader[] = "unifit";
        $aHeader[] = "aliquota";
        $aHeader[] = "pontos";

        return $aHeader;
    }
}
