<?php
namespace Logger\Service;

use Exception;
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
     * @throws Exception
     */
    public function collect(): Item
    {
        $data = [];
        $types = $this->config->get('types')->toArray();
        $stream = fopen($this->config->get('device'), 'r');
        $pattern = sprintf('#^\$.{2}(%s),(.+)#', implode('|', $types));
        $sentences = [];

        if (!is_resource($stream)) throw new Exception('Failed to open ' . $this->config->get('device'));

        do {
            $sentence = trim(fgets($stream));
            if (!preg_match($pattern, $sentence, $matches)) continue;
            $sentences[$matches[1]] = $matches[2];
        }
        while (0 < count(array_diff($types, array_keys($sentences))));

        fclose($stream);
        ksort($sentences);

        foreach ($sentences as $type => $sentence) {
            $class = 'Logger\\Model\\Nmea\\' . ucfirst(strtolower($type));
            $instance = call_user_func_array([$class, 'createFromArray'], [explode(',', $sentence)]);
            if ($instance instanceof Item) $data = array_merge($data, $instance->getArrayCopy());
        }

        return Item::createFromArray($data);
    }
}