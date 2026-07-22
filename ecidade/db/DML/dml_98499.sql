INSERT INTO sau_tipounidade
    (sd42_i_tp_unid_id, sd42_v_descricao)
SELECT 73, 'UNIDADE PRONTO ATENDIMENTO 24 HRS'
WHERE
    NOT EXISTS (
        SELECT sd42_i_tp_unid_id FROM sau_tipounidade WHERE sd42_i_tp_unid_id = 73
    );