<?php
/**
 * @package   construct
 */

namespace Construct\Internal\Repository;


class PublicController extends \Construct\Controller
{
    public static function download()
    {
        $requestFile = ipFile('') . ipRequest()->getRelativePath();
        $fileDir = ipFile('file/');

        if (mb_strpos($requestFile, $fileDir) !== 0) {
            return null;
        }



        $file = mb_substr($requestFile, mb_strlen($fileDir));
        $file = urldecode($file);

        if (empty($file)) {
            throw new \Construct\Exception('Required parameter is missing');
        }


        $absoluteSource = realpath(ipFile('file/' . $file));
        if (!$absoluteSource || !is_file($absoluteSource)) {
            throw new \Construct\Exception\Repository\Transform("File doesn't exist", array('filename' => $absoluteSource));
        }

        if (
            strpos($absoluteSource, realpath(ipFile('file/'))) !== 0
            ||
            strpos($absoluteSource, realpath(ipFile('file/secure'))) === 0
        ) {
            throw new \Exception("Requested file (" . $file . ") is outside of public dir");
        }


        $mime = \Construct\Internal\File\Functions::getMimeType($absoluteSource);

        $fsize = filesize($absoluteSource);


        // set headers
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header('Content-type: ' . $mime);
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $fsize);

        // download
        // @readfile($file_path);
        $file = @fopen($absoluteSource, "rb");
        if ($file) {
            while (!feof($file)) {
                print(fread($file, 1024 * 8));
                flush();
                if (connection_status() != 0) {
                    @fclose($file);
                    die();
                }
            }
            @fclose($file);
        }
        //TODO provide method to stop any output by construct
        constructQuery()->disconnect();
        exit;

    }

}
