<?php

use Classes\PostgresMigration;

class M14377AjusteConstraintIptuExercicio extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
        ALTER TABLE cadastro.iptucadtaxaexe 
            DROP CONSTRAINT iptucadtaxaexe_arretipo_fk,
            DROP CONSTRAINT iptucadtaxaexe_cadvencdesc_fk,
            DROP CONSTRAINT iptucadtaxaexe_procdiver_fk;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        ALTER TABLE cadastro.iptucadtaxaexe
            ADD CONSTRAINT iptucadtaxaexe_cadvencdesc_fk FOREIGN KEY (j08_cadvencdesc) REFERENCES cadvencdesc,
            ADD CONSTRAINT iptucadtaxaexe_arretipo_fk FOREIGN KEY (j08_arretipo) REFERENCES arretipo,
            ADD CONSTRAINT iptucadtaxaexe_procdiver_fk FOREIGN KEY (j08_procdiver) REFERENCES procdiver;
SQL
        );
    }
}
