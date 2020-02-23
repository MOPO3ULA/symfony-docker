<?php


namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UserAdmin extends AbstractAdmin
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
            ->add('is_active', null, ['label' => 'active'])
            ->add('username', TextType::class, ['label' => 'sonata.users.labels.nick'])
            ->add('email', EmailType::class, ['label' => 'sonata.users.labels.email'])
            ->add('date_registered', null, ['label' => 'sonata.users.labels.date_registration'])
            ->add('password', TextType::class, ['label' => 'sonata.users.labels.password']);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('username');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username', TextType::class, ['label' => 'sonata.users.labels.nick'])
            ->add('email', EmailType::class, ['label' => 'sonata.users.labels.email'])
            ->add('date_registered', null, ['label' => 'sonata.users.labels.date_registration'])
            ->add('_action', null,
                [
                    'label' => 'Действия',
                    'actions' => [
                        'show' => [],
                        'edit' => [],
                        'delete' => [],
                    ],

                ]
            );
    }
}