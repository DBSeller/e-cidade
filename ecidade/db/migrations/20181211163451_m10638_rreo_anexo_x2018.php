<?php

use Classes\PostgresMigration;

class M10638RreoAnexoX2018 extends PostgresMigration
{
    public function up() {
        $this->dicionarioUp(); 
    }

    public function down() {
        $this->dicionarioDown();
    }

    private function dicionarioUp() {
        $sql = "
            INSERT INTO orcparamrel VALUES (188, 'RREO - ANEXO X (2018)', 4, 'FONTE: Sistema E-Cidade, Unidade Responsável: [nome_departamento]. Emissão: [data_emissao], às [hora_emissao]. Assinado Digitalmente no dia [data_emissao], às [hora_emissao].');
            INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 11, 188);

            INSERT INTO orcparamseq VALUES 
               (188, 1, 'Plano Previdenciário', 0, 1, 0, false, false, false, false, false, 'Plano Previdenciário', true, false, 1, 0, '', false, NULL);

            INSERT INTO orcparamseq VALUES 
               (188, 2, 'Plano Financeiro', 0, 1, 0, false, false, false, false, false, 'Plano Financeiro', true, false, 1, 0, '', false, NULL);

            INSERT INTO orcparamseqorcparamseqcoluna VALUES 
               (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 188, 89, 1, 11, '')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 188, 90, 2, 11, '')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 188, 91, 3, 11, '');

            INSERT INTO orcparamseqorcparamseqcoluna VALUES 
               (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 188, 89, 1, 11, '')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 188, 90, 2, 11, '')
              ,(nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 188, 91, 3, 11, '');
        ";

        $this->execute($sql);
    }

    private function dicionarioDown() {
        
        $sql .= "
            DELETE
            FROM orcparamseqfiltropadrao
            WHERE o132_orcparamrel = 188;
            
            DELETE
            FROM orcparamseqorcparamseqcolunavalor
            WHERE o117_orcparamseqorcparamseqcoluna IN (SELECT o116_sequencial
                                                        FROM orcparamseqorcparamseqcoluna
                                                        WHERE o116_codparamrel = 188);
            
            DELETE
            FROM orcparamseqorcparamseqcoluna
            WHERE o116_codparamrel = 188;
            
            DELETE
            FROM orcparamseq
            WHERE o69_codparamrel = 188;
            
            DELETE
            FROM orcparamrelperiodos
            WHERE o113_orcparamrel = 188;
            
            DELETE
            FROM orcparamrel
            WHERE o42_codparrel = 188;
        ";

        $this->execute($sql);
    }

}
