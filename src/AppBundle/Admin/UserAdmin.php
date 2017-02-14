<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('firstname', 'text', ['label' => 'Prénom']);
        $formMapper->add('lastname', 'text', ['label' => 'Nom de famille']);
        $formMapper->add('email', 'text', ['label' => 'Email']);
        $formMapper->add('birthday', 'date', ['label' => 'Anniversaire']);
        $formMapper->add('country', 'text', ['label' => 'Pays']);
        $formMapper->add('city', 'text', ['label' => 'Ville']);
        $formMapper->add('facebook_id', 'text', ['label' => 'Id Facebook', 'disabled' => true]);
        $formMapper->add('status', null, ['label' => 'En ligne', 'attr' => ['checked' => 'checked']]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('firstname');
        $datagridMapper->add('lastname');
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('firstname', null, ['label' => 'Prénom'])
            ->add('lastname', null, ['label' => 'Nom de famille'])
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