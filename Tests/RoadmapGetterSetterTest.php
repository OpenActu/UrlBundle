<?php
namespace OpenActu\UrlBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
class RoadmapSetterGetterTest extends KernelTestCase
{
    private $container;

    public function __construct()
    {
	self::bootKernel();
	$this->container = self::$kernel->getContainer();
    }
    public function testIndex()
    {
	
	/**
	 * testing when calling as service
 	 */
	$this->validateUrlManagerService();

    }
    
    private function validateUrlManagerService()
    {
	$um = $this->container->get('open-actu.url.manager');
	
	/************************************************
	 *						*
	 * 	STEP 1 : getter / setter validation	*
	 *						*
	 ************************************************/
	$tests = array(

		/****************************************
		 * 					*
		 *	part 1 : http / https URL	*
		 * 	test 1 to 100			*
	 	 ***************************************/
		'test1' => array(
			'config' => array(
				'port_mode' => 'normal'
			),
			'data' => array(
				'scheme' => 'http', 
				'host' => 'www.google.fr', 
				'port' => 80,
				'path' => 'folder1/folder2/filename.txt', 
				'query' => 'ping=pong&test=0',
				'fragment' => 'test'
			),
			'render' => array(
				'url' 		   => 'http://www.google.fr/folder1/folder2/filename.txt?test=0&ping=pong#test',
				'success' 	   => true,
				'port' 		   => null,
				'folder'	   => 'folder1/folder2',
				'filename'	   => 'filename',
				'filenameExtension'=> 'txt',
				'domain'	   => 'google',
				'subdomain'	   => 'www',
				'topLevelDomain'   => 'fr',
			),
		),
		'test2' => array(
			'config' => array(
				'port_mode' => 'none'
			),
			'data' => array(
				'scheme' => 'http', 
				'host' => 'google.fr', 
				'port' => null,
				'path' => 'filename.txt', 
				'query' => null,
				'fragment' => null
			),
			'render' => array(
				'url' 		   => 'http://google.fr/filename.txt',
				'success' 	   => true,
				'port' 		   => null,
				'folder'	   => null,
				'filename'	   => 'filename',
				'filenameExtension'=> 'txt',
				'domain'	   => 'google',
				'subdomain'	   => null,
                                'topLevelDomain'   => 'fr',
			),
		),
		'test3' => array(
			'config' => array(
				'port_mode' => 'forced'
			),
			'data' => array(
				'scheme' => 'http', 
				'host' => 'google.fr', 
				'port' => null,
				'path' => 'folder/filename.txt', 
				'query' => null,
				'fragment' => null
			),
			'render' => array(
				'url' 		   => 'http://google.fr:80/folder/filename.txt',
				'success' 	   => true,
				'port' 		   => 80,
				'folder'	   => 'folder',
				'filename'	   => 'filename',
				'filenameExtension'=> 'txt',
				'domain'	   => 'google',
				'subdomain'	   => null,
                                'topLevelDomain'   => 'fr',
			),
		),
		'test4' => array(
			'config' => array(
				'port_mode' => 'none'
			),
			'data' => array(
				'scheme' => 'http', 
				'host' => 'google.fr', 
				'port' => null,
				'path' => 'filename.txt', 
				'query' => null,
				'fragment' => null
			),
			'render' => array(
				'url' 		   => 'http://google.fr/filename.txt',
				'success' 	   => true,
				'port' 		   => null,
				'folder'	   => null,
				'filename'	   => 'filename',
				'filenameExtension'=> 'txt',
				'domain'	   => 'google',
				'subdomain'	   => null,
                                'topLevelDomain'   => 'fr',
			),
		),
		'test5' => array(
			'config' => array(
				'port_mode' => 'normal'
			),
			'data' => array(
				'scheme' => 'http', 
				'host' => 'localhost', 
				'port' => 8080,
				'path' => 'folder/.htaccess', 
				'query' => null,
				'fragment' => null
			),
			'render' => array(
				'url' 		   => 'http://localhost:8080/folder/.htaccess',
				'success' 	   => true,
				'port' 		   => 8080,
				'folder'	   => 'folder',
				'filename'	   => '.htaccess',
				'filenameExtension'=> null,
				'domain'	   => 'localhost',
				'subdomain'	   => null,
                                'topLevelDomain'   => null,
			),
		),
		'test6' => array(
			'config' => array(
				'port_mode' => 'normal'
			),
			'data' => array(
				'scheme' => 'https', 
				'host' => '127.0.0.1', 
				'port' => 8080,
				'path' => 'folder', 
				'query' => null,
				'fragment' => 'thisIsFreeMessage'
			),
			'render' => array(
				'url' 		   => 'https://127.0.0.1:8080/folder#thisIsFreeMessage',
				'success' 	   => true,
				'port' 		   => 8080,
				'folder'	   => null,
				'filename'	   => 'folder',
				'filenameExtension'=> null,
				'domain'	   => '127.0.0.1',
				'subdomain'	   => null,
                                'topLevelDomain'   => null,
			),
		),
		'test7' => array(
			'config' => array(
				'port_mode' => 'normal'
			),
			'data' => array(
				'scheme' => 'https', 
				'host' => '127.0.0.1', 
				'port' => 8080,
				'path' => '.folder', 
				'query' => null,
				'fragment' => 'thisIsFreeMessage'
			),
			'render' => array(
				'url' 		   => 'https://127.0.0.1:8080/.folder#thisIsFreeMessage',
				'success' 	   => true,
				'port' 		   => 8080,
				'folder'	   => null,
				'filename'	   => '.folder',
				'filenameExtension'=> null,
				'domain'	   => '127.0.0.1',
				'subdomain'	   => null,
                                'topLevelDomain'   => null,
			),
		),
		/****************************************
		 * 					*
		 *	part 2 : file URL		*
		 * 	test 101 to 200			*
	 	 ***************************************/
		'test101' => array(
			'config' => array(
				'port_mode' => 'normal'
			),
			'data' => array(
				'scheme' => 'file', 
				'host' => null, 
				'port' => null,
				'path' => 'path/.folder', 
				'query' => null,
				'fragment' => null
			),
			'render' => array(
				'url' 		   => 'file:///path/.folder',
				'success' 	   => true,
				'port' 		   => null,
				'folder'	   => 'path',
				'filename'	   => '.folder',
				'filenameExtension'=> null,
				'domain'	   => null,
				'subdomain'	   => null,
                                'topLevelDomain'   => null,
			),
		),

	);
	
	foreach($tests as $testName => $test)
	{
		$config = $test['config'];
		$data	= $test['data'];
		$render = $test['render'];

		// First, we initialize the URL manager
		$um->reset();

		// Configuration settings
		$um->changePortMode($config['port_mode']);
		
		// Data settings
		$um->setScheme($data['scheme']);
		$um->setHost($data['host']);
		$um->setPort($data['port']);
		$um->setPath($data['path']);
		$um->setQuery($data['query']);
		$um->setFragment($data['fragment']);
		
		// Validation step
		if($um->hasErrors())
		{
			$test = !($render['success']===false);
			if($test)
			{
				foreach($um->getErrors() as $error)
				{
					echo $error->getMessage().' # '.$error->getCode();
				}
			}
			$this->assertTrue($render['success']===false);
		}
		else
		{
			$test = array(
				$render['url']      => true,
				'success'           => ($render['success']===true),
				'scheme'            => ($um->GetScheme() === $data['scheme']),
				'host'	            => ($um->getHost() === $data['host']),
				'port'	            => ($um->getPort() === $render['port']),
				'folder'            => ($um->getFolder() === $render['folder']),
				'filename'          => ($um->getFilename() === $render['filename']),
				'filenameExtension' => ($um->getFilenameExtension() === $render['filenameExtension']),
				'url'		    => ($um->isEquals($render['url'])),	
				'domain'	    => ($um->getDomain() === $render['domain']),
				'subdomain'	    => ($um->getSubdomain() === $render['subdomain']),
			        'topLevelDomain'    => ($um->getTopLevelDomain() === $render['topLevelDomain']),
			);	
			$check = true;
			foreach($test as $subject => $result)
			{
				$check = $check && $result;
			}

			if($check === false)
			{
				var_dump($test);
			}			
			$this->assertTrue($check);
		}
	}
}
}
