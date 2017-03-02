<?php
namespace OpenActu\UrlBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
class RoadmapTest extends KernelTestCase
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
	$url = $this->container->get('open-actu.url.manager');
	
	/**
 	 * Build url with approach object
	 *
	 * step 1 : check getters / setters
	 * @author   yanroussel <yanroussel@gmail.com>	 
	 */	
	$asserts = array(
			array(
				'scheme' => 'http', 
				'host' => 'www.google.fr', 
				'port' => 80,
				'port_mode' => 'normal',
				'attempted_port' => null,
				'path' => 'folder1/folder2/filename.txt', 
				'fragment' => 'test',
				'query' => 'ping=pong&test=0',
				'equals' => 'http://www.google.fr:80/folder1/folder2/filename.txt?test=0&ping=pong#test'
			),
			array(
				'scheme' => 'http', 
				'host' => 'google.fr', 
				'port' => null,
				'port_mode' => 'forced',
				'attempted_port' => 80, 
				'path' => 'folder/filename.txt',
				'equals' => 'http://google.fr/folder/filename.txt',
			),
			array(
				'scheme' => 'http', 
				'host' => 'google.fr', 
				'port' => null,
				'port_mode' => 'none',
				'attempted_port' => null, 
				'path' => 'filename.txt',
				'equals' => 'http://google.fr/filename.txt'
			),
			array(
				'scheme' => 'http', 
				'host' => 'localhost', 
				'port' => 8080,
				'port_mode' => 'normal',
				'attempted_port' => 8080, 
				'path' => 'folder/.htaccess',
				'equals' => 'http://localhost:8080/folder/.htaccess',
			),
			array(
				'scheme' => 'https', 
				'host' => '127.0.0.1', 
				'port' => 8080,
				'port_mode' => 'normal',
				'attempted_port' => 8080,
				'path' => 'folder',
				'fragment' => 'thisIsFreeMessage',
				'equals' => 'https://127.0.0.1:8080/folder#thisIsFreeMessage',
			),
	);
	foreach($asserts as $assert)
	{
		$url->reset();
		$url->setPath($assert['path']);
		$url->changePortMode($assert['port_mode']);
		$url->setScheme($assert['scheme']);
		$url->setHost($assert['host']);
		if(!empty($assert['query']))
			$url->setQuery($assert['query']);
		if(!empty($assert['fragment']))		
			$url->setFragment($assert['fragment']);
		if($assert['port'])
			$url->setPort($assert['port']);
		if($url->hasErrors())
		{
			foreach($url->getErrors() as $error)
			{
				echo $error->getMessage().' # '.$error->getCode();
			}
		}
		else
		{
			$this->assertTrue(
				($url->getScheme() === $assert['scheme']) && 
				($url->getHost() === $assert['host']) &&
				($url->getPort() === $assert['attempted_port']) &&
				($url->getPath() === $assert['path'])
			);
			
			if(!empty($assert['equals']))
				$this->assertTrue($url->isEquals($assert['equals']));
		
		}
	}
	
	/**
	$urls = array(
		'https://www.ola.fr',
		'htTp://www.lemAonde.fr/x/y//z/test.html?name=toto',
		'http://www.boursier.com/file.txt.old#:no-limit',
		'www.afp.fr/article.php',
		'localhost',
	);
	foreach($urls as $item){
		$url->load($item);
		//$new_url = $url->withPath('/toto.thml');
		$new_url = $url->withQuery('iti=toto&alfred=34&à la bonne franquette=c\'est trop top!');
		
		
		echo $new_url."\r\n";
	}
	*/
	$this->assertTrue(true);
    }
}
