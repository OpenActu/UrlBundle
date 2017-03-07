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

As we can see, we want to sanitize this URL and it works. But what's append if we change the URL with 'localhost/myspace/filename.html'. The sanitization works but if you check, the scheme is 'http', the domain is 'path' and the folder become 'subpath'. This is because the sanitize method "guess" that the URL must work "with a maximum of efficiency" as an http request.   
