<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ville
 *
 * @ORM\Table(name="ville")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VilleRepository")
 */
class Ville
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="codePostal", type="string", length=10)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;
    
    /**
     * @var string
     *
     * @ORM\Column(name="position_geo", type="string", length=255)
     */
    private $positionGeo;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="code_region", type="integer")
     */
    private $codeRegion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom_region", type="string", length=255)
     */
    private $nomRegion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="chef_lieu", type="string", length=255)
     */
    private $chefLieu;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="num_departement", type="integer")
     */
    private $numDepartement;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom_departement", type="string", length=255)
     */
    private $nomDepartement;
    
    /**
     * @var string
     *
     * @ORM\Column(name="prefecture", type="string", length=255)
     */
    private $prefecture;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="num_circonscription", type="integer")
     */
    private $numCirconscription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     */
    private $pays;
    
    /**
     * @var string
     *
     * @ORM\Column(name="gps", type="string", length=255)
     */
    private $gps;
         
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Ville
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }
    
    public function getGps() {
        return $this->gps;
    }

    public function setGps($gps) {
        $this->gps = $gps;
    }
    
    public function getPositionGeo() {
        return $this->positionGeo;
    }

    public function getCodeRegion() {
        return $this->codeRegion;
    }

    public function getNomRegion() {
        return $this->nomRegion;
    }

    public function getChefLieu() {
        return $this->chefLieu;
    }

    public function getNumDepartement() {
        return $this->numDepartement;
    }

    public function getNomDepartement() {
        return $this->nomDepartement;
    }

    public function getPrefecture() {
        return $this->prefecture;
    }

    public function getNumCirconscription() {
        return $this->numCirconscription;
    }

    public function setPositionGeo($positionGeo) {
        $this->positionGeo = $positionGeo;
    }

    public function setCodeRegion($codeRegion) {
        $this->codeRegion = $codeRegion;
    }

    public function setNomRegion($nomRegion) {
        $this->nomRegion = $nomRegion;
    }

    public function setChefLieu($chefLieu) {
        $this->chefLieu = $chefLieu;
    }

    public function setNumDepartement($numDepartement) {
        $this->numDepartement = $numDepartement;
    }

    public function setNomDepartement($nomDepartement) {
        $this->nomDepartement = $nomDepartement;
    }

    public function setPrefecture($prefecture) {
        $this->prefecture = $prefecture;
    }

    public function setNumCirconscription($numCirconscription) {
        $this->numCirconscription = $numCirconscription;
    }

    public function getPays() {
        return $this->pays;
    }

    public function setPays($pays) {
        $this->pays = $pays;
    }
    
    public function __toString() {
        return $this->codePostal;    
    }
}

