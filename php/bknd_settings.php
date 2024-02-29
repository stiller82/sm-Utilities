<?php
  global $wpdb;

  if (isset($_POST["submit_settings"])) {

    foreach ($GLOBALS['init_db'] as $function) {
      $wert = ($_POST[$function["name"]] === "on" ? true : false);

      $data = array("status" => $wert);
      $where = array("name" => $function["name"]);
      $wpdb->update($GLOBALS['table'], $data, $where);
    }

    echo "<meta http-equiv='refresh' content='0'>";
  }

  $functions = $wpdb->get_results("SELECT *  FROM " . $GLOBALS['table']);

  echo '<h1>Utilities</h1>';
  echo '<form action="#" method="post">';

  echo '<h2>Allgemein</h2>';
  echo '<table class="widefat">';
  echo '<tbody>';
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
  echo '</tbody>';
  echo '</table>';

  echo '<h2>Admin Menü</h2>';
  echo '<table class="widefat">';
  echo '<tbody>';
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
  echo '</tbody>';
  echo '</table>';

  echo '<h2>Top Menü</h2>';
  echo '<table class="widefat">';
  echo '<tbody>';
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
  echo '</tbody>';
  echo '</table>';

  echo '<hr>';
  echo '<div">';
  echo '<input type="submit" class="button" name="submit_settings" value="Speichern" />';
  echo '</div">';
  echo '</form>';
  echo '<hr>';
  echo '<p>Copyright by stiller media &copy; 2024</p>';
?>