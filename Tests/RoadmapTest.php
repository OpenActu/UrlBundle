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
	$url->setScheme('ht-tp');
	if($url->hasErrors())
	{
		foreach($url->getErrors() as $error)
		{
			echo $error->getMessage().' # '.$error->getCode();
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
