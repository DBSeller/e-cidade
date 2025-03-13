<?php

use Classes\PostgresMigration;

class M17937RelatorioAnexo8 extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into orcparamrel (o42_codparrel, o42_descrrel, o42_orcparamrelgrupo, o42_notapadrao) values (256, 'ANEXO VIII - MARGEM DE EXPANSÃO DAS DESPESAS OBRIGATÓRIAS DE CARÁTER CONTINUADO', 3, 'Fonte: Sistema E-cidade, [nome_departamento] Data da emissão: [data_emissao], Hora de Emissão: [hora_emissao].');
insert into orcparamrelperiodos (o113_sequencial, o113_periodo, o113_orcparamrel) values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 1, 256);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (256, 1, 'Aumento Permanente da Receita', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Aumento Permanente da Receita', 't', 'f', 1, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (256, 2, '(-) Transferências Constitucionais', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', '(-) Transferências Constitucionais', 't', 'f', 2, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (256, 3, '(-) Transferências ao FUNDEB', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', '(-) Transferências ao FUNDEB', 't', 'f', 3, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (256, 4, 'Saldo Final do Aumento Permanente de Receita (I)', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Saldo Final do Aumento Permanente de Receita (I)', 'f', 't', 4, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (256, 5, 'Redução Permanente de Despesa (II)', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Redução Permanente de Despesa (II)', 't', 'f', 5, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (256, 6, 'Margem Bruta (III) = (I+II)', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Margem Bruta (III) = (I+II)', 'f', 't', 6, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (256, 7, 'Saldo Utilizado da Margem Bruta (IV)', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Saldo Utilizado da Margem Bruta (IV)', 'f', 't', 7, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (256, 8, '   Novas DOCC', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', '   Novas DOCC', 't', 'f', 8, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (256, 9, '   Novas DOCC geradas por PPP', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', '   Novas DOCC geradas por PPP', 't', 'f', 9, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (256, 10, 'Margem Líquida de Expansão de DOCC (V) = (III-IV)', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Margem Líquida de Expansão de DOCC (V) = (III-IV)', 'f', 't', 10, 0, '', 'f', 0);
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2011, 'Valor', 1, '', 'valor', '', 0, 256);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 256, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 256, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 256, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 256, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 256, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 256, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 256, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 256, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 256, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 256, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from orcparamseqfiltroorcamento where o133_orcparamrel = 256;
delete from orcparamrelperiodos where o113_orcparamrel = 256;
delete from orcparamseqfiltropadrao where o132_orcparamrel = 256;
delete from orcparamseqorcparamseqcolunavalor where o117_orcparamseqorcparamseqcoluna in (
    select o116_sequencial from orcparamseqorcparamseqcoluna where o116_codparamrel = 256
);
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 256;
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 256;
delete from orcparamseq where o69_codparamrel = 256;
delete from orcparamrel where o42_codparrel = 256;
SQL
        );
    }
}
