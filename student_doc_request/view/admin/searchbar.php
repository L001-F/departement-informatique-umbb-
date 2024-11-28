<?php 
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    header("Location: index.php");
} else {
    require_once '../../model/class.php'; 
    $adm = new Scolarite();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Confirmer'])) {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $status = $_POST['status'];
        $adm->status($status, $id);
    }
}

// Initialize $input and $data
$input = isset($_POST['student']) ? $_POST['student'] : '';
$data = [];
if (!empty($input)) {
    $data = $adm->searchStudent($input);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Document</title>
</head>

    <style>
        /* Navbar */
nav {
    background-color: #3f5062;
    color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid #f4f5f6;
}

.nav-container {
    display: flex;
    justify-content: space-between;
    width: 100%;
    align-items: center;
}

.nav-links {
    list-style: none;
    display: flex;
    margin: 0;
    padding: 0;
}

.nav-links li {
    margin-right: 20px;
}

.nav-links a {
    text-decoration: none;
    color: #f4f5f6;
    font-size: 16px;
    font-weight: bold;
    transition: color 0.3s;
}

.nav-links a:hover {
    color: #ff9800;
}
/* Search Results */
.search-results {
    margin: 20px auto;
    padding: 20px;
    max-width: 900px;
    background-color: #f4f5f6;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-results h2 {
    text-align: center;
    color: #3f5062;
    margin-bottom: 20px;
    font-size: 24px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th,
.table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
    color: #333;
}

.table th {
    background-color: #3f5062;
    color: #fff;
    font-weight: bold;
}

.table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table tr:hover {
    background-color: #f1f1f1;
}

</style>
<body>
<nav>
    <div class="nav-container">
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="students.php">Étudiants</a></li>
            <li><a href="requests.php">Demandes</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
        <div class="search-box">
            <form action="" method="post">
                <input type="search" placeholder="Rechercher ..." name="student">
                <button type="submit" name="search">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<!--search results-->
<div class="search-results">
    <h2>Résultats de la Recherche</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Niveau</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($data as $val) {
                echo "<tr>
                        <td>{$val['matricule']}</td>
                        <td>{$val['nom']}</td>
                        <td>{$val['prenom']}</td>
                        <td>{$val['niveau']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php 
$id=isset($_GET['id'])? $_GET['id']:'';
if(!$id){
    header("Location: index.php");
  }else{
    include('../../model/class.php') ;
    $adm=new Scolarite();

 if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Confirmer'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $status = $_POST['status'];
    $adm->status($status, $id);}
}

?> 

</body>
</html>
