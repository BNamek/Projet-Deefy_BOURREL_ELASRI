<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AudioTrack;

interface Render {
    public function render(int $selector) : string;
    const COMPACT = 1;

}
