<?php

use Classes\PostgresMigration;

class M10668ListaGrupoServico2018 extends PostgresMigration
{
    public function up()
    {
        $sSql = "
            insert into db_estruturavalor values (
              (select nextval('db_estruturavalor_db121_sequencial_seq')),
              '150000',
              '01.09',
              'Disponibilização, sem cessão definitiva, de conteúdo de áudio, vídeo, imagem e texto por meio de internet, respeitada a imunidade de livros, jornais e periódicos (exceto a distribuição de conteúdos pelas prestadoras de Serviço de Acesso Condicionado, de que trata a Lei nº 12.485 de 12 de setembro de 2011, sujeita ao ICMS)',
              17,
              2,
              2
            );
            insert into issgruposervico ( q126_sequencial, q126_db_estruturavalor) values ((select nextval('issgruposervico_q126_sequencial_seq')), (select currval('db_estruturavalor_db121_sequencial_seq')));
            insert into issconfiguracaogruposervico ( q136_sequencial,q136_issgruposervico, q136_exercicio,q136_tipotributacao, q136_valor, q136_localpagamento) values (
              (select nextval('issconfiguracaogruposervico_q136_sequencial_seq')),
              (select currval('issgruposervico_q126_sequencial_seq')),
              2018,
              2,
              2,
              1
            );
            
            insert into db_estruturavalor values (
              (select nextval('db_estruturavalor_db121_sequencial_seq')),
              '150000',
              '06.06',
              'Aplicação de tatuagens, piercings e congêneres',
              68,
              2,
              2
            );
            insert into issgruposervico ( q126_sequencial, q126_db_estruturavalor) values ((select nextval('issgruposervico_q126_sequencial_seq')), (select currval('db_estruturavalor_db121_sequencial_seq')));
            insert into issconfiguracaogruposervico ( q136_sequencial,q136_issgruposervico, q136_exercicio,q136_tipotributacao, q136_valor, q136_localpagamento) values (
              (select nextval('issconfiguracaogruposervico_q136_sequencial_seq')),
              (select currval('issgruposervico_q126_sequencial_seq')),
              2018,
              2,
              2,
              1
            );
            
            insert into db_estruturavalor values (
              (select nextval('db_estruturavalor_db121_sequencial_seq')),
              '150000',
              '16.02',
              'Outros serviços de transporte de natureza municipal',
              177,
              2,
              2
            );
            insert into issgruposervico ( q126_sequencial, q126_db_estruturavalor) values ((select nextval('issgruposervico_q126_sequencial_seq')), (select currval('db_estruturavalor_db121_sequencial_seq')));
            insert into issconfiguracaogruposervico ( q136_sequencial,q136_issgruposervico, q136_exercicio,q136_tipotributacao, q136_valor, q136_localpagamento) values (
              (select nextval('issconfiguracaogruposervico_q136_sequencial_seq')),
              (select currval('issgruposervico_q126_sequencial_seq')),
              2018,
              2,
              2,
              1
            );
            
            insert into db_estruturavalor values (
              (select nextval('db_estruturavalor_db121_sequencial_seq')),
              '150000',
              '17.25',
              'Inserção de textos, desenhos e outras materiais de propaganda e publicidade, em qualquer meio (exceto em livros, jornais, periódicos e nas modalidades de serviços de radiodifusão sonora e de sons e imagens de recepção livre gratuita)',
              179,
              2,
              2
            );
            insert into issgruposervico ( q126_sequencial, q126_db_estruturavalor) values ((select nextval('issgruposervico_q126_sequencial_seq')), (select currval('db_estruturavalor_db121_sequencial_seq')));
            insert into issconfiguracaogruposervico ( q136_sequencial,q136_issgruposervico, q136_exercicio,q136_tipotributacao, q136_valor, q136_localpagamento) values (
              (select nextval('issconfiguracaogruposervico_q136_sequencial_seq')),
              (select currval('issgruposervico_q126_sequencial_seq')),
              2018,
              2,
              2,
              1
            );
            
            insert into db_estruturavalor values (
              (select nextval('db_estruturavalor_db121_sequencial_seq')),
              '150000',
              '25.05',
              'Cessão de uso de espaços em cemitérios para sepultamento',
              220,
              2,
              2
            );
            insert into issgruposervico ( q126_sequencial, q126_db_estruturavalor) values ((select nextval('issgruposervico_q126_sequencial_seq')), (select currval('db_estruturavalor_db121_sequencial_seq')));
            insert into issconfiguracaogruposervico ( q136_sequencial,q136_issgruposervico, q136_exercicio,q136_tipotributacao, q136_valor, q136_localpagamento) values (
              (select nextval('issconfiguracaogruposervico_q136_sequencial_seq')),
              (select currval('issgruposervico_q126_sequencial_seq')),
              2018,
              2,
              2,
              1
            );

        ";
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "
            delete from 
                issconfiguracaogruposervico 
            where 
                q136_sequencial  in (
                    select 
                        q136_sequencial
                    from 
                        issconfiguracaogruposervico
                        inner join issgruposervico on q136_issgruposervico = q126_sequencial
                        inner join db_estruturavalor on q126_db_estruturavalor = db121_sequencial
                    where 
                        db121_db_estrutura = 150000 and 
                        q136_exercicio = 2018 and
                        db121_estrutural in('01.09', '06.06', '16.02', '17.25', '25.05')
                    );
    
            delete from 
                issgruposervico 
            where 
                q126_sequencial  in (
                    select 
                        q126_sequencial
                    from 
                        issgruposervico
                        inner join db_estruturavalor on q126_db_estruturavalor = db121_sequencial
                    where 
                        db121_db_estrutura = 150000 and 
                        db121_estrutural in('01.09', '06.06', '16.02', '17.25', '25.05')
                );
            
            delete from 
                db_estruturavalor 
            where 
                db121_db_estrutura = 150000 and 
                db121_estrutural in('01.09', '06.06', '16.02', '17.25', '25.05');
        ";
        $this->execute($sSql);
    }
}
