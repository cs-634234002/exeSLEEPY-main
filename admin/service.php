<?php
require 'config.php';
if (isset($_POST['submit'])) {
  $service = $_POST["service"];
  $status = "เปิดให้บริการ";
  $description = $_POST["description"];
  if ($_FILES["image"]["error"] === 4) {
    echo "<script>alert('ไม่พบรูปภาพ')</script>";
  } else {
    $fileName = $_FILES["image"]["name"];
    $tmpName = $_FILES["image"]["tmp_name"];

    $validImageExtension = ['jpg', 'jpeg', 'png'];
    $imageExtension = explode('.', $fileName);
    $imageExtension = strtolower(end($imageExtension));
    if (!in_array($imageExtension, $validImageExtension)) {
      echo "<script>alert('นามสกุลรูปภาพไม่ถูกต้อง')</script>";
    }
    // else if($fileSize>1000000){
    //   echo "<script>alert('ขนาดรูปภาพใหญ่เกินไป')</script>";
    // }
    else {
      $newImageName = uniqid();
      $newImageName .= '.' . $imageExtension;

      move_uploaded_file($tmpName, 'img/' . $newImageName);
      $sql = "INSERT INTO tb_service (service,status,image,description) VALUES ('$service','$status','$newImageName','$description')";
      $query = mysqli_query($conn, $sql);
      echo "<script>alert('บันทึกข้อมูลสำเร็จ')</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/dashboard.css">
  <title>Document</title>
</head>

<body>
  <style>
    @import url(http://fonts.googleapis.com/css?family=Kanit);

    body {
      font-family: 'Kanit', sans-serif;
    }
    .center-align {
    text-align: center;
  }
  </style>
  <?php include 'include/header.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <?php include 'include/sidebarMenu.php'; ?>
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">ข้อมูลผู้บริการ</h1>
          <div class="btn-toolbar mb-2 mb-md-0">
            <!-- เพิ่มข้อมูล -->
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#add_data">เพิ่มข้อมูลบริการ</button>
            <div class="modal fade" id="add_data" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">



                <form class="modal-content" method="post" action="" enctype="multipart/form-data">
                  <div class="modal-header">
                    <h5 class="modal-title">เพิ่มข้อมูลบริการ</h5>
                  </div>
                  <div class="modal-body">


                    <div class="mb-3">
                      <label for="service" class="form-label">ชื่อบริการ</label>
                      <input type="text" class="form-control" id="service" name="service" required>
                    </div>

                    <!-- <div class="mb-3">
                        <label class="form-label">จำนวน</label>
                        <select class="form-select" name="" id="quantity-select">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          <option value="10">10</option>
                        </select>
                      </div>

                      <div class="mb-3">
                      <label class="form-label">ราคา</label>
                      <input type="text" class="form-control" name="price" id="price-input" required/>
                    </div>

                    <div class="mb-3">
                    <label class="form-label">วันที่ฝาก</label>
                    <input type="date" class="form-control" name="user_date" required>
                    </div>

                    <div class="mb-3">
                    <label class="form-label">วันที่รับกลับ</label>
                    <input type="date" class="form-control" name="user_date" required>
                    </div>

                      <div class="mb-3">
                        <label class="form-label">เวลา</label>
                        <select class="form-select" name="user_time" onchange="checkTimeAvailability(this)">
                          <option value="9.00-10.00">9.00-11.00</option>
                          <option value="10.00-11.00">10.00-11.00</option>
                          <option value="11.00-12.00">11.00-12.00</option>
                          <option value="13.00-14.00">13.00-14.00</option>
                          <option value="14.00-15.00">14.00-15.00</option>
                          <option value="15.00-16.00">15.00-16.00</option>
                          <option value="16.00-17.00">16.00-17.00</option>
                        </select>
                      </div> -->

                    <!-- <div class="mb-3">
                      <label for="status" class="form-label">สถานะบริการ</label>
                      <input type="text" class="form-control" id="status" name="status" disabled placeholder="เปิดให้บริการ">
                      </div> -->

                    <div class="mb-3">
                      <label class="form-label">สถานะบริการ</label>
                      <select class="form-select" name="status" onchange="checkTimeAvailability(this)">
                        <option value="เปิด">เปิด</option>
                        <option value="ปิด">ปิด</option>
                        <option value="เต็ม">เต็ม</option>
                      </select>
                    </div>


                    <div class="mb-3">
                      <label class="form-label">รูปภาพ</label>
                      <input type="file" class="form-control" name="image" id="image" accept=".jpg, .jpeg, .png" value="" />
                    </div>

                    <div class="mb-3">
                      <label for="description" class="form-label">รายละเอียดบริการ</label>
                      <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                  </div>
                  
                  <!-- บันทึกข้อมูลเข้าไปในตาราง -->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" name="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- table data -->
        <table class="table table-bordered table-hover">
          <thead>
            <tr class="table-info">
              <th scope="col"style="text-align: center;">ลำดับ</th>
              <th scope="col"style="text-align: center;">ชื่อบริการ</th>
              <th scope="col"style="text-align: center;" width="400px" heigth="200px">รูปภาพ</th>
              <th scope="col"style="text-align: center;">รายละเอียด</th>
              <th scope="col"style="text-align: center;">สถานะบริการ</th>
              <th scope="col"style="text-align: center;">ลบข้อมูล</th>
              <th scope="col"style="text-align: center;">แก้ไขข้อมูล</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include 'config.php';
            $pic = mysqli_query($conn, "SELECT * FROM `tb_service`");
            while ($row = mysqli_fetch_array($pic)) {
              echo "
                              <tr>
                                  <td class='center-align'>$row[id]</td>
                                  <td class='center-align'>$row[service]</td>
                                  <td class='center-align'><img src='./img/$row[image]' width='200px' heigth='70px' ></td>
                                  <td class='center-align'>$row[description]</td>
                                  <td class='center-align'>$row[status]</td>
                                  <td class='center-align'><a href='service_delete.php? id= $row[id]' class='btn btn-danger' type='button'>ลบข้อมูล</a></td>
                                  <td class='center-align'><a href='update_delete.php? id= $row[id]' class='btn btn-success' type='button'>แก้ไขข้อมูล</a></td>
                              </tr>
                                    ";
            }
            ?>
          </tbody>
        </table>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="assets/dashboard.js"></script>
        
        <script>
          // ค้นหา element ของ select และ input
          const quantitySelect = document.getElementById('quantity-select');
          const priceInput = document.getElementById('price-input');

          // เพิ่มเหตุการณ์เมื่อมีการเปลี่ยนแปลงใน select
          quantitySelect.addEventListener('change', function() {
            const selectedQuantity = parseInt(quantitySelect.value); // ค่าจำนวนที่เลือก
            const additionalPrice = 300; // ราคาเพิ่มเติมที่ต้องการให้เพิ่ม

            const totalPrice = selectedQuantity * additionalPrice; // คำนวณราคารวม

            priceInput.value = totalPrice; // กำหนดค่าราคาให้กับ input
          });
        </script>

        <script>
          function checkTimeAvailability(selectElement) {
            const selectedTime = selectElement.value;
            const selectOptions = selectElement.getElementsByTagName('option');

            // ตรวจสอบตัวเลือกทั้งหมด
            for (let i = 0; i < selectOptions.length; i++) {
              const option = selectOptions[i];
              if (option.value !== selectedTime) {
                option.disabled = false; // เปิดใช้งานตัวเลือกที่ไม่ซ้ำกัน
              } else {
                option.disabled = true; // ปิดใช้งานตัวเลือกที่ซ้ำกัน
              }
            }
          }
        </script>

</body>

</html>