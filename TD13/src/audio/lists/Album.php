<?php
namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\AlbumTrack;

class Album extends AudioList{
    private string $artiste;
    private string $datesortie;
    public function __construct(string $n, ?array $tab,string $a,string $d){
        parent ::__construct($n, $tab);
        $this -> artiste = $a;
        $this -> datesortie = $d;
    }

    public function __set(string $val, string $v){
        if($val == 'artiste'){
            $this -> artiste = $v;
        }elseif($val == 'datesortie'){
            $this -> datesortie = $v;
        }
    }


}
