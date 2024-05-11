<?php
    // Inclusion du fichier contenant les fonctions nécessaires
    require 'monparcours.php';

    // Vérification de l'existence de l'identifiant dans les paramètres GET
    if(!empty($_GET['id'])) {
        // Nettoyage de l'identifiant pour éviter les attaques par injection SQL
        $id = checkInput($_GET['id']);
    }
     
    // Connexion à la base de données
    $db = Database::connect();
    // Préparation de la requête SQL pour récupérer les informations du programme avec l'identifiant spécifié
    $instruction = $db->prepare("SELECT programmes.id, programmes.nom, programmes.contenu, programmes.image, programmes.date, cursus.nom AS cursus_category FROM programmes LEFT JOIN cursus ON programmes.cursus_category = cursus.id WHERE programmes.id = ?");
    // Exécution de la requête avec l'identifiant en paramètre
    $instruction->execute(array($id));
    // Récupération de la première ligne de résultat
    $élément = $instruction->fetch();
    // Déconnexion de la base de données
    Database::disconnect();

    // Fonction pour nettoyer les données entrées par l'utilisateur
    function checkInput($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

?>

<!DOCTYPE html>
<html>
    <head>
      <title>Mon Parcours en Géographie</title>
      <meta charset="utf-8"/>
      <meta nom="viewport" content="width=device-width, initial-scale=1">
      <!-- Inclusion des bibliothèques externes -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
      <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
      <link rel="stylesheet" href="../css/styles.css">
    </head>

    <body>

      <h1 class="text-logo"><span class="bi bi-book"></span> Mon Parcours en Géographie <span class="bi bi-book"></span></h1>
        <div class="container admin">
          <div class="row">
            <div class="col-md-6">
              <!-- Formulaire pour afficher les détails du programme -->
              <h2><strong>Voir un programme</strong></h2>
              <br>
              <form>
                <!-- Affichage des détails du programme -->
                <div>
                  <label>Nom:</label><?php echo '  '.$élément['nom'];?>
                </div>
                <br>
                <div>
                  <label>Contenu :</label><?php echo '  '.$élément['contenu'];?>
                </div>
                <br>
                <label>Image:</label><?php echo '  '.$élément['image'];?>
                </div>
                <br>
                <div>
                  <label>Date :</label><?php echo '  '.date_format(date_create($élément['date']), 'Y-m-d') ;?>
                </div>
                <br>
                <div>
                  <label>Cursus :</label><?php echo '  '.$élément['cursus_category'];?>
                </div>
                <br>
              </form>
              <br>
              <!-- Bouton de retour -->
              <div class="form-opérations">
                <a class="btn btn-primary" href="index.php"><span class="bi-arrow-left"></span> Retour</a>
              </div>
              <br>
            </div>
            <div class="col-md-6 site">
              <!-- Affichage de l'image du programme -->
              <div class="img-thumbnail">
                <img src="<?php echo '../images/'.$élément['image'];?>" alt="...">
                <!-- Affichage de la date et du nom du programme -->
                <div class="date"><?php date_format(date_create($élément['date']), 'Y-m-d') ;?></div>
                <div class="caption">
                  <h4><?php echo $élément['nom'];?></h4>
                  <p><?php echo $élément['contenu'];?></p>
                  <a href="#" class="btn btn-order" role="button"><span class="bi-cart-fill"></span> consulter</a>
                </div>
              </div>
            </div>
          </div>
        </div>   
    </body>
</html>
