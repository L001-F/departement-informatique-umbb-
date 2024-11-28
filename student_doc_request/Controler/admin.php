<?php

include("../model/class.php");
$admin=new Scolarite();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loginA'])){
    $username=$_POST['username'];
    $password=$_POST['password'];

        if($admin->loginA($username,$password)){
            $adm=$admin->loginA($username,$password);
            $id=$adm['id'];
            header('location:../view/admin/admin.php?id='.$id);
            exit;
        }else{
            header('location:../view/admin/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Confirmer'])) {
            $status = $_POST['status'];
            $id =$_POST['id'];
            $admin->status($status,$id);
            $adminId = $_POST['adminId'] ;
            header('location:../view/admin/admin.php?adminId='.$adminId);
        }
}

 


