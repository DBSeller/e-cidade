<?php

use Classes\PostgresMigration;

class M12817ParametrosFuncaoIptu extends PostgresMigration
{
    public function up()
    {
        $sql  = <<<SQL

update db_sysfuncoesparam set db42_ordem = 1 where db42_sysfuncoesparam = 1010;
update db_sysfuncoesparam set db42_ordem = 2 where db42_sysfuncoesparam = 1011;
update db_sysfuncoesparam set db42_ordem = 3 where db42_sysfuncoesparam = 1012;
update db_sysfuncoesparam set db42_ordem = 4 where db42_sysfuncoesparam = 1013;
update db_sysfuncoesparam set db42_ordem = 5 where db42_sysfuncoesparam = 1014;
update db_sysfuncoesparam set db42_ordem = 6 where db42_sysfuncoesparam = 1015;

SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql  = <<<SQL

update db_sysfuncoesparam set db42_ordem = 1 where db42_sysfuncoesparam = 1010;
update db_sysfuncoesparam set db42_ordem = 1 where db42_sysfuncoesparam = 1011;
update db_sysfuncoesparam set db42_ordem = 1 where db42_sysfuncoesparam = 1012;
update db_sysfuncoesparam set db42_ordem = 1 where db42_sysfuncoesparam = 1013;
update db_sysfuncoesparam set db42_ordem = 5 where db42_sysfuncoesparam = 1014;
update db_sysfuncoesparam set db42_ordem = 6 where db42_sysfuncoesparam = 1015;

SQL;
        $this->execute($sql);
    }
}
