<?php

declare(strict_types=1);

namespace District09\QA\PHP\GrumPHP\EventListener;

use GrumPHP\Event\TaskEvent;
use GrumPHP\Task\Phpcs;
use GrumPHP\Task\PhpMd;
use GrumPHP\Task\PhpStan;
use GrumPHP\Task\Phpunit;
use GrumPHP\Task\TaskInterface;
use Nette\Neon\Neon;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Listener for GrumPHP task events.
 */
final class TaskEventListener
{
    /**
     * Configuration file types.
     */
    private const FILETYPE_XML = 'xml';
    private const FILETYPE_YAML = 'yaml';
    private const FILETYPE_NEON = 'neon';

    /**
     * Mapping of task types and their configuration files.
     *
     * @var array
     */
    private $taskInfo = [
        Phpcs::class => [
            'filename' => 'phpcs',
            'extension' => 'xml',
            'type' => self::FILETYPE_XML,
        ],
        PhpMd::class => [
            'filename' => 'phpmd',
            'extension' => 'xml',
            'type' => self::FILETYPE_XML,
        ],
        PhpStan::class => [
            'filename' => 'phpstan',
            'extension' => 'neon',
            'type' => self::FILETYPE_NEON,
        ],
        Phpunit::class => [
            'filename' => 'phpunit',
            'extension' => 'xml',
            'type' => self::FILETYPE_XML,
        ],
    ];

    /**
     * Create the task configuration file.
     *
     * @param TaskEvent $event
     *   The GrumPHP task event.
     */
    public function createTaskConfig(TaskEvent $event): void
    {
        $info = $this->getTaskConfigFileInfo($event->getTask());
        if (!$info) {
            return;
        }

        // Candidate configuration files.
        $keyPrefix = strtoupper($info['filename']) . '_SKIP_';
        $packagePath = __DIR__ . '/../../../configs/';

        $candidates = [
            $keyPrefix . 'LOCAL' => sprintf(
                '%s.local.%s',
                $info['filename'],
                $info['extension']
            ),
            $keyPrefix . 'PROJECT' => sprintf(
                '%s.%s',
                $info['filename'],
                $info['extension']
            ),
            $keyPrefix . 'EXT_DIST' => sprintf(
                '%s.%s.dist',
                $info['filename'],
                $info['extension']
            ),
            $keyPrefix . 'DIST_EXT' => sprintf(
                '%s.dist.%s',
                $info['filename'],
                $info['extension']
            ),
            $keyPrefix . 'GLOBAL' => sprintf(
                '%s%s.%s',
                $packagePath,
                $info['filename'],
                $info['extension']
            ),
        ];

        // Search for the candidates and merge or copy them.
        $filesystem = new Filesystem();
        $dataMerged = null;

        foreach ($candidates as $env_var => $file) {
            // Ignore if configured to skip or if the file is missing.
            if (!empty($_SERVER[$env_var]) || !$filesystem->exists($file)) {
                continue;
            }

            // Read and parse the configuration file.
            $data = $this->readTaskConfigFile($info['type'], $file);

            if ($data === false) {
                // Just copy it if not readable.
                $filesystem->copy($file, $info['grumphp']);
                return;
            }

            // Merge the data.
            if ($dataMerged === null) {
                $dataMerged = $data;
            } elseif ($data) {
                $dataMerged = array_merge_recursive($data, $dataMerged);
            }
        }

        // Save the configuration file.
        $this->writeTaskConfigFile($info['type'], $info['grumphp'], $dataMerged);
    }

    /**
     * Get some information about the task configuration file.
     *
     * @param \GrumPHP\Task\TaskInterface $task
     *   The GrumPHP task.
     *
     * @return array|null
     *   The task configuration info (filename, extension, type and name of the
     *   temporary merged file for GrumPHP) as associative array.
     */
    private function getTaskConfigFileInfo(TaskInterface $task): ?array
    {
        $taskClass = get_class($task);
        if (empty($this->taskInfo[$taskClass])) {
            return null;
        }

        $info = $this->taskInfo[$taskClass];
        $info['grumphp'] = sprintf(
            '%s.qa-php.%s',
            $info['filename'],
            $info['extension']
        );

        return $info;
    }

    /**
     * Read and parse a task configuration file.
     *
     * @param string $type
     *   The file type.
     * @param string $file
     *   Path to the file.
     *
     * @return array|false
     *   The configuration data or false if not supported.
     */
    private function readTaskConfigFile(string $type, string $file)
    {
        switch ($type) {
            case self::FILETYPE_YAML:
                return Yaml::parseFile($file);

            case self::FILETYPE_NEON:
                return Neon::decode(file_get_contents($file));
        }

        return false;
    }

    /**
     * Write a config file.
     *
     * @param string $type
     *   The file type.
     * @param string $file
     *   Path to the file.
     * @param array|null $data
     *   The configuration data.
     */
    private function writeTaskConfigFile(string $type, string $file, array $data): void
    {
        switch ($type) {
            case self::FILETYPE_YAML:
                $rawData = Yaml::dump($data);
                break;

            case self::FILETYPE_NEON:
                $rawData = Neon::encode($data, Neon::BLOCK);
                break;

            default:
                $rawData = '';
                break;
        }

        $filesystem = new Filesystem();
        $filesystem->dumpFile($file, $rawData);
    }
}
