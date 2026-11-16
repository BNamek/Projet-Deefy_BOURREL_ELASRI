<?php
namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AlbumTrack extends AudioTrack {
    private string $album;
    private string $anne;
    private int $numero;

    public function __get(string $n): mixed{
        if(property_exists($this, $n)){
            return $this -> $n;
        }

        return parent::__get($n); //si les attributs ne sont pas dans albumtrack alors on vas dans la classe audiotrack
    }

    public function __construct(string $titrePiste, string $cheminFichier, string $nomAlbum, int $numeroPiste){
        $this->album = $nomAlbum;
        $this->numero = $numeroPiste;

        parent::__construct($titrePiste, $cheminFichier);
    }

    public function __set(string $n, mixed $v): void {
        if ($n === 'album' || $n === 'anne' || $n === 'numero') {
            $this->$n = $v;
        } else {
            parent::__set($n, $v); //si les attributs ne sont pas dans albumtrack alors on vas dans la classe audiotrack pour changer les valeurs
        }
    }

    public function __toString() : string {
        return json_encode(get_object_vars($this));
    }
}
