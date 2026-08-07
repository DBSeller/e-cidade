create table w_orcparamseqfiltropadrao_98380 as select * from orcparamseqfiltropadrao where o132_orcparamrel > 149 and o132_orcparamrel < 99999;

delete from orcparamseqfiltropadrao where o132_orcparamrel > 149 and o132_orcparamrel < 99999;