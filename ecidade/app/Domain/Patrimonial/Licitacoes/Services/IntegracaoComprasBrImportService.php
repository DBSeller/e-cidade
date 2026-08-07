<?php

namespace App\Domain\Patrimonial\Licitacoes\Services;

use CgmFactory;
use cl_pcorcam;
use cl_pcorcamforne;
use cl_pcorcamitem;
use cl_pcorcamitemlic;
use cl_pcorcamjulg;
use cl_pcorcamval;
use ECidade\File\Csv\LerCsv;
use ItemLicitacao;
use LicitacaoRepository;
use OrcamentoCompra;
use OrcamentoFornecedor;
use stdClass;

class IntegracaoComprasBrImportService
{
    /**
     * Dados do orcamento
     *
     * @var OrcamentoCompra
     */
    public $orcamentoCompra;

    /**
     * Dados da Licitacao
     *
     * @var licitacao
     */
    public $licitacao;

    /**
     * Importacao do arquivo do compras BR
     *
     * @param string $file Caminho do arquivo
     * @return boolean
     */
    public function importFilePregao($file, $licitacao)
    {
        $this->licitacao = LicitacaoRepository::getByCodigo($licitacao);
        $this->processFileImport($file);
    }

    /**
     * Processa arquivo de importacao
     *
     * @param string $file
     * @return void
     */
    private function processFileImport($file)
    {
        $csv = new LerCsv($file);
        $csv->setCsvControl('|');
        $fornecedores = [];
        $lances = [];

        foreach ($csv->read() as $item) {
            switch ($item[0]) {
                case '1':
                    $fornecedores[] = $this->refactoryFornecedor($item);
                    break;
                case '2':
                    $lances[] = $this->refactoryLances($item);
                    break;
            }
        }

        $this->insertOrcamento();
        $this->insertFornecedor($fornecedores);
        $this->insertItens($lances);
        $this->licitacao->alterarSituacao(1);
    }

    /**
     * Refatora os dados do registro de fornecedor
     *
     * Adiciona as chaves correspondentes aos valores
     * para melhor manipulacao
     *
     * @param array $data
     * @return array
     */
    private function refactoryFornecedor($data)
    {
        return [
            'tipo_registro'   => $data[0],
            'numero_edital'   => $data[1],
            'ano_edital'      => $data[2],
            'tipo_documento'  => ($data[3] == 1) ? 'cnpj' : 'cpf',
            'documento'       => preg_replace('/\D/', '', $data[4]),
            'razao_social'    => substr($data[5], 0, 40),
            'end_rua'         => $data[6],
            'end_numero'      => $data[7],
            'end_bairro'      => $data[8],
            'end_complemento' => $data[9],
            'end_cidade'      => $data[10],
            'end_estado'      => $data[11],
            'end_cep'         => $data[12],
            'contato'         => substr($data[13], 0, 40),
            'telefone'        => $data[14],
            'celular'         => $data[15],
            'email'           => $data[16],
            'microempresa'    => $data[17]
        ];
    }

    /**
     * Refatora os dados do registro de lances
     *
     * Adiciona as chaves correspondentes aos valores
     * para melhor manipulacao
     *
     * @param array $data
     * @return array
     */
    private function refactoryLances($data)
    {
        return [
            'tipo_registro'   => $data[0],
            'numero_edital'   => $data[1],
            'ano_edital'      => $data[2],
            'numero_lote'     => $data[3],
            'numero_item'     => $data[4],
            'tipo_documento'  => preg_replace('/\D/', '', $data[5]),
            'documento'       => $data[6],
            'preco'           => $data[7],
            'marca'           => $data[8],
            'vencedor'        => $data[9],
        ];
    }

    /**
     * Insere dados do orcamentos de compra
     *
     * @return bool
     * @throws Exception
     */
    private function insertOrcamento()
    {
        $orcamento = new cl_pcorcam;
        $orcamento->pc20_dtate = date('Y-m-d');
        $orcamento->pc20_hrate = date('H:i');
        $orcamento->pc20_obs   = 'Importação Pregão Compras BR';

        $orcamento->incluir(null);

        if ($orcamento->erro_status == 0) {
            throw new \Exception($orcamento->erro_msg);
            return false;
        }

        $this->orcamentoCompra = new OrcamentoCompra($orcamento->pc20_codorc);
    }

