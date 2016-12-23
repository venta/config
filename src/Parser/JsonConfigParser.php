<?php declare(strict_types = 1);

namespace Venta\Config\Parser;

use Venta\Contracts\Config\ConfigFileParser;
use Venta\Contracts\Config\ConfigStringParser;
use Venta\Contracts\Filesystem\Filesystem;

/**
 * Class JsonConfigParser
 *
 * @package Venta\Config\Parser
 */
class JsonConfigParser implements ConfigFileParser, ConfigStringParser
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Construct function.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @inheritDoc
     */
    public function parseFile(string $filename): array
    {
        return $this->parseString($this->filesystem->read($filename));
    }

    /**
     * @inheritDoc
     */
    public function parseString(string $configuration): array
    {
        $array = json_decode($configuration, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                sprintf('Unable to parse configuration string: "%s".', json_last_error_msg()),
                json_last_error()
            );
        }

        return $array;
    }

    /**
     * @inheritDoc
     */
    public function supportedExtensions(): array
    {
        return ['json'];
    }
}