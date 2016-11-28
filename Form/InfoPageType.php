<?php

namespace nacholibre\PagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class InfoPageType extends AbstractType
{
    private $container;

    function __construct($container) {
        $this->container = $container;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $parameters = $this->container->getParameter('nacholibre_pages');
        $editor = $parameters['editor'];

        $builder->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
            ]);

        if ($editor['name'] == 'ckeditor') {
            $editorConfig = [
            ];

            if ($editor['elfinder_integration']) {
                $editorConfig['filebrowserBrowseRoute'] = $editor['elfinder_browse_route'];
                $editorConfig['filebrowserBrowseRouteParameters'] = [
                    'instance' => $editor['elfinder_instance'],
                    'homeFolder' => $editor['elfinder_homefolder'],
                ];
            }

            $builder->add('content', 'Ivory\CKEditorBundle\Form\Type\CKEditorType' , [
                'config_name' => $editor['config_name'],
                'config' => $editorConfig,
                'label' => 'Content',
                'required' => true,
            ]);

            $builder->add('content_bg', 'Ivory\CKEditorBundle\Form\Type\CKEditorType' , [
                'config_name' => $editor['config_name'],
                'config' => $editorConfig,
                'label' => 'Content BG',
                'required' => true,
                'mapped' => false,
            ]);
        } else {
            $builder->add('content', TextareaType::class , [
                'label' => 'Content',
                'required' => true,
            ]);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data->getStatic()) {
                $this->disableField($form->get('name'));
            }
        });
    }

    private function disableField($field) {
        $parent = $field->getParent();
        $options = $field->getConfig()->getOptions();
        $name = $field->getName();
        $type = $field->getConfig()->getType()->getName();
        //$parent->remove($name);
        $parent->add($name, $type, array_merge($options,['disabled' => true]));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $dataClass = $this->container->get('nacholibre.pages.manager')->getEntityClass();
        $resolver->setDefaults(array(
            'data_class' => $dataClass
        ));
    }
}
