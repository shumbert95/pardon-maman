<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class RuleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', 'text', ['label' => 'Nom']);
        $formMapper->add('description', CKEditorType::class, ['label' => 'Description' ]);
        $formMapper->add('status', null, ['label' => 'En ligne', 'attr' => ['checked' => 'checked']]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name', null, ['label' => 'Nom'])
            ->add('description', null, ['label' => 'Description'])
            ->add('status', null, ['label' => 'Status', 'editable' => true]);
    }

    public function preUpdate($object)
    {
        $object->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));
    }

    public function prePersist($object)
    {
        $object->setDateAdd( new \DateTime(date('Y-m-d H:i:s')));
    }
}