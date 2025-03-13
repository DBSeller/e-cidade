<?php
/**
 * Template Aviso Previo
 * Layout Registros do evento S-2250 - Aviso Previo
 */
return array(
    'ideVinculo' => array(
        'required' => true,
        'properties' => array(
            'cpfTrab' => array(
                'required' => true,
                'type' => 'string',
            ),
            'nisTrab' => array(
                'required' => true,
                'type' => 'string',
            ),
            'matricula' => array(
                'required' => true,
                'type' =>'string',
            ),
        )
    ),
    'infoAvPrevio' => array(
        'groups' => array(
            'detAvPrevio' => array(
                'label' => 'Detalha as informações do evento trabalhista',
                'properties' => array(
                    'dtAvPrv' => array(
                        'required' => true,
                        'type' => 'string',
                        'label' => 'Data em que o trabalhador ou o empregador recebeu o aviso de desligamento',
                    ),
                    'dtPrevDeslig' => array(
                        'required' => true,
                        'label' => 'Data prevista para o desligamento do trabalhador',
                        'type' => 'string'
                    ),
                    'tpAvPrevio' => array(
                        'required' => true,
                        'label' => 'Tipo de Aviso Prévio',
                        'type' => 'int'
                    ),
                    'detAvPrevio_observacao' => array(
                        'nome_api' => 'observacao',
                        'required' => false,
                        'type' => 'string',
                    )
                )
            ),
            'cancAvPrevio' => array(
                'properties' => array(
                    'dtCancAvPrv' => array(
                        'required' => true,
                        'type' => 'string',
                    ),
                    'cancAvPrevio_observacao' => array(
                        'nome_api' => 'observacao',
                        'required' => false,
                        'type' => 'string',
                    ),
                    'mtvCancAvPrevio' => array(
                        'required' => true,
                        'type' => 'int',
                    )
                )
            )
        )
    )
);
