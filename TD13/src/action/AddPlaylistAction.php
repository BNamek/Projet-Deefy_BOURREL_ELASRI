<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;

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
                //On crée le tableau qui va etre vide car ici on veux juste crée une playlist mais ne pas mettre de musique dedans
                $audio = array();
                //On la stock dans une session
                //Dans le post on met le nom pour recuprer la valeur qui est dans le titre et on le filtre pour mettre les caracteres speciaux entre guillemet
                $var = filter_var($_POST['titre'],FILTER_SANITIZE_SPECIAL_CHARS);
                $_SESSION['Playlist'] = new Playlist($var,$audio);
                $af = new AudioListRenderer($_SESSION['Playlist']);
                return $af ->render(1).'<br>
                <a href="?action=add-track">Ajouter une piste</a>';
            }
    }
}
