<?php
     
    require 'monparcours.php';
 
    $nomError = $contenuError = $cursus_categoryError = $dateError = $imageError = $nom = $contenu = $cursus_category = $date = $image = "";

    if(!empty($_POST)) {
        $nom            = checkInput($_POST['nom']);
        $contenu        = checkInput($_POST['contenu']);
        $cursus_category      = checkInput($_POST['cursus_category']);
        $date           = checkInput($_POST['date']); 
        $image          = checkInput($_FILES["image"]["name"]);
        $imagechemin    = '../images/'. basename($image);
        $imageExtension = pathinfo($imagechemin, PATHINFO_EXTENSION);

        $estReussi          = true;
        $estReussiChargé    = false;
        
        if(empty($nom)) {
            $nomError = 'Ce champ ne peut pas être vide';
            $estReussi = false;
        }
        if(empty($contenu)) {
            $contenuError = 'Ce champ ne peut pas être vide';
            $estReussi = false;
        } 
        if(empty($cursus_category)) {
            $cursus_categoryError = 'Ce champ ne peut pas être vide';
            $estReussi = false;
        } 
        if(empty($date)) {
            $dateError = 'Ce champ ne peut pas être vide';
            $estReussi = false;
        }
        if(empty($image)) {
            $imageError = 'Ce champ ne peut pas être vide';
            $estReussi = false;
        }
        else {
            $estReussiChargé = true;
            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif" ) {
                $imageError = "Les fichiers autorises sont: .jpg, .jpeg, .png, .gif";
                $estReussiChargé = false;
            }
            if(file_exists($imagechemin)) {
                $imageError = "Le fichier existe deja";
                $estReussiChargé = false;
            }
            if($_FILES["image"]["size"] > 700000) {
                $imageError = "Le fichier ne doit pas depasser les 700KB";
                $estReussiChargé = false;
            }
            if($estReussiChargé) {
                if(!move_uploaded_file($_FILES["image"]["tmp_nom"], $imagechemin)) {
                    $imageError = "Il y a eu une erreur lors de l'upload";
                    $estReussiChargé = false;
                } 
            } 
        }
        
        if($estReussi && $estReussiChargé) {
            $db = Database::connect();
            $instruction = $db->prepare("INSERT INTO programmes (nom,contenu,cursus_category,date,image) values(?, ?, ?, ?, ?)");
            $instruction->execute(array($nom,$contenu,$cursus_category,$date,$image));
            Database::disconnect();
            header("Location: index.php");
        }
    }

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
                <h2><strong>Ajouter un programme</strong></h2>
                <br>
                <form class="form" action="ajouter.php" role="form" method="post" enctype="multipart/form-data">
                    <br>
                    <div>
                        <label class="form-label" for="nom">Nom:</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Le nom" value="<?php echo $nom;?>">
                        <span class="help-inline"><?php echo $nomError;?></span>
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="contenu">Contenu:</label>
                        <input type="text" class="form-control" id="contenu" name="contenu" placeholder="Les descriptions" value="<?php echo $contenu;?>">
                        <span class="help-inline"><?php echo $contenuError;?></span>
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="cursus">Cursus:</label>
                        <select class="form-control" id="cursus_category" name="cursus_category">
                        <?php
                           $db = Database::connect();
                           foreach ($db->query('SELECT * FROM cursus') as $row) {
                                echo '<option value="'. $row['id'] .'">'. $row['nom'] . '</option>';;
                           }
                           Database::disconnect();
                        ?>
                        </select>
                        <span class="help-inline"><?php echo $cursus_categoryError;?></span>
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="date">Date: </label>
                        <input type="date" step="" class="form-control" id="date" name="date" placeholder="La date" value="<?php echo $date;?>">
                        <span class="help-inline"><?php echo $dateError;?></span>

                    </div>
                    <br>
                    
                    <br>
                    <div>
                        <label class="form-label" for="image">Sélectionner une image:</label>
                        <input type="file" id="image" name="image"> 
                        <span class="help-inline"><?php echo $imageError;?></span>
                    </div>
                    <br>
                    <div class="form-opérations">
                        <button type="submit" class="btn btn-success"><span class="bi-pencil"></span> Ajouter</button>
                        <a class="btn btn-primary" href="index.php"><span class="bi-arrow-left"></span> Retour</a>
                   </div>
                </form>
            </div>
        </div>   
    </body>
</html>