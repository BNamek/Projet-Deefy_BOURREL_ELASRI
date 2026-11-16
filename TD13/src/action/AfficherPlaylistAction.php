<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class AfficherPlaylistAction extends Action{

    public function execute(): string{
        $req = DeefyRepository::getInstance();
        $playlist = $req ->trouverToutePlaylists();

        $html = "<h2>Mes playlists</h2>";

        if (count($playlist) === 0) {
            return $html . "<p>Aucune playlist pour le moment.</p>";
        }

        $html .= "<ul>";
        foreach ($playlist as $p) {
            $nom = htmlspecialchars($p->nom);
            $html .= "<li><a href='?action=playlist&amp;id=" . $p->id . "'>" . $nom . "</a></li>";
        }
        $html .= "</ul>";

        return $html;


    }
}
