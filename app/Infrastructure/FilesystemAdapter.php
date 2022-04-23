<?php

namespace App\Infrastructure;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnixVisibility\VisibilityConverter;
use League\MimeTypeDetection\MimeTypeDetector;

class FilesystemAdapter extends LocalFilesystemAdapter implements FilesystemAdapterInterface
{
    public function __construct(
        string              $location = ROOT_DIR . '/files/',
        VisibilityConverter $visibility = null,
        int                 $writeFlags = LOCK_EX,
        int                 $linkHandling = self::DISALLOW_LINKS,
        MimeTypeDetector    $mimeTypeDetector = null)
    {
        parent::__construct($location, $visibility, $writeFlags, $linkHandling, $mimeTypeDetector);
    }
}
