<?php

use Classes\PostgresMigration;

class M10640RgfAnexoV2018 extends PostgresMigration
{
    public function up()
    {
        $this->atualizarMenu();
        $this->incluirRelatorio();
        $this->incluirPeriodo();
        $this->incluirLinha();
        $this->incluirColuna();
        $this->reordenar();
    }

    private function incluirRelatorio()
    {
        $sql = "
            INSERT INTO orcparamrel
            VALUES (187, 'RGF - ANEXO V (2018)', 4, '');
        ";

        $this->execute($sql);
    }

    private function incluirPeriodo()
    {
        $sql = "
            INSERT INTO orcparamrelperiodos
            VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 13, 187);
        ";

        $this->execute($sql);
    }

    private function incluirLinha()
    {
        $sql = "
            INSERT INTO orcparamseq
            VALUES (187, 1, 'TOTAL DOS RECURSOS VINCULADOS (I)', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'TOTAL DOS RECURSOS VINCULADOS (I)', FALSE, TRUE, 1, 1, '', FALSE, NULL),
                   (187, 2, 'Receitas de Impostos e de Transferência de Impostos - Educa', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Receitas de Impostos e de Transferência de Impostos - Educação', TRUE, FALSE, 2, 2, '', FALSE, NULL),
                   (187, 3, 'Transferências do FUNDEB 60%', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Transferências do FUNDEB 60%', TRUE, FALSE, 3, 2, '', FALSE, NULL),
                   (187, 4, 'Transferências do FUNDEB 40%', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Transferências do FUNDEB 40%', TRUE, FALSE, 4, 2, '', FALSE, NULL),
                   (187, 5, 'Outros Recursos Destinados à Educação', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Outros Recursos Destinados à Educação', TRUE, FALSE, 5, 2, '', FALSE, NULL),
                   (187, 6, 'Receitas de Impostos e de Transferência de Impostos - Saúde', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Receitas de Impostos e de Transferência de Impostos - Saúde', TRUE, FALSE, 6, 2, '', FALSE, NULL),
                   (187, 7, 'Outros Recursos Destinados à Saúde', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Outros Recursos Destinados à Saúde', TRUE, FALSE, 7, 2, '', FALSE, NULL),
                   (187, 8, 'Recursos Destinados à Assistência Social', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Recursos Destinados à Assistência Social', TRUE, FALSE, 8, 2, '', FALSE, NULL),
                   (187, 9, 'Recursos destinados ao RPPS - Plano Previdenciário', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Recursos destinados ao RPPS - Plano Previdenciário', TRUE, FALSE, 9, 2, '', FALSE, NULL),
                   (187, 10, 'Recursos destinados ao RPPS - Plano Financeiro', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Recursos destinados ao RPPS - Plano Financeiro', TRUE, FALSE, 10, 2, '', FALSE, NULL),
                   (187, 11, 'Recursos de Operações de Crédito (exceto destinados à Educa', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Recursos de Operações de Crédito (exceto destinados à Educação e à Saúde)', TRUE, FALSE, 11, 2, '', FALSE, NULL),
                   (187, 12, 'Recursos de Alienação de Bens/Ativos', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Recursos de Alienação de Bens/Ativos', TRUE, FALSE, 12, 2, '', FALSE, NULL),
                   (187, 13, 'Outras Destinações Vinculadas de Recursos', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Outras Destinações Vinculadas de Recursos', TRUE, FALSE, 13, 2, '', FALSE, NULL),
                   (187, 14, 'TOTAL DOS RECURSOS NÃO VINCULADOS (II)', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'TOTAL DOS RECURSOS NÃO VINCULADOS (II)', FALSE, TRUE, 14, 1, '', FALSE, NULL),
                   (187, 15, 'Recursos Ordinários', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Recursos Ordinários', TRUE, FALSE, 15, 2, '', FALSE, NULL),
                   (187, 16, 'TOTAL (III) = (I + II)', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'TOTAL (III) = (I + II)', FALSE, TRUE, 16, 1, '', FALSE, NULL),
                   (187, 17, 'OUTROS RECURSOS NÃO VINCULADOS', 0, 0, 0, FALSE, FALSE, FALSE, FALSE, TRUE, 'Outros Recursos não Vinculados', TRUE, FALSE, 0, 2, '', FALSE, 0)
        ";

        $this->execute($sql);
    }

    private function incluirColuna()
    {
        $sql = "
            INSERT INTO orcparamseqorcparamseqcoluna
            VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 187, 304, 9, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 187, 201, 1, 13, '(L[2]->disp_caixa + L[3]->disp_caixa + L[4]->disp_caixa + L[5]->disp_caixa + L[6]->disp_caixa + L[7]->disp_caixa + L[8]->disp_caixa + L[9]->disp_caixa + L[10]->disp_caixa + L[11]->disp_caixa + L[12]->disp_caixa + L[13]->disp_caixa)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 187, 179, 2, 13, '(L[2]->exanterior + L[3]->exanterior + L[4]->exanterior + L[5]->exanterior + L[6]->exanterior + L[7]->exanterior + L[8]->exanterior + L[9]->exanterior + L[10]->exanterior + L[11]->exanterior + L[12]->exanterior + L[13]->exanterior)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 187, 177, 3, 13, '(L[2]->vlrexatual + L[3]->vlrexatual + L[4]->vlrexatual + L[5]->vlrexatual + L[6]->vlrexatual + L[7]->vlrexatual + L[8]->vlrexatual + L[9]->vlrexatual + L[10]->vlrexatual + L[11]->vlrexatual + L[12]->vlrexatual + L[13]->vlrexatual)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 187, 189, 4, 13, '(L[2]->rp_nprocexant + L[3]->rp_nprocexant + L[4]->rp_nprocexant + L[5]->rp_nprocexant + L[6]->rp_nprocexant + L[7]->rp_nprocexant + L[8]->rp_nprocexant + L[9]->rp_nprocexant + L[10]->rp_nprocexant + L[11]->rp_nprocexant + L[12]->rp_nprocexant + L[13]->rp_nprocexant)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 187, 202, 5, 13, '(L[2]->financeira + L[3]->financeira + L[4]->financeira + L[5]->financeira + L[6]->financeira + L[7]->financeira + L[8]->financeira + L[9]->financeira + L[10]->financeira + L[11]->financeira + L[12]->financeira + L[13]->financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 187, 300, 6, 13, '(L[2]->insuficiencia_financeira + L[3]->insuficiencia_financeira + L[4]->insuficiencia_financeira + L[5]->insuficiencia_financeira + L[6]->insuficiencia_financeira + L[7]->insuficiencia_financeira + L[8]->insuficiencia_financeira + L[9]->insuficiencia_financeira + L[10]->insuficiencia_financeira + L[11]->insuficiencia_financeira + L[12]->insuficiencia_financeira + L[13]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 187, 301, 7, 13, '(L[2]->disp_caixa_liquida + L[3]->disp_caixa_liquida + L[4]->disp_caixa_liquida + L[5]->disp_caixa_liquida + L[6]->disp_caixa_liquida + L[7]->disp_caixa_liquida + L[8]->disp_caixa_liquida + L[9]->disp_caixa_liquida + L[10]->disp_caixa_liquida + L[11]->disp_caixa_liquida + L[12]->disp_caixa_liquida + L[13]->disp_caixa_liquida)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 187, 303, 8, 13, '(L[2]->rp_empenhado_nao_processado + L[3]->rp_empenhado_nao_processado + L[4]->rp_empenhado_nao_processado + L[5]->rp_empenhado_nao_processado + L[6]->rp_empenhado_nao_processado + L[7]->rp_empenhado_nao_processado + L[8]->rp_empenhado_nao_processado + L[9]->rp_empenhado_nao_processado + L[10]->rp_empenhado_nao_processado + L[11]->rp_empenhado_nao_processado + L[12]->rp_empenhado_nao_processado + L[13]->rp_empenhado_nao_processado)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 187, 304, 9, 13, '(L[2]->empenho_nao_liquidado_cancelado + L[3]->empenho_nao_liquidado_cancelado + L[4]->empenho_nao_liquidado_cancelado + L[5]->empenho_nao_liquidado_cancelado + L[6]->empenho_nao_liquidado_cancelado + L[7]->empenho_nao_liquidado_cancelado + L[8]->empenho_nao_liquidado_cancelado + L[9]->empenho_nao_liquidado_cancelado + L[10]->empenho_nao_liquidado_cancelado + L[11]->empenho_nao_liquidado_cancelado + L[12]->empenho_nao_liquidado_cancelado + L[13]->empenho_nao_liquidado_cancelado)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 187, 301, 7, 13, '(L[2]->disp_caixa - (L[2]->exanterior + L[2]->vlrexatual + L[2]->rp_nprocexant + L[2]->financeira) - L[2]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 187, 301, 7, 13, '(L[3]->disp_caixa - (L[3]->exanterior + L[3]->vlrexatual + L[3]->rp_nprocexant + L[3]->financeira) - L[3]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 187, 301, 7, 13, '(L[4]->disp_caixa - (L[4]->exanterior + L[4]->vlrexatual + L[4]->rp_nprocexant + L[4]->financeira) - L[4]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 187, 301, 7, 13, '(L[5]->disp_caixa - (L[5]->exanterior + L[5]->vlrexatual + L[5]->rp_nprocexant + L[5]->financeira) - L[5]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 187, 301, 7, 13, '(L[6]->disp_caixa - (L[6]->exanterior + L[6]->vlrexatual + L[6]->rp_nprocexant + L[6]->financeira) - L[6]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 187, 301, 7, 13, '(L[7]->disp_caixa - (L[7]->exanterior + L[7]->vlrexatual + L[7]->rp_nprocexant + L[7]->financeira) - L[7]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 187, 301, 7, 13, '(L[8]->disp_caixa - (L[8]->exanterior + L[8]->vlrexatual + L[8]->rp_nprocexant + L[8]->financeira) - L[8]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 187, 301, 7, 13, '(L[9]->disp_caixa - (L[9]->exanterior + L[9]->vlrexatual + L[9]->rp_nprocexant + L[9]->financeira) - L[9]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 187, 301, 7, 13, '(L[10]->disp_caixa - (L[10]->exanterior + L[10]->vlrexatual + L[10]->rp_nprocexant + L[10]->financeira) - L[10]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 187, 301, 7, 13, '(L[11]->disp_caixa - (L[11]->exanterior + L[11]->vlrexatual + L[11]->rp_nprocexant + L[11]->financeira) - L[11]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 187, 301, 7, 13, '(L[12]->disp_caixa - (L[12]->exanterior + L[12]->vlrexatual + L[12]->rp_nprocexant + L[12]->financeira) - L[12]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 187, 301, 7, 13, '(L[13]->disp_caixa - (L[13]->exanterior + L[13]->vlrexatual + L[13]->rp_nprocexant + L[13]->financeira) - L[13]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 187, 201, 1, 13, 'L[15]->disp_caixa + L[16]->disp_caixa'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 187, 179, 2, 13, 'L[15]->exanterior + L[16]->exanterior'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 187, 177, 3, 13, 'L[15]->vlrexatual + L[16]->vlrexatual'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 187, 189, 4, 13, 'L[15]->rp_nprocexant + L[16]->rp_nprocexant'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 187, 202, 5, 13, 'L[15]->financeira + L[16]->financeira'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 187, 300, 6, 13, 'L[15]->insuficiencia_financeira + L[16]->insuficiencia_financeira'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 187, 301, 7, 13, 'L[15]->disp_caixa_liquida + L[16]->disp_caixa_liquida'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 187, 303, 8, 13, 'L[15]->rp_empenhado_nao_processado + L[16]->rp_empenhado_nao_processado'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 187, 304, 9, 13, 'L[15]->empenho_nao_liquidado_cancelado + L[16]->empenho_nao_liquidado_cancelado'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 187, 301, 7, 13, '(L[15]->disp_caixa - (L[15]->exanterior + L[15]->vlrexatual + L[15]->rp_nprocexant + L[15]->financeira) - L[15]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 187, 201, 1, 13, '(F[1] + F[14])'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 187, 179, 2, 13, '(F[1] + F[14])'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 187, 177, 3, 13, '(F[1] + F[14])'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 187, 189, 4, 13, '(F[1] + F[14])'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 187, 202, 5, 13, '(F[1] + F[14])'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 187, 300, 6, 13, '(F[1] + F[14])'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 187, 301, 7, 13, '(F[1] + F[14])'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 187, 303, 8, 13, '(F[1] + F[14])'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 187, 304, 9, 13, '(F[1] + F[14])'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 187, 201, 1, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 187, 179, 2, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 187, 177, 3, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 187, 189, 4, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 187, 202, 5, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 187, 300, 6, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 187, 301, 7, 13, '(L[16]->disp_caixa - (L[16]->exanterior + L[16]->vlrexatual + L[16]->rp_nprocexant + L[16]->financeira) - L[16]->insuficiencia_financeira)'),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 187, 303, 8, 13, ''),
                   (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 187, 304, 9, 13, '');
        ";

        $this->execute($sql);
    }

    private function reordenar()
    {
        $sql = "
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 1,
                o69_ordem       = 1
            WHERE o69_codparamrel = 187
              AND o69_codseq = 1;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 2,
                o69_ordem       = 2
            WHERE o69_codparamrel = 187
              AND o69_codseq = 2;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 3,
                o69_ordem       = 3
            
            WHERE o69_codparamrel = 187
              AND o69_codseq = 3;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 4,
                o69_ordem       = 4
            WHERE o69_codparamrel = 187
              AND o69_codseq = 4;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 5,
                o69_ordem       = 5
            WHERE o69_codparamrel = 187
              AND o69_codseq = 5;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 6,
                o69_ordem       = 6
            WHERE o69_codparamrel = 187
              AND o69_codseq = 6;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 7,
                o69_ordem       = 7
            WHERE o69_codparamrel = 187
              AND o69_codseq = 7;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 8,
                o69_ordem       = 8
            WHERE o69_codparamrel = 187
              AND o69_codseq = 8;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 9,
                o69_ordem       = 9
            WHERE o69_codparamrel = 187
              AND o69_codseq = 9;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 10,
                o69_ordem       = 10
            WHERE o69_codparamrel = 187
              AND o69_codseq = 10;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 11,
                o69_ordem       = 11
            WHERE o69_codparamrel = 187
              AND o69_codseq = 11;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 12,
                o69_ordem       = 12
            WHERE o69_codparamrel = 187
              AND o69_codseq = 12;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 13,
                o69_ordem       = 13
            WHERE o69_codparamrel = 187
              AND o69_codseq = 13;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 14,
                o69_ordem       = 14
            WHERE o69_codparamrel = 187
              AND o69_codseq = 14;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 15,
                o69_ordem       = 15
            WHERE o69_codparamrel = 187
              AND o69_codseq = 15;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 17,
                o69_ordem       = 16
            WHERE o69_codparamrel = 187
              AND o69_codseq = 17;
            
            UPDATE orcparamseq
            SET o69_codparamrel = 187,
                o69_codseq      = 16,
                o69_ordem       = 17
            WHERE o69_codparamrel = 187
              AND o69_codseq = 16;        
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE 
            FROM orcparamseqfiltroorcamento 
            WHERE o133_orcparamrel = 187;

            DELETE
            FROM orcparamseqfiltropadrao
            WHERE o132_orcparamrel = 187;
            
            DELETE
            FROM orcparamseqorcparamseqcolunavalor
            WHERE o117_orcparamseqorcparamseqcoluna IN (SELECT o116_sequencial
                                                        FROM orcparamseqorcparamseqcoluna
                                                        WHERE o116_codparamrel = 187);
            
            DELETE
            FROM orcparamseqorcparamseqcoluna
            WHERE o116_codparamrel = 187;
            
            DELETE
            FROM orcparamseq
            WHERE o69_codparamrel = 187;
            
            DELETE
            FROM orcparamrelperiodos
            WHERE o113_orcparamrel = 187;
            
            DELETE
            FROM orcparamrel
            WHERE o42_codparrel = 187;
       ";

        $this->execute($sql);
    }

    private function atualizarMenu()
    {
        $sql = "
            UPDATE db_itensmenu
            SET descricao = 'Anexo V - Demonstrativo da Disponibilidade de Caixa e dos Restos a Pagar',
                desctec   = 'Anexo V - Demonstrativo da Disponibilidade de Caixa e dos Restos a Pagar'
            WHERE id_item = 10187;

            UPDATE db_menu
            SET menusequencia = 1
            WHERE id_item = 8113
              AND modulo = 209
              AND id_item_filho = 8114;
            
            UPDATE db_menu
            SET menusequencia = 2
            WHERE id_item = 8113
              AND modulo = 209
              AND id_item_filho = 8115;
            
            UPDATE db_menu
            SET menusequencia = 3
            WHERE id_item = 8113
              AND modulo = 209
              AND id_item_filho = 8121;
            
            UPDATE db_menu
            SET menusequencia = 4
            WHERE id_item = 8113
              AND modulo = 209
              AND id_item_filho = 8124;
            
            UPDATE db_menu
            SET menusequencia = 5
            WHERE id_item = 8113
              AND modulo = 209
              AND id_item_filho = 8700;
            
            UPDATE db_menu
            SET menusequencia = 6
            WHERE id_item = 8113
              AND modulo = 209
              AND id_item_filho = 10187;
            
            UPDATE db_menu
            SET menusequencia = 7
            WHERE id_item = 8113
              AND modulo = 209
              AND id_item_filho = 8701;
            
            UPDATE db_menu
            SET menusequencia = 8
            WHERE id_item = 8113
              AND modulo = 209
              AND id_item_filho = 10077;
            
            UPDATE db_menu
            SET menusequencia = 9
            WHERE id_item = 8113
              AND modulo = 209
              AND id_item_filho = 8125;
        ";

        $this->execute($sql);
    }
}
