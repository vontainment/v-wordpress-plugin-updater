<?php
/*
 * Project: Update API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: thupdate.php
 * Description: WordPress Update API
 */
?>

<div class="content-box">
  <h2>Themes</h2>
  <div id="Themes_table">
    <?php echo $themesTableHtml; ?>
  </div>
  <div class="plupload section">
    <div id="upload-container">
      <h2>Upload Theme</h2>
      <form action="/thupdate" method="post" enctype="multipart/form-data" class="dropzone" id="upload_theme_form">
        <div class="fallback">
          <input name="theme_file[]" type="file" multiple />
        </div>
      </form>
      <button class="reload-btn" onclick="window.location = '/thupdate'; window.location.reload();">Reload Page</button>
    </div>

    <div id="message-container">
      <h2>Upload Status</h2>
    </div>
  </div>
</div>


<script>
  Dropzone.autoDiscover = false;

  $(document).ready(function() {
    var myDropzone = new Dropzone("#upload_theme_form", {
      paramName: "theme_file[]",
      maxFilesize: 200,
      acceptedFiles: "application/zip,application/x-zip-compressed,multipart/x-zip",
      autoProcessQueue: true,
      parallelUploads: 6,
      init: function() {
        var dz = this;

        this.on("success", function(file, response) {
          // File uploaded successfully
          console.log(response); // You can handle the response from the server here

          // Create a success message element
          var successMsg = $('<div class="success-message">Successfully uploaded file: ' + file.name + '</div>');

          // Insert the success message below the form
          $('#message-container').append(successMsg);
        });

        this.on("error", function(file, errorMessage) {
          // File upload error
          console.log(errorMessage);

          // Create an error message element
          var errorMsg = $('<div class="error-message">Error uploading file: ' + file.name + '</div>');

          // Insert the error message below the form
          $('#message-container').append(errorMsg);
        });
      }
    });
  });
</script>