    /**
     * Insert no cadastro fornecedores orcamentos
     *
     * Caso nao possua cgm no sistema o mesmo sera criado
     *
     * @param array $fornecedores
     * @return bool
     */
    private function insertFornecedor($fornecedores)
    {
        $orcamentoFornecedor = new cl_pcorcamforne();

        foreach ($fornecedores as $item) {
            $fornecedor = CgmFactory::getCgmByCnpjCpf($item['documento']);

            if (empty($fornecedor)) {
                $type = ($item['tipo_documento'] == 'cnpj') ? 2 : 1;
                $fornecedor = CgmFactory::getInstanceByType($type);

                if ($type == 2) {
                    $fornecedor->setCnpj($item['documento']);
                } else {
                    $fornecedor->setCpf($item['documento']);
                }

                $fornecedor->setNome($item['razao_social']);
                $fornecedor->setLogradouro($item['end_rua']);
                $fornecedor->setNumero($item['end_numero']);
                $fornecedor->setBairro($item['end_bairro']);
                $fornecedor->setComplemento($item['end_complemento']);
                $fornecedor->setMunicipio($item['end_cidade']);
                $fornecedor->setUf($item['end_estado']);
                $fornecedor->setCep($item['end_cep']);
                $fornecedor->setContato($item['contato']);
                $fornecedor->setTelefone($item['telefone']);
                $fornecedor->setCelular($item['celular']);
                $fornecedor->setEmail($item['email']);

                $fornecedor->save();
            }

            $orcamentoFornecedor->pc21_codorc    = $this->orcamentoCompra->getCodigo();
            $orcamentoFornecedor->pc21_numcgm    = $fornecedor->getCodigo();
            $orcamentoFornecedor->pc21_importado = 't';

            $orcamentoFornecedor->incluir(null);

            if ($orcamentoFornecedor->erro_status == 0) {
                throw new \Exception($orcamentoFornecedor->erro_msg);
                return false;
            }
        }
    }

    /**
     * Insere dados do orcamento
     *
     * @param array $lances Lances do arquivo de importacao
     * @return bool
     */
    private function insertItens($lances)
    {
        foreach ($lances as $item) {
            try {
                $codigoLicLicitem = $this->getCodigoItemByOrdem($item['numero_item']);
                $itemLicitacao = new ItemLicitacao($codigoLicLicitem);
                $this->validateItemLicitacao($itemLicitacao);

                $orcamItem  = $this->insertOrcamItem($itemLicitacao);
                $fornecedor = CgmFactory::getCgmByCnpjCpf($item['documento']);
                $orcamForne = $this->orcamentoCompra->getOrcamentoDoFornecedor($fornecedor);

                $this->insertOrcamJulg($orcamItem, $orcamForne, $item);
                $this->insertOrcamVal($orcamItem, $orcamForne, $item, $itemLicitacao);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
                return false;
            }
        }
    }

    /**
     * Insere o item na tabela dos itens do orcamento,
     * caso nao exista registro.
     *
     * table: pcorcamitem
     *
     * @param ItemLicitacao $itemLicitacao
     * @throws Exception
     * @return cl_pcorcamitem|object
     */
    private function insertOrcamItem($itemLicitacao)
    {
        $orcamItem = $this->existsOrcamItem($itemLicitacao);

        if (!$orcamItem) {
            $orcamItem = new cl_pcorcamitem;
            $orcamItem->pc22_codorc = $this->orcamentoCompra->getCodigo();
            $orcamItem->incluir(null);

            if ($orcamItem->erro_status == 0) {
                throw new \Exception('pcorcamitem: ' . $orcamItem->erro_msg);
                return false;
            }

            $this->insertOrcamItemLic($orcamItem, $itemLicitacao);
        }

        return $orcamItem;
    }

    /**
     * Verifica se o item ja possui cadastro no
     * orcamento
     *
     * @param ItemLicitacao $itemLicitacao
     * @return stdClass|false
     */
    private function existsOrcamItem($itemLicitacao)
    {
        $orcamItem = false;

        $sqlDadosOrcamItem  = "SELECT o.*";
        $sqlDadosOrcamItem .= "FROM pcorcamitem o ";
        $sqlDadosOrcamItem .= "INNER JOIN pcorcamitemlic i ON i.pc26_orcamitem = o.pc22_orcamitem ";
        $sqlDadosOrcamItem .= "WHERE o.pc22_codorc = {$this->orcamentoCompra->getCodigo()} ";
        $sqlDadosOrcamItem .= "AND i.pc26_liclicitem = {$itemLicitacao->getCodigo()} ";

        $rsOrcamItem = db_query($sqlDadosOrcamItem);

        if ($rsOrcamItem == false) {
            throw new \Exception('pcorcamitem: erro na consulta do item.');
            return false;
        }

        if (pg_num_rows($rsOrcamItem) > 0) {
            $orcamItem = \db_utils::fieldsMemory($rsOrcamItem, 0);
        }

        return $orcamItem;
    }

