<?php

use Classes\PostgresMigration;

class M15300Anexo7RelatorioLegal extends PostgresMigration
{


    public function down()
    {
        $this->execute(<<<SQL_DOWN

delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 212;
delete from orcparamseq where o69_codparamrel = 212;

SQL_DOWN
        );
    }

    public function up()
    {

        $this->execute(<<<SQL_UP


update db_itensmenu set funcao = 'con2_tceroanexosin22011.php?anexo=7&relatorio=212' where id_item = 228230;
update orcparamrel set o42_descrrel = 'TCE/RO - ANEXO 7' where o42_codparrel = 212;


insert into orcparamseq values (212,  1, 'FPM', 1, 0, 0, false, false, false, false, false, 'FPM', false, false, 1, 0, '', false, 1);
insert into orcparamseq values (212,  2, 'FPE', 1, 0, 0, false, false, false, false, false, 'FPE', false, false, 2, 0, '', false, 1);
insert into orcparamseq values (212,  3, 'ITR', 1, 0, 0, false, false, false, false, false, 'ITR', false, false, 3, 0, '', false, 1);
insert into orcparamseq values (212,  4, 'IPI Exportação', 1, 0, 0, false, false, false, false, false, 'IPI Exportação', false, false, 4, 0, '', false, 1);
insert into orcparamseq values (212,  5, 'ITCMD', 1, 0, 0, false, false, false, false, false, 'ITCMD', false, false, 5, 0, '', false, 1);
insert into orcparamseq values (212,  6, 'IPVA', 1, 0, 0, false, false, false, false, false, 'IPVA', false, false, 6, 0, '', false, 1);
insert into orcparamseq values (212,  7, 'ICMS', 1, 0, 0, false, false, false, false, false, 'ICMS', false, false, 7, 0, '', false, 1);
insert into orcparamseq values (212,  8, 'LEI 87/96', 1, 0, 0, false, false, false, false, false, 'LEI 87/96', false, false, 8, 0, '', false, 1);
insert into orcparamseq values (212,  9, 'Ajustes da União', 1, 0, 0, false, false, false, false, false, 'Ajustes da União', false, false, 9, 0, '', false, 1);
insert into orcparamseq values (212, 10, 'Subtotal', 1, 0, 0, false, false, false, false, false, 'Subtotal', false, true, 10, 0, '', false, 1);
insert into orcparamseq values (212, 11, 'Rend. Aplic. Financeiras', 1, 0, 0, false, false, false, false, false, 'Rend. Aplic. Financeiras', false, false, 11, 0, '', false, 1);
insert into orcparamseq values (212, 12, 'Receita Total', 1, 0, 0, false, false, false, false, false, 'Receita Total', false, true, 12, 0, '', false, 1);

update orcparamseq set o69_manual = true where o69_codparamrel = 212 and o69_codseq <= 9;
update orcparamseq set o69_manual = true where o69_codparamrel = 212 and o69_codseq = 11;


insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 17, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 18, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 19, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 20, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 21, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 22, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 28, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 23, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 24, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 25, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 26, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  141,  1, 27, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 22, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 27, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 26, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 25, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 24, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 23, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 21, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 20, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 19, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 18, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,  191,  2, 17, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 212,   36,  3, 27, '');



insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 17, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 18, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 19, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 20, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 21, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 22, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 28, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 23, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 24, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 25, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 26, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  141,  1, 27, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 22, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 27, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 26, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 25, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 24, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 23, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 21, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 20, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 19, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 18, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,  191,  2, 17, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 212,   36,  3, 27, '');



insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 17, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 18, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 19, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 20, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 21, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 22, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 28, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 23, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 24, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 25, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 26, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  141,  1, 27, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 22, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 27, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 26, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 25, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 24, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 23, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 21, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 20, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 19, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 18, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,  191,  2, 17, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 212,   36,  3, 27, '');



insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 17, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 18, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 19, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 20, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 21, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 22, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 28, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 23, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 24, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 25, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 26, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  141,  1, 27, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 22, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 27, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 26, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 25, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 24, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 23, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 21, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 20, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 19, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 18, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,  191,  2, 17, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 212,   36,  3, 27, '');



insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 17, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 18, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 19, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 20, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 21, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 22, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 28, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 23, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 24, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 25, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 26, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  141,  1, 27, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 22, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 27, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 26, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 25, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 24, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 23, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 21, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 20, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 19, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 18, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,  191,  2, 17, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 212,   36,  3, 27, '');



insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 17, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 18, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 19, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 20, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 21, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 22, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 28, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 23, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 24, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 25, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 26, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  141,  1, 27, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 22, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 27, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 26, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 25, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 24, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 23, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 21, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 20, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 19, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 18, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,  191,  2, 17, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 212,   36,  3, 27, '');



insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 17, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 18, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 19, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 20, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 21, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 22, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 28, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 23, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 24, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 25, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 26, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  141,  1, 27, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 22, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 27, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 26, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 25, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 24, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 23, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 21, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 20, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 19, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 18, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,  191,  2, 17, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 212,   36,  3, 27, '');



insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 17, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 18, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 19, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 20, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 21, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 22, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 28, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 23, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 24, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 25, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 26, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  141,  1, 27, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 22, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 27, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 26, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 25, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 24, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 23, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 21, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 20, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 19, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 18, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,  191,  2, 17, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 212,   36,  3, 27, '');



insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 17, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 18, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 19, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 20, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 21, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 22, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 28, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 23, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 24, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 25, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 26, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  141,  1, 27, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 22, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 27, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 26, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 25, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 24, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 23, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 21, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 20, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 19, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 18, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,  191,  2, 17, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 212,   36,  3, 27, '');



/* TOTALIZADORA */

insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 17, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 18, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 19, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 20, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 21, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 22, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 28, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 23, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 24, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 25, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 26, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  141,  1, 27, 'L[1]->mes +L[2]->mes +L[3]->mes +L[4]->mes +L[5]->mes +L[6]->mes +L[7]->mes +L[8]->mes +L[9]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 22, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 28, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 27, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 26, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 25, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 24, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 23, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 21, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 20, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 19, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 18, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,  191,  2, 17, 'L[1]->ano +L[2]->ano +L[3]->ano +L[4]->ano +L[5]->ano +L[6]->ano +L[7]->ano +L[8]->ano +L[9]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 212,   36,  3, 27, '');

insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 17, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 18, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 19, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 20, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 21, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 22, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 28, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 23, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 24, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 25, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 26, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  141,  1, 27, '#saldo_arrecadado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 22, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 28, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 27, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 26, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 25, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 24, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 23, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 21, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 20, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 19, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 18, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,  191,  2, 17, '#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 212,   36,  3, 27, '');


insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 17, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 18, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 19, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 20, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 21, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 22, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 28, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 23, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 24, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 25, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 26, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  141,  1, 27, 'L[10]->mes + L[11]->mes');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 22, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 28, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 27, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 26, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 25, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 24, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 23, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 21, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 20, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 19, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 18, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,  191,  2, 17, 'L[10]->ano + L[11]->ano');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 28, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 17, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 18, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 19, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 20, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 21, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 22, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 23, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 24, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 25, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 26, '');
insert into orcparamseqorcparamseqcoluna values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 212,   36,  3, 27, '');

SQL_UP
);
    }


}
