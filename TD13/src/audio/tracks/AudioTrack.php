<?php
namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AudioTrack {
    private string $titre;
    private string $auteur;
    private string $date;
    private string $genre;
    private int $duree;
    private string $nomFichierAudio;
    private int $id;

    public function __get(string $n): mixed{
        if(property_exists($this, $n)){
            return $this -> $n;
        }
        throw new InvalidPropertyNameException ("$n: invalid property");
    }

    public function __set(string $n, mixed $v): void {
        if($n === 'titre')
            { $this->titre = $v; }
        elseif($n === 'auteur')
            { $this->auteur = $v; }
        elseif($n === 'date')
            { $this->date = $v; }
        elseif($n === 'genre'){
            $this->genre = $v;
            }
        elseif($n === 'duree'){
            if($v >= 0){
                $this->duree = $v;
            }else{
                throw new InvalidPropertyValueException( "$v : doit etre un nombre positif" );
            }
        }
        elseif($n === 'nomFichierAudio'){
                $this->nomFichierAudio = $v;
            }elseif($n === 'id'){
                $this ->id=$v;
            }
        else{
            throw new InvalidPropertyNameException ("$n: invalid property");
            }
        }

    public function __construct(string $t, string $cheminFichier)
    {
        $this->titre = $t;
        $this->nomFichierAudio = $cheminFichier;
    }
}
