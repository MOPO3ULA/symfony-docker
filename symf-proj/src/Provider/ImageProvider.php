<?php


namespace App\Provider;

use Gaufrette\Filesystem;
use Imagine\Image\ImagineInterface;
use SilasJoisten\Sonata\MultiUploadBundle\Traits\MultiUploadTrait;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;

class ImageProvider extends \Sonata\MediaBundle\Provider\ImageProvider
{
    use MultiUploadTrait;

    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions, array $allowedMimeTypes, ImagineInterface $adapter, MetadataBuilderInterface $metadata = null)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, $allowedExtensions, $allowedMimeTypes, $adapter, $metadata);
    }
}
