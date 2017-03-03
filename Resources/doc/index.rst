Getting Started With OpenActu\UrlBundle
=======================================

The url component provides a single way that allows you to simply validate, manage and more the URL you must use.

This bundle has for goal the guaranty of consistency in crawling processes on the URL problematics.  
 
Prerequisites
-------------

This version of the bundle is approved with Symfony 3.2+. 

Installation
------------

Step 1: Download FOSUserBundle using composer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Require the bundle with composer:

..note::

this step doesn't work for the moment - deployment in progress -

.. code-block:: bash

    $ composer require open-actu/url-bundle

Composer will install the bundle to your project's ``vendor/open-actu/url-bundle`` directory.
    
Step 2: Enable the bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Enable the bundle in the kernel::

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new OpenActu\UrlBundle\OpenActuUrlBundle(),
            // ...
        );
    }

Step 3: Configure the OpenActuUrlBundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Add the following configuration to your ``config.yml`` file according to which type
of datastore you are using.

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        open_actu_url:
            url:
                # ==========================
                # schemes requirement
                # ==========================
                # provides lines for the management of valid URL schemes
                # ==========================
                schemes: [ "http", "https" ]
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

Next Steps
~~~~~~~~~~

Now that you have completed the basic installation and configuration of the
OpenActuUrlBundle, you are ready to learn about more advanced features and usages
of the bundle.

The following documents are available:

.. toctree::
    :maxdepth: 1

    work_with_service

