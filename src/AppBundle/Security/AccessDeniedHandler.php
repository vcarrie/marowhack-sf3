<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\HttpUtils;

class AccessDeniedHandler implements AccessDeniedHandlerInterface {

    private $security;
    private $httpUtils;

    public function __construct(Security $security, HttpUtils $httpUtils)
    {
        $this->security = $security;
        $this->httpUtils = $httpUtils;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException) {

        $target_path = 'app_index';

        if($this->security->isGranted('ROLE_ADMIN')) {
            $target_path = 'admin_dashbord_index';
        }
        
        return $this->httpUtils->createRedirectResponse($request, $target_path);
    }

}