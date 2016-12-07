<?php // add the admin options page

add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
          add_options_page('GCS Settings Page', 'GCS Settings', 'customize', 'gcs-plugin', 'plugin_options_page');
        }
add_action( 'after_switch_theme', 'check_gcs_table' );
add_action('init','delete_custom_files');

function delete_custom_files(){
  //search for google custom search file
  if (file_exists('/run_once.php'))
    {
        $script= file_get_contents('../wp-content/custom-files/google-custom-search.php');
        echo $script;
       // deletes file and folder
         unlink('/run_once.php');
     }
function check_gcs_table(){
    global $wpdb;
    $prefix= $wpdb->prefix;
    $gcs_table = $prefix . "gcs_address";
      if ($wpdb->get_var("SHOW TABLES LIKE '$gcs_table'")!=$gcs_table)
        {
          // Table doesn't exists, create one
          $charset_collate = $wpdb->get_charset_collate();
          $sql = "CREATE TABLE $gcs_table (
               id mediumint(9) NOT NULL AUTO_INCREMENT,
               address_code varchar(1000) NOT NULL,
               UNIQUE KEY id (id)
             ) $charset_collate;";
          require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
          dbDelta( $sql );
        }
      }
  function plugin_options_page() {
      ?>
  <div>
    <!-- creation of the form -->
    <h1>GCS Settings</h1>
      <form id="gcsForm" action="" name="gcsForm" method="POST">
        <p>In the last line of the code snippet and type in this attribute to the opening 	&lt;gcse:searchbox-only&gt; tag:    resultsUrl='http://????.uwosh.edu/search-results/'<br>
          For Further Instructions<a target="_blank" href="https://kb.uwosh.edu/internal/page.php?id=56354"> Click Here</a></p><br>
          Enter Google Custom Search Code Below:<br>
          <textarea type='text' name='custom_search_address'id="gcs_address" placeholder="Enter Code Here" rows="14" cols="80"></textarea> <br>
          <?php submit_button('Submit', 'primary','button' ) ?>
      </form>
    <br>


    <script type="text/javascript">
    (function($) {
      $(document).ready(function(){
        //clicking button will get varialble from the form box
          $('#button').click(function(event){
              event.preventDefault();
                //gets the routing to find the form.php file
              var path = '<?php echo get_stylesheet_directory_uri(); ?>';
              var gcs_code = $('#gcs_address').val();
                // uses ajax to pass variable to form.php page
                  jQuery.ajax({
                   url:path+"/includes/form.php",
                   type: 'POST',
                   data: {gcs_code:gcs_code},
                   success: function (data) {
                     console.log("got this: " + data);
                     alert(data)
                            },
                   error: function (){
                      alert('Failed to enter data');
                      console.log("failed");
                     }
                            });
                         });
                     });
         })(jQuery);
   </script>
<?php
}
?>
