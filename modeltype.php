<!-- db thing-->
<?php
include 'database.php';
$pdo = Database::connect();
$sql = 'SELECT DISTINCT model_type FROM pm_master ORDER BY model_type ASC'; ?>
<?php
if (isset($_POST['modelop'])) {
    $select1 = $_POST['modelop'];
    echo $select1;
}
?>


<form action="" method="post">
    <select name="modelop">
        <?php
        if (isset($_POST['modelop'])) {
            echo "<option value='" . $_POST['modelop'] . "'>" . $_POST['modelop'] . "</option>";

        } else {
            echo "<option value=''>Select Model Type</option>";
        }
        foreach ($pdo->query($sql) as $row) {
            echo "<option value='" . $row['model_type'] . "'>" . $row['model_type'] . "</option>";
        }
        ?>
    </select>
    <input type="submit" name="submit" value="Go"/>
</form>

<?php
Database::disconnect();
?>