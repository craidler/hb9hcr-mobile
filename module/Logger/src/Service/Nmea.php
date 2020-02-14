<?php
namespace Logger\Service;

use Application\Model\Item;
use Laminas\Config\Config;

/**
 * Class Nmea
 * @package Logger\Service
 */
class Nmea
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var resource
     */
    protected $input;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Item
     */
    public function collect(): Item
    {
        $data = [];
        $types = $this->config->get('types')->toArray();
        $stream = fopen($this->config->get('device'), 'r');
        $pattern = sprintf('#^\$.{2}(%s),(.+)#', implode('|', $types));
        $sentences = [];

        do {
            $sentence = trim(fgets($stream));
            var_dump($sentence, $sentences);
            if (!preg_match($pattern, $sentence, $matches)) continue;
            $sentences[$matches[1]] = $matches[2];
            var_dump($sentences);
        }
        while (0 < count(array_diff($types, array_keys($sentences))));

        fclose($stream);

        foreach ($sentences as $type => $sentence) {
            $class = 'Logger\\Model\\Nmea\\' . ucfirst(strtolower($type));
            $instance = call_user_func_array([$class, 'createFromArray'], [explode(',', $sentence)]);
            if ($instance instanceof Item) $data = array_merge($data, $instance->getArrayCopy());
        }

        return Item::createFromArray($data);
    }
}