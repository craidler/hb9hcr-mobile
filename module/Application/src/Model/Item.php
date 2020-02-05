<?php
namespace Application\Model;

use ArrayObject;

/**
 * Class Item
 * @package Application\Model
 * @property string $id
 */
class Item extends ArrayObject
{
    /**
     * @param array|null $data
     * @return static
     */
    public static function create(array $data = null): self
    {
        $item = new static;
        $item->exchangeArray($data);
        return $item;
    }

    /**
     * @inheritdoc
     */
    public function __construct($input = array(), $flags = 0, $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);
        $this->offsetSet('id', uniqid());
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed|null
     */
    public function __call(string $name, array $arguments)
    {
        return $this->offsetExists($name) ? $this->offsetGet($name) : null;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->{$name}();
    }

    /**
     * @param string $name
     * @param mixed|null $value
     */
    public function __set(string $name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function exchangeArray($input)
    {
        parent::exchangeArray(array_merge($this->getArrayCopy(), $input));
    }
}