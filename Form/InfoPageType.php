<?php

namespace nacholibre\PagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use nacholibre\DoctrineTranslatableFormBundle\Form\AbstractTranslatableType;
use nacholibre\DoctrineTranslatableFormBundle\Form\TranslatableTextType;
use nacholibre\DoctrineTranslatableFormBundle\Form\TranslatableTextareaType;

class InfoPageType extends AbstractTranslatableType
{
    private $container;

    function __construct($container, $mapper) {
        $this->container = $container;
        parent::__construct($mapper);
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $parameters = $this->container->getParameter('nacholibre_pages');
        $editor = $parameters['editor'];

        $translatableBuilder = $this->createTranslatableMapper($builder, $options);

        $translatableBuilder
            ->add("name", TextType::class, [
                'required' => true,
            ])
        ;

        //$builder->add('name', TextType::class, [
        //    'label' => 'Name',
        //    'required' => true,
        //]);

        //$builder->add('name_en', TextType::class, [
        //    'label' => 'Name',
        //    'required' => true,
        //    'mapped' => false,
        //]);

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

            //$builder->add('content', 'Ivory\CKEditorBundle\Form\Type\CKEditorType' , [
            //    'config_name' => $editor['config_name'],
            //    'config' => $editorConfig,
            //    'label' => 'Content',
            //    'required' => true,
            //]);

            $translatableBuilder->add('content', 'Ivory\CKEditorBundle\Form\Type\CKEditorType' , [
                'config_name' => $editor['config_name'],
                'config' => $editorConfig,
                'label' => 'Content',
                'required' => true,
            ]);
        } else {
            $translatableBuilder->add('content', TextareaType::class , [
                'label' => 'Content',
                'required' => true,
            ]);
        }

        $builder->add('desc2', TextType::class, [
            'label' => 'test',
        ]);

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

        $this->configureTranslationOptions($resolver);
    }
}
