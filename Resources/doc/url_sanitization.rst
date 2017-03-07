URL Sanitization
================

The URL sanitization asserts that the param given in entry is correct syntaxically and can be used. An URL is the basis to access at any resources from computer. When you open the file "C:\\Windows\system32\test.cnf" (windows case) or "/home/myaccount/test.cnf" (linux case), you attempt to access to the URL formated respectively "file:///C:/Windows/system32/test.cnf" and "file:///home/myaccount/test.cnf". The goal of sanitization is to see if the resources given as parameter are well-formed before the resource calling.

After a (short, I promise !) introduction, we will see how simply sanitize our resources and manage its with error management.

Introduction : What's an URL ?
------------------------------

An URL is a string structured among a specific pattern done with standard RFC 3986. belong is a short description of the definition.

==============
Common section
==============

.. code-block:: php

    ----------://----------/----------?----------#----------
    \________/   \________/ \________/ \________/ \________/
      scheme        host       path       query    fragment
      
============
Host section
============

.. code-block:: php

    -------------------:-------------------@---------(.?)---------.-------------------.-------------------
    \_________________/ \_________________/ \____________________/ \_________________/ \_________________/
         username            password              subdomain            domain           topLevelDomain
    \____________________________________________________________________________________________________/
                                                     host

============
Path section
============

.. code-block:: php

     -------(/*)-------/(.?)-----(.*)-----(.------------------?)
     \________________/ \________________/\____________________/
           folder           filename        filenameExtension
     \_________________________________________________________/
                              path

Our first use : basic sanitization 
----------------------------------

The sanitization is managed with service "open-actu.url.manager". Then let's go !

.. code-block:: php

    // in your controller
    $um = $this->get('open-actu.url.manager');
	$um->sanitize(null,'/path/subpath/filename.txt');
    echo $um->getFilenameExtension();  // return 'txt'
    echo $um->getFolder();             // return 'path/subpath'
    var_dump($um->getHost());          // return null
    echo $um;                          // return 'file:///path/subpath/filename.txt'
    echo $um->getScheme();                      // return 'file'
    
    if($um->hasErrors())
    {
        foreach($um->getErrors() as $error)
        {
            echo $error->getMessage().' # '.$error->getCode();
        }
    }

As we can see, we want to sanitize this URL and it works. But what's append if we change the URL with 'localhost/myspace/filename.html'. The sanitization works but if you check, the scheme is 'http', the domain is 'path' and the folder become 'subpath'. This is because the sanitize method "guess" that the URL must work "with a maximum of efficiency" as an http request.   

Configuration management
------------------------

Many options to manage the bundle are available. 

================
Error management
================

In this bundle, the production of errors is managed either a throwable exception who stop the process or singles messages managed with methods "hasErrors()" and "getErrors()". This comportment depend of the value of parameter open_actu_url > url > level_exception. 

two modes are availabled : "INFO" and "ERROR"

* INFO : this mode store exception in a exception bag. The exceptions can be retrieved with method "getExceptions" on service manager
* ERROR: this mode provide an UrlException at the first error detected 

=================
Scheme validation
=================

Suppose we want sanitize 'sfp://localhost/myspace/filename.html'. It produce an error 'the current scheme is invalid (given "sfp"). Check your configuration to accept this scheme'. 

The schemes acceptation is defined in the app config.yml in area "open_actu_url > url > schemes".

===============
Port management
===============

You can define a default port for each scheme (see "open_actu_url > port > defaults section). You can manage the format output of the port. We have three modes availabled in section "open_actu_url > port > mode" : "normal", "forced" and "none".

* normal : If the port is the standard port used with the current scheme, the port will be omitted.
* forced : force the port information. If port is not given, the port takes the default port relative to the current scheme
* none : use port only if the information is done

You can manage this option dynamically by the "changePortMode" method.

=======
example
=======

.. code-block:: php

    // in your controller
    $um = $this->get('open-actu.url.manager');
    $um->changePortMode('normal');

Conclusion
----------

You see the basic capability of the sanitization. You're ready to read the next chapter.
