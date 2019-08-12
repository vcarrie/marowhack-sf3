<?php

namespace MediaBundle\Service;

use MediaBundle\Helpers\FileManager;
use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Security\Core\Security;

class FileTypeService
{
    const IMAGE_SIZE = [
        FileManager::VIEW_LIST => '22',
        FileManager::VIEW_THUMBNAIL => '100',
    ];

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Security
     */
    private $security;

    /**
     * FileTypeService constructor.
     *
     * @param Router   $router
     * @param Packages $packages
     */
    public function __construct(Router $router, Security $security)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function preview(FileManager $fileManager, SplFileInfo $file)
    {
        if ($fileManager->getImagePath()) {
            $filePath = htmlentities($fileManager->getImagePath().rawurlencode($file->getFilename()));
        } else {
            $filePath = $this->router->generate('media_file', array_merge($fileManager->getQueryParameters(), ['fileName' => rawurlencode($file->getFilename())]));
        }
        $extension = $file->getExtension();
        $type = $file->getType();
        if ($type === 'file') {
            $size = $this::IMAGE_SIZE[$fileManager->getView()];
            $fileIcon = $this->fileIcon($filePath, $extension, $size);

            return $fileIcon;
        } elseif ($type === 'dir') {
            $href = $this->router->generate('media', array_merge($fileManager->getQueryParameters(), ['route' => $fileManager->getRoute().DIRECTORY_SEPARATOR.rawurlencode($file->getFilename())]));

            return [
                'path' => $filePath,
                'html' => "<i class='fas fa-folder-o' aria-hidden='true'></i>",
                'folder' => '<a  href="'.$href.'">'.$file->getFilename().'</a>',
            ];
        }
    }

    public function accept($type)
    {
        switch ($type) {
            case 'image':
                $accept = 'image/*';
                break;
            case 'media':
                $accept = 'video/*';
                break;
            case 'file':
                return false;
            default:
                return false;
        }

        return $accept;
    }

    public function fileIcon($conf, $filePath, $nom, $extension = null, $size = 75)
    {
        if ($extension === null) {
            $filePathTmp = strtok($filePath, '?');
            $extension = pathinfo($filePathTmp, PATHINFO_EXTENSION);
        }

        $route = 'media_download';
//        if ($this->security->isGranted('ROLE_CLIENT')) {
//            $route = 'admin_media_download';
//        }

        $link = '&nbsp;Aucun fichier...';
        if($filePath) {
            $link = '
            <div class="download-file">
            <a href="'.$this->router->generate($route, array('path' => $conf.'__'.basename($filePath))).'__'.strtolower(preg_replace("/[A-Z]+/", "_$0", $nom)).'" >
            <i class="fas fa-download"></i>
            </a></div>';
        }

        switch (true) {
            case filter_var($filePath, FILTER_VALIDATE_URL):
            case preg_match('/(gif|png|jpe?g|svg)$/i', $extension):
            return [
                'path' => $filePath,
                'html' => "<div class='preview-capsule'><img src=\"".$this->router->generate($route, array('path' => $conf.'__'.basename($filePath))).'__'.strtolower(preg_replace("/[A-Z]+/", "_$0", $nom))."\" class='img-responsive'></div>".$link,
            ];
            case preg_match('/(mp4|ogg|webm)$/i', $extension):
                $fa = 'fa-file-video';
                break;
            case preg_match('/(pdf)$/i', $extension):
                $fa = 'fa-file-pdf';
                break;
            case preg_match('/(docx?)$/i', $extension):
                $fa = 'fa-file-word';
                break;
            case preg_match('/(xlsx?|csv)$/i', $extension):
                $fa = 'fa-file-excel';
                break;
            case preg_match('/(pptx?)$/i', $extension):
                $fa = 'fa-file-powerpoint';
                break;
            case preg_match('/(zip|rar|gz)$/i', $extension):
                $fa = 'fa-file-archive';
                break;
            default:
                $fa = 'fa-file';
        }





        return [
            'path' => $filePath,
            'html' => "<i class='fas {$fa}' aria-hidden='true'></i>".$link,
        ];
    }

}
