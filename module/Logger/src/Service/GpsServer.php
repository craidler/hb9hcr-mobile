<?php
namespace Logger\Service;

use Exception, SplObjectStorage;
use Laminas\Config\Config;
use Logger\Model\Entry;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * Class GpsServer
 */
class GpsServer implements MessageComponentInterface
{
    /**
     * @var SplObjectStorage
     */
    protected $clients;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var int
     */
    protected $m = 0;

    /**
     * GpsServer constructor
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->clients = new SplObjectStorage();
        $this->config = $config;
        $this->run();
    }

    /**
     * @inheritdoc
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        print $msg . PHP_EOL;
    }

    /**
     * @inheritdoc
     */
    public function onOpen(ConnectionInterface $conn)
    {
        printf('%d connected', $conn->resourceId);
        $this->clients->attach($conn);
    }

    /**
     * @inheritdoc
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    /**
     * @inheritdoc
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        print $e->getMessage() . PHP_EOL;
    }

    /**
     * @return void
     */
    protected function run()
    {
        $stream = fopen($this->config->get('nmea')->get('device'), 'r');
        stream_set_blocking($stream, true);
        while (true) {
            try {
                $this->handle(trim(fgets($stream)));
            }
            catch (Exception $e) {
                print $e->getMessage() . PHP_EOL;
            }
        }
        fclose($stream);
    }

    /**
     * @param string $sentence
     */
    protected function handle(string $sentence)
    {
        // No handling for empty string
        if (!strlen($sentence)) return;

        // No handling for excluded types
        $words = explode(',', $sentence);
        $config = $this->config->get('nmea');
        $pattern = sprintf('#^\$.{2}(%s)$#', implode('|', $config->get('types')->toArray()));
        if (!preg_match($pattern, $words[0], $match)) return;

        // Remove vendor specific prefix from type and push NMEA sentence to clients
        $words[0] = $match[1];
        $entry = Entry::createFromArray($words);
        foreach ($this->clients as $client) $client->send(json_encode($entry->getArrayCopy()));

        // Log NMEA sentence if needed
        // todo: check this, must only write a record once per interval
        if (0 != date('i') % $config->get('interval', 1)) return;
        $filename = sprintf($config->get('log'), $this->config->get('file')->get('path'), $words[0], date('Ymd'));
        $handle = fopen($filename, 'a');
        fwrite($handle, implode(',', array_values($entry->getArrayCopy())));
        fclose($handle);
    }
}