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
			array('scheme' => 'http', 'host' => 'www.google.fr', 'port' => 80,'port_mode' => 'normal','attempted_port' => null),
			array('scheme' => 'http', 'host' => 'google.fr', 'port' => null,'port_mode' => 'forced','attempted_port' => 80),
			array('scheme' => 'http', 'host' => 'google.fr', 'port' => null,'port_mode' => 'none','attempted_port' => null),
			array('scheme' => 'http', 'host' => 'localhost', 'port' => 8080,'port_mode' => 'normal','attempted_port' => 8080),
			array('scheme' => 'https', 'host' => '127.0.0.1', 'port' => 8080,'port_mode' => 'normal','attempted_port' => 8080),
	);
	foreach($asserts as $assert)
	{
		$url->reset();
		$url->changePortMode($assert['port_mode']);
		$url->setScheme($assert['scheme']);
		$url->setHost($assert['host']);
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
				($url->getPort() === $assert['attempted_port'])
			);
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
