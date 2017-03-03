Working With The OpenActuUrlBundle as service
=============================================

Calling the service
-------------------

The call is simply done with "open-actu.url.manager"::

    <?php
    // ... your controller ...
    
    $um = $this->get('open-actu.url.manager');
    

