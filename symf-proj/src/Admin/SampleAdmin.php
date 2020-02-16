<?php


namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class SampleAdmin extends AbstractAdmin
{
    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }


    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, ['label' => 'active'])
            ->add('genre', null, ['label' => 'genre'])
            ->add('userName', null, ['label' => 'userName'])
            ->add('userLink', null, ['label' => 'userLink', 'attr' => ['readonly' => true]])
            ->add('description', null, ['label' => 'description'])
            ->add('bpm', null, ['label' => 'bpm', 'attr' => ['readonly' => true]])
            ->add('size', null, ['label' => 'size', 'attr' => ['readonly' => true]]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', TextType::class, ['label' => 'id'])
            ->addIdentifier('title', TextType::class, ['label' => 'title'])
            ->add('genre', null, ['label' => 'genre'])
            ->add('userName', null, ['label' => 'userName'])
            ->add('userLink', null, ['label' => 'userLink'])
            ->add('description', null, ['label' => 'description'])
            ->add('bpm', null, ['label' => 'bpm'])
            ->add('size', null, ['label' => 'size'])
            ->add('_action', null,
                [
                    'actions' => [
                        'show' => [],
                        'edit' => [],
                        'delete' => [],
                    ],

                ]
            );
    }
}