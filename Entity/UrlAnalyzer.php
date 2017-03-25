<?php

namespace OpenActu\UrlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
/**
 * UrlAnalyser
 *
 * @ORM\MappedSuperclass(repositoryClass="OpenActu\UrlBundle\Repository\UrlAnalyzerRepository")
 * @ORM\HasLifecycleCallbacks
 */
abstract class UrlAnalyzer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var array
     *
     * @ORM\Column(name="children", type="array", nullable=true)
     */
    private $children;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="accept_update",type="boolean",nullable=true)
     */
    protected $acceptUpdate=true;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="accept_purge_response",type="boolean",nullable=true)
     */
    protected $acceptPurgeResponse=false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_dir",type="boolean",nullable=true)
     */
    protected $isDir=false;

    /**
     * @var string
     * 
     * @ORM\Column(name="request_error_message",type="string",nullable=true,length=255)
     */
    protected $requestErrorMessage;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var datetime
     * 
     * @ORM\Column(name="updated_at", type="datetime",nullable=true)
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="request_uri", type="text")
     */
    protected $requestUri;

    /**
     * @var string
     *
     * @ORM\Column(name="request_uri_without_query_nor_fragment", type="text")
     */
    protected $requestUriWithoutQueryNorFragment;

    /**
     * @var string
     *
     * @ORM\Column(name="request_uri_calculated",type="text")
     */
    protected $requestUriCalculated;

    /**
     * @var string
     *
     * @ORM\Column(name="request_scheme", type="string", nullable=false, length=20)
     */
    protected $requestScheme;

    /**
     * @var string
     *
     * @ORM\Column(name="request_host", type="string", nullable=true, length=255)
     */
    protected $requestHost;

    /**
     * @var string
     *
     * @ORM\Column(name="request_subdomain", type="string", nullable=true, length=100)
     */
    protected $requestSubdomain;

    /**
     * @var string
     *
     * @ORM\Column(name="request_domain", type="string", nullable=true, length=100)
     */
    protected $requestDomain;

    /**
     * @var string
     *
     * @ORM\Column(name="request_top_level_domain", type="string",nullable=true, length=20)
     */
    protected $requestTopLevelDomain;

    /**
     * @var string
     *
     * @ORM\Column(name="request_folder", type="string",nullable=true,length=255)
     */
    protected $requestFolder;

    /**
     * @var string
     * 
     * @ORM\Column(name="request_filename", type="string",nullable=true,length=255)
     */
    protected $requestFilename;
    
    /**
     * @var string
     *
     * @ORM\Column(name="request_filename_extension", type="string",nullable=true,length=20)
     */
    protected $requestFilenameExtension;

    /**
     * @var string
     *
     * @ORM\Column(name="request_path", type="string",nullable=true,length=255)
     */
    protected $requestPath;

    /**
     * @var string
     *
     * @ORM\Column(name="request_query", type="text", nullable=true)
     */
    protected $requestQuery;

    /**
     * @var string
     *
     * @ORM\Column(name="request_fragment", type="text", nullable=true)
     */
    protected $requestFragment;

    /**
     * @var string
     *
     * @ORM\Column(name="response_url", type="string", length=300, nullable=true)
     */
    protected $responseUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="content_type", type="string", length=255, nullable=true)
     */
    protected $contentType;

    /**
     * @var int
     *
     * @ORM\Column(name="http_code", type="integer", nullable=true)
     */
    protected $httpCode;

    /**
     * @var boolean
     *
     * @ORM\Column(name="encode_url", type="boolean", nullable=true)
     */
    protected $encodeUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="header_size", type="integer", nullable=true)
     */
    protected $headerSize;

    /**
     * @var int
     *
     * @ORM\Column(name="request_size", type="integer", nullable=true)
     */
    protected $requestSize;

    /**
     * @var int
     *
     * @ORM\Column(name="filetime", type="integer", nullable=true)
     */
    protected $filetime;

    /**
     * @var int
     *
     * @ORM\Column(name="ssl_verify_result", type="integer", nullable=true)
     */
    protected $sslVerifyResult;

    /**
     * @var int
     *
     * @ORM\Column(name="redirect_count", type="integer", nullable=true)
     */
    protected $redirectCount;

    /**
     * @var float
     *
     * @ORM\Column(name="total_time", type="float", nullable=true)
     */
    protected $totalTime;

    /**
     * @var float
     *
     * @ORM\Column(name="namelookup_time", type="float", nullable=true)
     */
    protected $namelookupTime;

    /**
     * @var float
     *
     * @ORM\Column(name="connect_time", type="float", nullable=true)
     */
    protected $connectTime;

    /**
     * @var float
     *
     * @ORM\Column(name="pretransfer_time", type="float", nullable=true)
     */
    protected $pretransferTime;

    /**
     * @var int
     *
     * @ORM\Column(name="size_upload", type="integer", nullable=true)
     */
    protected $sizeUpload;

    /**
     * @var int
     *
     * @ORM\Column(name="size_download", type="integer", nullable=true)
     */
    protected $sizeDownload;

    /**
     * @var int
     *
     * @ORM\Column(name="speed_download", type="integer", nullable=true)
     */
    protected $speedDownload;

    /**
     * @var int
     *
     * @ORM\Column(name="speed_upload", type="integer", nullable=true)
     */
    protected $speedUpload;

    /**
     * @var int
     *
     * @ORM\Column(name="download_content_length", type="integer", nullable=true)
     */
    protected $downloadContentLength;

    /**
     * @var int
     *
     * @ORM\Column(name="upload_content_length", type="integer", nullable=true)
     */
    protected $uploadContentLength;

    /**
     * @var float
     *
     * @ORM\Column(name="starttransfer_time", type="float", nullable=true)
     */
    protected $starttransferTime;

    /**
     * @var float
     *
     * @ORM\Column(name="redirect_time", type="float", nullable=true)
     */
    protected $redirectTime;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_url", type="string", length=300, nullable=true)
     */
    protected $redirectUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="permissions", type="string", length=4, nullable=true)
     */
    protected $permissions=null;

    /**
     * @var string
     *
     * @ORM\Column(name="primary_ip", type="string", length=30, nullable=true)
     */
    protected $primaryIp;

    /**
     * @var array
     *
     * @ORM\Column(name="certinfo", type="array", nullable=true)
     */
    protected $certinfo;

    /**
     * @var int
     *
     * @ORM\Column(name="primary_port", type="integer", nullable=true)
     */
    protected $primaryPort;

    /**
     * @var string
     *
     * @ORM\Column(name="local_ip", type="string", length=30, nullable=true)
     */
    protected $localIp;

    /**
     * @var int
     *
     * @ORM\Column(name="local_port", type="integer", nullable=true)
     */
    protected $localPort;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_children", type="integer", nullable=true)
     */
    protected $nbChildren;

    /**
     * @var bool
     *
     * @ORM\Column(name="use_url_without_query_nor_fragment", type="boolean",nullable=true)
     */
    protected $useUrlWithoutQueryNorFragment = true;

    /**
     * @ORM\OneToOne(targetEntity="OpenActu\UrlBundle\Entity\UrlResponseAnalyzer", cascade={"all"})
     */
    protected $response;

    /**
     * Statitics information
     */
    protected $statistics = array();

    /** 
     * @ORM\Column(type="enumUrlAnalyzerStatus") 
     */
    private $status;

    /**
     * @ORM\Column(type="enumUrlAnalyzerPortMode")
     */
    private $portMode;

    /**
     * Get statistics
     *
     * @return array
     */
    public function getStatistics()
    {
 	return $this->statistics;
    }

    /**
     * Set statistics
     *
     * @param array $statistic
     *
     * @return UrlAnalyzer
     */
    public function addStatistic(array $statistic)
    {
	$this->statistics[] = $statistic;
    }
    
    /**
     * Set statistics
     *
     * @param array[] $statistics
     *
     * @return UrlAnalyzer
     */
    public function setStatistics(array $statistics)
    {
	$this->statistics = $statistics;
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
     * Set requestUri
     *
     * @param string $requestUri
     *
     * @return UrlAnalyser
     */
    public function setRequestUri($requestUri)
    {
        $this->requestUri = $requestUri;

        return $this;
    }

    /**
     * Get requestUri
     *
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }

    /**
     * Set requestUriWithoutQueryNorFragment
     *
     * @param string $requestUriWithoutQueryNorFragment
     *
     * @return UrlAnalyzer
     */
    public function setRequestUriWithoutQueryNorFragment($requestUriWithoutQueryNorFragment)
    {
        $this->requestUriWithoutQueryNorFragment = $requestUriWithoutQueryNorFragment;

        return $this;
    }

    /**
     * Get requestUriWithoutQueryNorFragment
     *
     * @return string
     */
    public function getRequestUriWithoutQueryNorFragment()
    {
        return $this->requestUriWithoutQueryNorFragment;
    }

    /**
     * Set requestUriCalculated
     *
     * @param string $requestUriCalculated
     *
     * @return UrlAnalyzer
     */
    public function setRequestUriCalculated($requestUriCalculated)
    {
        $this->requestUriCalculated = $requestUriCalculated;

        return $this;
    }


    /**
     * Get requestUriCalculated
     *
     * @return string
     */
    public function getRequestUriCalculated()
    {
        return $this->requestUriCalculated;
    }

    /**
     * Set acceptUpdate
     *
     * @param string $acceptUpdate
     *
     * @return UrlAnalyzer
     */
    public function setAcceptUpdate($acceptUpdate)
    {
        $this->acceptUpdate = $acceptUpdate;

        return $this;
    }

    /**
     * Get acceptUpdate
     *
     * @return string
     */
    public function getAcceptUpdate()
    {
        return $this->acceptUpdate;
    }

    /**
     * Set nbChildren
     *
     * @param integer $nbChildren
     *
     * @return UrlAnalyzer
     */
    public function setNbChildren($nbChildren)
    {
        $this->nbChildren = $nbChildren;

        return $this;
    }

    /**
     * Get nbChildren
     *
     * @return integer
     */
    public function getNbChildren()
    {
        return $this->nbChildren;
    }

    /**
     * Set acceptPurgeResponse
     *
     * @param string $acceptPurgeResponse
     *
     * @return UrlAnalyzer
     */
    public function setAcceptPurgeResponse($acceptPurgeResponse)
    {
        $this->acceptPurgeResponse = $acceptPurgeResponse;

        return $this;
    }

    /**
     * Get acceptPurgeResponse
     *
     * @return string
     */
    public function getAcceptPurgeResponse()
    {
        return $this->acceptPurgeResponse;
    }

    /**
     * Set isDir
     *
     * @param boolean $isDir
     *
     * @return UrlAnalyzer
     */
    public function setIsDir($isDir)
    {
        $this->isDir = $isDir;

        return $this;
    }

    /**
     * Get isDir
     *
     * @return boolean
     */
    public function getIsDir()
    {
        return $this->isDir;
    }

    /**
     * Set useUrlWithoutQueryNorFragment
     *
     * @param boolean $useUrlWithoutQueryNorFragment
     *
     * @return UrlAnalyzer
     */
    public function setUseUrlWithoutQueryNorFragment($useUrlWithoutQueryNorFragment)
    {
        $this->useUrlWithoutQueryNorFragment = $useUrlWithoutQueryNorFragment;

        return $this;
    }

    /**
     * Get useUrlWithoutQueryNorFragment
     *
     * @return boolean
     */
    public function getUseUrlWithoutQueryNorFragment()
    {
        return $this->useUrlWithoutQueryNorFragment;
    }


    /**
     * Set requestQuery
     *
     * @param string $requestQuery
     *
     * @return UrlAnalyser
     */
    public function setRequestQuery($requestQuery)
    {
        $this->requestQuery = $requestQuery;

        return $this;
    }

    /**
     * Get requestQuery
     *
     * @return string
     */
    public function getRequestQuery()
    {
        return $this->requestQuery;
    }

    /**
     * Set requestFragment
     *
     * @param string $requestFragment
     *
     * @return UrlAnalyser
     */
    public function setRequestFragment($requestFragment)
    {
        $this->requestFragment = $requestFragment;

        return $this;
    }

    /**
     * Get requestFragment
     *
     * @return string
     */
    public function getRequestFragment()
    {
        return $this->requestFragment;
    }

    /**
     * Set responseUrl
     *
     * @param string $responseUrl
     *
     * @return UrlAnalyser
     */
    public function setResponseUrl($responseUrl)
    {
        $this->responseUrl = $responseUrl;

        return $this;
    }

    /**
     * Get responseUrl
     *
     * @return string
     */
    public function getResponseUrl()
    {
        return $this->responseUrl;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     *
     * @return UrlAnalyser
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set httpCode
     *
     * @param integer $httpCode
     *
     * @return UrlAnalyser
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    /**
     * Get httpCode
     *
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Set headerSize
     *
     * @param integer $headerSize
     *
     * @return UrlAnalyser
     */
    public function setHeaderSize($headerSize)
    {
        $this->headerSize = $headerSize;

        return $this;
    }

    /**
     * Get headerSize
     *
     * @return int
     */
    public function getHeaderSize()
    {
        return $this->headerSize;
    }

    /**
     * Set requestSize
     *
     * @param integer $requestSize
     *
     * @return UrlAnalyser
     */
    public function setRequestSize($requestSize)
    {
        $this->requestSize = $requestSize;

        return $this;
    }

    /**
     * Get requestSize
     *
     * @return int
     */
    public function getRequestSize()
    {
        return $this->requestSize;
    }

    /**
     * Set filetime
     *
     * @param integer $filetime
     *
     * @return UrlAnalyser
     */
    public function setFiletime($filetime)
    {
        $this->filetime = $filetime;

        return $this;
    }

    /**
     * Get filetime
     *
     * @return int
     */
    public function getFiletime()
    {
        return $this->filetime;
    }

    /**
     * Set sslVerifyResult
     *
     * @param integer $sslVerifyResult
     *
     * @return UrlAnalyser
     */
    public function setSslVerifyResult($sslVerifyResult)
    {
        $this->sslVerifyResult = $sslVerifyResult;

        return $this;
    }

    /**
     * Get sslVerifyResult
     *
     * @return int
     */
    public function getSslVerifyResult()
    {
        return $this->sslVerifyResult;
    }

    /**
     * Set redirectCount
     *
     * @param integer $redirectCount
     *
     * @return UrlAnalyser
     */
    public function setRedirectCount($redirectCount)
    {
        $this->redirectCount = $redirectCount;

        return $this;
    }

    /**
     * Get redirectCount
     *
     * @return int
     */
    public function getRedirectCount()
    {
        return $this->redirectCount;
    }

    /**
     * Set totalTime
     *
     * @param float $totalTime
     *
     * @return UrlAnalyser
     */
    public function setTotalTime($totalTime)
    {
        $this->totalTime = $totalTime;

        return $this;
    }

    /**
     * Get totalTime
     *
     * @return float
     */
    public function getTotalTime()
    {
        return $this->totalTime;
    }

    /**
     * Set namelookupTime
     *
     * @param float $namelookupTime
     *
     * @return UrlAnalyser
     */
    public function setNamelookupTime($namelookupTime)
    {
        $this->namelookupTime = $namelookupTime;

        return $this;
    }

    /**
     * Get namelookupTime
     *
     * @return float
     */
    public function getNamelookupTime()
    {
        return $this->namelookupTime;
    }

    /**
     * Set connectTime
     *
     * @param float $connectTime
     *
     * @return UrlAnalyser
     */
    public function setConnectTime($connectTime)
    {
        $this->connectTime = $connectTime;

        return $this;
    }

    /**
     * Get connectTime
     *
     * @return float
     */
    public function getConnectTime()
    {
        return $this->connectTime;
    }

    /**
     * Set pretransferTime
     *
     * @param float $pretransferTime
     *
     * @return UrlAnalyser
     */
    public function setPretransferTime($pretransferTime)
    {
        $this->pretransferTime = $pretransferTime;

        return $this;
    }

    /**
     * Get pretransferTime
     *
     * @return float
     */
    public function getPretransferTime()
    {
        return $this->pretransferTime;
    }

    /**
     * Set sizeUpload
     *
     * @param integer $sizeUpload
     *
     * @return UrlAnalyser
     */
    public function setSizeUpload($sizeUpload)
    {
        $this->sizeUpload = $sizeUpload;

        return $this;
    }

    /**
     * Get sizeUpload
     *
     * @return int
     */
    public function getSizeUpload()
    {
        return $this->sizeUpload;
    }

    /**
     * Set sizeDownload
     *
     * @param integer $sizeDownload
     *
     * @return UrlAnalyser
     */
    public function setSizeDownload($sizeDownload)
    {
        $this->sizeDownload = $sizeDownload;

        return $this;
    }

    /**
     * Get sizeDownload
     *
     * @return int
     */
    public function getSizeDownload()
    {
        return $this->sizeDownload;
    }

    /**
     * Set speedDownload
     *
     * @param integer $speedDownload
     *
     * @return UrlAnalyser
     */
    public function setSpeedDownload($speedDownload)
    {
        $this->speedDownload = $speedDownload;

        return $this;
    }

    /**
     * Get speedDownload
     *
     * @return int
     */
    public function getSpeedDownload()
    {
        return $this->speedDownload;
    }

    /**
     * Set speedUpload
     *
     * @param integer $speedUpload
     *
     * @return UrlAnalyser
     */
    public function setSpeedUpload($speedUpload)
    {
        $this->speedUpload = $speedUpload;

        return $this;
    }

    /**
     * Get speedUpload
     *
     * @return int
     */
    public function getSpeedUpload()
    {
        return $this->speedUpload;
    }

    /**
     * Set downloadContentLength
     *
     * @param integer $downloadContentLength
     *
     * @return UrlAnalyser
     */
    public function setDownloadContentLength($downloadContentLength)
    {
        $this->downloadContentLength = $downloadContentLength;

        return $this;
    }

    /**
     * Get downloadContentLength
     *
     * @return int
     */
    public function getDownloadContentLength()
    {
        return $this->downloadContentLength;
    }

    /**
     * Set uploadContentLength
     *
     * @param integer $uploadContentLength
     *
     * @return UrlAnalyser
     */
    public function setUploadContentLength($uploadContentLength)
    {
        $this->uploadContentLength = $uploadContentLength;

        return $this;
    }

    /**
     * Get uploadContentLength
     *
     * @return int
     */
    public function getUploadContentLength()
    {
        return $this->uploadContentLength;
    }

    /**
     * Set starttransferTime
     *
     * @param float $starttransferTime
     *
     * @return UrlAnalyser
     */
    public function setStarttransferTime($starttransferTime)
    {
        $this->starttransferTime = $starttransferTime;

        return $this;
    }

    /**
     * Get starttransferTime
     *
     * @return float
     */
    public function getStarttransferTime()
    {
        return $this->starttransferTime;
    }

    /**
     * Set redirectTime
     *
     * @param float $redirectTime
     *
     * @return UrlAnalyser
     */
    public function setRedirectTime($redirectTime)
    {
        $this->redirectTime = $redirectTime;

        return $this;
    }

    /**
     * Get redirectTime
     *
     * @return float
     */
    public function getRedirectTime()
    {
        return $this->redirectTime;
    }

    /**
     * Set redirectUrl
     *
     * @param string $redirectUrl
     *
     * @return UrlAnalyser
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    /**
     * Get redirectUrl
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Set permissions
     *
     * @param string $permissions
     *
     * @return UrlAnalyser
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * Get permissions
     *
     * @return string
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Set primaryIp
     *
     * @param string $primaryIp
     *
     * @return UrlAnalyser
     */
    public function setPrimaryIp($primaryIp)
    {
        $this->primaryIp = $primaryIp;

        return $this;
    }

    /**
     * Get primaryIp
     *
     * @return string
     */
    public function getPrimaryIp()
    {
        return $this->primaryIp;
    }

    /**
     * Set certinfo
     *
     * @param array $certinfo
     *
     * @return UrlAnalyser
     */
    public function setCertinfo($certinfo)
    {
        $this->certinfo = $certinfo;

        return $this;
    }

    /**
     * Get certinfo
     *
     * @return array
     */
    public function getCertinfo()
    {
        return $this->certinfo;
    }

    /**
     * Set primaryPort
     *
     * @param integer $primaryPort
     *
     * @return UrlAnalyser
     */
    public function setPrimaryPort($primaryPort)
    {
        $this->primaryPort = $primaryPort;

        return $this;
    }

    /**
     * Get primaryPort
     *
     * @return int
     */
    public function getPrimaryPort()
    {
        return $this->primaryPort;
    }

    /**
     * Set localIp
     *
     * @param string $localIp
     *
     * @return UrlAnalyser
     */
    public function setLocalIp($localIp)
    {
        $this->localIp = $localIp;

        return $this;
    }

    /**
     * Get localIp
     *
     * @return string
     */
    public function getLocalIp()
    {
        return $this->localIp;
    }

    /**
     * Set localPort
     *
     * @param integer $localPort
     *
     * @return UrlAnalyser
     */
    public function setLocalPort($localPort)
    {
        $this->localPort = $localPort;

        return $this;
    }

    /**
     * Get localPort
     *
     * @return int
     */
    public function getLocalPort()
    {
        return $this->localPort;
    }

    /**
     * Set requestScheme
     *
     * @param string $requestScheme
     *
     * @return UrlAnalyser
     */
    public function setRequestScheme($requestScheme)
    {
        $this->requestScheme = $requestScheme;

        return $this;
    }

    /**
     * Get requestScheme
     *
     * @return string
     */
    public function getRequestScheme()
    {
        return $this->requestScheme;
    }

    /**
     * Set requestHost
     *
     * @param string $requestHost
     *
     * @return UrlAnalyser
     */
    public function setRequestHost($requestHost)
    {
        $this->requestHost = $requestHost;

        return $this;
    }

    /**
     * Get requestHost
     *
     * @return string
     */
    public function getRequestHost()
    {
        return $this->requestHost;
    }

    /**
     * Set requestDomain
     *
     * @param string $requestDomain
     *
     * @return UrlAnalyser
     */
    public function setRequestDomain($requestDomain)
    {
        $this->requestDomain = $requestDomain;

        return $this;
    }

    /**
     * Get requestDomain
     *
     * @return string
     */
    public function getRequestDomain()
    {
        return $this->requestDomain;
    }

    /**
     * Set requestTopLevelDomain
     *
     * @param string $requestTopLevelDomain
     *
     * @return UrlAnalyser
     */
    public function setRequestTopLevelDomain($requestTopLevelDomain)
    {
        $this->requestTopLevelDomain = $requestTopLevelDomain;

        return $this;
    }

    /**
     * Get requestTopLevelDomain
     *
     * @return string
     */
    public function getRequestTopLevelDomain()
    {
        return $this->requestTopLevelDomain;
    }

    /**
     * Set requestFolder
     *
     * @param string $requestFolder
     *
     * @return UrlAnalyser
     */
    public function setRequestFolder($requestFolder)
    {
        $this->requestFolder = $requestFolder;

        return $this;
    }

    /**
     * Get requestFolder
     *
     * @return string
     */
    public function getRequestFolder()
    {
        return $this->requestFolder;
    }

    /**
     * Set requestFilename
     *
     * @param string $requestFilename
     *
     * @return UrlAnalyser
     */
    public function setRequestFilename($requestFilename)
    {
        $this->requestFilename = $requestFilename;

        return $this;
    }

    /**
     * Get requestFilename
     *
     * @return string
     */
    public function getRequestFilename()
    {
        return $this->requestFilename;
    }

    /**
     * Set requestFilenameExtension
     *
     * @param string $requestFilenameExtension
     *
     * @return UrlAnalyser
     */
    public function setRequestFilenameExtension($requestFilenameExtension)
    {
        $this->requestFilenameExtension = $requestFilenameExtension;

        return $this;
    }

    /**
     * Get requestFilenameExtension
     *
     * @return string
     */
    public function getRequestFilenameExtension()
    {
        return $this->requestFilenameExtension;
    }

    /**
     * Set requestPath
     *
     * @param string $requestPath
     *
     * @return UrlAnalyser
     */
    public function setRequestPath($requestPath)
    {
        $this->requestPath = $requestPath;

        return $this;
    }

    /**
     * Get requestPath
     *
     * @return string
     */
    public function getRequestPath()
    {
        return $this->requestPath;
    }

    /**
     * Set requestSubdomain
     *
     * @param string $requestSubdomain
     *
     * @return UrlAnalyser
     */
    public function setRequestSubdomain($requestSubdomain)
    {
        $this->requestSubdomain = $requestSubdomain;

        return $this;
    }

    /**
     * Get requestSubdomain
     *
     * @return string
     */
    public function getRequestSubdomain()
    {
        return $this->requestSubdomain;
    }

    /**
     * Set response
     *
     * @param \OpenActu\UrlBundle\Entity\UrlResponseAnalyzer $response
     *
     * @return UrlAnalyzer
     */
    public function setResponse(\OpenActu\UrlBundle\Entity\UrlResponseAnalyzer $response = null)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return \OpenActu\UrlBundle\Entity\UrlResponseAnalyzer
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return UrlAnalyzer
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Set port mode
     *
     * @param string $portMode
     *
     * @return UrlAnalyzer
     */
    public function setPortMode($portMode)
    {
        $this->portMode = $portMode;

        return $this;
    }

    /**
     * Get portMode
     *
     * @return string
     */
    public function getPortMode()
    {
        return $this->portMode;
    }

    /**
     * Set encodeUrl
     *
     * @param string $encodeUrl
     *
     * @return UrlAnalyzer
     */
    public function setEncodeUrl($encodeUrl)
    {
        $this->encodeUrl = $encodeUrl;

        return $this;
    }

    /**
     * Get encodeUrl
     *
     * @return string
     */
    public function getEncodeUrl()
    {
        return $this->encodeUrl;
    }


    /**
     * Set request error message
     *
     * @param string $requestErrorMessage
     *
     * @return UrlAnalyzer
     */
    public function setRequestErrorMessage($requestErrorMessage)
    {
        $this->requestErrorMessage = $requestErrorMessage;

        return $this;
    }

    /**
     * Get request error message
     *
     * @return string
     */
    public function getRequestErrorMessage()
    {
        return $this->requestErrorMessage;
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return UrlAnalyzer
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
  
    /**
     * Set children
     *
     * @param array $children
     *
     * @return UrlAnalyzer
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
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

		/**
		 * nbChildren
		 */
		$this->nbChildren = count($this->getChildren());		
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(LifecycleEventArgs $event)
    {
		/**
		 * updatedAt
		 */
		$this->updatedAt = new \DateTime();
		
		/**
		 * nbChildren
		 */
		$this->nbChildren = count($this->getChildren());
    }
    
}
