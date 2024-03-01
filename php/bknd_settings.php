<?php
	global $wpdb;

  if (isset($_POST["submit_settings"])) {

    foreach (sm_utilities::$initDB as $function) {
      $wert = ($_POST[$function["name"]] === "on" ? true : false);

      $data = array("status" => $wert);
      $where = array("name" => $function["name"]);
      $wpdb->update(sm_utilities::$table, $data, $where);
    }

    echo "<meta http-equiv='refresh' content='0'>";
  }

  $functions = $wpdb->get_results("SELECT *  FROM " . sm_utilities::$table);
?>

<h1>Utilities</h1>
<form action="#" method="post">

  <h2>Allgemein</h2>
  <table class="widefat">
    <tbody>
    <?php
      foreach ($functions as $function) {
        if ($function->bereich == "Allgemein") {
          echo '
            <tr>
              <td><input type="checkbox" name="' . $function->name . '"' . ($function->status == 1 ? " checked" : "") . '></td>
              <td>'. $function->name .'</td>
              <td>'. $function->beschreibung .'</td>
            </tr>';
        }
      }
    ?>
    </tbody>
  </table>

  <h2>Admin Menü</h2>
  <table class="widefat">
    <tbody>
      <?php
        foreach ($functions as $function) {
          if ($function->bereich == "Menü") {
            echo '
              <tr>
                <td><input type="checkbox" name="' . $function->name . '"' . ($function->status == 1 ? " checked" : "") . '></td>
                <td>'. $function->name .'</td>
                <td>'. $function->beschreibung .'</td>
              </tr>';
          }
        }
      ?>
    </tbody>
  </table>

  <h2>Top Menü</h2>
  <table class="widefat">
    <tbody>
      <?php
        foreach ($functions as $function) {
          if ($function->bereich == "Top") {
            echo '
              <tr>
                <td><input type="checkbox" name="' . $function->name . '"' . ($function->status == 1 ? " checked" : "") . '></td>
                <td>'. $function->name .'</td>
                <td>'. $function->beschreibung .'</td>
              </tr>';
          }
        }
      ?>
    </tbody>
  </table>
  <hr>
  <div>
    <input type="submit" class="button" name="submit_settings" value="Speichern">
  </div>
</form>
<hr>
<p>Copyright by stiller media &copy; 2024</p>