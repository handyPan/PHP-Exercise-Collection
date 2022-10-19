<?php require_once("lib/db.php"); ?>
<?php require_once("lib/common.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>
<?php
    $postIdFromUrl = $_GET["id"];
    if (isset($_POST["submit"])) {
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $comment = mysqli_real_escape_string($conn, $_POST["comment"]);
        date_default_timezone_set("America/Toronto");
        $currentTime = time();
        $dateTime = strftime("%B-%d-%Y %H:%M:%S", $currentTime);
        if (empty($dateTime) or empty($name) or empty($email) or empty($comment)) {
            $_SESSION["errorMessage"] = "Fields can't be empty.";
        } elseif (strlen($name)>200) {
            $_SESSION["errorMessage"] = "Name too long.";
        } elseif (strlen($email)>200) {
            $_SESSION["errorMessage"] = "Email too long.";
        } elseif (strlen($comment)>500) {
            $_SESSION["errorMessage"] = "Comment too long.";
        } else {
            global $conn;
            $query = "INSERT INTO comment(createdAt, creator, email, comment, approvedBy, status, postId) 
                                    VALUES('$dateTime', '$name', '$email', '$comment', '', 'OFF', '$postIdFromUrl')";
            $execute = mysqli_query($conn, $query);
            if ($execute) {
                $_SESSION["successMessage"] = "Comment added successfully.";
                redirectTo("readPost.php?id={$postIdFromUrl}");
            } else {
                $_SESSION["errorMessage"] = "Comment failed to add.";
                redirectTo("readPost.php?id=".$postIdFromUrl);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Blog</title>
    <!-- add the Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;1,400&family=Roboto:ital,wght@0,400;0,700;1,400&family=Lato:ital,wght@0,400;0,700;1,400&family=Rubik:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- <div id="page-top"></div> -->
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
    <!-- <div id="page-top"></div> -->
    <div class="container-fluid container-post">
        <div class="blog-header">
            <h1>Blog CMS System</h1>
            <p class="lead">This is a blog cms system to management the programming notes of Handy Pan.</p>
        </div>
        <div class="row">
            <!-- main area begins -->
            <div class="col-sm-8" id="read-post-main-area">        
                <div>
                    <?php 
                        echo errorMessage();
                        echo successMessage(); 
                    ?>
                </div>
                <?php
                    global $conn;
                    if (isset($_GET["btnSearch"])) {
                        $search = $_GET["search"];
                        $query = "SELECT * FROM post 
                                    WHERE createdAt LIKE '%$search%' OR title LIKE '%$search%' OR category LIKE '%$search%' OR author LIKE '%$search%' OR image LIKE '%$search%' or mainBody LIKE '%$search%' 
                                    ORDER BY createdAt DESC";
                    } else {
                        $postIdFromUrl = $_GET["id"];
                        $query = "SELECT * FROM post WHERE id='$postIdFromUrl' ORDER BY createdAt DESC";
                    }
                    $execute = mysqli_query($conn, $query);
                    while ($result = mysqli_fetch_array($execute)) {
                        $id = $result["id"];
                        $createdAt = $result["createdAt"]; 
                        $title = $result["title"]; 
                        $category = $result["category"]; 
                        $author = $result["author"]; 
                        $image = $result["image"]; 
                        $mainBody = $result["mainBody"]; 
                    }
                ?>
                <div class="blog-post">
                    <div class="blog-banner">
                        <img class="img-responsive img-rounded" src="upload/<?php echo $image; ?>" alt="">
                    </div>
                    <div>
                        <h2 class="blog-title"><?php  echo htmlentities($title); ?></h2>
                        <p class="blog-description">Category: <?php echo htmlentities($category); ?>&nbsp;<strong>&middot;</strong>&nbsp;Published on <?php echo htmlentities($createdAt); ?> </p>
                        <p class="blog-main-body">
                            <?php 
                                echo nl2br($mainBody); 
                            ?>
                        </p>
                    </div>
                </div>
                <br>
                <h3>Comments</h3>
                <?php
                    global $conn;
                    $query = "SELECT * FROM comment WHERE postId='$postIdFromUrl' ORDER BY createdAt DESC";
                    $execute = mysqli_query($conn, $query);
                    echo '<span class="field-info">' . mysqli_num_rows($execute)." comments found.</span><br><br>";
                    while ($result = mysqli_fetch_array($execute)) {
                        $commentId = $result["id"];
                        $createdAt = $result["createdAt"]; 
                        $creator = $result["creator"];
                        $comment = $result["comment"]; 
                ?>
                <div id="comment-row">
                    <span id="comment-<?php echo $commentId;?>"></span>
                    <div id="profile-img">
                        <img class="profile" src="img/profile.jpg">
                    </div>
                    <div id="comment-txt">
                        <p id="comment-info"><?php echo $creator;?>&nbsp;<strong>&middot;</strong>&nbsp;Published on <?php echo $createdAt;?></p>
                        <p id="comment-content"><?php echo nl2br($comment);?></p>
                    </div>
                </div>
                <?php
                    } 
                ?>
                <h3>Share your thoughts</h3>
                <div>
                    <form action="readPost.php?id=<?php echo $postIdFromUrl; ?>" method="post" enctype="multipart/form-data">
                        <fieldset>
                                <div class="form-group">
                                    <label for="name"><span class="field-info">Name:</span></label>
                                    <input class="form-control" type="text" name="name" id="name" placeholder="Name">
                                </div>
                                <div class="form-group">
                                    <label for="email"><span class="field-info">Email:</span></label>
                                    <input class="form-control" type="text" name="email" id="email" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label for="comment"><span class="field-info">Comment:</span></label>
                                    <textarea class="form-control" name="comment" id="comment"></textarea>
                                </div> 
                                <br>   
                                <button class="btn btn-primary btn-block" type="Submit" name="submit">Submit</button>
                        </fieldset>
                        <br>
                    </form>
                </div>
            </div>
            <!-- main area ends -->
            <!-- side area begins -->
            <div class="col-sm-offset-1 col-sm-3">
                <h2>About the author</h2>
                <img class="img-responsive img-circle img-icon" src="img/koala.webp" alt="">
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title">Categories</h2>
                    </div>
                    <div class="panel-body">
                        <?php
                            global $conn;
                            $queryCategory = "SELECT * FROM category ORDER BY categoryName ASC";
                            $execute = mysqli_query($conn, $queryCategory);
                            while ($result = mysqli_fetch_array($execute)) {
                                $id = $result["id"];
                                $createdAt = $result["createdAt"];
                                $categoryName = $result["categoryName"];
                                $creatorName = $result["creatorName"];
                        ?>
                        <a href="index.php?category=<?php echo urlencode($categoryName); ?>">
                        <span class="panel-category-name"><?php echo $categoryName; ?></span>
                        </a>
                        <br>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 class="panel-title">Recent Posts</h2>
                    </div>
                    <div class="panel-body">
                        <?php
                            global $conn;
                            $queryRecentPosts = "SELECT * FROM post ORDER BY createdAt DESC LIMIT 0, 5";
                            $execute = mysqli_query($conn, $queryRecentPosts);
                            while ($result = mysqli_fetch_array($execute)) {
                                $id = $result["id"];
                                $createdAt = $result["createdAt"];
                                $title = $result["title"]; 
                                $image = $result["image"];
                        ?>
                        <div class="recent-post-row">
                            <div class="recent-post-img">
                                <img class="img-responsive" src="upload/<?php echo htmlentities($image); ?>" alt="">
                            </div>
                            <div class="recent-post-txt">
                                <a href="readPost.php?id=<?php echo $id; ?>">
                                    <p class="recent-post-title"><?php echo htmlentities($title); ?></p>
                                </a>
                                <p class="recent-post-time"><?php echo htmlentities($createdAt); ?></p>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="panel-footer">
                        
                    </div>
                </div>
            </div>
            <!-- side area ends -->
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