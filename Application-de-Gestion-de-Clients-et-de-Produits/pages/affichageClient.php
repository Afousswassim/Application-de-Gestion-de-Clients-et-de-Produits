<?php
global $connexion;
session_start();

// Vérification de la session
if (!isset($_SESSION['us'])) {
    header("location:authentification.php");
}

// Inclusion des fichiers de configuration
require_once('configuration.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script type="text/javascript" src="../js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>

    <style>
        body {
            font-family: "Trebuchet MS", sans-serif;
        }

        .farid {
            font-family: "lucida console", sans-serif;
            font-size: 20px;
        }

        .col-lg-3 {
            position: relative;
            margin-left: 400px;
            margin-top: 60px;
        }

        .bar {
            position: absolute;
            text-align: right;
        }
    </style>
</head>

<body>
<?php include('menu.php'); ?>

<!--########################### AFFICHAGE DES CLIENTS #########################################-->
<div class="container">
    <div class="panel panel-success marge60 col-md-4 col-md-offset-4">
        <div class="panel-heading">Rechercher les clients</div>
        <div class="panel-body">

            <form method="GET" action="affichageClient.php" class="form-inline">
                <div class="input-group">
                    <input type="text" name="rechercheClient" placeholder="nom ou prenom" class="form-control">
                    <div class="input-group-btn form-inline">
                        <button class="btn btn-danger" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
                &nbsp; &nbsp;

                <?php
                // Gestion de la recherche
                if (isset($_GET['rechercheClient'])) {
                    $tape = $_GET['rechercheClient'];
                } else {
                    $tape = "";
                }

                $limite = isset($_GET['limite']) ? $_GET['limite'] : 5;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $defaut = ($page - 1) * $limite;

                // Requête pour afficher tous les clients y compris les critères de recherche
                $sql = "SELECT * FROM client WHERE nom LIKE '%$tape%' OR prenom LIKE '%$tape%' LIMIT $limite OFFSET $defaut";
                // Requête pour compter le nombre total de clients
                $sqlCompteur = "SELECT COUNT(*) AS compteur FROM client WHERE nom LIKE '%$tape%' OR prenom LIKE '%$tape%'";
                $resultatCompteur = mysqli_query($connexion, $sqlCompteur);

                // Vérification si la requête a réussi
                if ($resultatCompteur) {
                    $tableauCompteur = mysqli_fetch_assoc($resultatCompteur);
                    $nombreClients = $tableauCompteur['compteur']; // Nombre total de clients
                } else {
                    // Si la requête échoue, initialisez le compteur à 0
                    $nombreClients = 0;
                }

                // Calculer le nombre de pages
                $nombrePages = ceil($nombreClients / $limite); // Nombre total de pages
                ?>

            </form>
        </div>
    </div>
</div>
<div class="container">
    <div class="panel panel-primary marge20">
        <div class="panel-heading">
            <center><span class="farid">Liste des clients [<?php echo $nombreClients; ?> clients]</span></center>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>
                        <center>NOM</center>
                    </th>
                    <th>
                        <center>PRENOM</center>
                    </th>
                    <th>
                        <center>ACTIONS</center>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $resultat = mysqli_query($connexion, $sql);
                while ($ligne = mysqli_fetch_assoc($resultat)) {
                    ?>
                    <tr>
                        <td>
                            <center>
                                <?php echo strtoupper($ligne['nom']); ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php echo $ligne['prenom']; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <a href="modifierClient.php?idclient=<?php echo $ligne['idclient']; ?>">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a> &nbsp; &nbsp;
                                <a onclick="return confirm('vous êtes sûr ?')" href="supprimerClient.php?id=<?php echo $ligne['idclient']; ?>">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                            </center>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <div>
                <!--########### PAGINATION ###########-->
                <ul class="pagination">
                    <?php
                    if ($page == 1) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">Précèdent</a></li>';
                    }
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="affichageClient.php?page=' . ($page - 1) . '">Précèdent</a></li>';
                    }
                    for ($i = 1; $i <= $nombrePages; $i++) {
                        ?>
                        <li class="page-item <?php if ($page == $i) echo "active"; ?>">
                            <a class="page-link" href="affichageClient.php?page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php
                    }
                    if ($page >= $nombrePages) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">Suivant</a></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="affichageClient.php?page=' . ($page + 1) . '">Suivant</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>

</html>
