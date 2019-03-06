<?php
include_once ("./common.php");

if($_FILES["file1"]['tmp_name']) {
    $file_name = $_FILES['file1']['name']; // using just for this example, I pull $file_name from another function
    //echo strpos($file_name,'.pdf');
    $basename = substr($file_name,0,strpos($file_name,'.'));
    //echo $_FILES['pdfupload']['type'];
    //if (isset($_POST['submit'])){
    if($_FILES['file1']['type']=='application/pdf'){
        // Strip document extension
        $file_name = basename($file_name, '.pdf');
        // Convert this document
        // Each page to single image
        $img = new imagick("./".$file_name.'.pdf');

        // Set background color and flatten
        // Prevents black background on objects with transparency
        $img->setImageBackgroundColor('white');
        //$img = $img->flattenImages();

        // Set image resolution
        // Determine num of pages
        $img->setResolution(300,300);
        $num_pages = $img->getNumberImages();

        // Compress Image Quality
        $img->setImageCompressionQuality(100);
        $images = NULL;
        // Convert PDF pages to images
        for($i = 0;$i < $num_pages; $i++) {
            echo $i."<br>";
            $images[]=$basename.'-'.$i.'.jpg';
            // Set iterator postion
            $img->setIteratorIndex($i);

            // Set image format
            $img->setImageFormat('jpeg');

            // Write Images to temp 'upload' folder
            $img->writeImage('pdfimage/'.$file_name.'-'.$i.'.jpg');
        }
        echo "<pre>";
        print_r($images);
        $img->destroy();
    }
}

?>

<form action="<?php echo G5_URL;?>/pdftoimage.php" name="test_form" method="post" enctype="multipart/form-data">
    <input type="file" name="file1" >
    <input type="submit" value="전송">
</form>

