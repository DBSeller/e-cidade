<?php

use Classes\PostgresMigration;

class M13694AdicionaFinsPrevidenciariosEsocial extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into
                avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo )
            values
                ( 4000252 ,1 ,3000160 ,'Dependente para fins previdenciários:' ,'depFinsPrev_1' ,'f' ,'t' ,8 ,1 ,'' ,0 ,'false' ,'' ,'depFinsPrev_1' ),
                ( 4000253 ,1 ,3000161 ,'Dependente para fins previdenciários:' ,'depFinsPrev_2' ,'f' ,'t' ,8 ,1 ,'' ,0 ,'false' ,'' ,'depFinsPrev_2' ),
                ( 4000254 ,1 ,3000162 ,'Dependente para fins previdenciários:' ,'depFinsPrev_3' ,'f' ,'t' ,8 ,1 ,'' ,0 ,'false' ,'' ,'depFinsPrev_3' ),
                ( 4000255 ,1 ,3000163 ,'Dependente para fins previdenciários:' ,'depFinsPrev_4' ,'f' ,'t' ,8 ,1 ,'' ,0 ,'false' ,'' ,'depFinsPrev_4' ),
                ( 4000256 ,1 ,3000164 ,'Dependente para fins previdenciários:' ,'depFinsPrev_5' ,'f' ,'t' ,8 ,1 ,'' ,0 ,'false' ,'' ,'depFinsPrev_5' ),
                ( 4000257 ,1 ,3000165 ,'Dependente para fins previdenciários:' ,'depFinsPrev_6' ,'f' ,'t' ,8 ,1 ,'' ,0 ,'false' ,'' ,'depFinsPrev_6' ),
                ( 4000258 ,1 ,3000166 ,'Dependente para fins previdenciários:' ,'depFinsPrev_7' ,'f' ,'t' ,8 ,1 ,'' ,0 ,'false' ,'' ,'depFinsPrev_7' ),
                ( 4000259 ,1 ,3000167 ,'Dependente para fins previdenciários:' ,'depFinsPrev_8' ,'f' ,'t' ,8 ,1 ,'' ,0 ,'false' ,'' ,'depFinsPrev_8' ),
                ( 4000260 ,1 ,3000168 ,'Dependente para fins previdenciários:' ,'depFinsPrev_9' ,'f' ,'t' ,8 ,1 ,'' ,0 ,'false' ,'' ,'depFinsPrev_9' ),
                ( 4000261 ,1 ,3000169 ,'Dependente para fins previdenciários:' ,'depFinsPrev_10' ,'f' ,'t' ,8 ,1 ,'' ,0 ,'false' ,'' ,'depFinsPrev_10' );

            insert into
                avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo )
            values
                ( 4001303 ,4000252 ,'Sim' ,'depFinsPrev_sim_1' ,'f' ,0 ,'S' ,'depFinsPrev_sim_1' ),
                ( 4001304 ,4000252 ,'Não' ,'depFinsPrev_nao_1' ,'f' ,0 ,'N' ,'depFinsPrev_nao_1' ),
                ( 4001305 ,4000253 ,'Sim' ,'depFinsPrev_sim_2' ,'f' ,0 ,'S' ,'depFinsPrev_sim_2' ),
                ( 4001306 ,4000253 ,'Não' ,'depFinsPrev_nao_2' ,'f' ,0 ,'N' ,'depFinsPrev_nao_2' ),
                ( 4001307 ,4000254 ,'Sim' ,'depFinsPrev_sim_3' ,'f' ,0 ,'S' ,'depFinsPrev_sim_3' ),
                ( 4001308 ,4000254 ,'Não' ,'depFinsPrev_nao_3' ,'f' ,0 ,'N' ,'depFinsPrev_nao_3' ),
                ( 4001309 ,4000255 ,'Sim' ,'depFinsPrev_sim_4' ,'f' ,0 ,'S' ,'depFinsPrev_sim_4' ),
                ( 4001310 ,4000255 ,'Não' ,'depFinsPrev_nao_4' ,'f' ,0 ,'N' ,'depFinsPrev_nao_4' ),
                ( 4001311 ,4000256 ,'Sim' ,'depFinsPrev_sim_5' ,'f' ,0 ,'S' ,'depFinsPrev_sim_5' ),
                ( 4001312 ,4000256 ,'Não' ,'depFinsPrev_nao_5' ,'f' ,0 ,'N' ,'depFinsPrev_nao_5' ),
                ( 4001313 ,4000257 ,'Sim' ,'depFinsPrev_sim_6' ,'f' ,0 ,'S' ,'depFinsPrev_sim_6' ),
                ( 4001314 ,4000257 ,'Não' ,'depFinsPrev_nao_6' ,'f' ,0 ,'N' ,'depFinsPrev_nao_6' ),
                ( 4001315 ,4000258 ,'Sim' ,'depFinsPrev_sim_7' ,'f' ,0 ,'S' ,'depFinsPrev_sim_7' ),
                ( 4001316 ,4000258 ,'Não' ,'depFinsPrev_nao_7' ,'f' ,0 ,'N' ,'depFinsPrev_nao_7' ),
                ( 4001317 ,4000259 ,'Sim' ,'depFinsPrev_sim_8' ,'f' ,0 ,'S' ,'depFinsPrev_sim_8' ),
                ( 4001318 ,4000259 ,'Não' ,'depFinsPrev_nao_8' ,'f' ,0 ,'N' ,'depFinsPrev_nao_8' ),
                ( 4001319 ,4000260 ,'Sim' ,'depFinsPrev_sim_9' ,'f' ,0 ,'S' ,'depFinsPrev_sim_9' ),
                ( 4001320 ,4000260 ,'Não' ,'depFinsPrev_nao_9' ,'f' ,0 ,'N' ,'depFinsPrev_nao_9' ),
                ( 4001321 ,4000261 ,'Sim' ,'depFinsPrev_sim_10' ,'f' ,0 ,'S' ,'depFinsPrev_sim_10' ),
                ( 4001322 ,4000261 ,'Não' ,'depFinsPrev_nao_10' ,'f' ,0 ,'N' ,'depFinsPrev_nao_10' );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

            delete from
                avaliacaogrupoperguntaresposta
            where
                db108_avaliacaoresposta in (
                    select
                        db106_sequencial
                    from
                        avaliacaoresposta
                    where
                        db106_avaliacaoperguntaopcao in (4001303,4001304,4001305,4001306,4001307,4001308,4001309,4001310,4001311,4001312,4001313,4001314,4001315,4001316,4001317,4001318,4001319,4001320,4001321,4001322)
                );

            delete from
                avaliacaoresposta
            where
                db106_avaliacaoperguntaopcao in (4001303,4001304,4001305,4001306,4001307,4001308,4001309,4001310,4001311,4001312,4001313,4001314,4001315,4001316,4001317,4001318,4001319,4001320,4001321,4001322);

            delete from
                avaliacaoperguntaopcao
            where
                db104_sequencial in (4001303,4001304,4001305,4001306,4001307,4001308,4001309,4001310,4001311,4001312,4001313,4001314,4001315,4001316,4001317,4001318,4001319,4001320,4001321,4001322);

            delete from
                avaliacaopergunta
            where
                db103_sequencial in (4000252,4000253,4000254,4000255,4000256,4000257,4000258,4000259,4000260,4000261);
SQL
        );

    }
}
