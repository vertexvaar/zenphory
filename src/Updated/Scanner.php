<?php
namespace VerteXVaaR\Zenphory\Updated;

class Scanner
{
    public function scanDirectoryRecursive(string $directory)
    {
        $files = [];
        $this->scanDirectory(rtrim($directory, '/'), $files);
        return $files;
    }

    protected function scanDirectory(string $directory, array &$files)
    {
        foreach (scandir($directory) as $value) {
            if ('.' === $value || '..' === $value) {
                continue;
            } else {
                $value = $directory . '/' . $value;
                if (is_file($value)) {
                    $files[] = $value;
                } elseif (is_dir($value)) {
                    $this->scanDirectory($value, $files);
                }
            }
        }
    }
}
