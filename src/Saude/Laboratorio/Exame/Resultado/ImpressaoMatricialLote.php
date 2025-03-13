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

namespace ECidade\Saude\Laboratorio\Exame\Resultado;

use DBDepartamento;
use DBException;
use ECidade\Lib\Impressao\Matricial\ImpressaoEpson;
use \DBString;
use Exception;
use MedicamentoLaboratorio;

final class ImpressaoMatricialLote extends ImpressaoEpson
{

    private $requisicao;

    /**
     * @var string
     */
    private $nomeArquivo;

    public function __construct()
    {
        parent::__construct();
        $this->buscarAutenticadoraConfigurada();
    }

    public function setRequisicao($requisicao)
    {
        $this->requisicao = $requisicao;
        return $this;
    }

    public function getRequisicao()
    {
        return $this->requisicao;
    }

    /**
     * @param $dados
     * @throws DBException
     * @throws Exception
     */
    public function gerarArquivo($dados)
    {
        $this->adicionaConteudo($this->arrCodigos[self::INICIALIZAR])
             ->adicionaConteudo($this->arrCodigos[self::HABILITAR_MODO_CONDENSADO]);

        foreach ($dados as $requisicao) {
            $requisicao->cabecalhos = array();
            $this->setRequisicao($requisicao);

            $this->setTamanhoLinha(100);
            $this->montarDadosCabecalhoPrefeitura();
            $this->montarDadosCabecalhoPaciente();
            $this->setTamanhoLinha(145);

            foreach ($this->requisicao->aSetor as $iSetor => $oSetor) {
                $this->setLinhas(0);
                $this->requisicao->iSetor = $iSetor;
                $this->requisicao->sSetor = str_pad($oSetor->sDescricao, 75);
                $this->requisicao->sSetor = DBString::removerAcentuacao($this->requisicao->sSetor);
                $this->requisicao->aExames = $oSetor->aExames;
                $this->imprimirCabecalho();
                $this->imprimirCorpo();
            }
        }

        $this->imprimir();
        $this->nomeArquivo = "tmp/resultado_matricial_lote_" . time() . ".txt";
    }

    /**
     * @throws DBException
     * @throws Exception
     */
    public function montarDadosCabecalhoPrefeitura()
    {
        $departamento = new DBDepartamento($_SESSION['DB_coddepto']);
        $prefeitura  = $departamento->getInstituicao()->getDadosPrefeitura();

        $endereco  = $prefeitura->getLogradouro();
        $endereco .= ", " . $prefeitura->getNumero();

        if ($prefeitura->getComplemento() != "") {
            $endereco .= ", " . $prefeitura->getComplemento();
        }

        $municipio  = $prefeitura->getMunicipio() . " - " . $prefeitura->getUf();

        $telefone = $prefeitura->getTelefone();
        if ($prefeitura->getCNPJ() != "") {
            $telefone .= " - CNPJ: " . $prefeitura->getCNPJ();
        }

        $this->requisicao->cabecalhos[0] = new \stdClass();
        $subCabecalhoPrefeitura = 45;
        $padCabecalhoPrefeitura = 46;

        $this->requisicao->cabecalhos[0]->departamento = str_pad(
            substr($departamento->getNomeDepartamento(), 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );
        $this->requisicao->cabecalhos[0]->prefeitura = str_pad(
            substr($prefeitura->getDescricao(), 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );
        $this->requisicao->cabecalhos[0]->logradouro = str_pad(
            substr($endereco, 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );
        $this->requisicao->cabecalhos[0]->municipio = str_pad(
            substr($municipio, 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );
        $this->requisicao->cabecalhos[0]->telefone = str_pad(
            substr($telefone, 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );
        $this->requisicao->cabecalhos[0]->email = str_pad(
            substr($prefeitura->getEmail(), 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );
        $this->requisicao->cabecalhos[0]->site = str_pad(
            substr($prefeitura->getSite(), 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );

        $this->requisicao->cabecalhos[0]->departamento = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[0]->departamento
        );
        $this->requisicao->cabecalhos[0]->prefeitura = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[0]->prefeitura
        );
        $this->requisicao->cabecalhos[0]->logradouro = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[0]->logradouro
        );
        $this->requisicao->cabecalhos[0]->municipio = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[0]->municipio
        );
        $this->requisicao->cabecalhos[0]->telefone = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[0]->telefone
        );
        $this->requisicao->cabecalhos[0]->email = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[0]->email
        );
        $this->requisicao->cabecalhos[0]->site = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[0]->site
        );
    }

