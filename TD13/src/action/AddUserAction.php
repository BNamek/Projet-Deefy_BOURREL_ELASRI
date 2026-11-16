<?php
namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;

class AddUserAction extends Action{

    public function execute(): string{
        if($this ->http_method === 'GET'){
            $res = "<form action='?action=add-user' method='post'>\n
            <label for='Nom'>Nom : </label>\n
            <input type='text' id='Nom' name='Nom'>\n

            <label for='Mail'>Email : </label>\n
            <input type='email' id='Mail' name='Mail'>\n

            <label for='Age'>Age : </label>\n
            <input type='number' id='Age' name='Age'>\n

            <button type='submit'>Connexion </button>
            </form>";

            return $res;
        }else{
            $v1 = filter_var($_POST['Nom'],FILTER_SANITIZE_SPECIAL_CHARS);
            $v2 = filter_var($_POST['Mail'],FILTER_VALIDATE_EMAIL);
            $v3 = filter_var($_POST['Age'],FILTER_VALIDATE_INT);
            $stockage =[$v1,$v2,$v3];

            $_SESSION['User']=$stockage;

            $res = "Nom: $v1, Email: $v2, Age: $v3 ans";

            return $res;


        }
	}
}
