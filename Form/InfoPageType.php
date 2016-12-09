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
use Symfony\Component\Validator\Constraints as Assert;

use nacholibre\AdminBundle\Form\DynamicSlugType;

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
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $parameters = $this->container->getParameter('nacholibre_pages');
        $editor = $parameters['editor'];

        $translatableBuilder = $this->createTranslatableMapper($builder, $options);

        $translator = $this->container->get('translator');

        $page = $builder->getData();

        $translatableBuilder
            ->add("name", TextType::class, [
                'label' => $translator->trans('name'),
                'required' => true,
                //'constraints' => [
                //    new Assert\NotBlank(),
                //],
                'constraints_required_locales' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('slug', DynamicSlugType::class, [
                'label' => $translator->trans('slug'),
                'required' => true,
                'slug_input' => 'name',
                'toggable' => $page->getStatic() == false,
                'constraints_required_locales' => [
                    new Assert\NotBlank(),
                ],
                //'disabled' => true,
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
                'label' => $translator->trans('content'),
                'required' => true,
                //'constraints' => [
                //    new Assert\NotBlank(),
                //]
            ]);
        } else {
            $translatableBuilder->add('content', TextareaType::class , [
                'label' => $translator->trans('content'),
                'required' => true,
                //'constraints' => [
                //    new Assert\NotBlank(),
                //]
            ]);
        }

        //$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
        //    $data = $event->getData();
        //    $form = $event->getForm();

        //    if ($data->getStatic()) {
        //        $this->disableField($form->get('name'));
        //    }
        //});
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
