<?php

use Classes\PostgresMigration;

class M12498CadastroTaxas extends PostgresMigration
{

    public function up()
    {
        $this->execute("
            alter table iptucadtaxaexe add column j08_cadvencdesc int default null; 
            alter table iptucadtaxaexe add column j08_arretipo int default null; 
            alter table iptucadtaxaexe add column j08_procdiver int default null; 
            
            alter table iptucadtaxaexe add constraint iptucadtaxaexe_cadvencdesc_fk foreign key (j08_cadvencdesc) references cadvencdesc;
            alter table iptucadtaxaexe add constraint iptucadtaxaexe_arretipo_fk foreign key (j08_arretipo) references arretipo;
            alter table iptucadtaxaexe add constraint iptucadtaxaexe_procdiver_fk foreign key (j08_procdiver) references procdiver;
            
            create index iptucadtaxaexe_cadvencdesc_in on iptucadtaxaexe(j08_cadvencdesc);
            create index iptucadtaxaexe_arretipo_in on iptucadtaxaexe(j08_arretipo);
            create index iptucadtaxaexe_procdiver_in on iptucadtaxaexe(j08_procdiver);
        ");

        $this->execute("
            insert into db_syscampo 
            values (1010303,'j08_cadvencdesc','int4','Vínculo com o cadastro de vencimento','0', 'Vencimentos',10,'f','f','f',1,'text','Vencimentos'),
                   (1010304,'j08_arretipo','int4','Tipo de débito','0', 'Tipo',10,'f','f','f',1,'text','Tipo'),
                   (1010305,'j08_procdiver','int4','Procedência da taxa','0', 'Procedência',10,'f','f','f',1,'text','Procedência');
            
            insert into db_sysarqcamp 
            values (1629,1010303,10,0),
                   (1629,1010304,11,0),
                   (1629,1010305,12,0);
            
            insert into db_sysforkey 
            values (1629,1010303,1,54,0),
                   (1629,1010304,1,82,0),
                   (1629,1010305,1,374,0);
            
            insert into db_sysindices 
            values (1008426,'iptucadtaxaexe_cadvencdesc_in',1629,'0'),
                   (1008427,'iptucadtaxaexe_arretipo_in',1629,'0'),
                   (1008428,'iptucadtaxaexe_procdiver_in',1629,'0');
            
            insert into db_syscadind 
            values (1008426,1010303,1),
                   (1008427,1010304,1),
                   (1008428,1010305,1);
        ");
    }
    public function down()
    {

        $this->execute("
            alter table iptucadtaxaexe drop column j08_cadvencdesc;
            alter table iptucadtaxaexe drop column j08_arretipo;
            alter table iptucadtaxaexe drop column j08_procdiver;
            
            drop index if EXISTS iptucadtaxaexe_cadvencdesc_in;
            drop index if EXISTS iptucadtaxaexe_arretipo_in;
            drop index if EXISTS iptucadtaxaexe_procdiver_in; 
        ");

        $this->execute("
            delete from db_sysforkey where codarq = 1629 and codcam in (1010303, 1010304, 1010305);
            delete from db_syscadind where codind in (1008426, 1008427, 1008428);
            delete from db_sysindices where codind in (1008426, 1008427, 1008428);   
            delete from db_sysarqcamp where codarq = 1629 and codcam in (1010303, 1010304, 1010305);
            delete from db_syscampo where codcam in (1010303, 1010304, 1010305);
        ");
    }
}
