<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;


class PageAdmin extends AbstractAdmin
{


    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('code', 'text', ['label' => 'Code', 'required' => true]);
        $formMapper->add('title', 'text', ['label' => 'Titre', 'required' => true]);
        $formMapper->add('position', 'integer', ['label' => 'Position dans le footer', 'required' => false]);
        $formMapper->add('content', CKEditorType::class, ['label' => 'Contenu', 'required' => true]);
        $formMapper->add('status', null, ['label' => 'En ligne']);
//        $formMapper->add('participants', 'entity', ['class' => Participant::class, 'label' => 'Participants', 'disabled' => true]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('code')
                        ->add('title')
                        ->add('position');
    }

    public function configureListFields(ListMapper $list)
    {

       $list->addIdentifier('code', null, ['label' => 'Code'])
        ->add('title', null, ['label' => 'Titre'])
        ->add('position', null, ['label' => 'Position'])
        ->add('status', null, ['label' => 'En ligne']);
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