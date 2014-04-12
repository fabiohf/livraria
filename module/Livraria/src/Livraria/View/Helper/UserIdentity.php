<?php

namespace Livraria\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

class UserIdentity extends AbstractHelper
{
    protected $autoService;
    
    public function getAutoService() {
        return $this->autoService;
    }
    
    public function __invoke($namespace = null)
    {
        $sessionStorage = new SessionStorage($namespace);
        $this->autoService = new AuthenticationService();
        $this->autoService->setStorage($sessionStorage);
        
        if($this->getAutoService()->hasIdentity()) {
            return $this->getAutoService()->getIdentity();
        } else {
            return false;
        }
    }
}
