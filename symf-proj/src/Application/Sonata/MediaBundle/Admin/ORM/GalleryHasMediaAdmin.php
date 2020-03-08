<?php


namespace App\Application\Sonata\MediaBundle\Admin\ORM;


use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\MediaBundle\Admin\GalleryHasMediaAdmin as BaseGalleryHasMediaAdmin;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class GalleryHasMediaAdmin extends BaseGalleryHasMediaAdmin
{
    const DEFAULT_CONTEXT = 'gallery';
    const DEFAULT_PROVIDER = 'sonata.media.provider.image';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $link_parameters = [];

        if ($this->hasParentFieldDescription()) {
            $link_parameters = $this->getParentFieldDescription()->getOption('link_parameters', []);
        }

        if ($this->hasRequest()) {
            $context = $this->getRequest()->get('context', null);

            if (null !== $context) {
                $link_parameters['context'] = $context;
            }
        }

        $formMapper
            ->add('media', ModelListType::class, ['required' => false], [
                'link_parameters' => [
                    'context'  => self::DEFAULT_CONTEXT,
                    'provider' => self::DEFAULT_PROVIDER,
                ],
            ])
            ->add('enabled', null, ['required' => false])
            ->add('position', HiddenType::class)
        ;
    }
}
