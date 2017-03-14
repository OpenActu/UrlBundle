# Summary

This Bundle offers a powerfull toolkit who serve to sanitize, validate and submit url. 

As explained below, the prior objective is to give a very consistent crawling system (a little more than a "parse_url" and "curl exec" ...).

# Symfony installation

You can easily install in your Symfony Standard Distribution with composer:

    composer require open-actu/url dev-master

Then add the bundle to your AppKernel.

    // app/AppKernel.php
    $bundles = array(
        // ..
        new OpenActu\UrlBundle\OpenActuUrlBundle(),
        // ..
    );

Now you need to add the config url in the main config file 'app/config/config.yml'

    open_actu_url:
        url:
            # ==========================
            # schemes requirement
            # ==========================
            # provides lines for the management of valid URL schemes
            # ==========================
            schemes: [ "http", "https","file" ]
            # ==========================
            # scheme default
            # ==========================
            # this scheme will be used when no scheme is indicated
            # ==========================
            scheme_default: "http"
            # ==========================
            # level exception management
            # ==========================
            # two modes are availabled : "INFO" and "ERROR"
            # - INFO : this mode store exception in a exception bag. The exceptions can be retrieved with 
            #          method "getExceptions" on service manager
            # - ERROR: this mode provide an UrlException at the first error detected 
            # ==========================
            level_exception: "INFO"
            # ==========================
            # defaults ports requirements
            # ==========================
            # (OPTIONAL) Configuration area to manage the port use. 
            # three modes are availabled: "normal", "forced" and "none"
            # - normal (RECOMMANDED): If the port is the standard port used with the current scheme, the port will
            #                          be omitted.
            # - forced		: force the port information. If port is not given, the port takes the default port
            #                          relative to the current scheme
            # - none		: use port only if the information is done
            port:
                defaults:
                    - { scheme: http,port: 80 }
                    - { scheme: https,port: 443 }
                mode: "forced"
            # ===========================
            # protocol configuration
            # ===========================
            # (OPTIONAL) Configuration to manage the remote request sending
            # - get (DEFAULT) 	: send request as GET query
            # - post		: send request as POST query
            protocol:
                method: "get"
                timeout: 10

Finally, you must add a mapping type in the doctrine area (allways in 'app/config/config.yml')

    doctrine:
        dbal:
            ...
            mapping_types:
                enum: string

Congratulation, you can use this bundle !

# Use case

Here is done the basic example use we can have with it. This works only if you check the required minimal configuration (entity creating ...) as explained in the next chapters.

```sh
  ...
  use MyBundle\MyLink;
  ... 
  $usm= $this->container->get('open-actu.url_storage.manager');		
  $um = $this->container->get('open-actu.url.manager');
  
  // Configuration settings
  $um->changePortMode('normal');
  		
  /**
   * sanitize area - first step to work	 
   */
  $link = $um->sanitize(MyLink::class,"http://www.google.fr/");
  		
  # we push (this is not obligatory)
  $usm->push($link);
  
  if(null !== $link && !$um->hasErrors())
  {
 	/**
	 * now we can send request and receive response
	 */
	$um->send($link);

	/**
	 * we said that the link can not be updated
	 */
	$link->setAcceptUpdate(true);

	/**
	 * we can store the object in database
	 */
	$usm->push($link);
  			
  }
```

# Features

The source of the documentation is stored in the `Resources/doc/` folder :

[sanitize url](https://github.com/OpenActu/UrlBundle/blob/master/Resources/doc/url_sanitization.rst)
