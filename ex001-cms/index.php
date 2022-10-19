<?php require_once("lib/db.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
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
                    <li class="active"><a href="index.php">Home</a></li>
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
            <div class="col-sm-8">
                <?php
                    global $conn;
                    // determine the starting page number
                    if (isset($_GET["page"])) {
                        $page = $_GET["page"];
                        if (!is_numeric($page)) {
                            $page = 1;
                        }
                        $page = (int)$page;
                        if ($page <1) {
                            $page = 1;
                        }
                    } else {
                        $page = 1;
                    }
                    // each page shows 5 records, determine from which record in the result set to show the 5 records for $page
                    $showPostFrom = ($page - 1) * 5;

                    $searchFromUrl = "";
                    $categoryFromUrl = "";

                    if (isset($_GET["btnSearch"]) or isset($_GET["search"])) {
                        $searchFromUrl = $_GET["search"];
                        // get the count of results
                        $queryCount = "SELECT COUNT(*) FROM post 
                        WHERE createdAt LIKE '%$searchFromUrl%' OR title LIKE '%$searchFromUrl%' OR category LIKE '%$searchFromUrl%' OR author LIKE '%$searchFromUrl%' OR image LIKE '%$searchFromUrl%' or mainBody LIKE '%$searchFromUrl%' 
                        ORDER BY createdAt DESC";
                        $queryAllFoundedRecords = "SELECT * FROM post 
                        WHERE createdAt LIKE '%$searchFromUrl%' OR title LIKE '%$searchFromUrl%' OR category LIKE '%$searchFromUrl%' OR author LIKE '%$searchFromUrl%' OR image LIKE '%$searchFromUrl%' or mainBody LIKE '%$searchFromUrl%' 
                        ORDER BY createdAt DESC";
                        $query = "SELECT * FROM post 
                                    WHERE createdAt LIKE '%$searchFromUrl%' OR title LIKE '%$searchFromUrl%' OR category LIKE '%$searchFromUrl%' OR author LIKE '%$searchFromUrl%' OR image LIKE '%$searchFromUrl%' or mainBody LIKE '%$searchFromUrl%' 
                                    ORDER BY createdAt DESC LIMIT $showPostFrom, 5";
                        echo "<h4>" . mysqli_num_rows(mysqli_query($conn, $queryAllFoundedRecords))." posts found.</h4>";
                    } else if (isset($_GET["category"])) {
                        $categoryFromUrl = $_GET["category"];
                        // echo $_GET["category"] . " - " . $categoryFromUrl;
                        $queryCount = "SELECT COUNT(*) FROM post WHERE category='$categoryFromUrl' ORDER BY createdAt DESC";
                        $query = "SELECT * FROM post WHERE category='$categoryFromUrl' ORDER BY createdAt DESC LIMIT $showPostFrom, 5";
                    } else {
                        $queryCount = "SELECT COUNT(*) FROM post ORDER BY createdAt DESC";
                        $query = "SELECT * FROM post ORDER BY createdAt DESC LIMIT $showPostFrom, 5";
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
                ?>
                <div class="blog-post">
                    <?php
                        if ($image) {
                    ?>
                        <div class="blog-banner">
                            <img class="img-responsive img-rounded" src="upload/<?php echo $image; ?>" alt="">
                        </div>
                    <?php 
                        }
                    ?>
                    <div>
                        <h2 class="blog-title"><?php  echo htmlentities($title); ?></h2>
                        <p class="blog-description">Category: <?php echo htmlentities($category); ?>&nbsp;<strong>&middot;</strong>&nbsp;Published on <?php echo htmlentities($createdAt); ?> 
                        <?php
                            global $conn;
                            $queryApprovedCommentsCount = "SELECT COUNT(*) FROM comment WHERE postId='$id' AND status='ON'";
                            $executeApprovedCommentsCount = mysqli_query($conn, $queryApprovedCommentsCount);
                            $resultApprovedCommentsCount = mysqli_fetch_array($executeApprovedCommentsCount);
                            $resultApprovedCommentsCount = array_shift($resultApprovedCommentsCount);
                            if ($resultApprovedCommentsCount>0) {
                        ?>
                        <span class="badge pull-right comment-count">
                            Comments: <?php echo $resultApprovedCommentsCount;?>
                        </span>
                        <?php
                            }
                        ?>
                        </p>
                        <p class="blog-main-body">
                            <?php 
                                if (strlen($mainBody)>200) {
                                    $mainBody = substr($mainBody, 0, 200)." ...";
                                }
                                echo $mainBody; 
                            ?>
                        </p>
                    </div>
                    <a class="btn btn-info btn-read-blog" href="readPost.php?id=<?php echo $id; ?>">Read &rsaquo;</a>
                </div>
                <?php
                    }
                ?>
                <!-- pagination begins -->
                <nav>
                    <ul class="pagination pull-left pagination-lg">
                    <!-- back button begins -->
                    <?php
                        if ($page>1) {
                            if (isset($_GET["search"])) {
                    ?>
                                <li><a href="index.php?page=<?php echo $page-1; ?>&search=<?php echo $_GET["search"]; ?>">&laquo;</a></li> 
                    <?php   
                            } else if (isset($_GET["category"])) {
                    ?>            
                                <li><a href="index.php?page=<?php echo $page-1; ?>&category=<?php echo urlencode($_GET["category"]); ?>">&laquo;</a></li> 
                    <?php
                            } else {
                    ?>
                                <li><a href="index.php?page=<?php echo $page-1; ?>">&laquo;</a></li>
                    <?php
                            }
                        }
                    ?>
                    <!-- back button ends -->
                    <?php
                        global $conn;
                        $executeCount = mysqli_query($conn, $queryCount);
                        $resultCount = mysqli_fetch_array($executeCount);
                        $resultCount = array_shift($resultCount);
                        $pageCount = ceil($resultCount/5);
                        for ($i=1;$i<=$pageCount;$i++) {
                    ?>
                        <li <?php echo $i==$page?'class="active"':''; ?>>
                            <?php
                                if (isset($_GET["search"])) {
                            ?>
                                <a href="index.php?page=<?php echo $i; ?>&search=<?php echo $_GET["search"]; ?>"><?php echo $i; ?></a> 
                            <?php
                                } else if (isset($_GET["category"])) {
                            ?>
                                <a href="index.php?page=<?php echo $i; ?>&category=<?php echo urlencode($_GET["category"]); ?>"><?php echo $i; ?></a>
                            <?php        
                                } else {
                            ?>
                                <a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php
                                }
                            ?>
                        </li>
                    <?php
                        }
                    ?>
                    <!-- forward button begins -->
                    <?php
                        if ($page<$pageCount) {
                            if (isset($_GET["search"])) {
                    ?>
                                <li><a href="index.php?page=<?php echo $page+1; ?>&search=<?php echo $_GET["search"]; ?>">&raquo;</a></li> 
                    <?php
                            } else if (isset($_GET["category"])) {
                    ?>
                                <li><a href="index.php?page=<?php echo $page+1; ?>&category=<?php echo urlencode($_GET["category"]); ?>">&raquo;</a></li>
                    <?php            
                            } else {
                    ?>
                                <li><a href="index.php?page=<?php echo $page+1; ?>">&raquo;</a></li>
                    <?php
                            }
                        }
                    ?>
                    <!-- forward button ends -->
                    </ul>
                </nav>
                <!-- pagination ends -->
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