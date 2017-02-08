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
        $formMapper->add('content', CKEditorType::class, ['label' => 'Contenu', 'required' => true]);
        $formMapper->add('status', null, ['label' => 'En ligne']);
//        $formMapper->add('participants', 'entity', ['class' => Participant::class, 'label' => 'Participants', 'disabled' => true]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    public function configureListFields(ListMapper $list)
    {

       $list->addIdentifier('code', null, ['label' => 'Code'])
        ->add('title', null, ['label' => 'Titre'])
        ->add('content', null, ['label' => 'Contenu'])
        ->add('status', null, ['label' => 'En ligne']);
    }
}