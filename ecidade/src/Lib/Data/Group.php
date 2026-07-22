<?php

namespace ECidade\Lib\Data;

/**
 * Class Group
 */
class Group
{

    /**
     * @var array
     */
    private $fields;
    /**
     * @var array
     */
    private $data;
    /**
     * processed fields meta data
     * @var array
     */
    private $parsedFields = array();

    /**
     * keys to index
     * @var array
     */
    private $keys = array();

    public function __construct($fiels, array $data)
    {
        $this->fields = $fiels;
        $this->data = $data;
        $this->parseFields();
    }

    /**
     * Retorns dados Agrupados
     * @param null $data
     * @param null $item
     * @return array|stdClass|null
     */
    public function run($data = null, $item = null)
    {
        $groupedData = array();
        if (!empty($data)) {
            $groupedData = $data;
        }
        if (!empty($item)) {
            $this->data = array($item);
        }

        /**
         * Applies the transformation over each line
         */
        foreach ($this->data as $linha) {
            if (is_array($linha)) {
                $linha = (object)$linha;
            }
            $hash = $this->makehash($linha);
            if (empty($hash) && !empty($item)) {
                return $this->buildItem($linha);
            }
            if (empty($groupedData[$hash])) {
                $data = $this->buildItem($linha);
                $groupedData[$hash] = $data;
            }
            $data = $groupedData[$hash];
            $this->processAction($data, $linha);
        }
        return $groupedData;
    }

    /**
     * Build the line hash
     * @param $line
     * @return string
     */
    protected function makehash($line)
    {
        $hash = '';
        if (!empty($this->keys)) {
            $values = array();
            foreach ($this->keys as $key) {
                $values[] = $line->{$key};
            }
            $hash = implode('', $values);
            unset($values);
        }
        return $hash;
    }

    /**
     * Verifica se grupo tem index
     * @return bool
     */
    public function hasGroupIndex()
    {
        return count($this->keys) > 0;
    }

    /**
     * Reliza o parse do agrupamento
     * @return array
     */
    protected function parseFields()
    {
        foreach ($this->fields as $name => $field) {
            $action = '';
            if (!empty($field['action'])) {
                $action = $field['action'];
            }
            $parsedField = new \stdClass();
            $parsedField->original_name = $name;
            $parsedField->name = $name;
            if (!empty($field["alias"])) {
                $parsedField->name = $field["alias"];
            }
            $parsedField->action = $action;
            $parsedField->reference = '';
            $parsedField->runner = '';
            $parsedField->index = !empty($field["index"]);
            if ($parsedField->index) {
                $this->keys[] = $parsedField->original_name;
            }
            if (!empty($field["field"])) {
                $parsedField->reference = $field["field"];
            }
            if ($action == 'nestedGroup') {
                $parsedField->runner = new Group($field["fields"], array());
            }
            $this->parsedFields[] = $parsedField;
        }
        return $this->parsedFields;
    }

    /**
     * Constroi um item baseado no agrupamento
     * @param $line
     * @return \stdClass
     */
    protected function buildItem($line)
    {
        $data = new \stdClass();
        foreach ($this->parsedFields as $field) {
            $data->{$field->name} = null;
            if (isset($line->{$field->original_name})) {
                $data->{$field->name} = $line->{$field->original_name};
            }
            switch ($field->action) {
                case 'sum':
                case 'count':
                case 'avg':
                    $data->{$field->name} = 0;
                    break;

                case 'nestedGroup':
                    $data->{$field->name} = array();
                    break;
            }
        }
        return $data;
    }

    /**
     * Process the action designed to the field
     * @param $data
     * @param $line
     *
     * @return mixed
     */
    protected function processAction($data, $line)
    {
        /**
         * Realiza as
         */
        foreach ($this->parsedFields as $field) {
            if ($field->action != '') {
                switch ($field->action) {
                    case 'sum':
                        $data->{$field->name} += $line->{$field->name};
                        break;

                    case 'count':
                        $data->{$field->name} += 1;
                        break;

                    case 'nestedGroup':
                        $nestedGroup = ($field->runner->run($data->{$field->name}, $line));
                        if ($field->runner->hasGroupIndex()) {
                            $data->{$field->name} = $nestedGroup;
                        } else {
                            $data->{$field->name}[] = $nestedGroup;
                        }
                        break;

                    case 'avg':
                        if (empty($data->_quantity)) {
                            $data->_quantity = 0;
                        }
                        $data->_quantity += 1;
                        $data->{$field->name} = $data->{$field->reference} / $data->_quantity;
                        break;
                }
            }
        }
        return $data;
    }
}
