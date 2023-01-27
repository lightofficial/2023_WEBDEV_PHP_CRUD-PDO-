<?php

    session_start();
    require_once "config/db.php";

    if(isset($_POST['submit']))
    {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $position = $_POST['position'];
        $img = $_FILES['img'];

        // filename.jpg อัพโหลด
        $allow = array('jpg' , 'jpeg' , 'png'); //อนุญาติให้เก็บข้อมูลเฉพาะ ตามนี้
        $extension = explode("." ,$img['name']); //แยกชื่อไฟล์ออกจากนามสกุลไฟล์
        $fileActExt = strtolower(end($extension)); //แปลงนามสกุลไฟล์ให้เป็น พิมพ์เล็ก
        $fileNew = $firstname . $lastname . $position . rand() . "." . $fileActExt; // สั่งให้ random ตัวเลข
        $filePath = 'uploads/'.$fileNew; //อัพโหลดไปที่โฟลเดอร์นี้

        if (in_array($fileActExt , $allow)) //เช็คว่านามสกุลตรงกันมั้ย
        {
            if($img['size'] > 0 && $img['error'] == 0)
            {
                if (move_uploaded_file($img['tmp_name'] , $filePath))
                {
                    $sql = $conn->prepare("INSERT INTO users(firstname,lastname,position,img) VALUES(:firstname,:lastname,:position,:img)"); //เอาค่าไปฝากใน temp ก่อน แล้ว Insert
                    $sql->bindParam(':firstname',$firstname); //เรียกใช้ method bindParam แทนที่ค่า แล้วค่อยส่งค่าจริงเข้าสุู่ฐานข้อมูล
                    $sql->bindParam(':lastname',$lastname); 
                    $sql->bindParam(':position',$position);
                    $sql->bindParam(':img',$fileNew);
                    $sql->execute();
                    
                    if($sql) // ถ้า execute แล้วให้ทำอันนี้ต่อ
                    {
                        $_SESSION['success'] = "Data has been inserted succesfully";
                        header("location: index.php");
                    }
                    else
                    {
                        $_SESSION['error'] = "Data has not been inserted succesfully";
                        header("location: index.php");
                    }
                }
            }
        }
    }
?>