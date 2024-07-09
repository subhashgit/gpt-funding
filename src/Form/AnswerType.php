<?php

namespace App\Form;

use App\Entity\Answer;
use App\Model\DeprivationData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('answer', TextareaType::class, [
            //     'label' => false,
            //     'attr' => [
            //         'data-controller' => 'ckeditor',
            //         'rows' => 10,
            //     ],
            // ])
            ->add('answer', CKEditorType::class, [
                'label' => false,
                // 'config' => [
                //     'toolbar' => 'my_config', // or any other toolbar configuration you need
                //     // 'extraPlugins' => 'askAI',
                //     // 'uiColor' => '#ffffff', // match the configuration in fos_ck_editor.yaml
                // ],
                'config_name' => 'custom',
                'attr' => [
                    'rows' => 10,
                ],
            ])
            ->add('instruction', NumberType::class, [
                'html5' => true,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Max words count',
                ],
            ])
            ->add('denominators', ChoiceType::class, [
                'label' => false,
                'multiple' => true,
                'required' => false,
                'placeholder' => 'Select indexes',
                'attr' => [
                    'data-controller' => 'select2',
                ],
                'choices' => array_flip(array_filter(DeprivationData::FIELDS, static fn ($item) => str_contains($item, 'Decile'))),
            ])

            ->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event) {
                /** @var Answer $data */
                $data = $event->getData();
                if ($data->isGpt() and $data->getAnswer()) {
                    $a = array_filter(explode("\n\n", $data->getAnswer()));
                    $a = array_map(fn ($item) => '<p>'.$item.'</p>', $a);
                    $a = implode('', $a);

                    $a = explode("\n", $a);
                    $a = implode('<br>', $a);

                    $a = str_replace('<p><br></p>', '<p>&nbsp;</p>', $a);
                    $a = str_replace('<p><p>', '<p>', $a);
                    $a = str_replace('</p></p>', '</p>', $a);

                    $a = str_replace('<br /><br>', '<br />', $a);
                    $a = str_replace('<br /><br>', '<br />', $a);
                    $a = str_replace('<br /><br>', '<br />', $a);
                    $a = str_replace('<br /><br>', '<br />', $a);
                    $a = str_replace('<br /><br>', '<br />', $a);
                    $a = str_replace('<br /><br>', '<br />', $a);
                    $a = str_replace('<br><br>', '<br>', $a);
                    $a = str_replace('<br><br>', '<br>', $a);
                    $a = str_replace('<br><br>', '<br>', $a);
                    $a = str_replace('<br><br>', '<br>', $a);
                    $a = str_replace('<br><br>', '<br>', $a);
                    $a = str_replace('<br /><br />', '<br />', $a);
                    $a = str_replace('<br /><br />', '<br />', $a);
                    $a = str_replace('<br /><br />', '<br />', $a);
                    $a = str_replace('<br /><br />', '<br />', $a);
                    $a = str_replace('<br /><br />', '<br />', $a);
                    $a = str_replace('<br /><br />', '<br />', $a);
                    $a = str_replace('<br /><br>', '<br />', $a);

                    $data->setAnswer($a);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }
}
