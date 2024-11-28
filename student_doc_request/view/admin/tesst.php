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
// filters + combinision 
$data = []; // Initialize the data array

$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'toutes';
$filter_year = isset($_GET['filter_year']) ? $_GET['filter_year'] : 'toutes';

// Handle combinations of filters
if (!empty($filter_date) && $filter_type != 'toutes' && $filter_year != 'toutes') {
    // All filters applied
    $data = $adm->getDemandeByDateTypeAndYear($filter_date, $filter_type, $filter_year);
} elseif (!empty($filter_date) && $filter_type != 'toutes') {
    // Filters: Date and Type
    $data = $adm->getDemandeByDateAndType($filter_date, $filter_type);
} elseif (!empty($filter_date) && $filter_year != 'toutes') {
    // Filters: Date and Year
    $data = $adm->getDemandeByDateAndYear($filter_date, $filter_year);
} elseif ($filter_type != 'toutes' && $filter_year != 'toutes') {
    // Filters: Type and Year
    $data = $adm->getDemandeByTypeAndYear($filter_type, $filter_year); // New method
} elseif (!empty($filter_date)) {
    // Filter: Date only
    $data = $adm->getDemandeByDate($filter_date);
} elseif ($filter_type != 'toutes') {
    // Filter: Type only
    $data = $adm->getDemandeByType($filter_type);
} elseif ($filter_year != 'toutes') {
    // Filter: Year only
    $data = $adm->getDemandeByYear($filter_year);
} else {
    // No filters, fetch all
    $data = $adm->getAllDemande();
}
if (!empty($filter_date) && $filter_year != 'toutes') {
    $data = $adm->getDemandeByDateAndYear($filter_date, $filter_year);
} elseif ($filter_type != 'toutes' && $filter_year != 'toutes') {
    $data = $adm->getDemandeByTypeAndYear($filter_type, $filter_year);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Filter Demandes by Date</title>
</head>
<body>
<header>
    <div class="header-container">
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="#">
                    <img src="images/logoUMBB.png" alt="Université Logo" class="logo">
                </a>
                <button class="nav-btn active mx-2" data-target="deposer">
                    Consulter les demandes
                </button>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" id="offcanvasNavbar">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Link</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<!-- Content Section -->
<div class="content mt-5">
    <div class="tab-content active" id="deposer">
        <section class="deposer-section bg-white p-4 rounded shadow-sm mt-3 mb-5">
            <div class="filters-container mb-4">
<!-- Date Filter -->
<label for="end-date">Sélectionner par date :</label>
<input 
    type="date" 
    id="end-date" 
    class="date-picker" 
    value="<?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>" 
    onchange="updateFilters()">

<!-- Document Type Filter -->
<label for="document-type">Type de document :</label>
<select id="document-type" class="dropdown" onchange="updateFilters()">
    <option value="toutes" <?php echo ($filter_type == 'toutes') ? 'selected' : ''; ?>>Toutes</option>
    <option value="Certificat de scolarité" <?php echo ($filter_type == 'Certificat de scolarité') ? 'selected' : ''; ?>>Certificat de scolarité</option>
    <option value="Attestation de bonne Conduite" <?php echo ($filter_type == 'Attestation de bonne Conduite') ? 'selected' : ''; ?>>Attestation de bonne Conduite</option>
    <option value="Relevé de notes" <?php echo ($filter_type == 'Relevé de notes') ? 'selected' : ''; ?>>Relevé de notes</option>
</select>

<!-- Academic Year Filter -->
<label for="academic-year">Niveau académique :</label>
<select id="academic-year" class="dropdown" onchange="updateFilters()">
    <option value="toutes" <?php echo ($filter_year == 'toutes') ? 'selected' : ''; ?>>Toutes</option>
    <option value="L1" <?php echo ($filter_year == 'L1') ? 'selected' : ''; ?>>L1</option>
    <option value="L2" <?php echo ($filter_year == 'L2') ? 'selected' : ''; ?>>L2</option>
    <option value="L3" <?php echo ($filter_year == 'L3') ? 'selected' : ''; ?>>L3</option>
    <option value="M1" <?php echo ($filter_year == 'M1') ? 'selected' : ''; ?>>M1</option>
    <option value="M2" <?php echo ($filter_year == 'M2') ? 'selected' : ''; ?>>M2</option>
</select>


            <table class="table">
                <thead>
                    <tr>
                        <th>N° Demande</th>
                        <th>Matricule D'étudiant</th>
                        <th>Nom Complet</th>
                        <th>Type Document</th>
                        <th>Date Demande</th>
                        <th>Status</th>
                        <th>Mettre à jour</th>
                        <th>Confirmer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($data)) {
                        foreach ($data as $val) {
                            echo "<tr>
                                <td>{$val['id']}</td>
                                <td>{$val['matricule']}</td>
                                <td>{$val['nom']} {$val['prenom']}</td>
                                <td>{$val['type_doc']}</td>
                                <td>{$val['date_demande']}</td>
                                <td>{$val['status']}</td>
                                <td>
                                    <form method='post'>
                                        <select class='form-select' name='status'>
                                            <option value='en cours'>en cours</option>
                                            <option value='favorable'>favorable</option>
                                            <option value='unfavorable'>unfavorable</option>
                                        </select>
                                </td>
                                <td>
                                    <input type='hidden' name='id' value='{$val['id']}'>
                                    <button class='btn btn-success' type='submit' name='Confirmer'>Confirmer</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>Aucune demande trouvée pour cette date.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</div>

<script>
function updateFilters() {
    const date = document.getElementById('end-date').value;
    const type = document.getElementById('document-type').value;
    const year = document.getElementById('academic-year').value;
    const url = new URL(window.location.href);

    // Update or remove the date filter
    if (date) {
        url.searchParams.set('filter_date', date);
    } else {
        url.searchParams.delete('filter_date');
    }

    // Update or remove the document type filter
    if (type && type !== 'toutes') {
        url.searchParams.set('filter_type', type);
    } else {
        url.searchParams.delete('filter_type');
    }

    // Update or remove the academic year filter
    if (year && year !== 'toutes') {
        url.searchParams.set('filter_year', year);
    } else {
        url.searchParams.delete('filter_year');
    }

    // Reload the page with the updated URL
    window.location.href = url.toString();
}




</script>
</body>
</html>
