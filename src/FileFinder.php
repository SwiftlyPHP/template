<?php declare(strict_types=1);

namespace Swiftly\Template;

use function array_map;
use function rtrim;
use function is_file;

/**
 * Utility to search for a file across multiple directories
 */
class FileFinder
{
    /** @var string[] */
    private array $file_paths;

    /**
     * Create a FileFinder rooted in the given file path(s).
     *
     * @param string|string[] $filePath
     */
    public function __construct(string|array $filePath)
    {
        $this->file_paths = array_map(
            static fn (string $path): string => rtrim($path, '/\\'),
            (array) $filePath,
        );
    }

    /**
     * Attempt to find the given file and return its path.
     */
    public function find(string $file): ?string
    {
        foreach ($this->file_paths as $file_path) {
            $path = $this->try($file_path, $file);

            if ($path !== null) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Check to see if the given file exists in the `$root` directory.
     */
    private function try(string $root, string $file): ?string
    {
        $path = "$root/$file";

        if (!is_file($path)) {
            $path = null;
        }

        return $path;
    }
}
