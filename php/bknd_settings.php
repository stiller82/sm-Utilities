<?php
	global $wpdb;

  if($_POST["keyword_activate"]) {
      $data = array("status" => "1");
      $where = array("name" => $_POST["keyword_activate"]);
      $wpdb->update(sm_utilities::$table, $data, $where);
      
      echo "<meta http-equiv='refresh' content='0'>";
  }

  if($_POST["keyword_deactivate"]) {
      $data = array("status" => "0");
      $where = array("name" => $_POST["keyword_deactivate"]);
      $wpdb->update(sm_utilities::$table, $data, $where);
      
      echo "<meta http-equiv='refresh' content='0'>";
  }

  $functions = $wpdb->get_results("SELECT *  FROM " . sm_utilities::$table);
?>

<h1>Utilities</h1>
<form action="#" method="post">

  <h2>Allgemein</h2>
  <table class="bknd_tabell">
    <tbody>
    <?php
      foreach ($functions as $function) {
        if ($function->bereich == "Allgemein") {
          echo '
            <tr>
              <td><button class="status_button" id="post-query-submit" class="button" type="submit" title="'.($function->status  == 1 ? "Deaktivieren" : "Aktivieren").'" name="keyword_'.($function->status  == 1 ? "deactivate" : "activate").'" value="'.$function->name.'">'.($function->status  == 1 ? "&#128994" : "&#128308").'</button></td>
              <td>'. $function->name .'</td>
              <td>'. $function->beschreibung .'</td>
            </tr>';
        }
      }
    ?>
    </tbody>
  </table>

  <h2>Admin Menü</h2>
  <table class="bknd_tabell">
    <tbody>
      <?php
        foreach ($functions as $function) {
          if ($function->bereich == "Menü") {
            echo '
              <tr>
              <td><button class="status_button" id="post-query-submit" class="button" type="submit" title="'.($function->status  == 1 ? "Deaktivieren" : "Aktivieren").'" name="keyword_'.($function->status  == 1 ? "deactivate" : "activate").'" value="'.$function->name.'">'.($function->status  == 1 ? "&#128994" : "&#128308").'</button></td>
                <td>'. $function->name .'</td>
                <td>'. $function->beschreibung .'</td>
              </tr>';
          }
        }
      ?>
    </tbody>
  </table>

  <h2>Top Menü</h2>
  <table class="bknd_tabell">
    <tbody>
      <?php
        foreach ($functions as $function) {
          if ($function->bereich == "Top") {
            echo '
              <tr>
              <td><button class="status_button" id="post-query-submit" class="button" type="submit" title="'.($function->status  == 1 ? "Deaktivieren" : "Aktivieren").'" name="keyword_'.($function->status  == 1 ? "deactivate" : "activate").'" value="'.$function->name.'">'.($function->status  == 1 ? "&#128994" : "&#128308").'</button></td>
                <td>'. $function->name .'</td>
                <td>'. $function->beschreibung .'</td>
              </tr>';
          }
        }
      ?>
    </tbody>
  </table>
</form>
<hr>
<p>Copyright by stiller media &copy; 2024 | <?php echo "Version " . sm_utilities::$version; ?></p>