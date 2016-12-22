<?php declare(strict_types = 1);

namespace Venta\Config\Parser;

use League\Flysystem\FilesystemInterface;
use Venta\Contracts\Config\ConfigFileParser;
use Venta\Contracts\Config\ConfigStringParser;

/**
 * Class JsonConfigParser
 *
 * @package Venta\Config\Parser
 */
class JsonConfigParser implements ConfigFileParser, ConfigStringParser
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * Construct function.
     *
     * @param FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @inheritDoc
     */
    public function parseFile(string $filename): array
    {
        if ($this->filesystem->has($filename) && $contents = $this->filesystem->read($filename)) {
            return $this->parseString($contents);
        }

        throw new \RuntimeException(sprintf('Unable to parse configuration file: "%s".', $filename));
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