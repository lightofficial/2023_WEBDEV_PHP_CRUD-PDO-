<?php
    session_start();
    require_once ("config/db.php");

    if (isset($_POST['update']))
    {
        $id = $_POST['id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $position = $_POST['position'];
        $img = $_FILES['img'];

        $img2 = $_POST['img2']; // รูปสำรองกรณีที่ไม่ต้องการอัพเดทรูปภาพ
        $upload = $_FILES['img']['name'];

        if ($upload != '') //ถ้าหาก อัพโหลด ไม่ใช่ ค่าว่าง (มีรูปภาพอัพโหลดขึ้นมาจะเปลี่ยนใหม่)
        {
            $allow = array('jpg' , 'jpeg' , 'png'); //อนุญาติให้เก็บข้อมูลเฉพาะ ตามนี้
            $extension = explode("." ,$img['name']); //แยกชื่อไฟล์ออกจากนามสกุลไฟล์
            $fileActExt = strtolower(end($extension)); //แปลงนามสกุลไฟล์ให้เป็น พิมพ์เล็ก
            $fileNew = $firstname . $lastname . $position . rand() . "." . $fileActExt; // สั่งให้ random ตัวเลข
            $filePath = 'uploads/'.$fileNew; //อัพโหลดไปที่โฟลเดอร์นี้

            if (in_array($fileActExt , $allow)) //เช็คว่านามสกุลตรงกันมั้ย
            {
                if($img['size'] > 0 && $img['error'] == 0)
                {
                    move_uploaded_file($img['tmp_name'] , $filePath);
                }
            }
            else
            {
                $fileNew = $img2;
            }

            $sql = $conn->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname , position = :position , img = :img WHERE id = :id");
            $sql->bindParam(':id', $id);
            $sql->bindParam(':firstname', $firstname);
            $sql->bindParam(':lastname', $lastname);
            $sql->bindParam(':position', $position);
            $sql->bindParam(':img', $fileNew);
            $sql->execute();
            
            if($sql) // ถ้า execute แล้วให้ทำอันนี้ต่อ
                {
                    $_SESSION['success'] = "Data has been updated succesfully";
                    header("location: index.php");
                }
            else
                {
                    $_SESSION['error'] = "Data has not been updated succesfully";
                    header("location: index.php");
                }
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
    
    <div class="container mt-5">
        <h1>Edit data</h1>
        <hr>
            <form action="edit.php" method="post" enctype="multipart/form-data">
            <?php
                if(isset($_GET['id'])) 
                {
                    $id = $_GET['id'];
                    $stmt = $conn->query("SELECT * FROM users WHERE id = $id");
                    $stmt->execute();
                    $data = $stmt->fetch();

                    
                }

            ?>
            <div class="mb-3">
            <input type="text" readonly value="<?= $data['id']; ?>" required class="form-control" name="id">
                <label for="firstname" class="col-form-label">First Name :</label>
                <input type="text" value="<?= $data['firstname']; ?>" required class="form-control" name="firstname"> 
                <input type="hidden" value="<?= $data['img']; ?>" required class="form-control" name="img2">
            </div>

            <div class="mb-3">
                <label for="lastname" class="col-form-label">Last Name :</label>
                <input type="text" value="<?= $data['lastname']; ?>" required class="form-control" name="lastname">
            </div>

            <div class="mb-3">
                <label for="position" class="col-form-label">Position :</label>
                <input type="text" value="<?= $data['position']; ?>" required class="form-control" name="position">
            </div>

            <div class="mb-3">
                <label for="img" class="col-form-label">Image :</label>
                <input type="file" class="form-control" id="imgInput" name="img">
                <img class="mt-5 container text-center" width="550px" src="uploads/<?= $data['img'];?> " id="previewImg" alt="">
            </div>

                <div class="container overflow-hidden text-center ">
                    <a class="btn btn-secondary" href="index.php">Go Back</a>
                    <button type="submit" name="update" class="btn btn-success">Update</button>
                </div>
            </form>
        
        

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
