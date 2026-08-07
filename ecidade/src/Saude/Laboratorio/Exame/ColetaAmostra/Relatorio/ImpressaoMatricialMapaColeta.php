<?php

namespace ECidade\Saude\Laboratorio\Exame\ColetaAmostra\Relatorio;

use DBString;
use ECidade\Lib\Impressao\Matricial\ImpressaoEpson;
use Exception;

class ImpressaoMatricialMapaColeta extends ImpressaoEpson
{

    private $dados = array();

    const TAMANHO_REQUISICAO = 20;
    const TAMANHO_DATA = 17;
    const TAMANHO_NOME = 62;
    const TAMANHO_EXAMES = 40;

    public function __construct()
    {
        parent::__construct();
        $this->buscarAutenticadoraConfigurada();
    }

    private function tratarDadosDepartamento()
    {
        $subCabecalhoPrefeitura = 54;
        $padCabecalhoPrefeitura = 55;

        $this->dados->cabecalho->laboratorio = str_pad(
            substr($this->dados->cabecalho->laboratorio, 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );

        $this->dados->cabecalho->enderecoLaboratorio = str_pad(
            substr($this->dados->cabecalho->enderecoLaboratorio, 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );

        $this->dados->cabecalho->municipioUf = str_pad(
            substr(
                $this->dados->cabecalho->municipioDepartamento . ' - ' .$this->dados->cabecalho->ufDepartamento,
                0,
                $subCabecalhoPrefeitura
            ),
            $padCabecalhoPrefeitura
        );

        $telefone = db_formatar($this->dados->cabecalho->telefoneLaboratorio, 'telefone');
        $cnpj = db_formatar($this->dados->cabecalho->cnpjDepartamento, 'cnpj');
        $this->dados->cabecalho->telefoneCnpj = str_pad(
            substr($telefone . ' - CNPJ: ' . $cnpj, 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );

        $this->dados->cabecalho->emailDepartamento = str_pad(
            substr($this->dados->cabecalho->emailDepartamento, 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );

        $this->dados->cabecalho->siteDepartamento = str_pad(
            substr($this->dados->cabecalho->siteDepartamento, 0, $subCabecalhoPrefeitura),
            $padCabecalhoPrefeitura
        );

        $this->dados->cabecalho->laboratorio = DBString::removerAcentuacao(
            $this->dados->cabecalho->laboratorio
        );

        $this->dados->cabecalho->enderecoLaboratorio = DBString::removerAcentuacao(
            $this->dados->cabecalho->enderecoLaboratorio
        );

        $this->dados->cabecalho->emailDepartamento = DBString::removerAcentuacao(
            $this->dados->cabecalho->emailDepartamento
        );

        $this->dados->cabecalho->siteDepartamento = DBString::removerAcentuacao(
            $this->dados->cabecalho->siteDepartamento
        );

        $this->dados->cabecalho->nomeLaboratorio = DBString::removerAcentuacao(
            $this->dados->cabecalho->nomeLaboratorio
        );
    }

    /**
     * @inheritDoc
     */
    public function getCabecalho()
    {
        $setor = $this->dados->cabecalho->nomeSetor ? $this->dados->cabecalho->nomeSetor : 'TODOS';

        $this->tratarDadosDepartamento();
        $conteudo  = $this->arrCodigos[self::FONT_12_CPI];
        $conteudo .= "  {$this->dados->cabecalho->laboratorio} |     MAPA DE COLETA \r\n";
        $conteudo .= "  {$this->dados->cabecalho->enderecoLaboratorio} | \r\n";
        $conteudo .= "  {$this->dados->cabecalho->municipioUf} |";
        $conteudo .= " Periodo Inicial: {$this->dados->cabecalho->periodoInicial}\r\n";
        $conteudo .= "  {$this->dados->cabecalho->telefoneCnpj} |";
        $conteudo .= " Periodo Final: {$this->dados->cabecalho->periodoFinal}\r\n";
        $conteudo .= "  {$this->dados->cabecalho->emailDepartamento} |";
        $conteudo .= " Laboratorio: {$this->dados->cabecalho->laboratorio}\r\n";
        $conteudo .= "  {$this->dados->cabecalho->siteDepartamento} |";
        $conteudo .= " Setor: {$setor}\r\n";

        $conteudo .= str_repeat('-', $this->tamanhoLinha);
        $conteudo .= $this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA];
        $conteudo .= $this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA];
        $this->adicionaLinhas(8);

        $cabecalho = array(
            str_pad("REQUISICAO", self::TAMANHO_REQUISICAO),
            str_pad("NOME", self::TAMANHO_NOME),
            str_pad("DATA REQUISICAO", self::TAMANHO_DATA),
            str_pad("EXAMES", self::TAMANHO_EXAMES)
        );

        $conteudo .= $this->arrCodigos[self::HABILITAR_NEGRITO];
        $conteudo .= implode($cabecalho, '| ');
        $conteudo .= $this->arrCodigos[self::DESABILITAR_NEGRITO];
        $conteudo .= $this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA];
        $conteudo .= str_repeat('-', $this->tamanhoLinha);
        $this->adicionaLinhas(1);

        return $conteudo;
    }

    /**
     * @param $dados
     * @throws Exception
     */
    public function gerarArquivo($dados)
    {
        $this->dados = $dados;
        $this->adicionaConteudo($this->arrCodigos[self::INICIALIZAR])
            ->adicionaConteudo($this->arrCodigos[self::HABILITAR_MODO_CONDENSADO])
            ->adicionaConteudo($this->arrCodigos[self::FONT_12_CPI])
            ->setTamanhoPagina(55)
            ->setTamanhoLinha(145);

        $this->imprimirCabecalho();
        $this->imprimirCorpo();

        $this->imprimir();
        file_put_contents("tmp/testeRelatorio.txt", $this->getConteudo());
    }

    private function imprimirCorpo()
    {
        foreach ($this->dados->coletas as $coleta) {
            $exames = "";

            for ($i = 0; $i < count($coleta->exames); $i++) {
                $exames .= $coleta->exames[$i];
                $posicaoInicial = $i + 1;

                if ($posicaoInicial != count($coleta->exames)) {
                    $exames .= "-";
                }

                if ($posicaoInicial % 10 === 0 && $posicaoInicial < count($coleta->exames)) {
                    $exames .= $this->arrCodigos[self::QUEBRA_LINHA];
                    $exames .= str_pad(" ", self::TAMANHO_REQUISICAO) . "| ";
                    $exames .= str_pad(" ", self::TAMANHO_NOME) . "| ";
                    $exames .= str_pad(" ", self::TAMANHO_DATA) . "| ";
//                    $this->adicionaLinhas(1);
                }
            }
            $exames .= $this->arrCodigos[self::QUEBRA_LINHA];

            $corpo = array(
                str_pad($coleta->requisicao, self::TAMANHO_REQUISICAO),
                str_pad($coleta->nome, self::TAMANHO_NOME),
                str_pad($coleta->dataRequisicao, self::TAMANHO_DATA),
                $exames
            );
            $this->adicionaLinhas(1);

            $conteudo = implode($corpo, '| ');
            $this->validaQuebraPagina($conteudo)
                ->adicionaConteudo($conteudo)
                ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA]);
        }

        $this->quebrarPagina(false);
    }
}
