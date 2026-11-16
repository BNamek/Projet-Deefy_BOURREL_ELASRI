<?php
require_once 'vendor/autoload.php';

session_start();

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\AfficherPlaylistAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\repository\DeefyRepository;

//Création du dispatcher
$d = new Dispatcher();
//Ajout des routes possibles
$d -> addRoute('default',DefaultAction::class);
$d -> addRoute('playlist',DisplayPlaylistAction::class);
$d -> addRoute('add-playlist',AddPlaylistAction::class);
$d -> addRoute('add-track',AddPodcastTrackAction::class);
$d -> addRoute('add-user',AddUserAction::class);
//On lance ensuite le dispatcher qui vas donc vérifier dans le query qu'elle action on veut faire
//Si elle existe alors on appelle la classe qui lui convient pour réaliser le formulaire/ ou un affichage de playlist
// On doit maintenant charger la base de données grace au fichier de configuration
// Pour appelle la méthode vue que le constructeur est priver on ne peut pas faire de new
// donc on met :: apres le nom de la classe car les méthodes sont static
DeefyRepository::setConfig("config.db.ini");
$d ->addRoute('mes-playlists',AfficherPlaylistAction::class);
$d->run();
