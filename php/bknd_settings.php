<?php
    global $wpdb;
    $table = $wpdb->prefix . "crndi_utilities";

    if(isset($_POST["submit_settings"])) {
      $save = $wpdb->get_results("SELECT *  FROM ".$table);

      foreach($save as $function) {
       $wert = ($_POST[$function->name] == "on" ? true : false);

       $data = array("status" => $wert);
       $where = array("name" => $function->name);
       $wpdb->update($table, $data, $where);
      }

      echo "<meta http-equiv='refresh' content='0'>";
    }
    
    $functions = $wpdb->get_results("SELECT *  FROM ".$table);
?>

<h1>Utilities</h1>

<form action="#" method="post">
    <h2>Allgemein</h2>

<?php
    foreach($functions as $function) {
      if($function->bereich == "Allgemein") {
        echo '<div>
            <input type="checkbox" name="'.$function->name.'"'.($function->status == 1 ? " checked" : "").'>
            <label for="'.$function->name.'">'.$function->name.'</label>
            </div>';
      }
    }
?>

  <h2>Admin Menü</h2>
<?php
  foreach($functions as $function) {
    if($function->bereich == "Menü") {
      echo '<div>
          <input type="checkbox" name="'.$function->name.'"'.($function->status == 1 ? " checked" : "").'>
          <label for="'.$function->name.'">'.$function->name.'</label>
          </div>';
    }
  }
?>

  <h2>Top Menü</h2>
<?php
  foreach($functions as $function) {
    if($function->bereich == "Top") {
      echo '<div>
          <input type="checkbox" name="'.$function->name.'"'.($function->status == 1 ? " checked" : "").'>
          <label for="'.$function->name.'">'.$function->name.'</label>
          </div>';
    }
  }
?>

  <hr>
  <div>
      <input type="submit" class="button" name="submit_settings" value="Speichern"/>
  </div>
</form>