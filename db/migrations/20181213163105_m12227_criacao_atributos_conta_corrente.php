<?php

use Classes\PostgresMigration;

class M12227CriacaoAtributosContaCorrente extends PostgresMigration
{
    public function up()
    {

        $this->execute(
            <<<SQL_UP
insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'EO', 'Esfera Orçamentária', 'select o58_esferaorcamentaria from conlancamdot join orcdotacao on orcdotacao.o58_coddot = conlancamdot.c73_coddot and orcdotacao.o58_anousu = conlancamdot.c73_anousu where conlancamdot.c73_codlan = codigo_lancamento limit 1', 'Esfera orçamentária vinculada a dotação', 'esfera_orcamentaria', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'FUN', 'Função', 'select o58_funcao from conlancamdot join orcdotacao on orcdotacao.o58_coddot = conlancamdot.c73_coddot and orcdotacao.o58_anousu = conlancamdot.c73_anousu where conlancamdot.c73_codlan = codigo_lancamento limit 1', 'Função vinculada a dotação', 'funcao', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'SUBF', 'Subfunção', 'select o58_subfuncao from conlancamdot join orcdotacao on orcdotacao.o58_coddot = conlancamdot.c73_coddot and orcdotacao.o58_anousu = conlancamdot.c73_anousu where conlancamdot.c73_codlan = codigo_lancamento limit 1', 'Subfunção vinculada a dotação', 'subfuncao', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'PROG', 'Programa', 'select o58_programa from conlancamdot join orcdotacao on orcdotacao.o58_coddot = conlancamdot.c73_coddot and orcdotacao.o58_anousu = conlancamdot.c73_anousu where conlancamdot.c73_codlan = codigo_lancamento limit 1', 'Programa vinculada a dotação', 'programa', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'AC', 'Ação', 'select o58_projativ from conlancamdot join orcdotacao on orcdotacao.o58_coddot = conlancamdot.c73_coddot and orcdotacao.o58_anousu = conlancamdot.c73_anousu where conlancamdot.c73_codlan = codigo_lancamento limit 1', 'Ação vinculada a dotação', 'acao', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'SLG', 'Localizado de Gastos', 'select o58_localizadorgastos from conlancamdot join orcdotacao on orcdotacao.o58_coddot = conlancamdot.c73_coddot and orcdotacao.o58_anousu = conlancamdot.c73_anousu where conlancamdot.c73_codlan = codigo_lancamento limit 1', 'Localizador de Gastos vinculada a dotação', 'localizador_gastos', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'IUFR', 'Identificador de Uso da Fonte de Recurso', 'select o15_loaidentificadoruso from conlancamrecurso inner join orctiporec on c130_orctiporec = o15_codigo where c130_conlancam  = codigo_lancamento and c130_conta = conta_reduzida and c130_natureza = natureza', 'Localizado no Cadastro de Recurso', 'recurso_identificador_uso', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'TDFR', 'Tipo de Detalhamento da Fonte de Recurso', 'select o15_loatipo from conlancamrecurso inner join orctiporec on c130_orctiporec = o15_codigo where c130_conlancam  = codigo_lancamento and c130_conta = conta_reduzida and c130_natureza = natureza', 'Localizado no Cadastro de Recurso', 'recurso_tipo_detalhamento', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'GFR', 'Grupo da Fonte de Recurso', 'select o15_loagrupo from conlancamrecurso inner join orctiporec on c130_orctiporec = o15_codigo where c130_conlancam  = codigo_lancamento and c130_conta = conta_reduzida and c130_natureza = natureza', 'Localizado no Cadastro de Recurso', 'recurso_grupo', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'EFR', 'Especificação da Fonte de Recurso', 'select o15_loaespecificacao from conlancamrecurso inner join orctiporec on c130_orctiporec = o15_codigo where c130_conlancam  = codigo_lancamento and c130_conta = conta_reduzida and c130_natureza = natureza', 'Localizado no Cadastro de Recurso', 'recurso_especificacao', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'IRP', 'Identificador de Resultado Primário', 'select c60_identificadoresultadoprimario from conlancamdot join orcdotacao on orcdotacao.o58_coddot = conlancamdot.c73_coddot and orcdotacao.o58_anousu = conlancamdot.c73_anousu join conplanoorcamento on conplanoorcamento.c60_codcon = orcdotacao.o58_codele and conplanoorcamento.c60_anousu = orcdotacao.o58_anousu where conlancamdot.c73_codlan = codigo_lancamento limit 1', 'Encontrado na conta do plano orçamentário vinculada a dotação', 'identificacao_resultado_primario', 'NI');

insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'VP', 'Vinculação de Pagamento', '', '', 'vinculacao_pagamento', 'NI');

SQL_UP
        );
    }


    public function down()
    {
        $this->execute("delete from conplanoinfocomplementar where c121_sigla in ('EO', 'FUN', 'SUBF', 'PROG', 'AC', 'SLG', 'IUFR', 'TDFR', 'GFR', 'EFR', 'IRP', 'VP')");
    }
}
