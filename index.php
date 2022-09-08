<?php
session_start();
$db = new SQLite3('test.db');

$update = false;
$id = $name = $sal = "";
if (isset($_REQUEST['edit'])) {
    $id = $_REQUEST['edit'];
    $update = true;
    $record = $db->query("SELECT * FROM EMP WHERE EMPNO = $id");
    $results = $record->fetchArray(SQLITE3_ASSOC);

    if ($results == false) {
        $_SESSION['msg'] = "Employee do not exist.";
        header("location:index.php");
    }

    $id = $results['EMPNO'];
    $name = $results['EMPNAME'];
    $sal = $results['SAL'];
}
if (isset($_REQUEST['save'])) {
    $id = $_REQUEST['id'];
    $name = $_REQUEST['name'];
    $sal = $_REQUEST['salary'];
    $db->exec("INSERT INTO `emp` (`EMPNO`, `EMPNAME`, `SAL`) VALUES ('$id', '$name', '$sal')");
    $_SESSION['msg'] = "Employee Saved";
    header("location:index.php");
}
if (isset($_REQUEST['update'])) {
    $id = $_REQUEST['id'];
    $name = $_REQUEST['name'];
    $sal = $_REQUEST['salary'];

    $db->exec("UPDATE emp SET EMPNAME = '$name', SAL = $sal WHERE EMPNO = $id");
    $_SESSION['msg'] = "Employee Data Updated.";
    header("location:index.php");
}
if (isset($_REQUEST['del'])) {
    $id = $_REQUEST['del'];
    $db->exec("DELETE FROM emp WHERE EMPNO = $id");
    $_SESSION['msg'] = "Employee Data is deleted";
    header("location:index.php");
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha512-Dop/vW3iOtayerlYAqCgkVr2aTr2ErwwTYOvRFUpzl2VhCMJyjQF0Q9TjUXIo6JhuM/3i0vVEt2e/7QQmnHQqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <script type="text/javascript">
        function clear() {
            document.getElementsByTagName('id').clear();
            document.getElementsByTagName('name').clear();
            document.getElementsByTagName('salary').clear();
        }

        function back() {
            window.location.href = "https://www.google.com";
        }
    </script>
</head>

<body>
    <main class="container-fixed">
        <div class="row">
            <div class="col-sm-10 col-sm-push-1">

                <?php

                if (isset($_SESSION['msg'])) {
                    echo "<div class='msg'>";
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                    echo "</div>";
                }
                $query = $db->query("SELECT * FROM emp");
                ?>
                <table class="table table-striped table-hover table-condensed table-responsive">
                    <thead>
                        <tr>
                            <th style="width: 20px;text-align: center;">ID</th>
                            <th>Name</th>
                            <th>Salary</th>
                            <th style="width: 100px;text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
                            echo "<tr>";
                            echo "<td style='width: 20px;text-align: center;''>" . $row['EMPNO'] . "</td>";
                            echo "<td>" . $row['EMPNAME'] . "</td>";
                            echo "<td>" . $row['SAL'] . "</td>";
                            echo "<td style='width: 100px;text-align: center;'>";
                            echo "<a class='btn btn-warning btn-sm' href=index.php?edit=" . $row['EMPNO'] . "><span class='glyphicon glyphicon-pencil'></span><span class='sr-only'>Edit</span></a> <a class='btn btn-danger btn-sm' href=index.php?del=" . $row['EMPNO'] . "><span class='glyphicon glyphicon-trash'></span><span class='sr-only'>Delete</span></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <hr>

                <form action="#" method="POST" class="form">
                    <div class="form-group">
                        <label>Employee No.</label>
                        <input class="form-control" type="text" name="id" value="<?php echo $id; ?>" <?php echo isset($_REQUEST['edit']) ? 'disabled' : '' ?>>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" type="text" name="name" value="<?php echo $name; ?>">
                    </div>
                    <div class="form-group">
                        <label>Salary</label>
                        <input class="form-control" type="text" name="salary" value="<?php echo $sal; ?>">
                    </div>
                    <div class="form-group">
                        <?php if ($update == true) { ?>
                            <button class="btn btn-danger" type="submit" name="update" onclick="back()"> Update </button>
                        <?php } elseif ($update == false) { ?>
                            <button class="btn btn-primary" type="submit" name="save" onclick="clear()"> Add new </button>
                        <?php } ?>
                    </div>
                </form>

            </div>
        </div>

    </main>
</body>

</html>
