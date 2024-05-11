<?php
     
require 'monparcours.php';

$id = isset($_GET['id']) ? checkInput($_GET['id']) : null;

$élément = array();

if (!empty($_GET['id'])) {
    $id = checkInput($_GET['id']);
}


$élément = array();


$nomError = $contenuError = $cursus_categoryError = $dateError = $imageError = $nom = $contenu = $cursus_category = $date = $image = "";

if(!empty($_POST)) {
    $nom            = checkInput($_POST['nom']);
    $contenu        = checkInput($_POST['contenu']);
    $cursus_category      = checkInput($_POST['cursus_category']);
    $date           = checkInput($_POST['date']); 
    $image          = checkInput($_FILES["image"]["name"]);
    $imagePath      = '../images/'. basename($image);
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    $estReussi      = true;
    
    
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
        $estImageModifiée = false;
    }
    else {
        $estImageModifiée = true;
        $estChargéReussi = true;
        if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif" ) {
            $imageError = "Les fichiers autorises sont: .jpg, .jpeg, .png, .gif";
            $estChargéReussi = false;
        }
        if(file_exists($imagePath)) {
            $imageError = "Le fichier existe deja";
            $estChargéReussi = false;
        }
        if($_FILES["image"]["size"] > 700000) {
            $imageError = "Le fichier ne doit pas depasser les 700KB";
            $estChargéReussi = false;
        }
        if($estChargéReussi) {
            if(!move_uploaded_file($_FILES["image"]["tmp_nom"], $imagePath)) {
                $imageError = "Il y a eu une erreur lors de l'upload";
                $estChargéReussi = false;
            } 
        } 
    }
     
    if (($estReussi && $estImageModifiée && $estChargéReussi) || ($estReussi && !$estImageModifiée)) {
    $db = Database::connect();
    if ($estImageModifiée) {
        $instruction = $db->prepare("UPDATE programmes  set nom = ?, contenu = ?, cursus_category = ?, date = ?, image = ? WHERE id = ?");
        $instruction->execute(array($nom, $contenu, $cursus_category, $date, $image, $id));
    } else {
        $instruction = $db->prepare("UPDATE programmes  set nom = ?, contenu = ?, cursus_category = ?, date = ? WHERE id = ?");
        $instruction->execute(array($nom, $contenu, $cursus_category, $date, $id));
    }
    Database::disconnect();
    header("Location: index.php");
    } else if ($estImageModifiée && !$estChargéReussi) {
    $db = Database::connect();
    $instruction = $db->prepare("SELECT * FROM programmes where id = ?");
    $instruction->execute(array($id));
    $élément = $instruction->fetch();
    $image = $élément['image'];
    Database::disconnect();
    }

} 
else {
    $db = Database::connect();
    $instruction = $db->prepare("SELECT * FROM programmes where id = ?");
    $instruction->execute(array($id));
    $élément = $instruction->fetch();
    $nom           = $élément['nom'];
    $contenu    = $élément['contenu'];
    $cursus_category          = $élément['cursus_category'];
    $date       = $élément['date'];
    $image          = $élément['image'];
    Database::disconnect();
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
            <div class="col-md-6">
                <h1><strong>Modifier un élément</strong></h1>
                <br>
                <form class="form" action="<?php echo 'modifier.php?id='.$id;?>" role="form" method="post" enctype="multipart/form-data">
                    <br>
                    <div>
                        <label class="form-label" for="name">Nom:</label>
                        <input type="text" class="form-control" id="name" name="nom" placeholder="Nom" value="<?php echo $nom;?>">
                        <span class="help-inline"><?php echo $nomError;?></span>
                    </div>
                    <br>
                    <!---   --->
                    <!---   --->
                    <!-- Méthode 1 -->
                    <!---   --->
                    <!---  
                    <div>
                        <label class="form-label" for="contenu">Contenu:</label>
                        <select class="form-control" id="contenu" name="contenu" placeholder="Contenu">
                        <span class="help-inline"> Ouvrir("<" sur php)?php echo $contenuError; ?></span>
                  A voir ici___> {<}?php
                        // Connexion à la base de données
                        $db = Database::connect();

                        foreach ($db->query('SELECT * FROM programmes') as $row) 
                        {
                            if($row['id'] == $contenu)
                                echo '<option selected="selected" value="'. $row['id'] .'">'. $row['nom'] . '</option>';
                            else
                                echo '<option value="'. $row['contenu'] .'">'. $row['contenu'] . '</option>';;
                        }

                        // Déconnexion de la base de données
                        Database::disconnect();
                        ?>
                        </select>
                        <span class="help-inline">  ---{<}---  ?php echo $contenuError; ?></span>
                    </div>
                    -->


                    <!---   --->
                    <!---   --->
                    <!-- Méthode 2 -->
                    <!---   --->
                    <!---   --->
                    <div>
                        <label class="form-label" for="contenu">Contenu:</label>
                        <select class="form-control" id="contenu" name="contenu" placeholder="Contenu">
                            <?php
                                // Connexion à la base de données
                                $db = Database::connect();

                                // Requête SQL pour récupérer les données de la colonne "contenu" de la table "programmes"
                                $sql = "SELECT DISTINCT contenu FROM programmes";
                                $instruction = $db->query($sql);

                                // Boucle à travers les résultats et affichage des options de sélection
                                while ($row = $instruction->fetch()) {
                                    echo '<option value="' . $row['contenu'] . '">' . $row['contenu'] . '</option>';
                                }

                                // Déconnexion de la base de données
                                Database::disconnect();
                            ?>
                        </select>
                        <span class="help-inline"><?php echo $contenuError;?></span>
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="date">Date: </label>
                        <input type="date" step="" class="form-control" id="date" name="date" placeholder="La date" value="<?php echo $date;?>">
                        <span class="help-inline"><?php echo $dateError;?></span>
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="cursus_category">Cursus_category</label>
                        <select class="form-control" id="cursus_category" name="cursus_category" placeholder="Cursus" value="<?php echo $cursus_category;?>">
                        <span class="help-inline"><?php echo $cursus_categoryError;?></span>
                        <?php
                           $db = Database::connect();
                           foreach ($db->query('SELECT * FROM programmes') as $row) 
                           {
                                if($row['id'] == $date)
                                    echo '<option selected="selected" value="'. $row['id'] .'">'. $row['nom'] . '</option>';
                                else
                                    echo '<option value="'. $row['id'] .'">'. $row['nom'] . '</option>';;
                           }
                           Database::disconnect();
                        ?>
                        </select>
                        <span class="help-inline"><?php echo $dateError;?></span>
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="image">Image:</label>
                        <p><?php echo $image;?></p>
                        <label for="image">Sélectionner une nouvelle image:</label>
                        <input type="file" id="image" name="image"> 
                        <span class="help-inline"><?php echo $imageError;?></span>
                    </div>
                    <br>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><span class="bi-pencil"></span> Modifier</button>
                        <a class="btn btn-primary" href="index.php"><span class="bi-arrow-left"></span> Retour</a>
                   </div>
                </form>
            </div>
            <div class="col-md-6 site">
                <div class="img-thumbnail">
                    <img src="<?php echo '../images/'.$image;?>" alt="...">
                    <?php if(isset($élément['date'])): ?>
                    <div class="date"><?php echo date_format(date_create($élément['date']), 'Y-m-d');?></div>
                    <?php endif; ?>
                    <div class="caption">
                        <?php if (array_key_exists('nom', $élément)): ?>
                            <h4><?php echo $élément['nom']; ?></h4>
                        <?php endif; ?>
                        <p><?php echo $contenu; ?></p>
                        <a href="#" class="btn btn-order" role="button"><span class="bi-cart-fill"></span> Consulter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
