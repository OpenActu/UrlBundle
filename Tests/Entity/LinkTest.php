<?php

namespace OpenActu\UrlBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use OpenActu\UrlBundle\Tests\Entity\LinkResponseTest;

/**
 * LinkTest
 *
 * @ORM\Table(name="link_test")
 * @ORM\Entity(repositoryClass="OpenActu\UrlBundle\Tests\Repository\LinkTestRepository")
 */
class LinkTest extends UrlAnalyzer{
	
	public function __construct()
	{
		$this->setResponse(new LinkResponseTest());
	}
}
