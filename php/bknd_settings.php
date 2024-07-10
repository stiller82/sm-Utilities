<?php
	global $wpdb;
  $sm_utilities_table = sm_utilities::$table;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['sm_utilities_nonce_field']) || !wp_verify_nonce($_POST['sm_utilities_nonce_field'], 'sm_utilities_nonce')) {
        die('Sicherheitsprüfung fehlgeschlagen.');
    }

    $key_activate = sanitize_text_field($_POST["key_activate"]);
    $key_deactivate = sanitize_text_field($_POST["key_deactivate"]);
  }

  if($key_activate) {
      $data = array("status" => "1");
      $where = array("name" => $key_activate);

      $cache_key = 'sm_utilities_' . $key_activate;
      $cache_group = 'sm_utilities_group';

      $wpdb->update($sm_utilities_table, $data, $where);

      wp_cache_delete($cache_key, $cache_group);
      
      echo "<meta http-equiv='refresh' content='0'>";
  }

  if($key_deactivate) {
      $data = array("status" => "0");
      $where = array("name" => $key_deactivate);

      $cache_key = 'sm_utilities_' . $key_activate;
      $cache_group = 'sm_utilities_group';

      $wpdb->update($sm_utilities_table, $data, $where);

      wp_cache_delete($cache_key, $cache_group);
      
      echo "<meta http-equiv='refresh' content='0'>";
  }

  $prepared_query = $wpdb->prepare("SELECT * FROM $sm_utilities_table");
  $functions = $wpdb->get_results($prepared_query);

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
  wp_nonce_field('sm_utilities_nonce', 'sm_utilities_nonce_field');

  foreach ($bereiche as $bereich) {
    echo '
      <h2>'.esc_html($bereich[0]).'</h2>
        <table class="bknd_tabell">
          <tbody>';

    foreach ($functions as $function) {
      if ($function->bereich == $bereich[1]) {
        echo '
          <tr>
            <td><button class="status_button" id="post-query-submit" class="button" type="submit" title="'.($function->status  == 1 ? "Deaktivieren" : "Aktivieren").'" name="key_'.($function->status  == 1 ? "deactivate" : "activate").'" value="'.esc_html($function->name).'">'.($function->status  == 1 ? "&#128994" : "&#128308").'</button></td>
            <td>'. esc_html($function->name) .'</td>
            <td>'. esc_html($function->beschreibung) .'</td>
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
<p>Copyright by <a href="https://stillermedia.de">stiller media</a> &copy; 2024 | <?php echo "Version " . esc_html(sm_utilities::$version); ?></p>