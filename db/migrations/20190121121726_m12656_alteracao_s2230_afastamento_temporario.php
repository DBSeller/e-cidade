<?php

use Classes\PostgresMigration;

class M12656AlteracaoS2230AfastamentoTemporario extends PostgresMigration
{
    /**
     *
     */
    public function up()
    {
        $this->disableAudit();
        $this->alterTable();
        $this->normalizeData();
        $this->meta();
    }

    private function disableAudit()
    {
        $this->execute("SELECT fc_putsession('__disable_audit__', 'on');");
    }

    private function alterTable()
    {
        $sql = "
            ALTER TABLE esocial.avaliacaogruporespostaafastamentoesocial
              ADD eso13_empregador int;
            
            ALTER TABLE esocial.avaliacaogruporespostaafastamentoesocial
              ADD eso13_avaliacao int;
            
            ALTER TABLE esocial.avaliacaogruporespostaafastamentoesocial
              ADD CONSTRAINT avaliacaogruporespostaafastamentoesocial_avaliacao_db101_sequencial_fk
                FOREIGN KEY (eso13_avaliacao) REFERENCES habitacao.avaliacao;
            
            ALTER TABLE esocial.avaliacaogruporespostaafastamentoesocial
              ADD CONSTRAINT avaliacaogruporespostaafastamentoesocial_cgm_z01_numcgm_fk
                FOREIGN KEY (eso13_empregador) REFERENCES protocolo.cgm (z01_numcgm);
        ";

        $this->execute($sql);
    }

    private function normalizeData()
    {
        $sql = "
            WITH sub AS (
              SELECT DISTINCT r70_numcgm AS cgm, eso13_sequencial
              FROM esocial.avaliacaogruporespostaafastamentoesocial
                     JOIN esocial.afastamentoservidoresocial ON eso12_sequencial = eso13_afastamentoservidoresocial
                     JOIN pessoal.rhpessoalmov ON rh02_regist = eso12_rhpessoal
                     JOIN pessoal.rhlota ON r70_codigo = rh02_lota AND r70_instit = rh02_instit
                     join protocolo.cgm on cgm.z01_numcgm = rhlota.r70_numcgm
            )
            UPDATE esocial.avaliacaogruporespostaafastamentoesocial
            SET eso13_empregador = sub.cgm
            FROM sub
            WHERE avaliacaogruporespostaafastamentoesocial.eso13_sequencial = sub.eso13_sequencial;
            
            WITH sub AS (
              SELECT DISTINCT db102_avaliacao AS avalicao, eso13_sequencial
              FROM esocial.avaliacaogruporespostaafastamentoesocial
                     JOIN habitacao.avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = eso13_avaliacaogruporesposta
                     JOIN habitacao.avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta
                     JOIN habitacao.avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao
                     JOIN habitacao.avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta
                     JOIN habitacao.avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial
            )
            UPDATE esocial.avaliacaogruporespostaafastamentoesocial
            SET eso13_avaliacao = sub.avalicao
            FROM sub
            WHERE avaliacaogruporespostaafastamentoesocial.eso13_sequencial = sub.eso13_sequencial;
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    private function meta()
    {
        $sql = "
            INSERT INTO db_syscampo
            VALUES (1010312, 'eso13_avaliacao', 'int8', 'Avaliação', '0', 'Avaliação', 8, 'f', 'f', 'f', 1, 'text', 'Avaliação'),
                   (1010313, 'eso13_empregador', 'int8', 'Empregador', '0', 'Empregador', 8, 'f', 'f', 'f', 1, 'text', 'Empregador');
            
            INSERT INTO db_sysarqcamp
            VALUES (1010284, 1010313, 4, 0),
                   (1010284, 1010312, 5, 0);
            
            INSERT INTO db_sysforkey
            VALUES (1010284, 1010313, 1, 42, 0),
                   (1010284, 1010312, 1, 2980, 0);
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    public function down()
    {
        $this->downAlterTable();
        $this->downMeta();
    }

    private function downAlterTable()
    {
        $sql = "
            ALTER TABLE esocial.avaliacaogruporespostaafastamentoesocial
              DROP CONSTRAINT avaliacaogruporespostaafastamentoesocial_avaliacao_db101_sequencial_fk;
            
            ALTER TABLE esocial.avaliacaogruporespostaafastamentoesocial
              DROP COLUMN eso13_avaliacao;
            
            ALTER TABLE esocial.avaliacaogruporespostaafastamentoesocial
              DROP CONSTRAINT avaliacaogruporespostaafastamentoesocial_cgm_z01_numcgm_fk;
            
            ALTER TABLE esocial.avaliacaogruporespostaafastamentoesocial
              DROP COLUMN eso13_empregador;
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    private function downMeta()
    {
        $sql = "
            DELETE 
            FROM db_sysforkey 
            WHERE codcam IN (1010312, 1010313);

            DELETE 
            FROM db_sysarqcamp 
            WHERE codcam IN (1010312, 1010313);

            DELETE 
            FROM db_syscampo 
            WHERE codcam IN (1010312, 1010313);
        ";

        $this->execute($sql);
    }
}
