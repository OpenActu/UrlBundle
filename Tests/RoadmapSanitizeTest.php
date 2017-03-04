<?php
namespace OpenActu\UrlBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
class RoadmapSanitizeTest extends KernelTestCase
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
	$this->validateService();

    }
    
    private function validateService()
    {
	$um = $this->container->get('open-actu.url.manager');
	
	/************************************************
	 *						*
	 * 	STEP 1 : sanitize validation		*
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
				'url' => 'http://www.google.fr/folder1/folder2/filename.txt?a=b&c=d&ping=pong#test',
			),
			'render' => array(
				'url' 		   => 'http://www.google.fr/folder1/folder2/filename.txt?c=d&ping=pong&a=b#test',
				'success' 	   => true,
				'port' 		   => null,
				'folder'	   => 'folder1/folder2',
				'filename'	   => 'filename',
				'filenameExtension'=> 'txt',
				'domain'	   => 'google',
				'subdomain'	   => 'www',
				'topLevelDomain'   => 'fr',
				'fragment'	   => 'test',
			),
		),
		'test2' => array(
			'config' => array(
				'port_mode' => 'normal'
			),
			'data' => array(
				'url' => 'www.sub.google.fr?a=b',
			),
			'render' => array(
				'url' 		   => 'http://www.sub.google.fr/?a=b',
				'success' 	   => true,
				'port' 		   => null,
				'folder'	   => null,
				'filename'	   => null,
				'filenameExtension'=> null,
				'domain'	   => 'google',
				'subdomain'	   => 'www.sub',
				'topLevelDomain'   => 'fr',
				'fragment'	   => null,
			),
		),
		/****************************************
		 * 					*
		 *	part 2 : file URL		*
		 * 	test 100 to 200			*
	 	 ***************************************/
		'test100' => array(
			'config' => array(
				'port_mode' => 'normal'
			),
			'data' => array(
				'url' => 'file:///path/subpath/filename.txt',
			),
			'render' => array(
				'url' 		   => 'file:///path/subpath/filename.txt',
				'success' 	   => true,
				'port' 		   => null,
				'folder'	   => 'path/subpath',
				'filename'	   => 'filename',
				'filenameExtension'=> 'txt',
				'domain'	   => null,
				'subdomain'	   => null,
				'topLevelDomain'   => null,
				'fragment'	   => null,
			),
		),
		'test101' => array(
			'config' => array(
				'port_mode' => 'normal'
			),

			'data' => array(

				'url' => '/path/subpath/filename.txt',
			),
			'render' => array(
				'url' 		   => 'file:///path/subpath/filename.txt',
				'success' 	   => true,
				'port' 		   => null,
				'folder'	   => 'path/subpath',
				'filename'	   => 'filename',
				'filenameExtension'=> 'txt',
				'domain'	   => null,
				'subdomain'	   => null,
				'topLevelDomain'   => null,
				'fragment'	   => null,
			),

		),
		'test102' => array(
			'config' => array(
				'port_mode' => 'normal'
			),
			'data' => array(
				'url' => 'C:\path\subpath\the filename.txt',
			),
			'render' => array(
				'url' 		   => 'file:///C:/path/subpath/the%20filename.txt',
				'success' 	   => true,
				'port' 		   => null,
				'folder'	   => 'C:/path/subpath',
				'filename'	   => 'the%20filename',
				'filenameExtension'=> 'txt',
				'domain'	   => null,
				'subdomain'	   => null,
				'topLevelDomain'   => null,
				'fragment'	   => null,
			),
		),
	);
	
	foreach($tests as $testName => $test)
	{
		$config = $test['config'];
		$data	= $test['data'];
		$render = $test['render'];

		// Configuration settings
		$um->changePortMode($config['port_mode']);
		
		// Data settings
		$um->sanitize($data['url'],true);
		
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
				'port'	            => ($um->getPort() === $render['port']),
				'folder'            => ($um->getFolder() === $render['folder']),
				'filename'          => ($um->getFilename() === $render['filename']),
				'filenameExtension' => ($um->getFilenameExtension() === $render['filenameExtension']),
				'url'		    => ($um->isEquals($render['url'])),	
				'domain'	    => ($um->getDomain() === $render['domain']),
				'subdomain'	    => ($um->getSubdomain() === $render['subdomain']),
			        'topLevelDomain'    => ($um->getTopLevelDomain() === $render['topLevelDomain']),
				'fragment'	    => ($um->getFragment() === $render['fragment']),
			);	
			$check = true;
			foreach($test as $subject => $result)
			{
				$check = $check && $result;
			}

			if($check === false)
			{
				echo "\r\n-----------------\r\n".$um."\r\n".$render['url']."\r\n-----------------\r\n";
				
				var_dump($test);
			}			
			$this->assertTrue($check);
		}}
    }
}
