    <div id="results"></div>
    <div id="webcam"></div>
     
    <form>
        <input type="file" accept="image/*" capture="camera">
    </form>
    
<?php $script = <<< JS
$(function(){

    alert(1);
});

JS;
$this->registerJs($script);
?>