<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPlaylistAction extends Action{

    public function execute(): string{
        //On verifie si la méthode http est un get donc on enverra le formulaire a remplir pour crée une playlist
        // c'est a dire qu'on vas verifier si le serveur nous demande de crée une playlist on lui demandera de remplir
        // un formulaire
        if($this -> http_method === "GET"){
            //On crée le formulaire qui devras etre remplis
            $res = "<form action='?action=add-playlist' method='post'>\n
            <label for='titre'>Nom de la playlist : </label>\n
            <input type='text' id='titre' name='titre'>\n

            <button type='submit'>Valider </button>
            </form>";

            //On le retourne pour que l'utilisateur puisse le remplir
            return $res;
            }else{
                $titre = $_POST['titre'] ?? '';

                if ($titre === '') {
                    return 'Saisis un nom pour la playlist.';
                }

                $playlist = new Playlist($titre, []);

                $repository = DeefyRepository::getInstance();
                $repository->sauvegarderPlaylist($playlist);

                $_SESSION['Playlist'] = $playlist;

                $rendu = new AudioListRenderer($playlist);

                return $rendu->render(1) . '<p><a href="?action=add-track">Ajouter une piste</a></p>';
            }
    }
}
