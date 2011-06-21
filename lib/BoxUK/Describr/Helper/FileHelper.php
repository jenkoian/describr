<?php
namespace BoxUK\Describr\Helper;

/**
 * Functions to help the system work out what kind of files we're dealing with
 *
 * @package   BoxUK\Describr\Helper
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2010, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 */
class FileHelper
{

    /**
     * @var array Mapping of file types to known extensions for quick lookup
     *
     */
    private static $aFileTypes = array(
        'image'         => array( 'bmp', 'gif', 'jpg', 'jpeg', 'png', 'jpe', 'pjpg', 'tif' ),
        'flash'         => array( 'swf' ),
        'flashvideo'    => array( 'flv' ),
        'movie'         => array( 'mov', 'qt', 'avi', 'ram', 'wmv', 'mpg', 'mp4' ),
        'audio'         => array( 'mp3', 'au', 'wav', 'mid', 'midi', 'wma' ),
        'document'      => array( 'doc', 'pdf', 'txt', 'rtf', 'xls', 'ppt' )
    );

    /**
     * Returns a media file's file extension from its filename
     *
     * @param string $fullPathToFileOnDisk The full path to the file
     * @return string The file's extension, e.g. flv
     */
    public static function getFileExtension($fullPathToFileOnDisk) {
        return strtolower(substr(strrchr($fullPathToFileOnDisk, '.'), 1));
    }

    /**
     * @param string $fullPathToFileOnDisk e.g. /tmp/foo.png
     * @return string|null A rough categorisation of the file, such as
     * "document", "image" or "audio"
     */
    public static function getFileTypeFromExtension ($fullPathToFileOnDisk) {
        $fileExtension = self::getFileExtension($fullPathToFileOnDisk);

        foreach ( self::$aFileTypes as $type => $aExtensions ) {
            if ( in_array($fileExtension,$aExtensions) ) {
                return $type;
            }
        }

        return null;
    }

 


    /**
     * @param string $fullPathToFileOnDisk e.g. /tmp/foo.png
     * @return string MIME type of the file at $fullPathToFileOnDisk
     */
    public static function getMimeType($fullPathToFileOnDisk) {
        $mimeType = self::getMimeTypeFromMagicNumber($fullPathToFileOnDisk);
        if (!$mimeType || $mimeType === 'application/octet-stream') {
            $mimeType = self::getMimeTypeFromFileExtension($fullPathToFileOnDisk);
        }
        return $mimeType;
    }

    /**
     * @param string $fullPathToFileOnDisk e.g. /tmp/foo.png
     */
    private static function getMimeTypeFromMagicNumber($fullPathToFileOnDisk) {

        $finfo = finfo_open(FILEINFO_MIME);

        if (! $finfo) {
            return false;
        }
        $mimeType  = null;
        $mimeType = finfo_file($finfo, $fullPathToFileOnDisk);
        finfo_close($finfo);

        // trim off any ; charset=binary type stuff from the end, we just
        // want the mime type
        $mimeType = substr($mimeType, 0, strpos($mimeType, ';'));

        return $mimeType ?: false;
    }

    /**
     * Taken from http://stackoverflow.com/questions/1147931/how-do-i-determine-the-extensions-associated-with-a-mime-type-in-php
     * Returns the system MIME type (as defined in /etc/mime.types) for the
     * filename specified.
     *
     * @staticvar array $types Types identified in the
     * getSystemExtensionMimeTypes method - cached statically
     * @param string $file Full path to file on disk, e.g. /tmp/foo.png
     * @return string Mime type, e.g. text/plain
     */
    private static function getMimeTypeFromFileExtension($file) {
        static $types;
        if (!isset($types)) {
            $types = self::getSystemExtensionMimeTypes();
        }
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if (!$ext) {
            $ext = $file;
        }
        $ext = strtolower($ext);
        return isset($types[$ext]) ? $types[$ext] : null;
    }

    /**
     * Returns the system MIME type mapping of extensions to MIME types, as
     * defined in /etc/mime.types
     *
     * @todo test on Windows
     *
     * Taken from http://stackoverflow.com/questions/1147931/how-do-i-determine-the-extensions-associated-with-a-mime-type-in-php
     */
    private static function getSystemExtensionMimeTypes() {
        $out = array();
        $file = fopen('/etc/mime.types', 'r');
        while(($line = fgets($file)) !== false) {
            $line = trim(preg_replace('/#.*/', '', $line));
            if (!$line) {
                continue;
            }
            $parts = preg_split('/\s+/', $line);
            if (count($parts) === 1) {
                continue;
            }
            $type = array_shift($parts);
            foreach($parts as $part) {
                $out[$part] = $type;
            }
        }
        fclose($file);
        return $out;
    }
}