<?php  
    class Scolarite{
        private $db;
//class construct
function __construct(){

    $user='root';
    $pass='';
    $dsn='mysql:host=localhost;dbname=isil';
    //create connection
    try{
        $dbh=new PDO($dsn,$user,$pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 
    }catch(PDOException $e){
        die("ERREUR! : ".$e->getMessage());
    }
   $this->db=$dbh;
}

public function loginA($username,$password){
    try {
        $select = 'SELECT * FROM admin WHERE username= ? AND password=? ';
        $query = $this->db->prepare($select);
        $query->bindParam(1, $username);
        $query->bindParam(2, $password);
        $query->execute();
        $admin = $query->fetch();
        return $admin;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
public function loginS($matricule,$password){
    try {
        $select = 'SELECT * FROM student WHERE matricule= ? AND password=? ';
        $query = $this->db->prepare($select);
        $query->bindParam(1, $matricule);
        $query->bindParam(2, $password);
        $query->execute();
        $student = $query->fetch();
        return $student;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
//add Recourse fucntion
function addDemande($matricule,$type_doc){
    try{
    //:fn<-marqueur nomme
    $query = $this->db->prepare("INSERT INTO demandes (matricule,type_doc) 
    VALUES (:matricule, :type_doc)  ");
    //bindParam()<-requetes MySQL prepares avec PDO ,va lier un parametre a un nom specifier
    $query->bindParam(':matricule', $matricule);
    //$query->bindParam(':nom', $nom);
    //$query->bindParam(':prenom', $prenom);
    //$query->bindParam(':group', $niveau);
    //$query->bindParam(':specialites', $specialites);
    //$query->bindParam(':annee_scolaire', $annee_scolaire);
    $query->bindParam(':type_doc', $type_doc);
    if($query->execute())
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    Demande added successfully    
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} catch (PDOException $e) {
    echo  '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    Student not found!! '.$e->getMessage().'
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
    }
    
}

//display recourses list function
function getDemande($matricule){
    $select='SELECT demandes.matricule,student.nom,student.prenom,student.groupe,demandes.id,
    demandes.type_doc,demandes.status
     FROM demandes JOIN student ON demandes.matricule=student.matricule where demandes.matricule=:matricule';
    $resault=$this->db->prepare($select);
    $resault->bindParam(':matricule', $matricule);
    $resault->execute();
    $ligne=$resault->fetchall();
    return $ligne;
}

//display recourses list function
function getAllDemande(){
    $select = '
        SELECT demandes.*, student.nom, student.prenom
        FROM demandes
        JOIN student ON demandes.matricule = student.matricule';
    $resault = $this->db->query($select);
    $ligne = $resault->fetchAll();
    return $ligne;
}

function status($status, $id){
    try{
    $query = $this->db->prepare("UPDATE demandes SET status = :status WHERE id = :id");
    $query->bindParam(':status', $status);
    $query->bindParam(':id', $id);
    if($query->execute()){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
Status added successfully       
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
    }
} catch (PDOException $e) {
    echo  '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        Faild to add status
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    }
    
}
// function for searching student
function searchStudent($input){
    $select = "SELECT * FROM student where ((matricule LIKE '%$input%' ) OR (nom LIKE '%$input%' ) OR (prenom LIKE '%$input%') OR (groupe LIKE '%$input%')) ";
    $result = $this->db->query($select);
    $ligne = $result->fetchAll(PDO::FETCH_ASSOC); 
    return $ligne;
}

// get demande by date
public function getDemandeByDate($date) {
    $stmt = $this->db->prepare("SELECT * FROM demandes 
    JOIN student ON demandes.matricule = student.matricule 
    WHERE DATE(date_demande) = ?");
    $stmt->execute([$date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Function to fetch demandes by document type only
public function getDemandeByType($type) {
    // SQL query to filter by document type
    $sql = "SELECT * FROM demandes JOIN student ON demandes.matricule = student.matricule
    WHERE type_doc = :type";

    // Prepare the SQL statement
    $stmt = $this->db->prepare($sql);

    // Bind the parameter to the statement
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    // Fetch all results as an associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the filtered data
    return $data;
}

// Function to fetch demandes by both date and document type
public function getDemandeByDateAndType($filter_date, $filter_type) {
    // Only filter by document type if it's not 'toutes'
    if ($filter_type != 'toutes') {
        $query = "SELECT * FROM demandes JOIN student ON demandes.matricule = student.matricule
        WHERE DATE(date_demande) = :date_demande AND type_doc = :type_doc";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':date_demande', $filter_date);
        $stmt->bindParam(':type_doc', $filter_type);
    } else {
        // If filter_type is 'toutes', fetch by date only
        $query = "SELECT * FROM demandes WHERE DATE(date_demande) = :date_demande";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':date_demande', $filter_date);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getDemandeByYear($year) {
    // Prepare SQL query to select data based on the academic year (niveau)
    $query = "SELECT * FROM demandes JOIN student ON demandes.matricule = student.matricule
    WHERE niveau = :year";
    
    // Prepare the SQL statement
    $stmt = $this->db->prepare($query);
    
    // Bind the academic year parameter
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch all results as an associative array
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getDemandeByDateTypeAndYear($date, $type, $year) {
    $query = "SELECT * FROM demandes  JOIN student ON demandes.matricule = student.matricule
    WHERE DATE(date_demande) = :date AND type_doc = :type AND niveau = :year";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':year', $year);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getDemandeByDateAndYear($date, $year) {
    // SQL query to filter by date and year
    $query = "SELECT * FROM demandes 
              JOIN student ON demandes.matricule = student.matricule
              WHERE DATE(date_demande) = :date AND niveau = :year";
    
    // Prepare the SQL statement
    $stmt = $this->db->prepare($query);

    // Bind the parameters to the statement
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':year', $year, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    // Fetch all results as an associative array
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getDemandeByTypeAndYear($type, $year) {
    // SQL query to filter by type and year
    $query = "SELECT * FROM demandes 
              JOIN student ON demandes.matricule = student.matricule
              WHERE type_doc = :type AND niveau = :year";

    // Prepare the SQL statement
    $stmt = $this->db->prepare($query);

    // Bind the parameters to the statement
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':year', $year, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    // Fetch all results as an associative array
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




}


 