    public function montarDadosCabecalhoPaciente()
    {
        $this->requisicao->cabecalhos[1] = new \stdClass();
        $quantidadeCaracteres = $this->tamanhoLinha - 49;

        $this->requisicao->cabecalhos[1]->requisicao =  str_pad(
            "Requisicao: {$this->requisicao->iRequisicao}",
            $quantidadeCaracteres
        );

        $this->requisicao->cabecalhos[1]->nome =  str_pad(
            substr(
                "Paciente: {$this->requisicao->oSolicitante->sNome}",
                0,
                $quantidadeCaracteres
            ),
            $quantidadeCaracteres
        );

        $this->requisicao->cabecalhos[1]->idade =  str_pad(
            "Idade: {$this->requisicao->oSolicitante->iIdade}",
            $quantidadeCaracteres
        );

        if ($this->requisicao->oSolicitante->sSexo == 'F') {
            $this->requisicao->oSolicitante->sSexo = 'FEMININO';
        } else {
            $this->requisicao->oSolicitante->sSexo = 'MASCULINO';
        }

        $this->requisicao->cabecalhos[1]->sexo =  str_pad(
            "Sexo: {$this->requisicao->oSolicitante->sSexo}",
            $quantidadeCaracteres
        );

        $this->requisicao->cabecalhos[1]->medico =  str_pad(
            substr(
                "Medico: {$this->requisicao->oSolicitante->sMedico}",
                0,
                $quantidadeCaracteres
            ),
            $quantidadeCaracteres
        );

        $this->requisicao->cabecalhos[1]->convenio =  str_pad("Convenio: SUS", $quantidadeCaracteres);

        $this->requisicao->cabecalhos[1]->requisicao = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[1]->requisicao
        );
        $this->requisicao->cabecalhos[1]->nome = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[1]->nome
        );
        $this->requisicao->cabecalhos[1]->idade = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[1]->idade
        );
        $this->requisicao->cabecalhos[1]->sexo = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[1]->sexo
        );
        $this->requisicao->cabecalhos[1]->medico = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[1]->medico
        );
        $this->requisicao->cabecalhos[1]->convenio = DBString::removerAcentuacao(
            $this->requisicao->cabecalhos[1]->convenio
        );
    }

    public function getCabecalho()
    {
        $conteudo  = "  {$this->requisicao->cabecalhos[0]->departamento} |     DADOS PACIENTE \r\n";
        $conteudo .= "  {$this->requisicao->cabecalhos[0]->prefeitura} |";
        $conteudo .= "     {$this->requisicao->cabecalhos[1]->requisicao} \r\n";
        $conteudo .= "  {$this->requisicao->cabecalhos[0]->logradouro} |";
        $conteudo .= "     {$this->requisicao->cabecalhos[1]->nome} \r\n";
        $conteudo .= "  {$this->requisicao->cabecalhos[0]->municipio} |";
        $conteudo .= "     {$this->requisicao->cabecalhos[1]->idade} \r\n";
        $conteudo .= "  {$this->requisicao->cabecalhos[0]->telefone} |";
        $conteudo .= "     {$this->requisicao->cabecalhos[1]->sexo} \r\n";
        $conteudo .= "  {$this->requisicao->cabecalhos[0]->email} |";
        $conteudo .= "     {$this->requisicao->cabecalhos[1]->medico} \r\n";
        $conteudo .= "  {$this->requisicao->cabecalhos[0]->site} |";
        $conteudo .= "     {$this->requisicao->cabecalhos[1]->convenio} \r\n";
        $conteudo .= "  {$this->arrCodigos[self::SEPARADOR]} \r\n ";
        $conteudo .= " {$this->arrCodigos[self::HABILITAR_NEGRITO]}";
        $conteudo .= "  {$this->requisicao->sSetor} Valor de Referencia \r\n";
        $conteudo .= " {$this->arrCodigos[self::DESABILITAR_NEGRITO]}   ";
        $conteudo .= str_repeat("-", 107);
        $this->adicionaLinhas(10);

        return $conteudo;
    }

    public function imprimirCorpo()
    {
        $aPosicaoAtributos    = array();
        $aPosicaoAtributos[1] = str_repeat(' ', 2);
        $aPosicaoAtributos[2] = str_repeat(' ', 4);
        $aPosicaoAtributos[3] = str_repeat(' ', 8);
        $aPosicaoAtributos[4] = str_repeat(' ', 10);
        $aPosicaoAtributos[5] = str_repeat(' ', 12);

        foreach ($this->requisicao->aExames as $oDadosExame) {
            foreach ($oDadosExame->aAtributos as $oAtributos) {
                $this->setTamanhoLinha(100);
                $this->adicionaConteudo($this->arrCodigos[self::FONT_15_CPI]);
                $this->adicionaConteudo($this->arrCodigos[self::HABILITAR_NEGRITO]);

                if ($oAtributos->tipo != 1) {
                    $this->setTamanhoLinha(145);
                    $this->adicionaConteudo($this->arrCodigos[self::FONT_12_CPI]);
                    $this->adicionaConteudo($this->arrCodigos[self::DESABILITAR_NEGRITO]);
                    $oAtributos->nome .= ': ';
                }

                if (!empty($oAtributos->valorpercentual)) {
                    $oAtributos->valorpercentual .= " %";
                }

                $espacamento = $aPosicaoAtributos[$oAtributos->nivel];
                $nome = $espacamento . $oAtributos->nome;
                $nome = str_pad($nome, 50);

                $oAtributos->valorpercentual = str_pad($oAtributos->valorpercentual, 20);
                $oAtributos->valorabsoluto = str_pad($oAtributos->valorabsoluto, 28);
                $oAtributos->referencia = str_pad($oAtributos->referencia, 50);

                $nome = DBString::removerAcentuacao($nome);
                $oAtributos->valorpercentual = DBString::removerAcentuacao($oAtributos->valorpercentual);
                $oAtributos->valorabsoluto = DBString::removerAcentuacao($oAtributos->valorabsoluto);
                $oAtributos->referencia = DBString::removerAcentuacao($oAtributos->referencia);
                $oAtributos->referencia = str_replace("²", "2", $oAtributos->referencia);
                $oAtributos->referencia = str_replace("³", "3", $oAtributos->referencia);

                $texto  = "  {$nome}";
                if ($oAtributos->tipo != 1) {
                    $texto .= "  {$oAtributos->valorpercentual}  {$oAtributos->valorabsoluto}";
                    $texto .= "  {$oAtributos->referencia}";
                }

                $this->validaQuebraPagina($texto)
                    ->adicionaConteudo($texto)
                    ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA]);
            }

            $this->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA]);
            $this->adicionaLinhas(1);

            $this->imprimirMedicamentosExame($oDadosExame->aMedicamentosExame);
            $this->imprimirObservacaoResultado($oDadosExame->sObservacao);
            $this->imprimirMaterialColeta($oDadosExame->aDadosMaterialColeta);
            $this->imprimirObservacaoExame($oDadosExame->sObservacaoExame);

            if (isset($oDadosExame->mensagemLiberacao) && $oDadosExame->mensagemLiberacao != '') {
                $this->imprimirMensagemLiberacao($oDadosExame->mensagemLiberacao);
            }
        }

        $this->quebrarPagina(false)
             ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA]);
    }

    /**
     * @param MedicamentoLaboratorio[] $medicamentosExame
     */
    public function imprimirMedicamentosExame($medicamentosExame)
    {
        if (!empty($medicamentosExame)) {
            $nomesMedicamentos = array();

            foreach ($medicamentosExame as $oMedicamento) {
                $medicamento = DBString::removerAcentuacao($oMedicamento->getNome());
                $nomesMedicamentos[] = $medicamento;
            }

            $medicamentos = implode(', ', $nomesMedicamentos);

            $medicamentos = wordwrap(
                $medicamentos,
                $this->tamanhoLinha,
                $this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA] . "        "
            );

            $texto  = "    Medicamentos:";
            $texto .= $this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA];
            $texto .= "    {$medicamentos} ";
            $texto .= $this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA];
            $texto .= $this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA];

            $this->validaQuebraPagina($texto)
                 ->adicionaConteudo($texto);
        }
    }

    /**
     * @param string $observacao
     */
    public function imprimirObservacaoResultado($observacao)
    {
        if (!empty($observacao)) {
            $observacao = $this->tratarTexto($observacao);

            $this->adicionaConteudo("    Observacoes do Resultado:")
                 ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA])
                 ->adicionaConteudo("    {$observacao} ")
                 ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA])
                 ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA]);
        }
    }

    /**
     * @param string $observacao
     */
    public function imprimirObservacaoExame($observacao)
    {
        if (!empty($observacao)) {
            $observacao = $this->tratarTexto($observacao);

            $this->adicionaConteudo("    Observacoes do Exame:")
                 ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA])
                 ->adicionaConteudo("    {$observacao} ")
                 ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA])
                 ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA]);
        }
    }

    /**
     * @param string $mensagem
     */
    public function imprimirMensagemLiberacao($mensagem)
    {
        if (!empty($mensagem)) {
            $mensagem = $this->tratarTexto($mensagem);

            $this->adicionaConteudo("    {$mensagem} ")
                 ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA]);
        }
    }

    /**
     * @param $materiaisColeta
     */
    public function imprimirMaterialColeta($materiaisColeta)
    {
        foreach ($materiaisColeta as $materialColeta) {
            $material = str_pad("{$materialColeta->material_coleta}", 50);
            $material = DBString::removerAcentuacao($material);
            $metodo = DBString::removerAcentuacao($materialColeta->metodo_coleta);

            $texto  = "    Material: {$material}  Metodo: {$metodo} ";
            $texto .= $this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA];

            $this->validaQuebraPagina($texto)
                 ->adicionaConteudo($texto);
        }

        $this->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA])
        ->adicionaLinhas(1);
    }

    /**
     * @return string
     */
    public function getNomeArquivo()
    {
        return $this->nomeArquivo;
    }

    /**
     * @return string
     */
    private function tratarTexto($texto)
    {
        $this->validaQuebraPagina($texto);

        $texto = wordwrap(
            $texto,
            $this->tamanhoLinha,
            $this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA]
        );

        $texto = str_replace("\n", "\n    ", $texto);
        $texto = DBString::removerAcentuacao($texto);

        return $texto;
    }
}
