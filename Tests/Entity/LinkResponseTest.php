<?php

namespace OpenActu\UrlBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenActu\UrlBundle\Entity\UrlResponseAnalyzer;
/**
 * LinkResponseTest
 *
 * @ORM\Table(name="link_response_test")
 * @ORM\Entity(repositoryClass="OpenActu\UrlBundle\Tests\Repository\LinkResponseTestRepository")
 */
class LinkResponseTest extends UrlResponseAnalyzer{
}
