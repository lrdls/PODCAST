<!DOCTYPE html>
<?php
require('../devices/config/fcts.php');
$maxFileSize0 = getMaximumFileUploadSize();
$maxFileSize = formatBytes($maxFileSize0, 2);
// echo $max_upload_size;
// 209,715,200 => 200 Mo
?>

<html>
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>  -->
<!-- <script src="http://malsup.github.com/jquery.form.js"></script>  -->

<script src="jquery-1.7.1.min.js"></script> 
<script src="jquery.form.js"></script>

<form action="upload.php" method="post" enctype="multipart/form-data">
  <!-- MAX_FILE_SIZE doit précéder le champ input de type file -->

  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_upload_size0; ?>" />
    <input id="parcourir" type="file" name="fileToUpload"><br>
    <input id="upload" type="submit" value="Upload File to Server" disabled>
</form>

<div class="progress">
    <div class="bar"></div >
    <div class="percent">0%</div >
    <span class="wait">In Progress, please wait...</span>
</div>

<div id="status"></div>
<script>

$( document ).ready(function() {

    $('#upload').prop('disabled', true);

    $("#parcourir").click(function(){
        $('#upload').prop('disabled', false);
    }); 

    $(function() {

            $('.wait').hide();

            var bar = $('.bar');
            var percent = $('.percent');
            var status = $('#status');

            $('form').ajaxForm({
                beforeSend: function() {
                    status.empty();
                    var percentVal = '0%';
                    bar.width(percentVal);
                    percent.html(percentVal);
                    $('.wait').show();
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal);
                    percent.html(percentVal);
                },
                complete: function(xhr) {
                    status.html(xhr.responseText);
                    $('.wait').hide();
                    $('#upload').prop('disabled', true);
                }
            });
    }); 

});

</script>


</body>
</html>