<?php
    // Inclusion du fichier contenant les fonctions nécessaires
    require 'monparcours.php';
 
    // Vérification de l'existence de l'identifiant dans les paramètres GET
    if (!empty($_GET['id'])) {
        // Nettoyage de l'identifiant pour éviter les attaques par injection SQL
        $id = checkInput($_GET['id']);
    }

    // Vérification si des données sont soumises via la méthode POST
    if (!empty($_POST)) {
        // Récupération et nettoyage de l'identifiant à supprimer depuis le formulaire
        $id = checkInput($_POST['id']);
        // Connexion à la base de données
        $db = Database::connect();
        // Préparation de la requête SQL pour supprimer le programme avec l'identifiant spécifié
        $instruction = $db->prepare("DELETE FROM programmes WHERE id = ?");
        // Exécution de la requête avec l'identifiant en paramètre
        $instruction->execute(array($id));
        // Déconnexion de la base de données
        Database::disconnect();
        // Redirection vers la page principale après la suppression
        header("Location: index.php"); 
    }

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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link href="https://fonts.googleapis.com/css2?family=Abel&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <link rel="stylesheet" href="../css/styles.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    </head>
    
    <body>
        <h1 class="text-logo"><span class="bi bi-book"></span> Mon Parcours en Géographie <span class="bi bi-book"></span></h1>
        <div class="container admin">
            <div class="row">
                <!-- Formulaire pour supprimer un programme -->
                <h2><strong>Supprimer un programme</strong></h2>
                <br>
                <form class="form" action="supprimer.php" role="form" method="post">
                    <br>
                    <!-- Champ caché contenant l'identifiant du programme à supprimer -->
                    <input type="hidden" name="id" value="<?php echo $id;?>"/>
                    <!-- Message de confirmation -->
                    <p class="alert alert-warning">Etes vous sûr de vouloir supprimer ?</p>
                    <!-- Boutons pour confirmer ou annuler la suppression -->
                    <div class="form-actions">
                      <button type="submit" class="btn btn-warning">Oui</button>
                      <a class="btn btn-secondary" href="index.php">Non</a>
                    </div>
                </form>
            </div>
        </div>   
    </body>
</html>
