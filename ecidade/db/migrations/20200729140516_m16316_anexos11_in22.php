<?php

use Classes\PostgresMigration;

class M16316Anexos11In22 extends PostgresMigration
{
    public function up()
    {
        $this->relatorios();
        $this->linhasColunasAnexo11A();
        $this->linhasColunasAnexo11C();

        $this->execute(<<<SQL_UP
insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
values ( 228306 ,'Anexo 11' ,'Anexo 11' ,'con2_tceroanexosin22011.php?anexo=11' ,'1' ,'1' ,'TCE/RO - Anexo 11' ,'true' ),
       ( 228307 ,'Anexo 11 - A' ,'Anexo 11 - A' ,'con2_tceroanexosin22011.php?anexo=11a' ,'1' ,'1' ,'TCE/RO - Anexo 11 - A' ,'true' ),
       ( 228308 ,'Anexo 11 - B' ,'Anexo 11 - B' ,'con2_tceroanexosin22011.php?anexo=11b' ,'1' ,'1' ,'TCE/RO - Anexo 11 - B' ,'true' ),
       ( 228309 ,'Anexo 11 - C' ,'Anexo 11 - C' ,'con2_tceroanexosin22011.php?anexo=11c' ,'1' ,'1' ,'TCE/RO - Anexo 11 - C' ,'true' );

insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
values (228292, 228306, 12, 209),
       (228292, 228307, 13, 209),
       (228292, 228308, 14, 209),
       (228292, 228309, 15, 209);
SQL_UP
        );
    }

    public function down()
    {
        $this->execute(<<<SQL_DOWN

delete from orcparamseqfiltroorcamento where o133_orcparamrel in (237, 238, 239, 240);
delete from orcparamseqorcparamseqcoluna where o116_codparamrel in (237, 238, 239, 240);
delete from orcparamseqfiltropadrao where o132_orcparamrel in (237, 238, 239, 240);
delete from orcparamrelperiodos where o113_orcparamrel in (237, 238, 239, 240);
delete from orcparamseq where o69_codparamrel in (237, 238, 239, 240);
delete from orcparamrel where o42_codparrel in (237, 238, 239, 240);

SQL_DOWN
        );

        $this->execute(<<<SQL_DOWN
delete from db_menu where id_item_filho in (228306, 228307, 228308, 228309) AND modulo = 209;
delete from db_itensmenu where id_item in (228306, 228307, 228308, 228309);
SQL_DOWN
        );
    }

