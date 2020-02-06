<?php
namespace Application\Model;

use Exception;

/**
 * Class Page
 * @property int $current
 * @property int $records
 * @property int $pages
 * @property int $first
 * @property int $last
 * @property int $next
 * @property int $prev
 */
class Page extends Collection
{
    /**
     * @var int[]
     */
    protected $meta = [
        'current' => 0,
        'records' => 0,
        'pages' => 0,
        'first' => 0,
        'last' => 0,
        'next' => 0,
        'prev' => 0,
    ];

    /**
     * @param Collection $collection
     * @param int        $size
     * @param int        $page
     * @return Page|Collection
     */
    public static function createFromCollection(Collection $collection, int $size = 1, int $page = 0)
    {
        $instance = static::createFromArray([
            'class' => $collection->class,
            'collection' => $collection->slice($size * $page, $size),
        ]);

        $instance->meta = [
            'current' => $page,
            'records' => $collection->count(),
            'pages' => ceil($collection->count() / $size),
            'first' => 0,
            'last' => ceil($collection->count() / $size) - 1,
            'next' => ceil($collection->count() / $size) > $page + 1 ? $page + 1 : $page,
            'prev' => 0 < $page ? $page - 1 : $page,
        ];

        return $instance;
    }

    /**
     * @param string $name
     * @return int
     * @throws Exception
     */
    public function __get(string $name)
    {
        if (!array_key_exists($name, $this->meta)) throw new Exception('Can\'t find property ' . $name);
        return $this->meta[$name];
    }
}