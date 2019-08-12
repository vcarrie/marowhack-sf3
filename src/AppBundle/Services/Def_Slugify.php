<?php


namespace AppBundle\Services;

class Def_Slugify
{

    public function slugify($toSlugify){

        // Remove HTML tags
        $slug = preg_replace('/<(.*?)>/u', '', $this->_str_to_noaccent($toSlugify));

        // Remove inner-word punctuation.
        $slug = preg_replace('/[\'"‘’“”]/u', '', $slug);

        // Make it lowercase
        $slug = mb_strtolower($slug, 'UTF-8');

        // Get the "words".  Split on anything that is not a unicode letter or number.
        // Periods are OK too.
        preg_match_all('/[\p{L}\p{N}.]+/u', $slug, $words);
        $words = array_filter(
            $words[0], function($val) {
            return (mb_strlen($val) != 0);
        }
        );
        $slug = implode('-', $words);

        return $slug;
    }

    public function _str_to_noaccent($str) {
        $url = $str;
        $url = preg_replace('#Ç#', 'C', $url);
        $url = preg_replace('#ç#', 'c', $url);
        $url = preg_replace('#è|é|ê|ë#', 'e', $url);
        $url = preg_replace('#È|É|Ê|Ë#', 'E', $url);
        $url = preg_replace('#à|á|â|ã|ä|å#', 'a', $url);
        $url = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $url);
        $url = preg_replace('#ì|í|î|ï#', 'i', $url);
        $url = preg_replace('#Ì|Í|Î|Ï#', 'I', $url);
        $url = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $url);
        $url = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $url);
        $url = preg_replace('#ù|ú|û|ü#', 'u', $url);
        $url = preg_replace('#Ù|Ú|Û|Ü#', 'U', $url);
        $url = preg_replace('#ý|ÿ#', 'y', $url);
        $url = preg_replace('#Ý#', 'Y', $url);

        return ($url);
    }

}
