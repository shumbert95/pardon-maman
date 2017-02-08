<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Participant;
use AppBundle\Entity\Rule;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;


class ContestAdmin extends AbstractAdmin
{


    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', 'text', ['label' => 'Nom', 'required' => true]);
        $formMapper->add('description', CKEditorType::class, ['label' => 'Description', 'required' => true]);
        $formMapper->add('date_start', 'date', ['label' => 'Date de début', 'required' => true]);
        $formMapper->add('date_end', 'date',['label' => 'Date de fin', 'required' => true]);
        $formMapper->add('prize', null, ['label' => 'Prix', 'required' => true]);
        $formMapper->add('rules', 'entity', ['required' => true,
        'label' => 'Règles',
        'multiple' => true,
        'class' => Rule::class]);
        $formMapper->add('status', null, ['label' => 'En ligne']);
//        $formMapper->add('participants', 'entity', ['class' => Participant::class, 'label' => 'Participants', 'disabled' => true]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name', null, ['label' => 'Nom'])
            ->add('description', null, ['label' => 'Description'])
            ->add('date_start', 'date', ['label' => 'Date de début'])
            ->add('date_end', 'date', ['label' => 'Date de fin'])
            ->add('status', null, ['label' => 'En ligne', 'editable' => true]);
    }
}