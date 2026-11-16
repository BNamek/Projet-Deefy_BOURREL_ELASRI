<?php
namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\AlbumTrack;


use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AudioList {
    private $nom;
    private $nombrePiste;
    private $dureeTot;
    public $piste;
    private int $id;

    public function __construct(string $n, ?array $tab){
        $this ->nom = $n;
        $this ->piste = $tab;
        $this ->nombrePiste = count($tab);
        $this ->dureeTot = 0;
        $this ->calculer();
    }

    //Ajout de la fonction ajouter pour ajouter une audio liste dans la liste de la playlist
    public function ajouter(AudioTrack $aud):void{
        $taille = count($this -> piste);
        $this -> piste[$taille] =$aud;
        $this -> nombrePiste =count($this -> piste);
        $this -> calculer();
    }
    public function calculer():void{
        $this -> nombrePiste = count($this ->piste);

        if($this -> dureeTot >0){
            $this -> dureeTot = 0;
        }
        foreach($this ->piste as $p){
            $this -> dureeTot += $p->duree;
        }

    }

    public function __get(string $n){
        if(property_exists($this,$n)){
            return $this -> $n;
        }
        throw new InvalidPropertyNameException("$n : invalid proprety");
    }

    public function setId(int $i):void{
        $this ->id =$i;
    }

    public function __toString(): string{
        $res = " ";
        foreach($this as $a){
            $res = $res."$a <br>";
        }
        return $res;
    }

}
