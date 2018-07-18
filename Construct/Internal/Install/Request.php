<?php
namespace Construct\Internal\Install;

class Request extends \Construct\Request {



    //original function requires database access
    protected function isWebsiteRoot()
    {
        return true;
    }


}
