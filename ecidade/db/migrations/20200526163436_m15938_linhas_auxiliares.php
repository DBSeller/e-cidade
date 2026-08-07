<?php

use Classes\PostgresMigration;

class M15938LinhasAuxiliares extends PostgresMigration
{
    public function up()
    {
        $this->novosMenus();
        $this->relatorioAuxiliar();
    }

    public function novosMenus()
    {
        $this->execute("
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
            values ( 228258 ,'Linhas Auxiliares' ,'Linhas Auxiliares' ,'' ,'1' ,'1' ,'Linhas Auxiliares' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8467 ,228258 ,7 ,209 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
            values ( 228259 ,'Balanço Orçamentario' ,'Balanço Orçamentario' ,'con2_sigapfiscalbalorc001.php' ,'1' ,'1' ,'Balanço Orçamentario' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228258 ,228259 ,1 ,209 );
        ");
    }

    private function relatorioAuxiliar()
    {
        $this->execute("
            insert into orcparamrel (o42_codparrel, o42_orcparamrelgrupo, o42_descrrel, o42_notapadrao)
            values (219, 1, 'QUADRO AUXILIAR BALANÇO ORÇAMENTÁRIO', '');

            insert into orcparamrelperiodos( o113_sequencial ,o113_periodo ,o113_orcparamrel )
            values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 6, 219),
                   (nextval('orcparamrelperiodos_o113_sequencial_seq'), 7, 219),
                   (nextval('orcparamrelperiodos_o113_sequencial_seq'), 8, 219),
                   (nextval('orcparamrelperiodos_o113_sequencial_seq'), 9, 219),
                   (nextval('orcparamrelperiodos_o113_sequencial_seq'), 10, 219),
                   (nextval('orcparamrelperiodos_o113_sequencial_seq'), 11, 219);

            insert into orcparamseq(o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem )
             values (219, 1 ,'Meta da Receita Aprovada na LDO' ,1 ,1 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'Meta da Receita Aprovada na LDO' ,'t' ,'f' ,1 ,4 ,'' ,'false' ,0 );

            insert into orcparamseqorcparamseqcoluna( o116_sequencial ,o116_codseq ,o116_codparamrel ,o116_orcparamseqcoluna ,o116_ordem ,o116_periodo ,o116_formula )
            values ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),1 ,219 ,36 ,1 ,6 ,'' ),
                   ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),1 ,219 ,36 ,1 ,7 ,'' ),
                   ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),1 ,219 ,36 ,1 ,8 ,'' ),
                   ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),1 ,219 ,36 ,1 ,9 ,'' ),
                   ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),1 ,219 ,36 ,1 ,10 ,'' ),
                   ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),1 ,219 ,36 ,1 ,11 ,'' );

            insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem )
            values ( 219 ,2 ,'Meta da Despesa Aprovada na LDO' ,1 ,1 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'Meta da Despesa Aprovada na LDO' ,'t' ,'f' ,2 ,4 ,'' ,'false' ,0 );

            insert into orcparamseqorcparamseqcoluna( o116_sequencial ,o116_codseq ,o116_codparamrel ,o116_orcparamseqcoluna ,o116_ordem ,o116_periodo ,o116_formula )
            values ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq') ,2 ,219 ,36 ,1 ,6 ,'' ),
                   ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq') ,2 ,219 ,36 ,1 ,7 ,'' ),
                   ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq') ,2 ,219 ,36 ,1 ,8 ,'' ),
                   ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq') ,2 ,219 ,36 ,1 ,9 ,'' ),
                   ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq') ,2 ,219 ,36 ,1 ,10 ,'' ),
                   ( nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq') ,2 ,219 ,36 ,1 ,11 ,'' );
        ");
    }

    public function down()
    {
        $this->execute("
            delete from db_menu where id_item_filho = 228259 AND modulo = 209;
            delete from db_menu where id_item_filho = 228258 AND modulo = 209;
            delete from db_itensmenu where id_item = 228258;
            delete from db_itensmenu where id_item = 228259;
        ");

        $this->execute("
            DELETE FROM orcparamseqorcparamseqcoluna WHERE o116_codparamrel = 219;
            DELETE FROM orcparamseq where o69_codparamrel = 219;
            DELETE FROM orcparamrelperiodos WHERE o113_orcparamrel = 219;
            DELETE FROM orcparamrel WHERE o42_codparrel = 219;
        ");
    }


}
