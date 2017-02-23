<?php
namespace Hope\Database;

use Hope\Exceptions\UnprocessableEntityException;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Entity
 *
 * @package Hope/Database
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
abstract class Entity implements \JsonSerializable
{
    const TABLENAME = null;
    protected $columns = [];
    protected $fillable = [];
    protected $attributes;
    protected $rules = [];

    /**
     * Constructor
     *
     * @param array|null $data Data to fill entity.
     */
    public function __construct(array $data = null)
    {
        $this->attributes = new ParameterBag();

        foreach ($this->columns as $col) {
            $this->attributes->set($col, null);
        }

        if (is_null($data)) {
            return;
        }

        $this->fill($data);
    }

    /**
     * Fill attributes according $fillable.
     *
     * @param array $data Data to fill.
     *
     * @return void
     */
    public function fill(array $data)
    {
        foreach ($this->fillable as $column) {
            if (in_array($column, array_keys($data))) {
                $this->attributes->set($column, $data[$column]);
            }
        }
    }

    /**
     * Get entity attributes
     *
     * @return array List of attributes.
     */
    public function getAttributes()
    {
        return $this->attributes->all();
    }

    /**
     * Get entity fillable columns
     *
     * @return array List of fillable columns
     */
    public function getFillables()
    {
        return $this->fillable;
    }

    /**
     * Get attribute field
     *
     * @param string $name Name of attribute.
     *
     * @return mixed value of attribute
     */
    public function __get(string $name)
    {
        return $this->attributes->get($name);
    }

    /**
     * Set attribute field value
     *
     * @param string $name  Attribute name.
     * @param mixed  $value Attribute value.
     *
     * @return mixed Attribute value.
     */
    public function __set(string $name, $value)
    {
        return $this->attributes->set($name, $value);
    }

    /**
     * Validate attributes with specified rules
     *
     * @return boolean
     *
     * @throws UnprocessableEntityException Bad Request with errors.
     */
    public function validate()
    {
        $v = new \Valitron\Validator($this->getAttributes());

        foreach ($this->rules as $column => $rules) {
            if (is_array($rules)) {
                foreach ($rules as $rule) {
                    if (is_array($rule)) {
                        $rule_name = array_shift($rule);
                        $r = call_user_func_array([$v, 'rule'], array_merge([$rule_name, $column], $rule));
                    } else {
                        $r = $v->rule($rule, $column);
                    }
                    $r->label($column);
                }
            }
        }

        if (!$v->validate()) {
            throw new UnprocessableEntityException($v->errors());
        }

        return true;
    }

    /**
     * JSON Serializer
     *
     * @return string Json
     */
    public function jsonSerialize()
    {
        return $this->getAttributes();
    }
}
