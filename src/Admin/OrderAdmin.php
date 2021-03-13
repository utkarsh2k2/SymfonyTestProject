<?php

namespace App\Admin;

use App\Entity\Order;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class OrderAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('product', TextType::class);
        $formMapper->add('price', MoneyType::class, ['currency' => 'INR']);
        $formMapper->add('notes', TextareaType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('product');
        $datagridMapper->add('price');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('product');
        $listMapper->addIdentifier('price');
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->tab('Overview')
                        ->add('product')
                        ->add('price')
                        ->add('notes')
                    ->end()
            ->end();
        $showMapper->tab('Linking')
                        ->add('user')
                        ->add('employee')
                    ->end();
    }

    public function toString($object)
    {
        return $object instanceof Order ? $object->getProduct() : 'Order';
    }
}