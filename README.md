# UrlBundle

The UrlBundle is provided to manage URLs and give a set of tools 
to work with its. 

This tools is built first to provide a stable
 crawler processing. 

# Symfony installation

First, checkout a copy of the code. Just add the following to the deps file of your Symfony Standard Distribution:

    [OpenActuUrlBundle]
        git=git://github.com/OpenActu/UrlBundle.git
        target=/bundles/OpenActu/UrlBundle

Then add the bundle to your AppKernel and register the namespace with the autoloader.

    // app/AppKernel.php
    $bundles = array(
        // ..
        new OpenActu\UrlBundle\OpenActuUrlBundle(),
        // ..
    );

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ..
        'OpenActu' => __DIR__.'/../vendor/bundles'
        // ..
    ));

Now use the vendors script to clone the newly added repository into your project

    php bin/vendors install

# Features

