<?php
    session_start();
    require_once ("config/db.php");

    if(isset($_GET['delete']))
    {
        $delete_id = $_GET['delete'];
        $deletestmt = $conn->query("DELETE FROM users WHERE id = $delete_id");
        $deletestmt->execute();

        if($deletestmt)
        {
            echo "<script>alert('Data has been deleted successfully');</script>";
            $_SESSION['success'] = 'Data has been deleted successfully';
            header("refresh:1; url=index.php");
        }
    }
    
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Croc System</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"> 
        <form action="insert.php" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="firstname" class="col-form-label">First Name :</label>
            <input type="text" required class="form-control" name="firstname">
          </div>

          <div class="mb-3">
            <label for="lastname" class="col-form-label">Last Name :</label>
            <input type="text" required class="form-control" name="lastname">
          </div>

          <div class="mb-3">
            <label for="position" class="col-form-label">Position :</label>
            <input type="text" required class="form-control" name="position">
          </div>

          <div class="mb-3">
            <label for="img" class="col-form-label">Image :</label>
            <input type="file" required class="form-control" id="imgInput" name="img">
            <img width="100%" id="previewImg" alt="">
          </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
      </div>
      
    </div>
  </div>
</div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>CRUD Boostrap</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target='#userModal'>Add Users</button>
            </div>
        </div>
        <hr>
        <?php if (isset($_SESSION['success']))  {?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['error']))  {?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php } ?>

        <!-- User Data -->
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Firstname</th>
                <th scope="col">Lastname</th>
                <th scope="col">Position</th>
                <th scope="col">Img</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                    $stmt = $conn->query("SELECT * FROM users"); // เลือกทั้งหมด จากตาราง users
                    $stmt->execute(); // เริ่มประมวลผล
                    $users = $stmt->fetchAll(); // Get all users

                    if (!$users)
                    {
                        echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                    }
                    else
                    {
                        foreach($users as $user) { // loop ข้อมูล จาก users สู่ user
                ?>
                <tr>
                    <th scope="row"><?php echo $user['id']; ?></th>
                    <td><?php echo $user['firstname']; ?></td>
                    <td><?php echo $user['lastname']; ?></td>
                    <td><?php echo $user['position']; ?></td>
                    <td width="150px" height="150px"><img width="100%" height="100%" src="uploads/<?= $user['img']; ?>" class="rounded" alt=""></td>
                    <td>
                        <a href="edit.php?id=<?= $user['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="?delete=<?= $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')">Delete</a>
                    </td>
                </tr>

                <?php }
                    }
                ?>

            </tbody>
</table>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script>
    let imgInput = document.getElementById('imgInput');
    let previewImg = document.getElementById('previewImg');

    imgInput.onchange = evt => {
        const [file] = imgInput.files;
        if(file)
        {
            previewImg.src = URL.createObjectURL(file);

        }
    }
    
</script>
</body>
</html>

