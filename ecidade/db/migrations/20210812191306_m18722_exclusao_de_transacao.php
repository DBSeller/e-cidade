<?php

use Classes\PostgresMigration;

class M18722ExclusaoDeTransacao extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<SQL
create temporary table w_deletar as
select c46_seqtranslan,
       c47_seqtranslr
  from contrans
  join contranslan on c46_seqtrans = c45_seqtrans
  join contranslr on c47_seqtranslan = c46_seqtranslan
where c45_anousu = 2021
  and c45_coddoc in (140, 141)
  and c46_ordem in (2, 3);

delete from contranslrelemento where c114_contranslr in (select c47_seqtranslr from w_deletar);
delete from contranslrvinculo where c116_contranslrestorno in (select c47_seqtranslr from w_deletar);
delete from contranslrvinculo where c116_contranslrinclusao in (select c47_seqtranslr from w_deletar);

delete from contranslr where c47_seqtranslan in (select c46_seqtranslan from w_deletar);
delete from contranslan where c46_seqtranslan in (select c46_seqtranslan from w_deletar);
SQL
        );
    }
}
