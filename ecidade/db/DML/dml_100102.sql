insert into caddocumentoatributo
     select db45_sequencial + 3000000, db45_caddocumento, db45_codcam, db45_descricao, db45_valordefault, db45_tipo, db45_tamanho
       from caddocumentoatributo
            inner join caddocumento on db44_sequencial = db45_caddocumento
      where db44_cadtipodocumento = 3
        and db45_sequencial between 4 and 29;

insert into caddocumentoatributovalor
     select nextval('caddocumentoatributovalor_db43_sequencial_seq'), db43_documento, db43_caddocumentoatributo + 3000000, db43_valor
       from caddocumentoatributovalor
            inner join caddocumentoatributo on db45_sequencial = db43_caddocumentoatributo
            inner join caddocumento         on db44_sequencial = db45_caddocumento
      where db44_cadtipodocumento = 3
        and db45_sequencial between 4 and 29;

delete from caddocumentoatributovalor
      using caddocumentoatributo, caddocumento
      where db45_sequencial            = db43_caddocumentoatributo
        and db44_sequencial            = db45_caddocumento
        and db44_cadtipodocumento      = 3
        and db43_caddocumentoatributo <= 29;

delete from caddocumentoatributo
      using caddocumento
      where db45_caddocumento = db44_sequencial
        and db44_cadtipodocumento = 3
        and db45_sequencial      <= 29;