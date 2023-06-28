<?php 

    //include constants page
    include('../config/constants.php');

    //echo "Delete Food Page";

    if(isset($_GET['id']) && isset($_GET['image_name']))
    {
        //process to delete
        //echo "Process to delete";

        //get id and image name
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        //remove the image if availble
        //check whether the image is available or not and delete only if available
        if($image_name != "")
        {
            //it has image and need to remove from folder
            //get the image path
            $path = "../images/food/".$image_name;

            //remove image file from folder
            $remove = unlink($path);

            //check whether the image is removed or not
            if($remove==FALSE)
            {
                //failed to remove image
                $_SESSION['upload'] = "<div class='error'>Failed To Remove Image File</div>";
                //redirect to manage food
                header('location:'.SITEURL.'admin/manage-food.php');
                //stop the process of deleting food
                die();
            }
        }

        //delet food from database
        $sql = "DELETE FROM tbl_food WHERE id=$id";
        //execute the query
        $res = mysqli_query($conn, $sql);

        //check whether the query executed or not and set the session message respectively
        //redirect to manage food with session message
        if($res==TRUE)
        {
            //food deleted 
            $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }
        else
        {
            //failed to delete food
            $_SESSION['delete'] = "<div class='error'>Failed To Delete Food</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }

    }
    else
    {
        //redirect to manage food page
        //echo "Redirect";
        $_SESSION['unauthorize'] = "<div class='error'>Unauthorized Access</div>";
        header('location:'.SITEURL.'admin/manage-food.php');
    }

?>