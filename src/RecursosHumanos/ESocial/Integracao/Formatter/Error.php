<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\Integracao\Sped\Common\Configuracao\ConfiguracaoFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

/**
 * Formata os erros vindos da API
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 */
class Error
{
    /**
     * @var string - nome do evento
     * @see ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo::getByLayout
     */
    private $eventName;

    /**
     * @var boolean
     */
    private $html = true;

    /**
     * @var array
     */
    private $fieldsData;

    /**
     * @param string $eventName
     * @param boolean $html
     */
    public function __construct($eventName, $html = true)
    {
        $this->eventName = $eventName;
        $this->html = $html;
    }

    public function loadFieldsData()
    {
        $this->fieldsData = $this->extractFieldsData($this->eventName);
        return $this->fieldsData;
    }

    /**
     * @param array $errors - erros retornados pela API
     */
    public function formatErrors(array $errors)
    {
        $fieldsData = $this->fieldsData ?: $this->loadFieldsData();
        return $this->extractFieldsMessage($errors, $fieldsData);
    }

    public function extractLabels($path)
    {
        $caminho = mb_convert_encoding($path, 'ISO-8859-1', 'UTF-8');

        if ($caminho == 'Produção do eSocial') {
            return (object) array(
                'group' => (object) array(
                    'index' => 0,
                    'key' => 'eSocial',
                    'label' => 'eSocial',
                ),
                'field' => (object) array(
                    'key' => 'eSocial',
                    'label' => 'eSocial',
                ),
            );
        }
        $fieldsData = $this->fieldsData ?: $this->loadFieldsData();
        $path = explode('/', ltrim($path, '/'));

        // indice do evento, atualmente o ecidade envia um, entao ignoramos
        $index = array_shift($path);

        // pega apenas os ultimos dois itens
        // pois no ecidade só existe 1 nivel: grupo e campo
        $fields = array_slice($path, -2, 2);

        // indice do item quando grupo for uma lista
        $index = null;

        $group = $fields[0];
        $field = isset($fields[1]) ? $fields[1] : null;

        // key eh numerico, grupo eh um array, extrai o index
        if (ctype_digit((string) $group)) {
            $index = $group;
            $groupPath = array_slice($path, -3, 1);
            $group = array_shift($groupPath);
        }

        // verifica se existe grupo
        // - usa grupo como campo,
        // - se nao existe no fieldsData nao tem grupo
        if (!isset($fields[1]) && !isset($fieldsData[$group])) {
            $field = $group;
            $group = null;
        }

        // verifica se campo eh um grupo
        if (!empty($field[1]) && empty($fieldsData[$group]['fields'][$field])
            && !empty($fieldsData[$field])
        ) {
            $group = $field;
            $field = null;
        }

        $groupLabel = $group;
        $fieldLabel = $field;

        if (!empty($fieldsData[$group]['label'])) {
            $groupLabel = $fieldsData[$group]['label'];
        }

        if (!empty($fieldsData[$group]['fields'][$field])) {
            $fieldLabel = $fieldsData[$group]['fields'][$field];
        }

        return (object) array(
            'group' => (object) array(
                'index' => $index,
                'key' => $group,
                'label' => $groupLabel,
            ),
            'field' => (object) array(
                'key' => $field,
                'label' => $fieldLabel,
            ),
        );
    }

    public function formatLabels($data, $mask = " - %s")
    {
        $index = $data->group->index;
        $groupLabel = $data->group->label;
        $fieldLabel = $data->field->label;
        $endLine = $this->html ? "<br />" : "\n";
        $highlightPre = $this->html ? '<b>"' : '"';
        $highlightPost = $this->html ? '"</b>' : '"';
        $groupMask = sprintf('Grupo %s%s%s ', $highlightPre, $groupLabel, $highlightPost);
        $fieldMask = sprintf('campo %s%s%s ', $highlightPre, $fieldLabel, $highlightPost);
        $indexMask = sprintf('#%s ', $index);

        $label = ucfirst(sprintf(
            '%s%s%s%s',
            $groupLabel ? $groupMask : null,
            $index !== null ? $indexMask : null,
            $fieldLabel ? $fieldMask : null,
            $endLine
        ));

        return sprintf($mask, $label);
    }

