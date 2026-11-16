<?php
namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;

class DefaultAction extends Action{

    //Vas etre l'action par défaut que le programme vas faire.
    //Lorsqu'il n'y a pas de parametre/query action le programme lancera le defaut
    function execute(): string {
        return "<h1>Bienvenue !</h1>";
    }
}
