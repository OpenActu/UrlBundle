<?php
namespace OpenActu\UrlBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use OpenActu\UrlBundle\Entity\UrlCoreAnalyzer;
use OpenActu\UrlBundle\Entity\UrlAnalyzer;
use Doctrine\ORM\Query\ResultSetMapping;
class UrlCoreAnalyzerListener
{
	private $container;

	public function __construct($container)
	{
		$this->container = $container;
	}
	
	/**
	 * Insert statistic from UrlCoreAnalyzer item
	 *
	 */
	public function postLoad(LifecycleEventArgs $args)
        {
		$em 		= $this->container->get('doctrine.orm.entity_manager');
		$entity 	= $args->getObject();
		
		if(get_class($entity) === UrlCoreAnalyzer::class)
		{
			$classname 	= $entity->getClassname();
			$table_name 	= $em->getClassMetadata($classname)->getTableName();
			$table_core_name= $em->getClassMetadata(UrlCoreAnalyzer::class)->getTableName();
			
			$sql ='
			SELECT 
			  max(b.id) current_id,
			  avg(b.header_size) header_size, b.http_code,
			  max(b.updated_at) updatedAt, avg(b.total_time) totalTime,
			  avg(b.namelookup_time) namelookupTime, avg(b.connect_time) connectTime,
			  avg(b.pretransfer_time) pretransferTime, avg(b.size_download) sizeDownload,
			  avg(b.speed_download) speedDownload, avg(b.download_content_length) downloadContentLength,
			  avg(b.starttransfer_time) starttransferTime, count(b.response_url) count
			FROM `'.$table_name.'` b, `'.$table_core_name.'` z 
			WHERE 
			  z.uri_calculated = :requestUriCalculated AND
			  z.id = b.core_id
			GROUP BY 
			  z.uri_calculated, 
			  b.http_code 
			ORDER BY 
			  b.http_code';
			
			$rsm = new ResultSetMapping();
			$qb = $em->createNativeQuery($sql,$rsm);
			$qb->setParameter('requestUriCalculated', $entity->getUriCalculated());
			$rsm->addScalarResult('current_id','currentId');
			$rsm->addScalarResult('http_code', 'httpCode');		
			$rsm->addScalarResult('count', 'count');		
			$rsm->addScalarResult('sizeDownload', 'mediumSizeDownload');		
			$rsm->addScalarResult('downloadContentLength', 'mediumDownloadContentLength');		
			$rsm->addScalarResult('totalTime', 'mediumTotalTime');
			$statistics = $qb->getScalarResult();
			
			foreach($statistics as $statistic)
				if(!empty($statistic['currentId']) && ($statistic['currentId'] > $entity->getCurrentId()))
					$entity->setCurrentId($statistic['currentId']);
			
			$entity->setStatistics($statistics);
		}
	}
}
