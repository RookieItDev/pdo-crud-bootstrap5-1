<?php
 session_start();

 require_once "config/db.php";

 if (isset($_GET['delete'])) {
     $delete_id = $_GET['delete'];
     $deletestmt = $conn->query("DELETE FROM users WHERE id = $delete_id");
     $deletestmt->execute();

     if ($deletestmt) {
         echo "<script>alert('Data has been deleted successfully');</script>";
         $_SESSION['success'] = "Data has been deleted succesfully";
         header("refresh:1; url=index.php");
     }
     
 }
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();
// Buffer the following html with PHP so we can store it to a variable later
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  <div class="container-fluid">
      <p>My Report Demo</p>
      <?php 
      for($i=0;$i<2;$i++)
      {
      ?>
  <table class="table table-bordered print" style="border: 1;">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Position</th>
                    <th scope="col">Img</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody style="border: 1;">
                <?php 
                    $stmt = $conn->query("SELECT * FROM users");
                    $stmt->execute();
                    $users = $stmt->fetchAll();

                    if (!$users) {
                        echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                    } else {
                    foreach($users as $user)  {  
                ?>
                    <tr style="border: 1;">
                        <th scope="row"><?php echo $user['id']; ?></th>
                        <td><?php echo $user['firstname']; ?></td>
                        <td><?php echo $user['lastname']; ?></td>
                        <td><?php echo $user['position']; ?></td>
                        <td width="250px"><img class="rounded" width="100%" src="uploads/<?php echo $user['img']; ?>" alt=""></td>
                        <td>
                            <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Edit</a>
                            <a onclick="return confirm('Are you sure you want to delete?');" href="?delete=<?php echo $user['id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php }  } ?>
            </tbody>
            </table>
            <p style="page-break-after: always;"></p>
            <?php } ?>
  </div>
</body>
</html>
<?php
// Now collect the output buffer into a variable
//$html->Ln();
$html = ob_get_contents();
ob_end_clean();

// send the captured HTML from the output buffer to the mPDF class for processing

$mpdf->WriteHTML($html);

$mpdf->Output();
?>