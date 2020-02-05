<?php
namespace Application\Model;

use ArrayObject, Exception;

/**
 * Class Collection
 * @package Application\Model
 */
class Collection extends ArrayObject
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $filename;

    /**
     * @param array|null $data
     * @return static
     */
    public static function create(array $data = null): self
    {
        if (!$data) $data = [];
        if (!array_key_exists('class', $data)) $data['class'] = $class ?? Item::class;
        if (!array_key_exists('collection', $data)) $data['collection'] = [];

        $collection = new static;
        $collection->class = $data['class'];
        foreach ($data['collection'] as $item) $collection->append($item);
        return $collection;
    }

    /**
     * @param string $filename
     * @param string|null $class
     * @return static
     */
    public static function load(string $filename, string $class = null): self
    {
        $data = file_exists($filename) ? json_decode(file_get_contents($filename), JSON_OBJECT_AS_ARRAY) : [];
        if ($class) $data['class'] = $class;

        $collection = static::create($data);
        $collection->filename = $filename;
        return $collection;
    }

    /**
     * @return Item
     * @throws Exception
     */
    public function first(): Item
    {
        return $this->find(0);
    }

    /**
     * @return Item
     * @throws Exception
     */
    public function last(): Item
    {
        return $this->find($this->count() - 1);
    }

    /**
     * @param Item|string|int $value
     * @return Item
     * @throws Exception
     */
    public function next($value): Item
    {
        $index = $this->find($value, false) + 1;
        return $this->offsetGet($this->count() > $index ? $index : 0);
    }

    /**
     * @param Item|string|int $value
     * @return Item
     * @throws Exception
     * @throws Exception
     */
    public function prev($value): Item
    {
        $index = $this->find($value, false) - 1;
        return $this->offsetGet(0 <= $index ? $index : $this->count() - 1);
    }

    /**
     * @inheritdoc
     */
    public function append($value)
    {
        parent::append($value instanceof $this->class ? $value : call_user_func_array([$this->class, 'create'], [$value]));
    }

    /**
     * @return Collection
     */
    public function reverse(): Collection
    {
        return static::create([
            'class' => $this->class,
            'collection' => array_reverse($this->getArrayCopy()),
        ]);
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @return Collection
     */
    public function slice(int $offset, int $length = null): Collection
    {
        return static::create([
            'class' => $this->class,
            'collection' => array_slice($this->getArrayCopy(), $offset, $length),
        ]);
    }

    /**
     * @param string|null $filename
     * @return $this
     */
    public function persist(string $filename = null): self
    {
        $data = ['class' => $this->class, 'collection' => []];
        foreach ($this as $item) if ($item instanceof Item) $data['collection'][] = $item->getArrayCopy();
        file_put_contents($filename ?? $this->filename, json_encode($data));
        return $this;
    }

    /**
     * @param Item|string|int $value
     * @param bool $item
     * @return Item|int
     * @throws Exception
     */
    public function find($value, bool $item = true)
    {
        if (0 >= $this->count()) throw new Exception('Can\'t peek empty datastructure');

        if (is_int($value)) {
            if (!$this->offsetExists($value)) throw new Exception('Can\'t find offset ' . $value);
            return $item ? $this->offsetGet($value) : $value;
        }

        if ($value instanceof Item) {
            $value = $value->id;
        }

        if (is_string($value)) {
            foreach ($this as $i => $issue) if ($issue instanceof Item && $issue->id == $value) return $item ? $issue : $i;
        }

        throw new Exception('Can\'t find item by value ' . var_export($value, true));
    }
}