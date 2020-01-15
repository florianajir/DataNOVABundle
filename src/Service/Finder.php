<?php

namespace Fmaj\LaposteDatanovaBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class Finder
{
    const DEFAULT_FORMAT = 'JSON';
    const RESSOURCES_FOLDER = '@FmajLaposteDatanovaBundle/Resources/dataset';

    /** @var Filesystem $filesystem */
    private $filesystem;

    /** @var FileLocator $locator */
    private $locator;

    /** @var string $directory */
    private $directory;

    /** @var LoggerInterface $logger */
    private $logger;

    public function __construct(Filesystem $filesystem, FileLocator $locator, string $rootDir = self::RESSOURCES_FOLDER)
    {
        $this->filesystem = $filesystem;
        $this->locator = $locator;
        $rootDir = $this->locator->locate($rootDir, null, true);
        if (! is_string($rootDir)) {
            throw new \InvalidArgumentException('Unexpected root dir type');
        }
        $this->setWorkingDirectory($rootDir);
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function setWorkingDirectory(string $path): void
    {
        $directory = preg_replace('#/+#', '/', $path); // remove multiple slashes
        try {
            $directory = $this->locator->locate($directory);
            if (false === is_string($directory)) {
                $directory = (string) reset($directory);
                $this->logger->alert(
                    sprintf('Ambiguous filename %s, choosing %s', $path, $directory)
                );
            }
        } catch (\InvalidArgumentException $exception) {
            // continue to check if dir exists even if locator doesn't locate
        }
        $exists = $this->filesystem->exists($directory);
        if (!$exists) {
            try {
                $this->filesystem->mkdir($directory);
                $this->logger->notice('Working directory created at ' . $directory);
            } catch (IOException $exception) {
                $this->logger->error(
                    'An error occurred while creating directory at ' . $exception->getPath(),
                    $exception->getTrace()
                );
                throw $exception;
            }
        }
        $this->directory = $directory;
    }

    /**
     * Check if a dataset local file exists
     */
    public function exists(string $dataset, string $format, ?string $filter = null): bool
    {
        $uri = $this->getFilePath($dataset, $format, $filter);

        return $this->filesystem->exists($uri);
    }

    private function getFilePath(string $dataset, string $format, ?string $filter = null): string
    {
        $filter = preg_replace('#[:=]#', '_', $filter);
        $filepath = sprintf(
            '%s%s%s%s%s_%s.%s',
            $this->directory,
            DIRECTORY_SEPARATOR,
            $dataset,
            DIRECTORY_SEPARATOR,
            $dataset,
            $filter,
            $format
        );
        $filepath = preg_replace('#/+#', '/', $filepath); // remove multiple slashes

        return $filepath;
    }

    /**
     * Save a records to dataset file
     *
     * @return false|string saved file path
     */
    public function save(string $dataset, string $content, string $format = self::DEFAULT_FORMAT, string $filter = null, bool $force = false)
    {
        $saved = false;
        $filename = $dataset;
        $path = $this->getFilePath($filename, $format, $filter);
        if ($this->filesystem->exists($path) && !$force) {
            $this->logger->error('An error occurred while saving existing dataset at ' . $path);
        } else {
            try {
                $this->filesystem->dumpFile($path, $content);
                $this->logger->notice(sprintf('Saving %s dataset at %s', $dataset, $path));
                $saved = realpath($path);
            } catch (IOException $exception) {
                $this->logger->error(
                    'An error occurred while saving the dataset at ' . $exception->getPath(),
                    $exception->getTrace()
                );
            }
        }

        return $saved;
    }

    /**
     * @return false|string dataset file path
     */
    public function findDataset(string $dataset, string $format = self::DEFAULT_FORMAT, string $filter = null)
    {
        $datasetPath = false;
        $path = $this->getFilePath($dataset, $format, $filter);
        if ($this->filesystem->exists($path)) {
            $datasetPath = realpath($path);
        }

        return $datasetPath;
    }

    public function getContent(string $filepath): ?string
    {
        $content = null;
        if (file_exists($filepath)) {
            $content = file_get_contents($filepath);
        }

        return $content;
    }
}