    public function relatorios()
    {
        $this->execute(<<<SQL_UP
 insert into orcparamrel
 values (237, 'TCE/RO - ANEXO 11 - A', 4, null),
        (238, 'TCE/RO - ANEXO 11', 4, null),
        (239, 'TCE/RO - ANEXO 11 - B', 4, null),
        (240, 'TCE/RO - ANEXO 11 - C', 4, null);
 insert into orcparamrelperiodos
 values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 28, 237),
        (nextval('orcparamrelperiodos_o113_sequencial_seq'), 28, 238),
        (nextval('orcparamrelperiodos_o113_sequencial_seq'), 28, 239),
        (nextval('orcparamrelperiodos_o113_sequencial_seq'), 28, 240);
SQL_UP
        );
    }

    private function linhasColunasAnexo11A()
    {
        $this->execute(<<<SQL
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 1, 'FPM', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'FPM', 'f', 'f', 1, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 2, 'FPE', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'FPE', 'f', 'f', 2, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 3, 'ITR', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'ITR', 'f', 'f', 3, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 4, 'IPI Exportação', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'IPI Exportação', 'f', 'f', 4, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 5, 'ICMS-Desoneração (LC 87/96)', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'ICMS-Desoneração (LC 87/96)', 'f', 'f', 5, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 6, 'Complementação da União', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Complementação da União', 'f', 'f', 6, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 7, 'ITCMD', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'ITCMD', 'f', 'f', 7, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 8, 'IPVA', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'IPVA', 'f', 'f', 8, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 9, 'ICMS', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'ICMS', 'f', 'f', 9, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 10, 'SUBTOTAL', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'SUBTOTAL', 'f', 't', 10, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 11, 'Rend.Aplic.Financeiras', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Rend.Aplic.Financeiras', 'f', 'f', 11, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 12, 'RECEITA TOTAL', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'RECEITA TOTAL', 'f', 't', 12, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 13, '6.1.RECEITA DO FUNDEB', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.1.RECEITA DO FUNDEB', 'f', 'f', 13, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 14, '6.2.COMPLEMENTAÇÃO DO DO FUNDEB', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.2.COMPLEMENTAÇÃO DO DO FUNDEB', 'f', 'f', 14, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 15, '6.3. RENDIMENTOS APLICAÇÃO FINANCEIRA', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.3. RENDIMENTOS APLICAÇÃO FINANCEIRA', 'f', 'f', 15, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (237, 16, 'RECEITA TOTAL', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'RECEITA TOTAL', 'f', 't', 16, 1, '', 'f', 0);
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2014, 'Valor', 1, '', 'valor', '', 0, 237);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, 'F[10]+L[11]->valor');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, 'L[1]->valor+L[2]->valor+L[3]->valor+L[4]->valor+L[5]->valor+L[6]->valor+L[7]->valor+L[8]->valor+L[9]->valor');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2010, 'Receitas Realizadas (b)', 1, '', 'rarrec', '', 0, 237);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 28, 'L[13]->rarrec+L[14]->rarrec+L[15]->rarrec');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2010, 'Previsão Atualizada (a)', 1, '', 'previsao', '', 0, 237);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, 'L[13]->previsao+L[14]->previsao+L[15]->previsao');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_inicial');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_inicial');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 237, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_inicial');
SQL
        );
    }

    private function linhasColunasAnexo11C()
    {
        $this->execute(<<<SQL
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 1, '3.SALDO DO EXERCÍCIO ANTERIOR', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '3.SALDO DO EXERCÍCIO ANTERIOR', 'f', 'f', 1, 1, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 2, '4. RECEBIMENTO DO FUNDEB', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', '4. RECEBIMENTO DO FUNDEB', 'f', 't', 2, 2, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 3, '4.1. ARRECADAÇÃO ORDINÁRIA', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '4.1. ARRECADAÇÃO ORDINÁRIA', 'f', 'f', 3, 3, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 4, '4.2. RENDIMENTOS DE APLICAÇÃO FINANCEIRA', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '4.2. RENDIMENTOS DE APLICAÇÃO FINANCEIRA', 'f', 'f', 4, 3, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 5, '4.3 .COMPLEMENTAÇÃO DO FUNDEB', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '4.3 .COMPLEMENTAÇÃO DO FUNDEB', 'f', 'f', 5, 3, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 6, '5.TOTAL (3 + 4)', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', '5.TOTAL (3 + 4)', 'f', 't', 6, 2, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 7, '6. PAGAMENTOS EFETUADOS', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', '6. PAGAMENTOS EFETUADOS', 'f', 't', 7, 2, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 8, '6.1. RESTOS A PAGAR COM RECURSOS VINCULADOS', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.1. RESTOS A PAGAR COM RECURSOS VINCULADOS', 'f', 'f', 8, 3, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 9, '6.2. RESTOS A PAGAR SEM VINCULAÇÃO DE RECURSOS', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.2. RESTOS A PAGAR SEM VINCULAÇÃO DE RECURSOS', 'f', 'f', 9, 3, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 10, '6.3. ENSINO INFANTIL', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', '6.3. ENSINO INFANTIL', 'f', 't', 10, 3, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 11, '6.3.1. Creche', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.3.1. Creche', 'f', 'f', 11, 4, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 12, '6.3.2. Pré-Escola', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.3.2. Pré-Escola', 'f', 'f', 12, 4, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 13, '6.4. ENSINO FUNDAMENTAL', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.4. ENSINO FUNDAMENTAL', 'f', 'f', 13, 3, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 14, '6.5. ENSINO MÉDIO', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.5. ENSINO MÉDIO', 'f', 'f', 14, 3, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 15, '6.6. EDUCAÇÃO ESPECIAL', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.6. EDUCAÇÃO ESPECIAL', 'f', 'f', 15, 3, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 16, '6.7. EDUCAÇÃO DE JOVENS E ADULTOS', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.7. EDUCAÇÃO DE JOVENS E ADULTOS', 'f', 'f', 16, 3, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 17, '6.8. OUTROS ', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '6.8. OUTROS ', 'f', 'f', 17, 3, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 18, '7. SUB-TOTAL - SALDO FINANCEIRO A EXISTIR (5 - 6)', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', '7. SUB-TOTAL - SALDO FINANCEIRO A EXISTIR (5 - 6)', 'f', 't', 18, 2, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 19, '8. SALDO FINANCEIRO EXISTENTE NAS CONTAS DO FUNDEB', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '8. SALDO FINANCEIRO EXISTENTE NAS CONTAS DO FUNDEB', 'f', 'f', 19, 2, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 20, '9. DIFERENÇA ( 7 - 8 )', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', '9. DIFERENÇA ( 7 - 8 )', 'f', 't', 20, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 21, '10. Remuneração dos Profissionais do Magistério-(Mínimo de 6', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '10. Remuneração dos Profissionais do Magistério-(Mínimo de 60% do item 4)', 'f', 'f', 21, 1, '', 'f', 2);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 22, '11. Despesas diversas com recursos do Fundeb -(Máxima de 40%', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '11. Despesas diversas com recursos do Fundeb -(Máxima de 40% do item 4)', 'f', 'f', 22, 1, '', 'f', 2);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (240, 23, '13. Despesas com Educação de Jovens e Adultos (Máximo de 10%', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '13. Despesas com Educação de Jovens e Adultos (Máximo de 10% dos recursos do Fundeb, conforme definição em Lei', 'f', 'f', 23, 1, '', 'f', 2);
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 1, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 3, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 4, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 5, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 8, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 9, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 11, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="1011,3011,1011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 12, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 13, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 14, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 15, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 16, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 17, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 19, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110000000000" nivel="5" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="1011,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 21, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 22, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
  <conta estrutural="331000000000000" nivel="3" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="1011,111,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 240, 23, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="366" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="111,1011,3011" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2010, 'Valor', 1, '', 'valor', '', 0, 240);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_debito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_debito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_debito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_credito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_credito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_credito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_credito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_credito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_credito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_credito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_credito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_anterior_credito');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#saldo_final');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#pago_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#pago_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 240, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 28, '#pago_acumulado');

SQL
        );
    }
}
