<?php

namespace OpenActu\UrlBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use OpenActu\UrlBundle\Model\UrlManager;
class UrlAnalyzerAddType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('classname',HiddenType::class,array('data' => UrlAnalyzer::class, 'mapped' => false))		
		->add('acceptPurgeResponse',CheckboxType::class, array('required' => false))
		->add('useUrlWithoutQueryNorFragment', CheckboxType::class,array('required' => false))
		->add('encodeUrl',CheckboxType::class, array('required' => false, 'label' => 'encode url'))
		->add('requestUri',TextType::class, array('disabled' => false))
		->add('portMode',ChoiceType::class, array('disabled' => false,'choices' => array( UrlManager::PORT_MODE_NORMAL => UrlManager::PORT_MODE_NORMAL ,UrlManager::PORT_MODE_FORCED => UrlManager::PORT_MODE_FORCED, UrlManager::PORT_MODE_NONE => UrlManager::PORT_MODE_NONE)))
		->add('__add', SubmitType::class, array('label' => 'load url'))
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenActu\UrlBundle\Entity\UrlAnalyzer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'openactu_urlbundle_urlanalyzer_add';
    }


}
