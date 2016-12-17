<?php declare(strict_types = 1);

namespace Venta\Config\Parser;

use League\Flysystem\FilesystemInterface;
use Venta\Contracts\Config\ConfigFileParser;

/**
 * Class Json
 *
 * @package Venta\Config\Parser
 */
class Json implements ConfigFileParser
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
    public function fromFile(string $filename): array
    {
        if ($this->filesystem->has($filename)) {
            return $this->fromString($this->filesystem->read($filename));
        }

        throw new \RuntimeException(sprintf('Unable to parse configuration file: "%s".', $filename));
    }

    /**
     * @inheritDoc
     */
    public function fromString(string $configuration): array
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