    /**
     * Insere dados na tebela de relacao item do orcamento
     * e item da licitacao
     *
     * table: pcorcamitem
     *
     * @param cl_pcorcamitem $orcamItem
     * @param ItemLicitacao $itemLicitacao
     * @throws Exception
     * @return cl_pcorcamitemlic|false
     */
    private function insertOrcamItemLic($orcamItem, $itemLicitacao)
    {
        $orcamItemLic = new cl_pcorcamitemlic;
        $orcamItemLic->pc26_orcamitem  = $orcamItem->pc22_orcamitem;
        $orcamItemLic->pc26_liclicitem = $itemLicitacao->getCodigo();
        $orcamItemLic->incluir();

        if ($orcamItemLic->erro_status == 0) {
            throw new \Exception('pcorcamitem:' . $orcamItemLic->erro_msg);
            return false;
        }

        return $orcamItemLic;
    }

    /**
     * Insere dados na tabela de julgamento
     *
     * table: pcorcamjulg
     *
     * @param cl_pcorcamitem $orcamItem
     * @param OrcamentoFornecedor $orcamForne
     * @param array $item
     * @throws Exception
     * @return bool
     */
    private function insertOrcamJulg($orcamItem, $orcamForne, $item)
    {
        $orcamJulg = new cl_pcorcamjulg;

        $orcamJulg->pc24_pontuacao  = ($item['vencedor'] == 1) ? 1 : 2;
        $orcamJulg->pc24_orcamitem  = $orcamItem->pc22_orcamitem;
        $orcamJulg->pc24_orcamforne = $orcamForne->getCodigo();

        $orcamJulg->incluir(
            $orcamItem->pc22_orcamitem,
            $orcamForne->getCodigo()
        );

        if ($orcamJulg->erro_status == 0) {
            throw new \Exception('pcorcamjulg: ' . $orcamJulg->erro_sql);
            return false;
        }
    }

    /**
     * Insert na tabela de valores dos orcamentos
     *
     * table: pcorcamval
     *
     * @param cl_pcorcamitem $orcamItem
     * @param OrcamentoFornecedor $orcamForne
     * @param array $item
     * @param ItemLicitacao $itemLicitacao
     * @throws Exception
     * @return bool
     */
    private function insertOrcamVal($orcamItem, $orcamForne, $item, $itemLicitacao)
    {
        $orcamVal = new cl_pcorcamval;
        $orcamVal->pc23_vlrun = $item['preco'];
        $orcamVal->pc23_quant = $itemLicitacao->getItemSolicitacao()->getQuantidade();
        $orcamVal->pc23_valor = $orcamVal->pc23_vlrun * $orcamVal->pc23_quant;
        $orcamVal->pc23_obs   = 'MARCA: ' . mb_strtoupper($item['marca']);

        $orcamVal->incluir(
            $orcamForne->getCodigo(),
            $orcamItem->pc22_orcamitem
        );

        if ($orcamVal->erro_status == 0) {
            throw new \Exception('pcorcamjulg: ' . $orcamVal->erro_msg);
            return false;
        }
    }

    /**
     * Valida se os itens do arquivo pertence
     * a lictacao
     *
     * @param ItemLicitacao $itemLicitacao
     * @throws Exception
     * @return void|false
     */
    private function validateItemLicitacao($itemLicitacao)
    {
        $lictacaoCodigo = $this->licitacao->getCodigo();

        if ($itemLicitacao->getCodigoLicitacao() != $lictacaoCodigo) {
            throw new \Exception("Item {$itemLicitacao->getCodigo()} não pertence a licitação.");
            return false;
        }
    }

    /**
     * Busca sequencial do item pela ordem
     *
     * Como o numero do item na exportacao e a ordem,
     * esse metodo e responsavel pela traducao para o sequencial
     *
     * @throws Exception
     * @return integer
     */
    private function getCodigoItemByOrdem($item)
    {
        $liclicitem = new \cl_liclicitem();
        $lictacaoCodigo = $this->licitacao->getCodigo();

        $where  = "l21_codliclicita = {$lictacaoCodigo}";
        $where .= " and l21_ordem = {$item}";

        $sqlBuscaCodigo = $liclicitem->sql_query_file(null, "l21_codigo", null, $where);
        $resultCodigo   = db_query($sqlBuscaCodigo);

        if (pg_num_rows($resultCodigo) !== 1) {
            throw new \Exception("Item {$item} não encontrado na licitação.");
        }

        return pg_fetch_object($resultCodigo)->l21_codigo;
    }
}
