update acordoposicao
set ac26_numeroaditamento = ac26_sequencial
where ac26_acordoposicaotipo <> 1
      and (ac26_numeroaditamento is null or trim(ac26_numeroaditamento) = '')
      and exists (select 1 from db_config where upper(uf) = 'RS');
