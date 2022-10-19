<?php require_once("lib/db.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>

<?php checkLoginState(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                <!-- <h1>John Doe</h1> -->
                <ul id="side-menu" class="nav nav-pills nav-stacked">
                    <li><a href="dashboard.php"><span class="glyphicon glyphicon-th"></span>&nbsp;&nbsp;Dashboard</a></li>
                    <li><a href="addPost.php"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Add Post</a></li>
                    <li><a href="categories.php"><span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp; Manage Categories</a></li>
                    <li><a href="admins.php"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Manage Admins</a></li>
                    <li class="active">
                        <a href="comments.php"><span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;Manage Comments
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
                        </a>
                    </li>
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
                <h1>Manage Comments</h1>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>Item No.</th>
                            <th>Created At</th>
                            <th>Creator</th>
                            <th>Email</th>
                            <th>Comment</th>
                            <th>For Post</th>
                            <th>Action</th>
                            <th>(Un)Approved By</th>
                            <th>Details</th>
                        </tr>
                        <?php
                            $itemNum = 0;
                            global $conn;
                            $query = "SELECT * FROM comment ORDER BY createdAt DESC";
                            $execute = mysqli_query($conn, $query);
                            while ($result = mysqli_fetch_array($execute)) {
                                $itemNum++;
                                $id = $result["id"];
                                $createdAt = $result["createdAt"];
                                $creator = $result["creator"];  
                                $email = $result["email"]; 
                                $comment = $result["comment"];
                                $approvedBy = $result["approvedBy"];
                                $status = $result["status"];
                                $postId = $result["postId"]; 
                        ?>
                        <tr>
                            <td><?php echo $itemNum; ?></td>
                            <td><?php echo $createdAt; ?></td>
                            <td>
                                <?php 
                                    // if (strlen($creator)>30) {
                                    //     $creator = substr($creator, 0, 30)." ...";
                                    // } 
                                    echo $creator;
                                ?>
                            </td>
                            <td><?php echo $email; ?></td>
                            <td>
                                <?php 
                                    // if (strlen($comment)>30) {
                                    //     $comment = substr($comment, 0, 30)." ...";
                                    // } 
                                    echo $comment;
                                ?>
                            </td>
                            <td>
                                <?php
                                    global $conn;
                                    $queryPost = "SELECT * FROM post WHERE id='$postId'";
                                    $executePost = mysqli_query($conn, $queryPost);
                                    $resultPost = mysqli_fetch_array($executePost);
                                    $titlePost = $resultPost["title"];
                                    echo $titlePost;
                                ?>
                            </td>
                            <td>
                                <?php
                                    if ($status=="OFF") {
                                ?>
                                    <a class="btn btn-success btn-action" href="approveComment.php?id=<?php echo $id; ?>&status=ON">Approve</a>
                                <?php
                                    } else {
                                ?>
                                    <a class="btn btn-warning btn-action" href="approveComment.php?id=<?php echo $id; ?>&status=OFF">Unapprove</a>
                                <?php
                                    }
                                ?>
                                <a class="btn btn-danger btn-action" href="deleteComment.php?id=<?php echo $id; ?>">Delete</a>
                            </td>
                            <td>
                                <?php
                                    echo $approvedBy;
                                ?>
                            </td>
                            <td>
                                <a class="btn btn-primary" href="readPost.php?id=<?php echo $postId; ?>#comment-<?php echo $id; ?>" target="_blank">Preview</a>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
                </div>
            </div>
            <!-- main area ends -->
        </div> 
    </div>
    <div id="body-filler"></div>
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