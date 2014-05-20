<?php

namespace EVT\DIYBundle\Services\Storage;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gaufrette\Filesystem;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

 /**
 * ImageUploader
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class ImageUploader
{
    private static $acceptedExtension = ['image/jpeg' => true, 'image/png' => true];
    private static $maxFileSize = 1048576;
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function upload(UploadedFile $file)
    {
        if (!isset(self::$acceptedExtension[$file->getClientMimeType()])) {
            throw new BadRequestHttpException('Invalid Mime Type');
        }

        if ($file->getClientSize() > self::$maxFileSize) {
            throw new BadRequestHttpException(sprinf('Max size %s MB', (self::$maxFileSize/1024)));
        }

        $filename = sprintf(
            '%s/%s/%s/%s.%s',
            date('Y'),
            date('m'),
            date('d'),
            uniqid(),
            $file->getClientOriginalExtension()
        );

        $adapter = $this->filesystem->getAdapter();
        $adapter->setMetadata($filename, ['contentType' => $file->getClientMimeType()]);
        $adapter->write($filename, file_get_contents($file->getPathname()));

        return $filename;
    }
}
