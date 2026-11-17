<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action{
    public function execute():string{
        //On test qu'il y a bien la playlist dans la session
        if(isset($_SESSION['Playlist'])){
            //Si la method de l'http est un get donc qu'il demande des données
            if($this -> http_method === 'GET'){
                //On envoie donc un formulaire a remplire qui vas etre le podcast a ajouter dans la playlist
                $res = "<form enctype='multipart/form-data' action='?action=add-track' method='post'>

                <label for='fichier'> Selectionner le fichier :</label>
                <input type = 'file' id='fichier' name='userfile'>

                <label for='titre'> Nom du podcast :</label>
                <input type = 'text' id='titre' name='titre'>

                <label for='auteur'> Auteur du podcast :</label>
                <input type = 'text' id='auteur' name='auteur'>

                <label for='genre'> Genre du podcast :</label>
                <input type = 'text' id='genre' name='genre'>

                <label for='dure'> Durée du podcast :</label>
                <input type = 'number' id='dure' name='duree'>

                <button type='submit'>Valider </button>
                </form>";

                return $res;
            }else{
                if(substr($_FILES['userfile']['name'], -4) === '.mp3' && $_FILES['userfile']['type'] === 'audio/mpeg'){
                    $var1 = filter_var($_POST['titre'],FILTER_SANITIZE_SPECIAL_CHARS);
                    $var2 = filter_var($_POST['auteur'],FILTER_SANITIZE_SPECIAL_CHARS);
                    $var3 = filter_var($_POST['genre'],FILTER_SANITIZE_SPECIAL_CHARS);
                    $var4 = filter_var($_POST['duree'],FILTER_VALIDATE_INT);

                    //Convertit en texte une génération de 8 octet au hasard et on colle l'extension mp3 a la fin
                    $nom = bin2hex(random_bytes(8)) . '.mp3';
                    //On sort le fichier de tmp name qui est un rangement temporaires pour le mettre dans audio
                    //Le DIR donne le dossier du fichier php ou on se situe et on rajoute /audio pour le ranger dans un dossier audio
                    $destination = __DIR__ . '/../../audio/' . $nom;

                    if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $destination)) {
                        return 'Impossible d’enregistrer le fichier MP3.';
                    }

                    //On met donc le nouveau nom du fichier dans le chemin avec audio qui l'endroit ou on la stocker
                    $aud = new PodcastTrack($var1,'audio/'.$nom);
                    $aud -> __set('auteur',$var2);
                    $aud -> __set('genre',$var3);
                    $aud -> __set('duree',$var4);

                    $audioL = $_SESSION['Playlist'];

                    $repository = DeefyRepository::getInstance();
                    $repository->ajoutPiste($audioL->id, $aud, $nom);

                    $audioL -> ajouterPiste($aud);
                    $_SESSION['Playlist']=$audioL;
                    $af = new AudioListRenderer($_SESSION['Playlist']);
                    return $af ->render(1).'<br>
                    <a href="?action=add-track">Ajouter encore une piste</a>';
                }else{
                    return'Le fichier sélectionné doit etre un fichier audio .mp3
                    <br> <a href="?action=add-track">Crée une nouvelle piste</a>';
                }
            }
        }else{
            return "<strong> Il n'y a pas de playlist existante</strong>";
        }
    }
}
