<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21015CriacaoEstruturaMenusRclLdoLoa extends Migration
{
    public function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228684 ,'Outros Anexos' ,'Outros Anexos' ,'' ,'1' ,'1' ,'Outros Anexos' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228507 ,228684 ,9 ,228358 );

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228685 ,'Previsão da Receita Corrente Líquida - LDO/LOA' ,'Previsão da Receita Corrente Líquida - LDO/LOA' ,'pla2_abas_planejamento_previsao_receita.php' ,'1' ,'1' ,'Previsão da Receita Corrente Líquida - LDO/LOA' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228684 ,228685 ,1 ,228358 );
SQL
        );
    }

    public function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where id_item_filho = 228685;
delete from db_itensmenu where id_item = 228685;

delete from db_menu where id_item_filho = 228684;
delete from db_itensmenu where id_item = 228684;
SQL
        );
    }

    public function upRelatorio()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into orcparamrel (o42_codparrel, o42_descrrel, o42_orcparamrelgrupo, o42_notapadrao) values (267, 'PREVISÃO DA RECEITA CORRENTE LÍQUIDA - LDO/LOA', 3, 'Fonte: Sistema E-cidade, [nome_departamento] Data da emissão: [data_emissao], Hora de Emissão: [hora_emissao].');
insert into orcparamrelperiodos (o113_sequencial, o113_periodo, o113_orcparamrel) values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 1, 267);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 1, 'RECEITAS CORRENTES (I)', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'RECEITAS CORRENTES (I)', 'f', 't', 1, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 2, 'Impostos, Taxas e Contribuições de Melhoria', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Impostos, Taxas e Contribuições de Melhoria', 'f', 't', 2, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 3, 'IPTU', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'IPTU', 't', 'f', 3, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 4, 'ISS', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'ISS', 't', 'f', 4, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 5, 'ITBI', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'ITBI', 't', 'f', 5, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 6, 'IRRF', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'IRRF', 't', 'f', 6, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 7, 'Outros Impostos, Taxas e Contribuições de Melhoria', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Outros Impostos, Taxas e Contribuições de Melhoria', 't', 'f', 7, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 8, 'Contribuições', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Contribuições', 't', 'f', 8, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 9, 'Receita Patrimonial', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Receita Patrimonial', 'f', 't', 9, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 10, 'Rendimentos de Aplicação Financeira', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Rendimentos de Aplicação Financeira', 't', 'f', 10, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 11, 'Outras Receitas Patrimoniais', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Outras Receitas Patrimoniais', 't', 'f', 11, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 12, 'Receita Agropecuária', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Receita Agropecuária', 't', 'f', 12, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 13, 'Receita Industrial', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Receita Industrial', 't', 'f', 13, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 14, 'Receita de Serviços', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Receita de Serviços', 't', 'f', 14, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 15, 'Transferências Correntes', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Transferências Correntes', 'f', 't', 15, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 16, 'Cota-Parte do FPM', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Cota-Parte do FPM', 't', 'f', 16, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 17, 'Cota-Parte do ICMS', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Cota-Parte do ICMS', 't', 'f', 17, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 18, 'Cota-Parte do IPVA', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Cota-Parte do IPVA', 't', 'f', 18, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 19, 'Cota-Parte do ITR', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Cota-Parte do ITR', 't', 'f', 19, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 20, 'Transferências da LC 87/1996', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Transferências da LC 87/1996', 't', 'f', 20, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 21, 'Transferências da LC 61/1989', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Transferências da LC 61/1989', 't', 'f', 21, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 22, 'Transferências do FUNDEB', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Transferências do FUNDEB', 't', 'f', 22, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 23, 'Outras Transferências Correntes', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Outras Transferências Correntes', 't', 'f', 23, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 24, 'Outras Receitas Correntes', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Outras Receitas Correntes', 't', 'f', 24, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 25, 'DEDUÇÕES (II)', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'DEDUÇÕES (II)', 'f', 't', 25, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 26, 'Contribuição do Servidor para o Plano de Previdência', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Contribuição do Servidor para o Plano de Previdência', 't', 'f', 26, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 27, 'Compensação Financeira entre Regimes Previdência', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Compensação Financeira entre Regimes Previdência', 't', 'f', 27, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 28, 'Rendimentos de Aplicações de Recursos Previdenciários', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Rendimentos de Aplicações de Recursos Previdenciários', 't', 'f', 28, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 29, 'Dedução de Receita para Formação do FUNDEB', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Dedução de Receita para Formação do FUNDEB', 't', 'f', 29, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 30, 'RECEITA CORRENTE LÍQUIDA (III) = (I - II)', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'RECEITA CORRENTE LÍQUIDA (III) = (I - II)', 'f', 't', 30, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 31, '( - ) Transferências obrigatórias da União relativas às emen', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '( - ) Transferências obrigatórias da União relativas às emendas individuais (art. 166-A, § 1º, da CF) (IV)', 't', 'f', 31, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 32, 'RECEITA CORRENTE LÍQUIDA AJUSTADA PARA PARA CÁLCULO DOS LIMI', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'RECEITA CORRENTE LÍQUIDA AJUSTADA PARA PARA CÁLCULO DOS LIMITES DE ENDIVIDAMENTO (V) = (III - IV)', 'f', 't', 32, 0, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 33, '( - ) Transferências obrigatórias da União relativas às emen', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '( - ) Transferências obrigatórias da União relativas às emendas de bancada (art. 166, § 16, da CF) (VI)', 't', 'f', 33, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (267, 34, 'RECEITA CORRENTE LÍQUIDA AJUSTADA PARA  CÁLCULO DOS LIMITES ', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'RECEITA CORRENTE LÍQUIDA AJUSTADA PARA  CÁLCULO DOS LIMITES DA DESPESA COM PESSOAL (VII) = (V - VI)', 'f', 't', 34, 0, '', 'f', 0);
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 3, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="411125000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911125000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="411180110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911180110000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 4, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="411180200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911180200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="411145110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911145110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="411145120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911145120000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 5, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="411180141000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="411180143000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911180141000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911180143000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="411125300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911125300000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 6, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="411130311000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="411130341000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911130311000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911130341000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 7, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="411000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="411125000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="911125000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="411145110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="411125300000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="411130310000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="411130340000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="411180110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="411180200000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="411145120000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="411180140000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="911145110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="911125300000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="911130310000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="911130340000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="911180100000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="911180200000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="911145120000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="911180140000000" nivel="" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 8, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912000000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 10, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210040000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210400000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210500000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413299900000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413290010000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 11, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210100000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210200000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210300000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210400000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210500000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413299900000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210010000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413290010000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210040000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913210040000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913290010000000" nivel="" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 12, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="414000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="914000000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 13, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="415000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="915000000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 14, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="416000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="916000000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 16, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417115110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417115120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417115130000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417210102000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417180121000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417180131000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417180141000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 17, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417215000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417280111000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 18, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417215100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417280121000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 19, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417115200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417180151000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 20, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417195100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417180610000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 21, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417215200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417280130000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 22, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417580110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417180910000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 23, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417115110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417115120000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417115130000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417215000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417215100000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417115200000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417195100000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417215200000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417515000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417150000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417180120000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417180130000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417180140000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417280110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417280120000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417280130000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417180610000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417180150000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417580110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="417180910000000" nivel="" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 24, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="419000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="919000000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor="
"/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 26, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412150100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412150300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180200000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 27, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412150200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412155000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412155100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="419990300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180400000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412150200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="419900310000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 28, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413210400000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 29, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="917000000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 31, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="400000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="900000000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="4501" id="fonterecurso"/>
 <complemento operador="in" valor="3110,3140" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 267, 33, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="400000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="900000000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="4501" id="fonterecurso"/>
 <complemento operador="in" valor="3120,3150" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2022, 'Valor Ano +1', 1, '', 'valor_ano_mais_um', '', 0, 267);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2022, 'Valor Ano +2', 1, '', 'valor_ano_mais_dois', '', 0, 267);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2022, 'Valor Ano Referência', 1, '', 'valor_ano_referencia', '', 0, 267);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 267, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');        
SQL
        );
    }


    public function downRelatorio()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 267;     
delete from orcparamseqcoluna where o115_relatorio = 267;
delete from orcparamseqfiltropadrao where o132_orcparamrel = 267;
delete from orcparamseq where o69_codparamrel = 267;
delete from orcparamrelperiodos where o113_orcparamrel = 267;
delete from orcparamrel where o42_codparrel = 267;
SQL
        );
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upRelatorio();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downRelatorio();
    }
}
