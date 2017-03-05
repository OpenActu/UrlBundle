<?php
namespace OpenActu\UrlBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use OpenActu\UrlBundle\Entity\LinkTest;
//use OpenActu\UrlBundle\Tests\Entity\LinkResponseTest;
class RoadmapSendTest extends KernelTestCase
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
	 * 	STEP 1 : sending validation		*
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
				'url' => 'http://www.lepoiwwwnt.fr/automobile/securite/segolene-royal-favorable-a-une-interdiction-complete-des-voitures-diesel-22-12-2016-2092285_657.php?test=toto#test',
			),
			'render' => array(
				
			),
		),
		/****************************************
		 * 					*
		 *	part 2 : file URL		*
		 * 	test 100 to 200			*
	 	 ***************************************/
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
		$link = new LinkTest();
		$um->send($link);

		// Validation step
		if($um->hasErrors())
		{
			foreach($um->getErrors() as $error)
			{
				echo $error->getMessage().' # '.$error->getCode();
			}
		}
		else
		{
			/** @todo **/
		}
		$em = $this->container->get('doctrine.orm.entity_manager');
		$em->persist($link);
		$em->flush();			
 
	}
   }
}
