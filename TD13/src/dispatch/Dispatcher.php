<?php
namespace iutnc\deefy\dispatch;

class Dispatcher{
    private $action;
    private array $tab;

    public function __construct(){
        //On construite un tableau vide au depart qui ne contient aucune route
        $this -> tab = [];
        //Si il n'y a rien dans le query alors l'action est le defaut, c'est a dire si il n'ya pas de query action
        if(!isset($_GET['action'])){
            $this -> action = 'default';
        }else{
            $this -> action = $_GET['action'];
        }
    }

    public function run():void{
        if(array_key_exists($this -> action,$this -> tab)){
            $obj = $this ->tab[$this->action];
            $r = new $obj();
            $r = $r ->execute();
            $this -> renderPage($r);
        }else{
            echo 'La valeur du query n est pas la bonne ou n existe pas';
        }
    }

    public function addRoute(string $action, string $class): self{
        $this -> tab[$action] = $class;
        return $this;
    }

    private function renderPage(string $html):void{
        echo '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Deefy</title>

        </head>
        <body>
        <h1>Deefy</h1>
        <br>
        <a href="?action=default">Acceuil</a>
        <br>
        <a href="?action=add-user">Se connecter</a>
        <br>
        <a href="?action=add-playlist">Ajouter une playlist</a>
        <br>
        <a href="?action=add-track">Ajouter un podcast ou une musique a la playlist</a>
        <br>
        <a href="?action=playlist">Afficher les playlistes présentes</a>
        <br>
        <a href="?action=mes-playlists">Afficher les playlistes présentes dans la base de donné</a>
        <br>'.$html.'</body> </html>';
    }
}
