<?php
namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class DisplayPlaylistAction extends Action{

    public function execute(): string{
        // Si un id est dans l'URL : on charge la playlist depuis la BD
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if ($id === false) {
                return "<strong>Identifiant de playlist invalide</strong>";
            }

            $playlist = DeefyRepository::getInstance()->trouverPlaylistParId($id);
            if ($playlist === null) {
                return "<strong>Playlist introuvable</strong>";
            }

            // Elle devient la playlist courante
            $_SESSION['Playlist'] = $playlist;
        }

        // Affichage de la playlist courante (fonctionnalités 2 et 5)
        if (isset($_SESSION['Playlist'])) {
            $rendu = new AudioListRenderer($_SESSION['Playlist']);
            return $rendu->render(1) . "<br><a href='?action=add-track'>Ajouter une piste</a>";
        }

        return "<strong>Aucune playlist courante</strong>";
    }
}
