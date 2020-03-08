<?php

namespace App\Application\Sonata\MediaBundle\Admin\ORM;

use Sonata\MediaBundle\Admin\ORM\MediaAdmin as BaseAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class MediaAdmin
 * @package App\Application\Sonata\MediaBundle\Admin\ORM
 */
class MediaAdmin extends BaseAdmin
{
    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection->add('create_gallery', 'create/gallery/uploaded/medias');
    }
}
