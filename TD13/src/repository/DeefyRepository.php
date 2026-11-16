<?php
namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\PodcastTrack;
use PDO;

class DeefyRepository{
    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

     private function __construct(array $conf) {
     $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],
     [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
     }

      public static function getInstance(){
      if (is_null(self::$instance)) {
      self::$instance = new DeefyRepository(self::$config);
      }
      return self::$instance;
      }

      public static function setConfig(string $file) {
      $conf = parse_ini_file($file);
      if ($conf === false) {
      throw new \Exception("Error reading configuration file");
      }

      self::$config = [ 'dsn'=> "$conf[driver]:host=$conf[host];dbname=$conf[database]",
          'user'=> $conf['username'],'pass'=> $conf['password'] ];
      }

      public function trouverToutePlaylists(): array {
            $sql = "select id,nom from playlist";

            $req = $this ->pdo->prepare($sql);
            $req -> execute();

            $req -> setFetchMode(PDO::FETCH_ASSOC);

            $playlist = [];
            while($ligne = $req -> fetch()){
                $play = new Playlist($ligne['nom'],[]);
                $play ->setId($ligne['id']);
                $playlist[] = $play;
            }
            return $playlist;
        }

        public function trouverPlaylistParId(int $id){
            $sql = "select id,nom from playlist where id = :idplay";

            $req = $this ->pdo->prepare($sql);
            $req -> execute([':idplay'=> $id]);

            $req -> setFetchMode(PDO::FETCH_ASSOC);

            $ligne = $req -> fetch();

            if ($ligne === false) {
                return null;
            }

            $play = new Playlist($ligne['nom'],[]);
            $play ->setId($ligne['id']);

            $sql2 = "SELECT t.id, t.titre, t.genre, t.duree, t.filename,
                   t.artiste_album, t.auteur_podcast
            FROM track t
            INNER JOIN playlist2track p2t ON t.id = p2t.id_track
            WHERE p2t.id_pl = ?
            ORDER BY p2t.no_piste_dans_liste";

            $req2 = $this ->pdo->prepare($sql2);
            $req2 -> execute([$id]);

            while($ligne = $req2 -> fetch()){
                $piste = new PodcastTrack($ligne['titre'], 'audio/' . $ligne['filename']);
                $piste->auteur = $ligne['auteur_podcast'] ?? $ligne['artiste_album'];
                $piste ->__set('genre',$ligne['genre']);
                $piste ->__set('duree',$ligne['duree']);
                $play ->ajouterPiste($piste);
            }

            return $play;
        }

}
