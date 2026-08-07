<?php

use Classes\PostgresMigration;

class M17977CriaDocParagrafos extends PostgresMigration
{
    public function up()
    {
        $stmt1 = $this->query("select nextval('db_documento_db03_docum_seq')");
        $documento = $stmt1->fetch()[0];

        $stmt1 = $this->query("select nextval('db_paragrafo_db02_idparag_seq')");
        $paragrafo = $stmt1->fetch()[0];

        $texto = 'O MUNICÍPIO DE SÃO BORJA, Estado do Rio Grande do Sul, no uso de suas atribuições legais e em cumprimento ao disposto no art. 2º da Lei nº 9.452, de 20 de março de 1997, notifica os partidos políticos, os sindicatos de trabalhadores e as entidades empresariais com sede neste município de São Borja, da liberação de recursos financeiros provenientes do Governo Federal, ocorridos no período de #$periodo_inicial# e #$periodo_final#, a seguir especificados: ';

        $this->execute(<<<sql
insert into db_tipodoc(db08_codigo, db08_descr) values (92001, 'NOTIFICAÇÃO DE RECEBIMENTO RECURSOS');
insert into  db_documento(db03_docum, db03_descr, db03_tipodoc, db03_instit)
    values ({$documento},'NOTIFICAÇÃO DE RECEBIMENTO RECURSOS', 92001, 1);
insert into db_paragrafo(db02_idparag,
                         db02_descr,
                         db02_texto,
                         db02_alinha,
                         db02_inicia,
                         db02_espaca,
                         db02_alinhamento,
                         db02_altura,
                         db02_largura,
                         db02_tipo,
                         db02_instit)
    values ({$paragrafo}, 'NOTIFICAÇÃO DE RECEBIMENTO DE RECURSOS FEDERAIS', '$texto', 0, 0, 1, 'J', 0, 0, 1, 1);
insert into db_docparag(db04_docum, db04_idparag, db04_ordem) values ({$documento}, {$paragrafo}, 1);
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            DELETE FROM db_tipodoc WHERE db08_codigo = 92001;
sql
        );
    }
}
