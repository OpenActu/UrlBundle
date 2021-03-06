<?php
namespace OpenActu\UrlBundle\Model\ProtocolSender;

use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use OpenActu\UrlBundle\Model\Url;
interface ProtocolSenderInterface
{
	public function sendRequest(UrlAnalyzer &$object,Url &$url,array $parameters,array $config=array());
}
