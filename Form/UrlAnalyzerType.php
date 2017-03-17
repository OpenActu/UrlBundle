<?php

namespace OpenActu\UrlBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
class UrlAnalyzerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('acceptUpdate',CheckboxType::class, array('required' => false))
		->add('acceptPurgeResponse',CheckboxType::class, array('required' => false))
		->add('isDir',CheckboxType::class, array('disabled' => true))
		->add('requestErrorMessage',TextType::class, array('disabled' => true))
		->add('createdAt',DateTimeType::class, array('disabled' => true))
		->add('updatedAt',DateTimeType::class, array('disabled' => true))
		->add('requestUri',TextType::class, array('disabled' => true))
		->add('requestUriWithoutQueryNorFragment',TextType::class, array('disabled' => true))
		->add('requestScheme',TextType::class, array('disabled' => true))
		->add('requestHost',TextType::class, array('disabled' => true))
		->add('requestSubdomain',TextType::class, array('disabled' => true))
		->add('requestDomain',TextType::class, array('disabled' => true))
		->add('requestTopLevelDomain',TextType::class, array('disabled' => true))
		->add('requestFolder',TextType::class, array('disabled' => true))
		->add('requestFilename',TextType::class, array('disabled' => true))
		->add('requestFilenameExtension',TextType::class, array('disabled' => true))
		->add('requestPath',TextType::class, array('disabled' => true))
		->add('requestQuery',TextType::class, array('disabled' => true))
		->add('requestFragment',TextType::class, array('disabled' => true))
		->add('responseUrl', TextType::class, array('disabled' => true))
		->add('contentType', TextType::class, array('disabled' => true))
		->add('httpCode', IntegerType::class, array('disabled' => true))
		->add('headerSize', IntegerType::class, array('disabled' => true))
		->add('requestSize', IntegerType::class, array('disabled' => true))
		->add('filetime', IntegerType::class, array('disabled' => true))
		->add('sslVerifyResult', IntegerType::class, array('disabled' => true))
		->add('redirectCount', IntegerType::class, array('disabled' => true))
		->add('totalTime',NumberType::class, array('disabled' => true))
		->add('namelookupTime',NumberType::class, array('disabled' => true))
		->add('connectTime',NumberType::class, array('disabled' => true))
		->add('pretransferTime',NumberType::class, array('disabled' => true))
		->add('sizeUpload', IntegerType::class, array('disabled' => true))
		->add('sizeDownload', IntegerType::class, array('disabled' => true))
		->add('speedDownload', IntegerType::class, array('disabled' => true))	
		->add('speedUpload', IntegerType::class, array('disabled' => true))
		->add('downloadContentLength', IntegerType::class, array('disabled' => true))
		->add('uploadContentLength', IntegerType::class, array('disabled' => true))
		->add('starttransferTime',NumberType::class, array('disabled' => true))
		->add('redirectTime',NumberType::class, array('disabled' => true))
		->add('redirectUrl', TextType::class, array('disabled' => true))
		->add('permissions', TextType::class, array('disabled' => true))
		->add('primaryIp', TextType::class, array('disabled' => true))
		->add('certinfo', CollectionType::class, array('disabled' => true))
		->add('primaryPort', IntegerType::class, array('disabled' => true))
		->add('localIp', TextType::class, array('disabled' => true))
		->add('localPort', IntegerType::class, array('disabled' => true))
		
		//->add('response')
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
        return 'openactu_urlbundle_urlanalyzer';
    }


}
