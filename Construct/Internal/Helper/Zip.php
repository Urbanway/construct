<?php


namespace Construct\Internal\Helper;


class Zip
{
    public static function extract($archivePath, $destinationDir)
    {
        if (class_exists('\\ZipArchive')) {
            $zip = new \ZipArchive();
            if ($zip->open($archivePath) === true) {
                $zip->extractTo($destinationDir);
                $zip->close();
            } else {
                throw new \Construct\Exception('Zip extraction failed.');
            }
        } else {
            require_once(ipFile('Construct/Internal/PclZip.php'));
            $zip = new \PclZip($archivePath);
            if (!$zip->extract(PCLZIP_OPT_PATH, $destinationDir)) {
                throw new \Construct\Exception('Zip extraction failed.');
            }
        }
    }

} 
