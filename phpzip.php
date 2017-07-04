#!/usr/bin/php
<?php
/**
 * Performs a recursive zip of the current directory into a file called site.zip.
 *
 * Useful for those times when you don't have shell access to a sever and want
 * to zip up the whole site either as a back up or to reduce bandwidth for download 
 * via FTP. Copy the script into the site root and call it from a browser.
 * 
 * I take no credit for the code, it's pretty much a direct lift from Dador's Stack
 * Overflow answer to the question of zipping a folder from PHP. <https://stackoverflow.com/a/4914807/1828840>
 * 
 * @example  http://domain.com/phpzip.php
 * @author   Alex Adams <alex@jujudigital.com>
 * 
 * @version  1.0.0
 * @date     2017-07-04
 * @licence  MIT
 *
 */
$root_path = realpath('./');

// Initialize archive object
$zip = new ZipArchive();
$zip->open('site.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root_path),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $file_path = $file->getRealPath();
        $relative_path = substr($file_path, strlen($root_path) + 1);

        // Add current file to archive
        $zip->addFile($file_path, $relative_path);
    }
}

// Zip archive will be created only after closing object
$zip->close();
echo "Site zipped";
?>