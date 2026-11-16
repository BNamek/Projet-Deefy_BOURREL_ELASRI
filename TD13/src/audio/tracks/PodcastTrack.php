<?php
namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class PodcastTrack extends AudioTrack{

    public function __construct(string $titreP, string $cheminFichier){
        parent::__construct($titreP,$cheminFichier);

    }
}
