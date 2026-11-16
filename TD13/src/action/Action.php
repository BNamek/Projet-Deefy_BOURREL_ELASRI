<?php

namespace iutnc\deefy\action;

abstract class Action {
    //Creation des attributs qui sont null
    protected $http_method = null;
    protected $hostname = null;
    protected $script_name = null;

    //Creation du constructeur qui va stocker dans les attributs
    //la requete http, le nom de l'hote et le nom du script
    public function __construct(){

        $this->http_method = $_SERVER['REQUEST_METHOD'];
        $this->hostname = $_SERVER['HTTP_HOST'];
        $this->script_name = $_SERVER['SCRIPT_NAME'];
    }

    //Une foncion qui va executer qu'elle que chose et qui retournera un string donc une chaine de caractere
    abstract public function execute() : string;

}
