<?php

namespace OpenActu\UrlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use OpenActu\UrlBundle\Entity\LinkResponseTest;
/**
 * LinkTest
 *
 * @ORM\Table(name="link_test")
 * @ORM\Entity(repositoryClass="OpenActu\UrlBundle\Repository\LinkTestRepository")
 */
class LinkTest extends UrlAnalyzer{
	
	
	public function __construct()
	{
		$this->setResponse(new LinkResponseTest());
	}
	public function getResponseClass()
	{
		return LinkResponseTest::class;
	}
}
