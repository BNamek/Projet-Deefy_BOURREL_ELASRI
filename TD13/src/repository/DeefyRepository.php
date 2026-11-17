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

        public function ajoutPiste(int $playlistId, PodcastTrack $piste, string $nomFichier): void{
            $sql = 'insert into track (titre, auteur_podcast, genre, duree, filename, type) values (:titre, :auteur, :genre, :duree, :fichier, :type)';

            $requete = $this->pdo->prepare($sql);
            $requete->execute(['titre' => $piste->titre, 'auteur' => $piste->auteur, 'genre' => $piste->genre, 'duree' => $piste->duree, 'fichier' => $nomFichier, 'type' => 'P',]);

            $pisteId = (int) $this->pdo->lastInsertId();

            // Calcule la prochaine position dans la playlist.
            $sql = 'select max(no_piste_dans_liste) as derniere_position from playlist2track where id_pl = :playlist';

            $requete = $this->pdo->prepare($sql);
            $requete->execute(['playlist' => $playlistId]);
            $ligne = $requete->fetch(PDO::FETCH_ASSOC);
            $position = (int) ($ligne['derniere_position'] ?? 0) + 1;

            // Rattacher la piste à la playlist.
            $sql = 'insert into playlist2track (id_pl, id_track, no_piste_dans_liste) values (:playlist, :piste, :position)';

            $requete = $this->pdo->prepare($sql);
            $requete->execute(['playlist' => $playlistId, 'piste' => $pisteId, 'position' => $position,]);
        }

        public function sauvegarderPlaylist(Playlist $playlist): void{
            $sql = 'insert into playlist (nom) values (:nom)';

            $requete = $this->pdo->prepare($sql);
            $requete->execute(['nom' => $playlist->nom]);

            $playlist->setId((int) $this->pdo->lastInsertId());
        }

}