    private function extractFieldsData($eventName)
    {
        $data = array();
        $tipoEvento = Tipo::getByLayout($eventName);

        $esocialConfig = ConfiguracaoFactory::getInstanceByTipo($tipoEvento);
        $idAvalicacao = $esocialConfig->getFormulario($tipoEvento);
        $avaliacao = new \Avaliacao($idAvalicacao);
        $template = \ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory::get($eventName)->getDePara();

        $extractPropertiesHandler = function (array $properties) {
            $data = array();
            foreach ($properties as $key => $property) {
                $name = is_int($key) ? $property : $key;
                $nameAPI = is_array($property) ? $name : $property;
                $nameEcidade = $name;

                if (is_array($property) && !empty($property['nome_api'])) {
                    $nameAPI = $property['nome_api'];
                }

                $data[$nameEcidade] = array(
                    'key' => $nameAPI,
                    'label' => (
                        is_array($property) && isset($property['label']) ? $property['label'] : null
                    ),
                );
            }
            return $data;
        };

        $extractGroupDataHandler = function ($groups, &$data)
 use (&$extractGroupDataHandler, $extractPropertiesHandler) {
            foreach ($groups as $key => $group) {
                if (isset($group['groups'])) {
                    $extractGroupDataHandler($group['groups'], $data);
                }

                if (!isset($data[$key])) {
                    $data[$key] = array(
                        'key' => !empty($group['nome_api']) ? $group['nome_api'] : $key,
                        'label' => !empty($group['label']) ? $group['label'] : null,
                        'groups' => !empty($group['groups']) ? array_keys($group['groups']) : array(),
                        'fields' => array(),
                        'type' => isset($group['type']) ? $group['type'] : null,
                    );
                }

                if (isset($group['properties'])) {
                    $data[$key]['fields'] = $extractPropertiesHandler($group['properties']);
                }

                if (isset($group['items'])) {
                    $data[$key]['fields'] = $extractPropertiesHandler($group['items']['properties']);
                }
            }

            return $data;
        };

        // extrai todos os grupos e campos do template, deixando todos no mesmo nivel
        $templateData = array();
        $extractGroupDataHandler($template, $templateData);

        foreach ($avaliacao->getGrupos() as $grupo) {
            $groupKey = $grupo->getIdentificadorCampo();

            if (!isset($templateData[$groupKey])) {
                continue;
            }

            $groupKeyAPI = $templateData[$groupKey]['key'];

            if (!isset($data[$groupKeyAPI])) {
                $data[$groupKeyAPI] = array(
                    'label' => $templateData[$groupKey]['label'] ?: $grupo->getDescricao(),
                    'fields' => array(),
                    'groups' => $templateData[$groupKey]['groups'],
                    'type' => $templateData[$groupKey]['type'],
                );
            }

            if ($data[$groupKeyAPI]['type'] == 'array') {
                $data[$groupKey] = $templateData[$groupKey];
            }

            foreach ($grupo->getPerguntas() as $pergunta) {
                $fieldKey = $pergunta->getIdentificadorCampo();
                if (!isset($templateData[$groupKey]['fields'][$fieldKey])) {
                    continue;
                }
                $fieldAPI = $templateData[$groupKey]['fields'][$fieldKey];
                $fieldKeyAPI = $fieldAPI['key'];
                $data[$groupKeyAPI]['fields'][$fieldKeyAPI] = $fieldAPI['label'] ?: $pergunta->getDescricao();
            }

            unset($templateData[$groupKey]);
        }

        // grupos que nao tem no ecidade
        foreach ($templateData as $group) {
            $data[$group['key']] = array(
                'label' => $group['label'] ?: $group['key'],
                'fields' => array(),
                'groups' => $group['groups'],
                'type' => $group['type'],
            );

            foreach ($group['fields'] as $field) {
                $data[$group['key']]['fields'][$field['key']] = $field['label'] ?: $field['key'];
            }
        }

        return $data;
    }

    private function extractFieldsMessage(array $errors, $fieldsData)
    {
        $message = sprintf("Há inconsistências nas informações:%s", $this->html ? '<br />' : "\n");

        foreach ($errors as $error) {
            $data = $this->extractLabels($error->localizacao);
            $group = $data->group->key;
            $field = $data->field->key;

            // grupo tem grupos filhos na API, mas o ecidade só existe um nivel de grupo
            if (empty($field) && !empty($fieldsData[$group]['groups'])) {
                $message .= $this->formatUnknownGroups($group, $fieldsData);
                continue;
            }

            $message .= $this->formatFieldsMessage($error, $data);
        }

        return $message;
    }

    private function formatUnknownGroups($group, $fieldsData, $inner = false)
    {
        $endLine = $this->html ? "<br />" : "\n";
        $highlightPre = $this->html ? '<b>"' : '"';
        $highlightPost = $this->html ? '"</b>' : '"';
        $message = $inner ? '' : ' - Um dos seguintes grupos é obrigatório: ' . $endLine;

        foreach ($fieldsData[$group]['groups'] as $group) {
            // nao tem label, provavelmente um array
            // - exemplo dependente_[1-10], adicionado label somente no primeiro
            if (!isset($fieldsData[$group]['label'])) {
                continue;
            }

            $label = $fieldsData[$group]['label'];

            if ($group == $label && $fieldsData[$group]['groups']) {
                $message .= $this->formatUnknownGroups($group, $fieldsData, true);
                continue;
            }

            $message .= sprintf("  %s%s%s %s", $highlightPre, $label, $highlightPost, $endLine);
        }

        return substr($message, 0, strlen($message) - strlen($endLine)) . $endLine;
    }

    public function formatFieldsMessage($error, $data)
    {
        // Padronizando as mensagens de erro de padrao retornadas da api
        // para nao exibir a expressao regular da validação.
        if ($error->tipo == 'pattern') {
            $error->mensagem = "Resposta inválida ou pergunta não preenchida.";
        }
        return sprintf(
            "%s: %s%s",
            $this->formatLabels($data),
            \DBString::utf8_decode_all($error->mensagem),
            $this->html ? "<br />" : "\n"
        );
    }
}
