<?php

use Classes\PostgresMigration;

class M17931AnexoIv extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into orcparamrel (o42_codparrel, o42_descrrel, o42_orcparamrelgrupo, o42_notapadrao) values (253, 'ANEXO IV - EVOLUÇÃO DO PATRIMÔNIO LÍQUIDO		', 3, 'Fonte: Sistema E-cidade, [nome_departamento] Data da emissão: [data_emissao], Hora de Emissão: [hora_emissao].');
insert into orcparamrelperiodos (o113_sequencial, o113_periodo, o113_orcparamrel) values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 1, 253);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (253, 1, 'Patrimônio/Capital', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Patrimônio/Capital', 't', 'f', 1, 0, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (253, 2, 'Reservas', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Reservas', 't', 'f', 2, 0, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (253, 3, 'Resultado Acumulado', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Resultado Acumulado', 't', 'f', 3, 0, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (253, 4, 'Patrimônio', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Patrimônio', 't', 'f', 4, 0, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (253, 5, 'Reservas	', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Reservas	', 't', 'f', 5, 0, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (253, 6, 'Lucros ou Prejuízos Acumulados', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Lucros ou Prejuízos Acumulados', 't', 'f', 6, 0, '', 'f', 3);
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, 'Ano menos três', 1, '', 'valor_menos_tres', '', 0, 253);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, 'Ano menos dois', 1, '', 'valor_menos_dois', '', 0, 253);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, 'Ano menos quatro', 1, '', 'valor_menos_quatro', '', 0, 253);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 253, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from orcparamseqfiltroorcamento where o133_orcparamrel = 253;
delete from orcparamrelperiodos where o113_orcparamrel = 253;
delete from orcparamseqfiltropadrao where o132_orcparamrel = 253;
delete from orcparamseqorcparamseqcolunavalor where o117_orcparamseqorcparamseqcoluna in (
    select o116_sequencial from orcparamseqorcparamseqcoluna where o116_codparamrel = 253
);
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 253;
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 253;
delete from orcparamseq where o69_codparamrel = 253;
delete from orcparamrel where o42_codparrel = 253;
SQL
        );
    }
}
