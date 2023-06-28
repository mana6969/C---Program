<?php 

    //Include constants.php file Here
    include('../config/constants.php');

    //get the id of admin to be deleted
    $id = $_GET['id'];

    //create sql query to delete admin
    $sql = "DELETE FROM tbl_admin WHERE id=$id";
    
    //Execute the Query
    $res = mysqli_query($conn, $sql);

    //Check Whether the query executed successfully or not
    if($res==TRUE)
    {
        //Query Executed Successfully and Admin Deleted
        //echo "Admin Deleted";
        //Create Session Variable to Display Message
        $_SESSION['delete'] = "<div class='success'>Admin Deleted Successfully</div>";
        //Redirect to Manage Admin Page 
        header('location:'.SITEURL.'admin/manage-admin.php');
    }
    else
    {
        //Failed to Delete Admin
        //echo "Failed to Delete Admin";
        $_SESSION['delete'] = "<div class='error'>Failed to Delete Admin. Try Again Later.</div>";
        //Redirect to Manage Admin Page 
        header('location:'.SITEURL.'admin/manage-admin.php');
    }

    //redirect to manage admin page with message(succes/error)

?>