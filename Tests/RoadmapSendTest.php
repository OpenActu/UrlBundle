<?php
namespace OpenActu\UrlBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
/**
 *   -------------------
 *   |                 |
 *   |  Configuration  |
 *   |                 |
 *   -------------------
 *
 *    write here the namespace URL you have declared
 * 
 */
//use <YourBundle>\Entity\<YourUrlEntity>;

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

	$usm= $this->container->get('open-actu.url_storage.manager');		
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
				'url' => 'http://www.lepoint.fr/automobile/securite/segolene-royal-favorable-a-une-interdiction-complete-des-voitures-diesel-22-12-2016-2092285_657.php?test=toto#test',
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
		
		/**
		 * sanitize area - first step to work
		 */
		/**
                 * 	write here your Entity URL
                 */
		//$link = $um->sanitize(<YourUrlEntity>::class,$data['url'],true);
		echo 'You must configure the roadmap in line '.__LINE__;
		return;
		# we push (this is not obligatory)
//		$usm->push($link);

		if(null !== $link && !$um->hasErrors())
		{
			/**
			 * now we can send request and receive response
			 */
			$um->send($link);
			
			/**
			 * we can store the object in database
			 */
			$usm->push($link);

		}
		elseif($um->hasErrors())
		{
			$usm->push($link);
		}		
	}
   }
}
