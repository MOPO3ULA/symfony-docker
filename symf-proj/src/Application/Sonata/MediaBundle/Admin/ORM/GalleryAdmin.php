<?php

namespace App\Application\Sonata\MediaBundle\Admin\ORM;

use App\Application\Sonata\MediaBundle\Entity\Gallery;
use App\Application\Sonata\MediaBundle\Entity\GalleryHasMedia;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;
use Sonata\MediaBundle\Admin\GalleryAdmin as BaseGalleryAdmin;

/**
 * Class GalleryAdmin
 * @package App\Application\Sonata\MediaBundle\Admin\ORM
 */
class GalleryAdmin extends BaseGalleryAdmin
{
    const DEFAULT_CONTEXT = 'gallery';

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, ['label' => 'Имя'])
            ->add('enabled', 'boolean', ['editable' => true])
            ->add('context', 'trans', ['catalogue' => 'SonataMediaBundle'])
            ->add('defaultFormat', 'trans', ['catalogue' => 'SonataMediaBundle']);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        // define group zoning
        $formMapper
            ->with('Gallery', ['class' => 'col-md-9'])->end()
            ->with('Options', ['class' => 'col-md-3'])->end();

        $formMapper
            ->with('Options')
            ->add('enabled', null, ['required' => false])
            ->add('name')
            ->end()
            ->with('Gallery')
            ->add('galleryHasMedias', CollectionType::class, ['by_reference' => false], [
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
                'admin_code' => 'sonata.media.admin.gallery_has_media',
            ])
            ->end();
    }

    /**
     * @param Gallery $gallery
     */
    public function prePersist($gallery)
    {
        $gallery->setContext(self::DEFAULT_CONTEXT);
        /** @var GalleryHasMedia[] $galleryHasMedias */
        $galleryHasMedias = $gallery->getGalleryHasMedias()->toArray();
        foreach ($galleryHasMedias as $item) {
            if (!$item->getMedia()) {
                $gallery->removeGalleryHasMedia($item);
            }
        }
    }

    /**
     * @param Gallery $object
     */
    public function preUpdate($object)
    {
        $this->prePersist($object);
    }
}
