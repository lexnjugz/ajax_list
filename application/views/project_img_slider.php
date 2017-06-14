<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/dropzone/dropzone.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/dropzone/basic.min.css') ?>">
<script type="text/javascript" src="<?php echo base_url('assets/dropzone/dropzone.min.js') ?>"></script>

<style type="text/css">

body{
    background-color: #E8E9EC;
}

.dropzone {
    margin-top: 100px;
    border: 2px dashed #0087F7;
}

</style>

<div class="" id="upload_form">

                <h3 class="modal-title">Upload Project Image Slider</h3>
        
            <div>
                <div class="dropzone">

                  <div class="dz-message">
                    <h3> Drag and Drop Slider Images Here</h3>
                  </div>

                </div>
            </div>
       
</div>



 <script>
 Dropzone.autoDiscover = false;

        var project_slider_upload= new Dropzone(".dropzone",{
        url: "<?php echo base_url('index.php/project/project_slider_upload') ?>/"+id,
        maxFilesize: 2,
        method:"post",
        acceptedFiles:"image/*",
        paramName:"userfile",
        dictInvalidFileType:"This file type is not supported.",
        addRemoveLinks:true,
        });


        //Event when Starting upload
        image_upload.on("sending",function(a,b,c){
            a.token=Math.random();
            c.append("image_token",a.token); //Preparing token for each photo
        });


        //Event when photos are deleted
        image_upload.on("removedfile",function(a){
            var token=a.token;
            $.ajax({
                type:"post",
                data:{token:token},
                url:"<?php echo base_url('index.php/project/project_remove_image') ?>",
                cache:false,
                dataType: 'json',
                success: function(){
                    console.log("photos deleted");
                },
                error: function(){
                    console.log("Error");

                }
            });
        });
</script>