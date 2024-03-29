<?php

namespace Swiftly\Template;

use function array_map;
use function rtrim;
use function is_file;

/**
 * Utility to search for a file across multiple directories
 */
class FileFinder
{
    /** @var string[] $file_paths */
    private array $file_paths;

    /**
     * Create a FileFinder rooted in the given file path(s)
     *
     * @no-named-arguments
     * @param string|string[] $file_path Root path(s)
     */
    public function __construct($file_path)
    {
        $this->file_paths = array_map(
            fn ($path) => rtrim($path, '/\\'),
            (array)$file_path
        );
    }

    /**
     * Attempt to find the given file and return its path
     *
     * @param string $file File name
     * @return string|null Absolute path
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
     * Check to see if the given file exists in the `$root` directory
     *
     * @param string $root Root path
     * @param string $file File name
     * @return string|null Absolute path
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
