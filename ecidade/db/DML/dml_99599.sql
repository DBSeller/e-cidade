insert into orcparamseqfiltropadrao
  select nextval('orcparamelementospadrao_o132_sequencial_seq'), o132_orcparamrel, o132_orcparamseq, 2016, o132_filtro
    from orcparamseqfiltropadrao
   where o132_orcparamrel in (156, 157, 158)
     and not exists( select *
                       from orcparamseqfiltropadrao fp
                      where fp.o132_orcparamrel = orcparamseqfiltropadrao.o132_orcparamrel
                        and fp.o132_orcparamseq = orcparamseqfiltropadrao.o132_orcparamseq
                        and fp.o132_anousu = 2016 );