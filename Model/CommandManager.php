<?php
namespace OpenActu\UrlBundle\Model;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CommandManager{

	protected $_name;
	protected $_output;
	protected $_log;
	protected $_id;

	public function getLog(){ return $this->_log; }
	public function setLog($log){ $this->_log = $log; }
	public function getName(){ return $this->_name; }
	public function setName($name){ $this->_name = $name; }
	public function getOutput(){ return $this->_output; }
	public function setOutput($output){ $this->_output = $output; }
	public function getId(){ return $this->_id; }
	public function setId($id){ $this->_id = $id; }
	public function __construct($name,InputInterface $input,OutputInterface $output){

		$unlock 		= ($input->getOption('unlock') == 'true');
		$reset_log		= ($input->getOption('reset-log') == 'true');

		$this->setId(str_pad(rand(1,99999),5,"0",STR_PAD_LEFT));
		$this->setName($name);
		$this->setOutput($output);
		$this->logStart($reset_log);
		if($unlock)
			$this->unlock();
		$this->lock();

	}

	public function __destruct(){
		$this->unlock();
	}

        public static function getVarRootDir()
        {
		return realpath(__DIR__.'/../../../../var');
        }
	/**
	 *	start the log processing
	 *
	 */
	private function logStart($reset_log = false){
		$this->setLog(new Logger($this->getName()));
		$fog = self::getVarRootDir().'/logs/'.preg_replace("/[^a-z0-9]/i",'-',$this->getName()).'.log';
		
		if($reset_log)
			@unlink($fog);
		$this->getLog()->pushHandler(new StreamHandler($fog, Logger::INFO));
	}

	private function format_label($text){
		return '[P'.$this->getId().'] '.$text;
	}
	/**
         *	add warning message
	 *
	 */
	public function addWarning($text){
		$log = $this->getLog();
		if(!isset($log))
			$this->log_start();
		$this->getOutput()->writeln('<error>'.$text.'</error>');
		$this->getLog()->addWarning($this->format_label($text));
	}

	/**
	 *	add info message
	 *
	 */
	public function addInfo($text){
		$log = $this->getLog();
		if(!isset($log))
			$this->log_start();
		$this->getOutput()->writeln('<info>'.$text.'</info>');
		$this->getLog()->addInfo($this->format_label($text));
	}
	/**
	 *	add error message
	 *
	 */
	public function addError($text){
		$log = $this->getLog();
		if(!isset($log))
			$this->log_start();
		$this->getOutput()->writeln('<error>'.$text.'</error>');
		$this->getLog()->addError($this->format_label($text));
	}

	/**
	 *	lock current process
	 *
	 */
	protected function lock(){
		$path = self::getVarRootDir();
		if(!realpath($path.'/locks'))
			mkdir($path.'/locks');
		$lock = $path.'/locks/'.preg_replace("/[^a-z0-9]/i",'-',$this->getName()).'.lck';
		if(file_exists($lock)){
			$this->addError('process killed ... task allready locked !');
			die();
		}
		file_put_contents($lock,'this file serve to lock the current task. To reactivate this task, just delete this file');
		$this->addInfo('process started');
	}

	/**
	 *	unlock current process
	 *
	 */
	protected function unlock(){
		$lock = self::getVarRootDir().'/locks/'.preg_replace("/[^a-z0-9]/i",'-',$this->getName()).'.lck';
		if(file_exists($lock))
			unlink($lock);
		$this->addInfo('process stopped');
	}
}
