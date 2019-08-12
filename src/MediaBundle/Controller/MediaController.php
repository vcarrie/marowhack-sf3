<?php

namespace MediaBundle\Controller;

use MediaBundle\Event\FileManagerEvents;
use MediaBundle\Helpers\File;
use MediaBundle\Helpers\FileManager;
use MediaBundle\Helpers\UploadHandler;
use MediaBundle\Twig\OrderExtension;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Arthur Gribet <a.gribet@gmail.com>
 */
class MediaController extends Controller
{
    /**
     * @var FileManager
     */
    protected $fileManager;

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function uploadFileAction(Request $request, $idUser = 0)
    {

        $queryParameters = $request->query->all();
        $fileManager = $this->newFileManager($queryParameters, $idUser);

        $options = [
            'upload_dir' => $fileManager->getCurrentPath() . DIRECTORY_SEPARATOR,
            'upload_url' => $fileManager->getImagePath(),
            'accept_file_types' => $fileManager->getRegex(),
            'print_response' => false,
        ];
        
        
        if (isset($fileManager->getConfiguration()['upload'])) {
            $options = $options + $fileManager->getConfiguration()['upload'];
        }


        $this->dispatch(FileManagerEvents::PRE_UPDATE, ['options' => &$options]);

        $uploadHandler = new UploadHandler($options);

        $response = $uploadHandler->response;
        $retour = array();

        foreach ($response['files'] as &$file) {
            if (isset($file->error)) {
                $file->error = $this->get('translator')->trans($file->error);
                $retour['files'][] = array('name' => $file->name, 'nom' => strtolower(preg_replace("/[A-Z]+/", "_$0", $queryParameters['nom'])), 'size' => $file->size, 'type' => $file->type, "error" => $file->error);
            }else{
                $retour['files'][] = array('conf' => $queryParameters['conf'], 'url' => $file->url, 'name' => $file->name, 'nom' => strtolower(preg_replace("/[A-Z]+/", "_$0", $queryParameters['nom'])), 'size' => $file->size, 'type' => $file->type);
            }


            if (!$fileManager->getImagePath()) {
                $file->url = $this->generateUrl('media_file', array_merge($fileManager->getQueryParameters(), ['fileName' => $file->url]));
            }

        }

        $this->dispatch(FileManagerEvents::POST_UPDATE, ['response' => &$response]);

        return new JsonResponse($retour);
    }

    /**
     * @param Request $request
     * @param $path
     *
     * @return BinaryFileResponse
     * @throws \Exception
     */
    public function binaryFileResponseAction(Request $request, $path)
    {
        $pathParams = explode('__', $path);
        $url = $this->getBasePath(array('conf' => $pathParams[0]))['dir'] . DIRECTORY_SEPARATOR . $pathParams[1];
        $path_parts = pathinfo($url);
        
        return $this->file($url, $pathParams[2].'.'.$path_parts['extension']);        
    }

    /**
     * @param FileManager $fileManager
     * @param $path
     * @param string $parent
     * @param bool $baseFolderName
     *
     * @return array|null
     */
    private function retrieveSubDirectories(FileManager $fileManager, $path, $parent = DIRECTORY_SEPARATOR, $baseFolderName = false)
    {
        $directories = new Finder();
        $directories->in($path)->ignoreUnreadableDirs()->directories()->depth(0)->sortByType()->filter(function (SplFileInfo $file) {
            return $file->isReadable();
        });

        if ($baseFolderName) {
            $directories->name($baseFolderName);
        }
        $directoriesList = null;

        foreach ($directories as $directory) {
            /** @var SplFileInfo $directory */
            $fileName = $baseFolderName ? '' : $parent . $directory->getFilename();

            $queryParameters = $fileManager->getQueryParameters();
            $queryParameters['route'] = $fileName;
            $queryParametersRoute = $queryParameters;
            unset($queryParametersRoute['route']);

            $filesNumber = $this->retrieveFilesNumber($directory->getPathname(), $fileManager->getRegex());
            $fileSpan = $filesNumber > 0 ? " <span class='label label-default'>{$filesNumber}</span>" : '';

            $directoriesList[] = [
                'text' => $directory->getFilename() . $fileSpan,
                'icon' => 'fa fa-folder-o',
                'children' => $this->retrieveSubDirectories($fileManager, $directory->getPathname(), $fileName . DIRECTORY_SEPARATOR),
                'a_attr' => [
                    'href' => $fileName ? $this->generateUrl('media', $queryParameters) : $this->generateUrl('media', $queryParametersRoute),
                ], 'state' => [
                    'selected' => $fileManager->getCurrentRoute() === $fileName,
//				    'expanded' => $fileName ? substr($fileManager->getCurrentRoute(), 0, strlen($fileName)) === $fileName : true,
                    'opened' => true,
                ],
//                'tags' => [$this->retrieveFilesNumber($directory->getPathname(), $fileManager->getRegex())]
            ];
        }

        return $directoriesList;
    }

    /**
     * Tree Iterator.
     *
     * @param $path
     * @param $regex
     *
     * @return int
     */
    private function retrieveFilesNumber($path, $regex)
    {
        $files = new Finder();
        $files->in($path)->files()->depth(0)->name($regex);

        return iterator_count($files);
    }

    /*
     * Base Path
     */
    private function getBasePath($queryParameters)
    {
        $conf = $queryParameters['conf'];
        $managerConf = $this->getParameter('definima_media')['conf'];
        if (isset($managerConf[$conf]['dir'])) {
            return $managerConf[$conf];
        } elseif (isset($managerConf[$conf]['service'])) {
            $extra = isset($queryParameters['extra']) ? $queryParameters['extra'] : [];
            $conf = $this->get($managerConf[$conf]['service'])->getConf($extra);

            return $conf;
        }
    }

    /**
     * @return mixed
     */
    private function getKernelRoute()
    {
        return $this->getParameter('kernel.root_dir');
    }

    /**
     * @param $queryParameters
     * @return FileManager
     * @throws \Exception
     */
    private function newFileManager($queryParameters)
    {
        if (!isset($queryParameters['conf'])) {
            throw new \Exception('Please define a conf parameter in your route');
        }
        
        $webDir = $this->getParameter('definima_media')['web_dir'];
        $this->fileManager = new FileManager($queryParameters, $this->getBasePath($queryParameters), $this->getKernelRoute(), $this->get('router'), $webDir);

        return $this->fileManager;
    }

    protected function dispatch($eventName, array $arguments = [])
    {
        $arguments = array_replace([
            'filemanager' => $this->fileManager,
        ], $arguments);

        $subject = $arguments['filemanager'];
        $event = new GenericEvent($subject, $arguments);
        $this->get('event_dispatcher')->dispatch($eventName, $event);
    }
}
