<?php
	global $wpdb;

  if($_POST["keyword_activate"]) {
      $data = array("status" => "1");
      $where = array("name" => $_POST["keyword_activate"]);
      $wpdb->update(wp_utilities::$table, $data, $where);
      
      echo "<meta http-equiv='refresh' content='0'>";
  }

  if($_POST["keyword_deactivate"]) {
      $data = array("status" => "0");
      $where = array("name" => $_POST["keyword_deactivate"]);
      $wpdb->update(wp_utilities::$table, $data, $where);
      
      echo "<meta http-equiv='refresh' content='0'>";
  }

  $functions = $wpdb->get_results("SELECT *  FROM " . wp_utilities::$table);

  $bereiche = [
    ["Allgemein", "Allgemein"],
    ["Admin Menü", "Menü"],
    ["Top Menü", "Top"]
  ];

?>

<h1>Utilities</h1>
<hr>
<form action="#" method="post">

<?php
  foreach ($bereiche as $bereich) {
    echo '
      <h2>'.$bereich[0].'</h2>
        <table class="bknd_tabell">
          <tbody>';

    foreach ($functions as $function) {
      if ($function->bereich == $bereich[1]) {
        echo '
          <tr>
            <td><button class="status_button" id="post-query-submit" class="button" type="submit" title="'.($function->status  == 1 ? "Deaktivieren" : "Aktivieren").'" name="keyword_'.($function->status  == 1 ? "deactivate" : "activate").'" value="'.$function->name.'">'.($function->status  == 1 ? "&#128994" : "&#128308").'</button></td>
            <td>'. $function->name .'</td>
            <td>'. $function->beschreibung .'</td>
          </tr>';
      }
    }

    echo '
        </tbody>
      </table>';
  }
?>  

</form>
<hr>
<p>Copyright by <a href="https://stillermedia.de">stiller media</a> &copy; 2024 | <?php echo "Version " . wp_utilities::$version; ?></p>