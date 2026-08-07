<?php

use Classes\PostgresMigration;

class M16146TabelaCamposandpadrao extends PostgresMigration
{
    public function up()
    {
        $this->upDicionarioDados();
       $this->upDDL();

    }
    
    public function upDDL()
    {
        $this->execute("CREATE SEQUENCE protocolo.camposandpadrao_p110_sequencial_seq");

        $tabela = $this->table('camposandpadrao', [
            'schema' => 'protocolo',
            'id' => 'p110_sequencial',
            'primary_key' => [
                'p110_sequencial'
            ],
            'constraint' =>' camposandpadrao_p110_sequencial_pk'
        ]);
        $tabela
            ->addColumn('p110_andpadrao_codigo', 'integer')
            ->addColumn('p110_andpadrao_ordem',  'integer')
            ->addColumn('p110_codcam',           'integer')
            ->addColumn('p110_obrigatorio',      'boolean',  ['default' => 'f'])
            ->addForeignKey(
                [
                    'p110_andpadrao_codigo',
                    'p110_andpadrao_ordem'
                ],
                'protocolo.andpadrao',
                [
                    'p53_codigo',
                    'p53_ordem'
                ]
            )
            ->addForeignKey('p110_codcam',    'configuracoes.db_syscampo',  'codcam')
            ->addIndex(
                [
                    'p110_andpadrao_codigo',
                    'p110_andpadrao_ordem'
                ],
                [
                    'name' => 'camposandpadrao_andpadrao_in'
                ]
            )
            ->addIndex(
                [
                    'p110_codcam'
                ],
                [
                    'name' => 'camposandpadrao_codcam_in'
                ]
            )
            ->create();

        $this->execute("
            ALTER TABLE protocolo.camposandpadrao 
                ALTER COLUMN p110_sequencial 
                SET DEFAULT nextval('protocolo.camposandpadrao_p110_sequencial_seq')
        ");
    }

    public function upDicionarioDados()
    {
        $sqlDicionario = <<<SQL_DICIONARIO_UP
            insert into db_sysarquivo values (1010595, 'camposandpadrao', 'Tabela que vincula campos do sistema ao andamento padrão, para que seja possivel preencher informações ao fazer um despacho no processo', 'p110', '2020-07-02', 'camposandpadrao', 2, 'f', 't', 't', 't' );

            insert into db_sysarqmod values (4,1010595);

            insert into db_syscampo values(1011644,'p110_sequencial','int8','Código sequencial da tabela que serve de primary key e facilita manutenção na tabela.','0', 'Código',19,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1011645,'p110_andpadrao','int8','Andamento padrão ao qual o campo está vinculado','0', 'Andamento Padrão',19,'f','f','f',1,'text','Andamento Padrão');
            update db_syscampo set nomecam = 'p110_andpadrao_codigo', conteudo = 'int8', descricao = 'Tipo de processo do andamento padrão ao qual o campo está vinculado', valorinicial = '0', rotulo = 'Tipo Processo', nulo = 'f', tamanho = 19, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo Processo' where codcam = 1011645;
            insert into db_syscampo values(1011646,'p110_codcam','int8','Código do campo que está vinculado ao andamento padrão','0', 'Campo',19,'f','f','f',1,'text','Campo');
            insert into db_syscampo values(1011647,'p110_obrigatorio','bool','Informa se o campo terá o preenchimento obrigatório ou não','f', 'Obrigatório',1,'f','f','f',5,'text','Obrigatório');

            insert into db_syscampodef values(1011647,'false','');
            insert into db_syscampo values(1011648,'p110_andpadrao_ordem','int8','Ordem do andamento padrão','0', 'Ordem',19,'f','f','f',1,'text','Ordem');

            insert into db_sysarqcamp values(1010595,1011644,1,0);
            insert into db_sysarqcamp values(1010595,1011645,2,0);
            insert into db_sysarqcamp values(1010595,1011648,3,0);
            insert into db_sysarqcamp values(1010595,1011646,4,0);
            insert into db_sysarqcamp values(1010595,1011647,5,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010595,1011644,1,1011644);

            insert into db_sysforkey values(1010595,1011645,1,396,0);
            insert into db_sysforkey values(1010595,1011648,2,396,0);
            insert into db_sysforkey values(1010595,1011646,1,144,0);

            insert into db_sysindices values(1008586,'camposandpadrao_andpadrao_in',1010595,'0');
            insert into db_syscadind values(1008586,1011645,1);
            insert into db_syscadind values(1008586,1011648,2);
            insert into db_sysindices values(1008587,'camposandpadrao_codcam_in',1010595,'0');
            insert into db_syscadind values(1008587,1011646,1);
            insert into db_sysindices values(1008588,'camposandpadrao_pk_in',1010595,'0');
            insert into db_syscadind values(1008588,1011644,1);

            insert into db_syssequencia values(1000950, 'camposandpadrao_p110_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000950 where codarq = 1010595 and codcam = 1011644;

SQL_DICIONARIO_UP;

        $this->execute($sqlDicionario);

    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downDDL();
    }
    
    public function downDDL()
    {
        $this->execute("DROP TABLE protocolo.camposandpadrao");
        $this->execute("DROP SEQUENCE protocolo.camposandpadrao_p110_sequencial_seq");
    }

    public function downDicionarioDados()
    {
        $sqlDicionario = <<<SQL_DICIONARIO_DOWN
            DELETE FROM db_syssequencia where codsequencia = 1000950;
            DELETE FROM db_syscadind where codind IN (1008586, 1008587, 1008588);
            DELETE FROM db_sysindices where codind IN (1008586, 1008587, 1008588);
            DELETE FROM db_sysforkey where codarq = 1010595;
            DELETE FROM db_sysprikey where codarq = 1010595;
            DELETE FROM db_syscampodef where codcam = 1011647;
            DELETE FROM db_syscampodep where codcam = 1011645;
            DELETE FROM db_sysarqcamp where codarq = 1010595;
            DELETE FROM db_syscampo where codcam IN (1011644, 1011645, 1011646, 1011647, 1011648);
            DELETE FROM db_sysarqmod where codmod = 4 and codarq = 1010595;
            DELETE FROM db_sysarquivo where codarq = 1010595;
SQL_DICIONARIO_DOWN;

        $this->execute($sqlDicionario);
    }
}
