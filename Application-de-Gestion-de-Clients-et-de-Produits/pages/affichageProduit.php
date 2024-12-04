<?php
global $connexion;
session_start();

// تحقق مما إذا كان المستخدم مسجلاً للدخول، وإذا لم يكن كذلك، يتم توجيهه إلى صفحة تسجيل الدخول.
if (!isset($_SESSION['us'])) {
    header("location:authentification.php");
}

require_once('configuration.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <style>
        body {
            font-family: "Trebuchet MS", sans-serif;
        }

        .farid {
            font-family: "lucida console", sans-serif;
            font-size: 20px;
        }
    </style>
</head>

<body>
<?php include('menu.php'); ?>

<!-- قسم عرض المنتجات -->
<div class="container">
    <div class="panel panel-success marge60 col-md-4 col-md-offset-4">
        <div class="panel-heading">Rechercher...</div>
        <div class="panel-body">
            <form method="get" action="affichageProduit.php" class="form-inline">
                <div class="input-group">
                    <input type="text" name="rechercheProduit" placeholder="libellé ou prix ou quantité" class="form-control">
                    <div class="input-group-btn form-inline">
                        <button class="btn btn-danger" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
                &nbsp; &nbsp;

                <?php
                // التحقق من قيمة البحث المدخلة
                if (isset($_GET['rechercheProduit'])) {
                    $tape = $_GET['rechercheProduit'];
                } else {
                    $tape = "";
                }

                // إعدادات pagination
                $limite = isset($_GET['limite']) ? $_GET['limite'] : 5;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $defaut = ($page - 1) * $limite;

                // استعلام لجلب المنتجات بناءً على شروط البحث
                $sql = "SELECT * FROM produit WHERE libelle LIKE '%$tape%' OR prix LIKE '%$tape%' OR quantite LIKE '%$tape%' LIMIT $limite OFFSET $defaut";

                // استعلام لحساب عدد المنتجات
                $sqlCompteur = "SELECT count(*) AS compteur FROM produit WHERE libelle LIKE '%$tape%'";
                $resultatCompteur = mysqli_query($connexion, $sqlCompteur);
                $tableauCompteur = mysqli_fetch_assoc($resultatCompteur);
                $nombre = $tableauCompteur['compteur'];

                // حساب عدد الصفحات
                $reste = $nombre % $limite;
                $pageNombre = ($reste === 0) ? ($nombre / $limite) : (floor($nombre / $limite) + 1);
                ?>
            </form>
        </div>
    </div>
</div>

<div class="container marge60">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <center class="farid">Liste des produits</center>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th><center>LIBELLE</center></th>
                    <th><center>PRIX</center></th>
                    <th><center>QUANTITE</center></th>
                    <th><center>ACTIONS</center></th>
                </tr>
                </thead>
                <tbody>
                <?php
                // تنفيذ استعلام جلب المنتجات
                $resultat1 = mysqli_query($connexion, $sql);
                while ($ligne = mysqli_fetch_assoc($resultat1)) { ?>
                    <tr>
                        <td><center><?php echo $ligne['libelle'] ?></center></td>
                        <td><center><?php echo floor($ligne['prix']) . ' DH' ?></center></td>
                        <td><center><?php echo $ligne['quantite'] ?></center></td>
                        <td>
                            <center>
                                <a href="modifierProduit.php?idproduit=<?php echo $ligne['idproduit']?>">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a> &nbsp; &nbsp;
                                <a onclick="return confirm('vous êtes sûr ?')" href="supprimerProduit.php?id=<?php echo $ligne['idproduit']?>">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                            </center>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <div>
                <!-- قسم الترقيم -->
                <ul class="pagination">
                    <?php
                    // عرض الأزرار المناسبة للترقيم
                    if ($page == 1) {
                        echo '<li class="page-item disabled"><a class="page-link" href="">Précèdent</a></li>';
                    }
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="affichageProduit.php?page=' . ($page - 1) . '">Précèdent</a></li>';
                    }
                    for ($i = 1; $i <= $pageNombre; $i++) { ?>
                        <li class="page-item <?php if ($page == $i) echo " active "; ?>">
                            <a class="page-link" href="affichageProduit.php?page=<?php echo $i ?>"><?php echo $i ?></a>
                        </li>
                    <?php }

                    if ($page >= $pageNombre) {
                        echo '<li class="page-item disabled"><a class="page-link" href="">Suivant</a></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="affichageProduit.php?page=' . ($page + 1) . '">Suivant</a></li>';
                    } ?>
                </ul>
            </div>

        </div>
    </div>
</div>
</body>

</html>
