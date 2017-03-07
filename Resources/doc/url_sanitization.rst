URL Sanitization
================

The URL sanitization asserts that the param given in entry is correct syntaxically and can be read

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

