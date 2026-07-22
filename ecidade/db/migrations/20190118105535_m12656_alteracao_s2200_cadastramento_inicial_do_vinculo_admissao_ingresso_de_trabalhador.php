<?php

use Classes\PostgresMigration;

/**
 * Class M12656AlteracaoS2200CadastramentoInicialDoVinculoAdmissaoIngressoDeTrabalhador
 */
class M12656AlteracaoS2200CadastramentoInicialDoVinculoAdmissaoIngressoDeTrabalhador extends PostgresMigration
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

    /**
     *
     */
    private function disableAudit()
    {
        $this->execute("SELECT fc_putsession('__disable_audit__', 'on');");
    }

    /**
     *
     */
    private function alterTable()
    {
        $sql = "
            ALTER TABLE esocial.avaliacaogruporespostarhpessoal
              ADD eso02_empregador int;
            
            ALTER TABLE esocial.avaliacaogruporespostarhpessoal
              ADD eso02_avaliacao int;
            
            ALTER TABLE esocial.avaliacaogruporespostarhpessoal
              ADD CONSTRAINT avaliacaogruporespostarhpessoal_avaliacao_db101_sequencial_fk
                FOREIGN KEY (eso02_avaliacao) REFERENCES habitacao.avaliacao;
            
            ALTER TABLE esocial.avaliacaogruporespostarhpessoal
              ADD CONSTRAINT avaliacaogruporespostarhpessoal_cgm_z01_numcgm_fk
                FOREIGN KEY (eso02_empregador) REFERENCES protocolo.cgm (z01_numcgm);
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    private function normalizeData()
    {
        $sql = "
            WITH sub AS (
              SELECT DISTINCT r70_numcgm AS cgm, eso02_sequencial
              FROM esocial.avaliacaogruporespostarhpessoal
                     JOIN pessoal.rhpessoalmov ON rh02_regist = eso02_rhpessoal
                     JOIN pessoal.rhlota ON r70_codigo = rh02_lota AND r70_instit = rh02_instit
                     join protocolo.cgm on cgm.z01_numcgm = rhlota.r70_numcgm
            )
            UPDATE esocial.avaliacaogruporespostarhpessoal
            SET eso02_empregador = sub.cgm
            FROM sub
            WHERE avaliacaogruporespostarhpessoal.eso02_sequencial = sub.eso02_sequencial;
            
            WITH sub AS (
              SELECT DISTINCT db102_avaliacao AS avalicao, eso02_sequencial
              FROM esocial.avaliacaogruporespostarhpessoal
                     JOIN habitacao.avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = eso02_avaliacaogruporesposta
                     JOIN habitacao.avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta
                     JOIN habitacao.avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao
                     JOIN habitacao.avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta
                     JOIN habitacao.avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial
            )
            UPDATE esocial.avaliacaogruporespostarhpessoal
            SET eso02_avaliacao = sub.avalicao
            FROM sub
            WHERE avaliacaogruporespostarhpessoal.eso02_sequencial = sub.eso02_sequencial;
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

    /**
     *
     */
    private function downAlterTable()
    {
        $sql = "
            ALTER TABLE esocial.avaliacaogruporespostarhpessoal
              DROP CONSTRAINT avaliacaogruporespostarhpessoal_avaliacao_db101_sequencial_fk;
            
            ALTER TABLE esocial.avaliacaogruporespostarhpessoal
              DROP COLUMN eso02_avaliacao;
            
            ALTER TABLE esocial.avaliacaogruporespostarhpessoal
              DROP CONSTRAINT avaliacaogruporespostarhpessoal_cgm_z01_numcgm_fk;
            
            ALTER TABLE esocial.avaliacaogruporespostarhpessoal
              DROP COLUMN eso02_empregador;
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
            VALUES (1010308, 'eso02_avaliacao', 'int8', 'Avaliação', '0', 'Avaliação', 8, 'f', 'f', 'f', 1, 'text', 'Avaliação'),
                   (1010309, 'eso02_empregador', 'int8', 'Empregador', '0', 'Empregador', 8, 'f', 'f', 'f', 1, 'text', 'Empregador');
            
            INSERT INTO db_sysarqcamp
            VALUES (3924, 1010309, 4, 0),
                   (3924, 1010308, 5, 0);
            
            INSERT INTO db_sysforkey
            VALUES (3924, 1010309, 1, 42, 0),
                   (3924, 1010308, 1, 2980, 0);
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
            WHERE codcam IN (1010308, 1010309);

            DELETE 
            FROM db_sysarqcamp 
            WHERE codcam IN (1010308, 1010309);

            DELETE 
            FROM db_syscampo 
            WHERE codcam IN (1010308, 1010309);
        ";

        $this->execute($sql);
    }
}
