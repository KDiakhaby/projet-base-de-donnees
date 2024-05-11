<?php
// Définition de la classe Database
class Database
{
  // Informations de connexion à la base de données
  private static $dbHost = "localhost";
  private static $dbName = "monparcours";
  private static $dbUser = "root";
  private static $dbUserPassword = "";
  private static $connection = null;

  // Méthode statique pour se connecter à la base de données
  public static function connect()
  {
    try 
    {
      // Création d'une nouvelle instance PDO pour la connexion à la base de données MySQL
      self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName, self::$dbUser, self::$dbUserPassword);
    } 
    catch (PDOException $e) 
    {
      // En cas d'échec de la connexion, affichez le message d'erreur et arrêtez le script
      die ($e->getMessage());
    }
    // Retourne l'instance de connexion à la base de données
    return self::$connection;
  }
  
  // Méthode statique pour se déconnecter de la base de données
  public static function disconnect()
  {
    // Définit l'instance de connexion à la base de données sur null pour fermer la connexion
    self::$connection = null;
  }
}

// Appel de la méthode de connexion à la base de données
Database::connect();
?>
