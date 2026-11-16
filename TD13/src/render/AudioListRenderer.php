<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AudioTrack;

class AudioListRenderer implements Render {
    private AudioList $liste;

    public function __construct(AudioList $alb) {
        $this->liste = $alb;
    }

    public function render(int $selector): string {
        $res = "<h2>" . $this->liste->nom . "</h2>";

        foreach ($this->liste->piste as $piste) {
            $res = $res . $this->compacte($piste);
        }

        $res = $res . "<br>Nombre de pistes : " . $this->liste->nombrePiste;
        $res = $res . "<br>Durée totale : " . $this->liste->dureeTot;

        return $res;
    }

    private function compacte(AudioTrack $piste): string {
        $res = "<br>" . $piste->titre . " - " . $piste->auteur .
        " <br> <audio controls src=" . $piste->nomFichierAudio . "> </audio>";

        return $res;
    }
}
