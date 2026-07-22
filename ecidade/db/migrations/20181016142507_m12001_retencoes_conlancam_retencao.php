<?php

use Classes\PostgresMigration;

class M12001RetencoesConlancamRetencao extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute("insert into db_sysarquivo values (1010328, 'conlancamretencao', 'Retençao vinculada ao lançamento', 'c127', '2018-10-16', 'Retençao vinculada ao lançamento', 0, 'f', 'f', 'f', 'f' );");
        $this->execute("insert into db_sysarqmod values (32,1010328);");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010016 ,'c127_sequencial' ,'int4' ,'Código Sequencial' ,'' ,'Código Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código Sequencial' );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010017 ,'c127_conlancam' ,'int4' ,'Código do Lancamento' ,'' ,'Código do Lancamento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código do Lancamento' );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010018 ,'c127_retencaotiporec' ,'int4' ,'Retenção' ,'' ,'Retenção' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Retenção' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010328 ,1010016 ,1 ,0 );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010328 ,1010017 ,2 ,0 );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010328 ,1010018 ,3 ,0 );");
        $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010328,1010016,1,1010017);");
        $this->execute("insert into db_sysforkey values(1010328,1010017,1,760,0);");
        $this->execute("insert into db_sysindices values(1008334,'conlancamretencao_retencao_in',1010328,'0');");
        $this->execute("insert into db_sysindices values(1008335,'conlancamretencao_conlancam_on',1010328,'0');");
        $this->execute("insert into db_syscadind values(1008335,1010017,1);");
        $this->execute("insert into db_syssequencia values(1000772, 'conlancamretencao_c127_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);");
        $this->execute("update db_sysarqcamp set codsequencia = 1000772 where codarq = 1010328 and codcam = 1010016;");

        $this->execute(
            <<<SQL
            CREATE SEQUENCE contabilidade.conlancamretencao_c127_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            CREATE TABLE contabilidade.conlancamretencao(
            c127_sequencial		 int4  default nextval('conlancamretencao_c127_sequencial_seq'),
            c127_conlancam		 int4  not null,
            c127_retencaotiporec int4  not null,
            CONSTRAINT conlancamretencao_sequ_pk PRIMARY KEY (c127_sequencial));
            
          ALTER TABLE contabilidade.conlancamretencao
        ADD CONSTRAINT conlancamretencao_retencaotiporec_fk FOREIGN KEY (c127_retencaotiporec)
        REFERENCES retencaotiporec;
        
        ALTER TABLE contabilidade.conlancamretencao
        ADD CONSTRAINT conlancamretencao_conlancam_fk FOREIGN KEY (c127_conlancam)
        REFERENCES conlancam;  
                    
        CREATE  INDEX conlancamretencao_retencao_in ON contabilidade.conlancamretencao(c127_retencaotiporec);
        
        CREATE  INDEX conlancamretencao_conlancam_on ON contabilidade.conlancamretencao(c127_conlancam);



SQL
        );

    }

    public function down()
    {

        $this->execute("DROP TABLE IF EXISTS contabilidade.conlancamretencao;");
        $this->execute("DROP SEQUENCE IF EXISTS contabilidade.conlancamretencao_c127_sequencial_seq;");
        $this->execute("delete from db_sysforkey where codarq = 1010328");
        $this->execute("delete from db_sysprikey where codarq = 1010328;");
        $this->execute("delete from db_syscadind where codind in(1008334,1008335 )");
        $this->execute("delete from db_sysindices where codind in(1008334,1008335)");
        $this->execute("delete from db_sysarqcamp where codcam in(1010016, 1010017, 1010018) ");
        $this->execute("delete from db_syscampo where codcam in(1010016, 1010017, 1010018) ");
        $this->execute("delete from db_syssequencia where codsequencia in(1000772) ");
        $this->execute("delete from db_sysarqmod where codarq in(1010328) ");
        $this->execute("delete from db_sysarquivo where codarq in(1010328) ");
        
        
        

    }
}
