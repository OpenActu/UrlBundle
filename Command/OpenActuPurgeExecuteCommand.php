<?php

namespace OpenActu\UrlBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use OpenActu\UrlBundle\Model\UrlManager;
use OpenActu\UrlBundle\Model\CommandManager;
class OpenActuPurgeExecuteCommand extends ContainerAwareCommand
{
    /**
     * @var Console manager
     */
    private $manager;

    protected function configure()
    {
        $this
            ->setName('open-actu:purge:execute')
            ->setDescription('Purge of the responses depending on the delay requirements and the purge\'s authorization at true')
            ->addArgument('delay', InputArgument::REQUIRED, 'delay (only if you don\'t want the default delay)')
	    ->addArgument('unit', InputArgument::REQUIRED, '(default: hour) unit used for delay.the unit accepted are second, minute, hour, day or month')
 	    ->addArgument('class', InputArgument::REQUIRED, 'class name of form <YourBundle>:<YourEntity>')
	    ->addOption('unlock', 'u', InputOption::VALUE_OPTIONAL, 'force to unlock the current task', false)	
	    ->addOption('reset-log', 'r', InputOption::VALUE_OPTIONAL, 'force to reset the log file stored in /app/logs', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	
	$this->manager 	= new CommandManager($this->getName(),$input, $output);
	$em		= $this->getContainer()->get('doctrine.orm.entity_manager');
	
	$delay 	= $input->getArgument('delay');
	$unit	= $input->getArgument('unit');
	$class	= $input->getArgument('class');
	
	$units	= array(
	  UrlManager::PURGE_UNIT_SECOND,
	  UrlManager::PURGE_UNIT_MINUTE,
	  UrlManager::PURGE_UNIT_DAY,
	  UrlManager::PURGE_UNIT_HOUR,
	  UrlManager::PURGE_UNIT_MONTH
	);

	if(!is_numeric($delay))
	{
		$this->manager->addError('the delay must be numeric ("'.$delay.'" given)');
		return;
	}
	
	if(!in_array($unit,$units))
	{
		$this->manager->addError('the unit can be '.implode(', ',$units).' ('.$unit.' given)');
		return;
	}

	try
	{
		$repo 	= $em->getRepository($class);
	}
	catch(\Exception $e)
	{
		$this->manager->addError($e->getMessage());
		return;
	}
	$entities	= $repo->getEntitiesToBePurged($delay,$unit);
	$cpt		= count($entities);
        foreach($entities as $entity)
	{
		/**
		 * response removing
		 *
		 */
		$response = $em->getRepository($entity->getResponseClass())->find($entity->getResponse()->getId());
		$em->remove($response);
		
		$entity->setResponse(null);
	}
	$em->flush();
	
	$msg = $cpt.' response'.(($cpt>1) ? 's' : '').' purged';
	$this->manager->addInfo($msg);

    }

}
