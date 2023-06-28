<?php include('partials/menu.php'); ?>

<?php 
    //check whether id is set or not 
    if(isset($_GET['id']))
    { 
        //get all the details
        $id = $_GET['id'];

        //sql query to get the selsected food
        $sql2 = "SELECT * FROM tbl_food WHERE id=$id";

        //execute the query
        $res2 = mysqli_query($conn, $sql2);

        //get the value based on query executed
        $row2 = mysqli_fetch_assoc($res2);

        //get the individual values of selected food 
        $title = $row2['title'];
        $descriptiopn = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['category_id'];
        $featured = $row2['featured'];
        $active = $row2['active'];

    }
    else
    {
        //redirect to manage food
        header('location:'.SITEURL.'admin/manage-food.php');
    }
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>

        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">

            <table class="tbl-30">

                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo $title; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5"><?php echo $descriptiopn; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price" value="<?php echo $price; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php 
                            if($current_image=="")
                            {
                                //image not available
                                echo "<div class='error'>Image Not Available</div>";
                            }
                            else
                            {
                                //image available
                                ?>
                                <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="150px">
                                <?php
                            }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Select New Imgae: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Category: </td>
                    <td>
                        <select name="category">

                            <?php 
                                //query to get active categories
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";

                                //execute the query
                                $res = mysqli_query($conn, $sql);

                                //count rows 
                                $count = mysqli_num_rows($res);

                                //check whether category available or not 
                                if($count>0)
                                {
                                    //category available
                                    while($row=mysqli_fetch_assoc($res))
                                    {
                                        $category_title = $row['title'];
                                        $category_id = $row['id'];

                                        //echo "<option value='$category_id'>$category_title</option>";
                                        ?>
                                        <option <?php if($current_category==$category_id){echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
                                        <?php
                                    }
                                }
                                else
                                {
                                    //category not available
                                    echo "<option value='0'>Category Not Available</option>";
                                }
                            ?>

                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input <?php if($featured=="Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes"> Yes
                        <input <?php if($featured=="No"){echo "checked";} ?> type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if($active=="Yes"){echo "checked";} ?> type="radio" name="active" value="Yes"> Yes
                        <input <?php if($active=="No"){echo "checked";} ?> type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>

        <?php 
        
            if(isset($_POST['submit']))
            {
                //echo "Button Clicked";

                //get all the details from the form
                $id = $_POST['id'];
                $title = $_POST['title'];
                $descriptiopn = $_POST['description'];
                $price = $_POST['price'];
                $current_image = $_POST['current_image'];
                $category = $_POST['category'];
                $featured = $_POST['featured'];
                $active = $_POST['active'];

                //upload the image if selected
                //check whether upload button is clicked or not
                if(isset($_FILES['image']['name']))
                {
                    //upload button clicked
                    $image_name = $_FILES['image']['name']; //new image name

                    //check whether the file is available or not
                    if($image_name != "")
                    {
                        //image is available
                        //uploading new image
                        //rename the image
                        $ext = end(explode('.', $image_name));

                        $image_name = "Food_Name_".rand(000, 999).'.'.$ext; //e.g. Food_Name_123.jpg

                        $src_path = $_FILES['image']['tmp_name'];

                        $dest_path = "../images/food/".$image_name;

                        //Finally Upload the Image
                        $upload = move_uploaded_file($src_path, $dest_path);

                        //check whether the image is uploaded or not
                        //and if the image is not uploaded then we will stop the process and redirect with error message
                        if($upload==FALSE)
                        {
                            //set message
                            $_SESSION['upload'] = "<div class='error'>Failed to Upload New Image</div>";
                            //redirect to manage food page
                            header('location:'.SITEURL.'admin/manage-food.php');
                            //stop the process
                            die();
                        }

                        //remove current image if available
                        if($current_image != "")
                        {
                            //current image is available
                            //remove the image
                            $remove_path = "../images/food/".$current_image;

                            $remove = unlink($remove_path);

                            //check whether the image is removed or not 
                            if($remove==FALSE)
                            {
                                //failed to remove current image
                                $_SESSION['remove-failed'] = "<div class='error'>Failed To Remove Current Image</div>";

                                //redirect to manage food
                                header('location:'.SITEURL.'admin/manage-food.php');
                                //stop the process
                                die();
                            }
                        }
                    }
                    else
                    {
                        $image_name = $current_image;   //default image when image not selected
                    }
                }
                else
                {
                    $image_name = $current_image;   //  default image when button is not clicked
                }

                //update the food in database
                $sql3 = "UPDATE tbl_food SET
                        title ='$title',
                        description='$descriptiopn',
                        price = $price,
                        image_name = '$image_name',
                        category_id = '$category',
                        featured = '$featured',
                        active = '$active'
                        WHERE id=$id
                ";

                //execute the sql query
                $res3 = mysqli_query($conn, $sql3);

                //check whether the query is executed or not 
                if($res3==TRUE)
                {
                    //query executed and food updated
                    $_SESSION['update'] = "<div class='success'>Food Updated Successfully</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
                else
                {
                    //failed to update food
                    $_SESSION['update'] = "<div class='error'>Failed To Update Food</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }

            }
        
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>