<?php

namespace MediaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{


    public function ajaxIconAction(Request $request)
    {
        $filePath = $request->query->get('path');
        $conf = $request->query->get('conf');
        $nom = $request->query->get('nom');
        $iconData = $this->get('file_type_service')->fileIcon($conf, $filePath,$nom);
        return new JsonResponse(['icon' => $iconData]);
    }

}