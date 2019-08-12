<?php
namespace AdminBundle\Twig;

use AppBundle\Services\Def_Slugify;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SlugifyExtension extends AbstractExtension
{

    /**
     * @var Def_Slugify $slugifyService
     */
    protected $slugifyService;

    /**
     * Constructor
     *
     */
    public function __construct($slugifyService)
    {
        $this->slugifyService = $slugifyService;

    }

    public function getFilters()
    {
         return array(
            new TwigFilter('slugify', array($this, 'slugify')),
        );
    }

    public function slugify($slug)
    {
        return $this->slugifyService->slugify($slug);
    }

}