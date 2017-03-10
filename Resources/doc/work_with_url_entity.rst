Work with URL entity
====================

You can check an URL easily as shown in the first part. Now we will see the way to manage with doctrine entity the URL's datas.

-------------------
Build an URL entity
-------------------
In order, you need to create your owns entities. Don't trouble, it's very easy !

Suppose you want the entity MyLink as URL manager. You need to create 4 files for that :

* file YourBundle/Entity/MyLink.php

.. code-block:: php

  <?php
  namespace YourBundle\Entity;
    
  use Doctrine\ORM\Mapping as ORM;
  use OpenActu\UrlBundle\Entity\UrlAnalyzer;
  use YourBundle\Entity\MyLinkResponse;
  /**
   * MyLink
   *
   * @ORM\Table(name="my_link")
   * @ORM\Entity(repositoryClass="YourBundle\Repository\MyLinkRepository")
   */
  class LinkTest extends UrlAnalyzer{
	       
	  public function __construct()
	  {
		  $this->setResponse(new MyLinkResponse());
	  }
	  public function getResponseClass()
  	{
	   return MyLinkResponse::class;
	  }
  }

* file YourBundle/Entity/MyLinkResponse.php

.. code-block:: php

  <?php
  namespace MyBundle\Entity;
  
  use Doctrine\ORM\Mapping as ORM;
  use OpenActu\UrlBundle\Entity\UrlResponseAnalyzer;
  /**
   * MyLinkResponse
   *
   * @ORM\Table(name="my_link_response")
   * @ORM\Entity(repositoryClass="MyBundle\Repository\MyLinkResponseRepository")
   */
  class MyLinkResponse extends UrlResponseAnalyzer{
  }
  
* file YourBundle/Repository/MyLink.php
  
* file YourBundle/Repository/MyLinkResponse.php
  
  in progress ...
