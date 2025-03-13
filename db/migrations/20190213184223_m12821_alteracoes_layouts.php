<?php

use Classes\PostgresMigration;

class M12821AlteracoesLayouts extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            delete from db_layoutcampos where db52_codigo = 1839;
            delete from db_layoutcampos where db52_codigo = 1838;
            delete from db_layoutcampos where db52_codigo = 1837;
            delete from db_layoutcampos where db52_codigo = 1836;
            delete from db_layoutcampos where db52_codigo = 1835;
            delete from db_layoutcampos where db52_codigo = 1834;
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171741 ,131 ,'cnpjsetorgoverno' ,'CNPJ DO SETOR DE GOVERNO' ,1 ,1 ,'' ,14 ,'f' ,'t' ,'d' ,'' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171743 ,131 ,'datainicialinformacao' ,'DATA INICIAL DA INFORMAÇÃO' ,1 ,1 ,'' ,8 ,'f' ,'t' ,'d' ,'' ,0 );
            update db_layoutcampos set db52_posicao = db52_posicao+8 where db52_layoutlinha = 131 and db52_posicao >= 1 and db52_codigo <> 171743;
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171744 ,131 ,'datafinalinformacao' ,'DATA FINAL DA INFORMAÇÃO' ,1 ,1 ,'' ,8 ,'f' ,'t' ,'d' ,'' ,0 );
            update db_layoutcampos set db52_posicao = db52_posicao+8 where db52_layoutlinha = 131 and db52_posicao >= 1 and db52_codigo <> 171744;
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171745 ,131 ,'datageracaoarquivo' ,'DATA DA GERAÇÃO DO ARQUIVO' ,1 ,1 ,'' ,8 ,'f' ,'t' ,'d' ,'' ,0 );
            update db_layoutcampos set db52_posicao = db52_posicao+8 where db52_layoutlinha = 131 and db52_posicao >= 1 and db52_codigo <> 171745;
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171746 ,131 ,'nomesetorgoverno' ,'NOME DO SETOR DE GOVERNO' ,1 ,1 ,'' ,80 ,'f' ,'t' ,'d' ,'' ,0 );
            update db_layoutcampos set db52_posicao = db52_posicao+80 where db52_layoutlinha = 131 and db52_posicao >= 1 and db52_codigo <> 171746;
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171748 ,132 ,'naturezainformacao' ,'NATUREZA DA INFORMAÇÃO' ,1 ,255 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171749 ,132 ,'indicadorsuperavitfinanceiro' ,'INDICADOR DE SUPERÁVIT FINANCEIRO' ,1 ,256 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171750 ,132 ,'codigorecursovinculado' ,'CÓDIGO DO RECURSO VINCULADO' ,1 ,257 ,'' ,4 ,'f' ,'t' ,'d' ,'' ,0 );
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171751 ,138 ,'salarioinicialcargo' ,'SALÁRIO INICIAL DO CARGO' ,1 ,377 ,'' ,17 ,'f' ,'t' ,'d' ,'' ,0 );

            -- AJUSTANDO POSICOES DO CABECALHO
            update db_layoutcampos SET db52_posicao = 1 where db52_codigo = 171741;
            update db_layoutcampos SET db52_posicao = (select db52_posicao + db52_tamanho from db_layoutcampos where db52_codigo = 171741) where db52_codigo = 171743;
            update db_layoutcampos SET db52_posicao = (select db52_posicao + db52_tamanho from db_layoutcampos where db52_codigo = 171743) where db52_codigo = 171744;
            update db_layoutcampos SET db52_posicao = (select db52_posicao + db52_tamanho from db_layoutcampos where db52_codigo = 171744) where db52_codigo = 171745;
            update db_layoutcampos SET db52_posicao = (select db52_posicao + db52_tamanho from db_layoutcampos where db52_codigo = 171745) where db52_codigo = 171746;
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
          delete from db_layoutcampos where db52_codigo in(171741, 171743, 171744, 171745, 171746, 171748, 171749, 171750, 171751);
SQL;
        $this->execute($sql);
    }
}
