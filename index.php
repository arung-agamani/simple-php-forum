<?php
session_start();
include("./lib/dbConnect.php");
include("./lib/post.php");
$conn = dbConnect();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
            echo bin2hex(random_bytes(16));
            ?></title>
    <link rel="stylesheet" href="./static/css/bootstrap.min.css">
    <link rel="stylesheet" href="./static/styles.css">
    <link rel="stylesheet" href="./static/utility.css">
    <script src="./static/js/jquery.min.js"></script>
    <script src="./static/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./static/css/quill.snow.css">
    <script src="./static/js/quill.js"></script>

    <script>
        var quillArray = [];

        function initializeReply(elementId) {
            var quill = new Quill('#editor-' + elementId, {
                theme: 'snow'
            });
            quillArray.push({
                postId: elementId,
                quillObj: quill
            });
            document.getElementById("reply-" + elementId).style.display = "none";
            document.getElementById("submit-" + elementId).style.display = "inline-block";
        }

        function submitReply(elementId, username, parentId) {
            var quill = quillArray.find(x => x.postId == elementId).quillObj.root;
            console.log(btoa(quill.innerHTML));
            var formData = new FormData();
            formData.append("username", username);
            formData.append("message", btoa(quill.innerHTML));
            formData.append("id_parent", parentId);
            fetch('./api/post.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json()).then((data) => {
                if (data.status === 200) {
                    alert("Post success!\n" + data.message);
                    window.location.reload();
                } else {
                    alert("Post failed!\n" + data.message);
                }
            })
        }

        function submitNewPost(elementId, username) {
            var quill = quillArray.find(x => x.postId == elementId).quillObj.root;
            console.log(btoa(quill.innerHTML));
            var formData = new FormData();
            formData.append("username", username);
            formData.append("message", btoa(quill.innerHTML));
            fetch('./api/post.php', {
                method: 'POST',
                body: formData
            }).then(res => res.json()).then((data) => {
                if (data.status === 200) {
                    alert("Post success!\n" + data.message);
                    window.location.reload();
                } else {
                    alert("Post failed!\n" + data.message);
                }
            })
        }

        function getReplies(id_parent) {
            fetch('./api/post.php?id_parent=' + id_parent).then(res => res.json())
                .then(data => {
                    // console.log(data);
                    const replyData = JSON.parse(data.message);
                    var targetPost = document.getElementById("post-reply-container-" + id_parent);
                    console.log(replyData);
                    for (var reply of replyData) {
                        var stringToPost = `<div class="post">
                        <div class="post-header">
                            <!-- <p class="post-title">Test Title</p> -->
                            <p class="post-user">Reply by ${reply[2]}</p>
                        </div>
                        <div class="post-content">
                            ${atob(reply[3])}
                        </div>
                    </div>`;
                        var el = new DOMParser().parseFromString(stringToPost, "text/html");
                        targetPost.appendChild(el.getRootNode().body.firstChild);
                    }
                })
        }
    </script>
    <script>

    </script>
</head>

<body>
    <header class="container header">
        <h1>Awoo Forums</h1>
        <p>Place for people gather and talk for a better public discourse.</p>
    </header>
    <main>
        <div class="container main">
            <h1><?php
                if ($_SESSION["username"]) {
                    echo "Logged as " . $_SESSION["username"];
                } else {
                    echo "You're not logged in.";
                }
                ?></h1>
            <?php
            $main_posts = get_posts($conn);
            if ($main_posts->num_rows > 0) {
                while ($post = $main_posts->fetch_assoc()) { ?>
                    <div class="post">
                        <div class="post-header">
                            <!-- <p class="post-title">Test Title</p> -->
                            <p class="post-user">Conversation by <?php echo $post["username"]; ?></p>
                        </div>
                        <div class="post-content">
                            <?php
                            echo base64_decode($post["message"]);
                            ?>
                        </div>
                        <p style="color:cornflowerblue; cursor:pointer;" onclick="getReplies('<?php echo $post["id"] ?>');">See Replies</p>
                        <div class="post-replies" id="post-reply-container-<?php echo $post["id"] ?>"></div>
                        <div class="post-reply">
                            <button id=<?php echo "reply-" . $post["id"] ?> onclick="<?php echo "initializeReply('" . $post["id"] . "');"; ?>">Reply</button>
                            <div id=<?php echo "editor-" . $post["id"] ?>></div>
                            <button style="display: none;" id=<?php echo "submit-" . $post["id"] ?> onclick="<?php echo "submitReply('" . $post["id"] . "', '" . $_SESSION["username"] . "','" . $post["id"] . "');"; ?>">Post Reply</button>
                        </div>
                    </div>
            <?php    }
            }
            ?>
            <!-- <div class="post">
                <div class="post-header">
                    <p class="post-title">Test Title</p>
                    <p class="post-user">by <?php echo "Arung Agamani"; ?></p>
                </div>
                <div class="post-content">
                    <?php
                    echo "<p><strong>This is an injected html</strong>. from PHP</p>";
                    ?>
                </div>
                <div class="post-reply">
                    <button id=<?php echo "reply-post1" ?> onclick=<?php echo "initializeReply('post1');"; ?>>Reply</button>
                    <div id=<?php echo "editor-post1" ?>></div>
                    <button style="display: none;" id=<?php echo "submit-post1" ?> onclick=<?php echo "submitReply(\"post1\")"; ?>>Post Reply</button>
                </div>
            </div> -->
            <div class="post">
                <div class="post-reply">
                    <button id=<?php echo "reply-new" ?> onclick=<?php echo "initializeReply('new');"; ?>>Create New Post</button>
                    <div id=<?php echo "editor-new" ?>></div>
                    <button style="display: none;" id=<?php echo "submit-new" ?> onclick="<?php echo "submitNewPost('new', '" . $_SESSION["username"] . "');"; ?>">Create Post</button>
                </div>
            </div>
        </div>
    </main>
    <footer class=" container-fluid footer">
        <p>This is footer section</p>
    </footer>
</body>

</html>