<?php

namespace OpenActu\UrlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * UrlCoreAnalyzer
 *
 * @ORM\Table(name="url_core_analyzer")
 * @ORM\Entity(repositoryClass="OpenActu\UrlBundle\Repository\UrlCoreAnalyzerRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UrlCoreAnalyzer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="classname", type="string", length=255)
     */
    private $classname;

    /**
     * @var string
     *
     * @ORM\Column(name="uri_calculated", type="text")
     */
    private $uriCalculated;

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="text")
     */
    protected $uri;

    /**
     * @var string
     *
     * @ORM\Column(name="uri_without_query_nor_fragment", type="text")
     */
    protected $uriWithoutQueryNorFragment;

    /**
     * @var string
     *
     * @ORM\Column(name="scheme", type="string", nullable=false, length=20)
     */
    protected $scheme;

    /**
     * @var string
     *
     * @ORM\Column(name="host", type="string", nullable=true, length=255)
     */
    protected $host;

    /**
     * @var string
     *
     * @ORM\Column(name="subdomain", type="string", nullable=true, length=100)
     */
    protected $subdomain;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", nullable=true, length=100)
     */
    protected $domain;

    /**
     * @var string
     *
     * @ORM\Column(name="top_level_domain", type="string",nullable=true, length=20)
     */
    protected $topLevelDomain;

    /**
     * @var string
     *
     * @ORM\Column(name="folder", type="string",nullable=true,length=255)
     */
    protected $folder;

    /**
     * @var string
     * 
     * @ORM\Column(name="filename", type="string",nullable=true,length=255)
     */
    protected $filename;
    
    /**
     * @var string
     *
     * @ORM\Column(name="filename_extension", type="string",nullable=true,length=20)
     */
    protected $filenameExtension;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string",nullable=true,length=255)
     */
    protected $path;

    /**
     * @var string
     *
     * @ORM\Column(name="query", type="text", nullable=true)
     */
    protected $query;

    /**
     * @var string
     *
     * @ORM\Column(name="fragment", type="text", nullable=true)
     */
    protected $fragment;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    protected $statistics=array();
    
    /**
     * @var integer
     *
     */
    protected $currentId = -1;
    
    public function getCurrentId()
    {
	return $this->currentId;
    }
    
    public function setCurrentId($id)
    {
	$this->currentId = $id;

        return $this;
    }

    public function setStatistics(array $statistics)
    {
	$this->statistics = $statistics;
    
	return $this;
    }

    public function getStatistics()
    {
	return $this->statistics;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return UrlAnalyzer
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set classname
     *
     * @param string $classname
     *
     * @return UrlCoreAnalyzer
     */
    public function setClassname($classname)
    {
        $this->classname = $classname;

        return $this;
    }

    /**
     * Get classname
     *
     * @return string
     */
    public function getClassname()
    {
        return $this->classname;
    }

    /**
     * Set uriCalculated
     *
     * @param string $uriCalculated
     *
     * @return UrlCoreAnalyzer
     */
    public function setUriCalculated($uriCalculated)
    {
        $this->uriCalculated = $uriCalculated;

        return $this;
    }

    /**
     * Get uriCalculated
     *
     * @return string
     */
    public function getUriCalculated()
    {
        return $this->uriCalculated;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Set uri
     *
     * @param string $uri
     *
     * @return UrlCoreAnalyzer
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set uriWithoutQueryNorFragment
     *
     * @param string $uriWithoutQueryNorFragment
     *
     * @return UrlCoreAnalyzer
     */
    public function setUriWithoutQueryNorFragment($uriWithoutQueryNorFragment)
    {
        $this->uriWithoutQueryNorFragment = $uriWithoutQueryNorFragment;

        return $this;
    }

    /**
     * Get uriWithoutQueryNorFragment
     *
     * @return string
     */
    public function getUriWithoutQueryNorFragment()
    {
        return $this->uriWithoutQueryNorFragment;
    }

    /**
     * Set scheme
     *
     * @param string $scheme
     *
     * @return UrlCoreAnalyzer
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * Get scheme
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Set host
     *
     * @param string $host
     *
     * @return UrlCoreAnalyzer
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set subdomain
     *
     * @param string $subdomain
     *
     * @return UrlCoreAnalyzer
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    /**
     * Get subdomain
     *
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * Set domain
     *
     * @param string $domain
     *
     * @return UrlCoreAnalyzer
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set topLevelDomain
     *
     * @param string $topLevelDomain
     *
     * @return UrlCoreAnalyzer
     */
    public function setTopLevelDomain($topLevelDomain)
    {
        $this->topLevelDomain = $topLevelDomain;

        return $this;
    }

    /**
     * Get topLevelDomain
     *
     * @return string
     */
    public function getTopLevelDomain()
    {
        return $this->topLevelDomain;
    }

    /**
     * Set folder
     *
     * @param string $folder
     *
     * @return UrlCoreAnalyzer
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return UrlCoreAnalyzer
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filenameExtension
     *
     * @param string $filenameExtension
     *
     * @return UrlCoreAnalyzer
     */
    public function setFilenameExtension($filenameExtension)
    {
        $this->filenameExtension = $filenameExtension;

        return $this;
    }

    /**
     * Get filenameExtension
     *
     * @return string
     */
    public function getFilenameExtension()
    {
        return $this->filenameExtension;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return UrlCoreAnalyzer
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set query
     *
     * @param string $query
     *
     * @return UrlCoreAnalyzer
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set fragment
     *
     * @param string $fragment
     *
     * @return UrlCoreAnalyzer
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * Get fragment
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist(LifecycleEventArgs $event)
    {
	
		/**
		 * createdAt
	         */
		$this->createdAt = new \DateTime();
	
    }

}
