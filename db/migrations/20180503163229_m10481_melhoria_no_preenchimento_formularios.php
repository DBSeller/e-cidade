<?php

use Classes\PostgresMigration;

class M10481MelhoriaNoPreenchimentoFormularios extends PostgresMigration
{
    public function up()
    {
        // remove obrigatoriedade das perguntas dos grupos abaixo
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = false where db103_avaliacaogrupopergunta in (3000199, 3000201, 3000206, 3000207, 3000208, 3000194, 3000224, 3000225);
            update habitacao.avaliacao set db101_descricao = 'S2200 - Cadastramento Inicial Vínculo e Admissão' where db101_sequencial = 3000013;
            update habitacao.avaliacao set db101_descricao = 'S1030 - Tabela de Cargos' where db101_sequencial = 3000017;
            update habitacao.avaliacao set db101_descricao = 'S1040 - Tabela de Funções' where db101_sequencial = 3000018;
            update habitacao.avaliacao set db101_descricao = 'S1020 - Lotação Tributária' where db101_sequencial = 3000014;
            update habitacao.avaliacao set db101_descricao = 'S1000/S1005 - Empregador/Obras' where db101_sequencial = 3000015;
            update habitacao.avaliacao set db101_descricao = 'S1010 - Tabela de Rubricas' where db101_sequencial = 3000016;
            update habitacao.avaliacao set db101_descricao = 'S1070 - Tabela de Processos' where db101_sequencial = 3000020;
            update habitacao.avaliacao set db101_descricao = 'S1050 - Tabela de Horários' where db101_sequencial = 3000019;
            update habitacao.avaliacao set db101_descricao = 'S2260 - Convocação para Trabalho Intermitente' where db101_sequencial = 3000021;
            update habitacao.avaliacao set db101_descricao = 'S2250 - Aviso Prévio' where db101_sequencial = 3000022;
        ");
    }

    public function down()
    {
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = true where db103_sequencial in (3000966, 3000967, 3000968, 3000969, 3000970, 3000971, 3000869, 3000870, 3000871);
            update habitacao.avaliacao set db101_descricao = 'Formulários S2190 e S2200 v2.3' where db101_sequencial = 3000013;
            update habitacao.avaliacao set db101_descricao = 'Tabela de Cargos s1030 v.2.4' where db101_sequencial = 3000017;
            update habitacao.avaliacao set db101_descricao = 'Tabela de Funções s1040 v2.4' where db101_sequencial = 3000018;
            update habitacao.avaliacao set db101_descricao = 'Formulário S1020 v2.4.01_Beta' where db101_sequencial = 3000014;
            update habitacao.avaliacao set db101_descricao = 'Formulários S1000 e S1005 v2.4.01_Beta' where db101_sequencial = 3000015;
            update habitacao.avaliacao set db101_descricao = 'Formulário S1010 - TABELA DE RUBRICAS v2.4.01_Beta' where db101_sequencial = 3000016;
            update habitacao.avaliacao set db101_descricao = 'Tabela de Processos s1070 v2.4' where db101_sequencial = 3000020;
            update habitacao.avaliacao set db101_descricao = 'Tabela de Horarios s1050 v2.4' where db101_sequencial = 3000019;
            update habitacao.avaliacao set db101_descricao = 'Formulário S-2190 - Registro Preliminar' where db101_sequencial = 3000021;
            update habitacao.avaliacao set db101_descricao = 'Aviso Prévio - S-2250 - 2.4.02' where db101_sequencial = 3000022; 
        ");
    }
}
