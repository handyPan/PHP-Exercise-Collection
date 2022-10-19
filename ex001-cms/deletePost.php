<?php require_once("lib/db.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>

<?php checkLoginState(); ?>

<?php
    if (isset($_POST["submit"])) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);
        
        global $conn;
        $query = "DELETE FROM post WHERE id='$id'";
        $execute = mysqli_query($conn, $query);
    
        if ($execute) {
            $_SESSION["successMessage"]="Post deleted successfully.";
            redirectTo("dashboard.php");
        } else {
            $_SESSION["errorMessage"] = "Post failed to delete.";
            redirectTo("dashboard.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Post</title>
    <!-- add the Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;1,400&family=Roboto:ital,wght@0,400;0,700;1,400&family=Lato:ital,wght@0,400;0,700;1,400&family=Rubik:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- navigation bar begins -->
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a href="index.php" class="navbar-brand"><img src="img/Blog_pic.png"></a>
                <div id="filler"></div>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <?php
                        if (isset($_SESSION["userLoggedIn"])) {
                            echo "<li><a href=\"dashboard.php\">Dashboard</a></li>";
                        }
                    ?>
                </ul>
                <form action="index.php" class="navbar-form navbar-right" method="get">
                    <?php showNavbarProfile(); ?>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search something..." name="search">
                    </div>
                    <button class="btn btn-primary" type="Submit" name="btnSearch">Go!</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- navigation bar ends -->
    <div class="container-fluid">
        <div class="row">
            <!-- side area begins -->
            <div class="col-sm-2" id="dashboard-side-area">
                <ul id="side-menu" class="nav nav-pills nav-stacked">
                    <li><a href="dashboard.php"><span class="glyphicon glyphicon-th"></span>&nbsp;&nbsp;Dashboard</a></li>
                    <li class="active"><a href="addPost.php"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Add Post</a></li>
                    <li><a href="categories.php"><span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp; Manage Categories</a></li>
                    <li><a href="admins.php"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Manage Admins</a></li>
                    <li><a href="comments.php"><span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;Manage Comments
                            <?php
                                global $conn;
                                $queryCount = "SELECT COUNT(*) FROM comment WHERE status='OFF'";
                                $executeCount = mysqli_query($conn, $queryCount);
                                $resultCount = mysqli_fetch_array($executeCount);
                                $resultCount = array_shift($resultCount);
                                if ($resultCount>0) {
                            ?>
                            <span class="label label-danger pull-right comment-count">
                                <?php echo $resultCount;?>
                            </span>
                            <?php
                                }
                            ?>
                        </a></li>
                    <li><a href="index.php?page=1" target="_blank"><span class="glyphicon glyphicon-equalizer"></span>&nbsp;&nbsp;Live Blog</a></li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Logout</a></li>
                </ul>
            </div>
            <!-- side area ends -->
            <!-- main area begins -->
            <div class="col-sm-10" id="dashboard-main-area">
                <div>
                    <?php 
                        echo errorMessage();
                        echo successMessage(); 
                    ?>
                </div>
                <h1>Delete Post</h1>
                <div>
                    <?php
                        global $conn;
                        $postIdFromUrl = $_GET["id"];
                        $query = "SELECT * FROM post WHERE id='$postIdFromUrl' ORDER BY createdAt DESC";
                        $execute = mysqli_query($conn, $query);
                    while ($result = mysqli_fetch_array($execute)) {
                        $title = $result["title"]; 
                        $category = $result["category"];
                        $image = $result["image"]; 
                        $mainBody = $result["mainBody"]; 
                    }    
                    ?>
                    <form action="deletePost.php" method="post" enctype="multipart/form-data">
                        <fieldset>
                                <div class="form-group" id="post-id">
                                    <label for="id"><span class="field-info">Id:</span></label>
                                    <input class="form-control" type="text" name="id" id="id" value="<?php echo $postIdFromUrl; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="title"><span class="field-info">Title:</span></label>
                                    <input class="form-control" type="text" name="title" id="title" value="<?php echo $title; ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="category"><span class="field-info">Category:</span></label>
                                    <input class="form-control" type="text" name="category" id="category" value="<?php echo $category; ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="image"><span class="field-info">Image:</span></label>
                                    <img class="edit-post-banner" id="post-banner-preview" src="upload/<?php echo $image; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="mainBody"><span class="field-info">Main Body:</span></label>
                                    <textarea class="form-control" name="mainBody" id="mainBody" disabled>
                                        <?php echo trim($mainBody); ?>
                                    </textarea>
                                </div> 
                                <br>   
                                <button class="btn btn-danger btn-block" type="Submit" name="submit">Delete Post</button>
                        </fieldset>
                        <br>
                    </form>
                </div>
            </div>
            <!-- main area ends -->
        </div> 
    </div>
    <!-- footer begins -->
    <div id="footer">
        <!-- <hr> -->
        <p>Created by Handy Pan &copy; 2022 All rights reserved.</p>
        <a href="#">
            Check out more projects
        </a>
        <!-- <hr> -->
    </div>
    <!-- footer ends -->
    <!-- <div id="footer-bottom"></div> -->

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/closeAlert.js"></script>
</body>
</html>