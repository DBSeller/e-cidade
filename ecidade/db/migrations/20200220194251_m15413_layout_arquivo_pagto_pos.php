<?php

use Classes\PostgresMigration;

class M15413LayoutArquivoPagtoPos extends PostgresMigration
{
    public function up()
    {
        $this->execute('
            insert into db_layouttxt( db50_codigo ,db50_layouttxtgrupo ,db50_descr ,db50_quantlinhas ,db50_obs ) values ( 308 ,2 ,\'PAGTO_POS\' ,0 ,\'\' );
            insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta )values ( 10294 ,308 ,\'HEADER DO ARQUIVO\' ,1 ,130 ,0 ,0 ,\'\' ,\'\' ,\'0\' );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171794 ,10294 ,\'cnpjsetorgoverno\' ,\'CNPJ SETOR DE GOVERNO (ÓRGÃO/ENTIDADE)\' ,2 ,1 ,\'\' ,14 ,\'f\' ,\'t\' ,\'e\' ,\'Informar o CNPJ do Setor de Governo, (órgão/entidade) junto ao Ministério da Fazenda\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171795 ,10294 ,\'datainicialinformacao\' ,\'DATA INICIAL DA INFORMAÇÃO\' ,4 ,15 ,\'\' ,8 ,\'f\' ,\'t\' ,\'e\' ,\'Informar a data inicial do período (1º de janeiro do exercício referente a entrega dos dados), no formato ddmmaaaa\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171796 ,10294 ,\'datafinalinformacao\' ,\'DATA FINAL DA INFORMAÇÃO\' ,4 ,23 ,\'\' ,8 ,\'f\' ,\'t\' ,\'e\' ,\'Informar a data final do período a que se referem os dados, no formato ddmmaaaa\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171797 ,10294 ,\'datageracaoarquivo\' ,\'DATA DA GERAÇÃO DO ARQUIVO\' ,4 ,31 ,\'\' ,8 ,\'f\' ,\'t\' ,\'e\' ,\'Informar a data da geração do arquivo noformato ddmmaaaa\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171798 ,10294 ,\'nomesetorgoverno\' ,\'NOME SETOR DE GOVERNO (ÓRGÃO/ENTIDADE)\' ,13 ,39 ,\'\' ,80 ,\'f\' ,\'t\' ,\'d\' ,\'Nome do órgão ou entidade responsável pelos dados e informações\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171799 ,10294 ,\'codigoremessa\' ,\'CÓDIGO DA REMESSA\' ,2 ,119 ,\'\' ,12 ,\'f\' ,\'t\' ,\'e\' ,\'Código da Remessa. Deverá ser gerado pelo próprio Órgão/Entidade, e será utilizado como identificador exclusivo da remessa\' ,0 );

            
            insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 10295 ,308 ,\'REGISTRO\' ,3 ,80 ,0 ,0 ,\'\' ,\'\' ,\'0\' );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171784 ,10295 ,\'idfolha\' ,\'IDENTIFICADOR DA FOLHA DE PAGAMENTO\' ,2 ,1 ,\'\' ,12 ,\'t\' ,\'t\' ,\'e\' ,\'\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171785 ,10295 ,\'codigoregistrofuncionario\' ,\'CÓDIGO DO REGISTRO DO FUNCIONÁRIO\' ,2 ,13 ,\'\' ,12 ,\'f\' ,\'t\' ,\'e\' ,\'\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171786 ,10295 ,\'datapagamento\' ,\'DATA DE PAGAMENTO\' ,4 ,25 ,\'\' ,8 ,\'f\' ,\'t\' ,\'e\' ,\'\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171787 ,10295 ,\'valorpago\' ,\'VALOR PAGO\' ,3 ,33 ,\'\' ,17 ,\'f\' ,\'t\' ,\'e\' ,\'\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171788 ,10295 ,\'codigobanco\' ,\'COD. BCO DEPÓSITO DA FOLHA DE PAGTO. ENT\' ,2 ,50 ,\'\' ,5 ,\'f\' ,\'t\' ,\'e\' ,\'\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171789 ,10295 ,\'codigoagencia\' ,\'COD. AGENCIA BCO. DEPT FOLHA PAGTO. ENT\' ,2 ,55 ,\'\' ,5 ,\'f\' ,\'t\' ,\'e\' ,\'\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171790 ,10295 ,\'codigocontacorrente\' ,\'CODIGO CC BCO DEPT FOLHA PAGTO ENT\' ,2 ,60 ,\'\' ,20 ,\'f\' ,\'t\' ,\'e\' ,\'\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171791 ,10295 ,\'ultimopagamento\' ,\'ÚLTIMO PAGAMENTO\' ,1 ,80 ,\'\' ,1 ,\'f\' ,\'t\' ,\'d\' ,\'\' ,0 );
            
            insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 10296 ,308 ,\'TRAILLER DO ARQUIVO \' ,5 ,22 ,0 ,0 ,\'\' ,\'\' ,\'0\' );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171792 ,10296 ,\'descricao\' ,\'DESCRICAO\' ,13 ,1 ,\'\' ,11 ,\'f\' ,\'t\' ,\'d\' ,\'Valor padrão “FINALIZADOR”\' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171793 ,10296 ,\'totalregistros\' ,\'TOTAL REGISTROS\' ,2 ,12 ,\'\' ,10 ,\'f\' ,\'t\' ,\'e\' ,\'Totalizador de Registros, onde contem a quantidade de registros gerada no arquivo\' ,0 );
        ');
    }

    public function down()
    {
        $this->execute('
            delete from db_layoutcampos where db52_layoutlinha IN (10294, 10295, 10296);
            delete from db_layoutlinha where db51_codigo IN (10294, 10295, 10296);
            delete from db_layouttxt where db50_codigo = 308; 
        ');
    }
}
