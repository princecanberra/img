<html>
<head>
<title> PHP Upload Resize</title>
</head>
<body>
<?

  if(trim($_FILES["fileUpload"]["tmp_name"]) != "")
	
	{
	   // check file size
	
		if($_FILES["fileUpload"]['size']>=4120000){
		
			echo "Your file size is too big";
		
		   exit();
		
		}
		// check images type 
		$imageData = @getimagesize($_FILES["fileUpload"]["tmp_name"]);
		if($imageData === FALSE || !($imageData[2] == IMAGETYPE_GIF || $imageData[2] == IMAGETYPE_JPEG || $imageData[2] == IMAGETYPE_PNG)){
		
			echo "Please upload image file only";
		
		   exit();
		
		}
		
		$images = $_FILES["fileUpload"]["tmp_name"];
		$new_images = microtime(true)."Thumbnails_".$_FILES["fileUpload"]["name"];
		$img = microtime(true).$_FILES["fileUpload"]["name"];
		copy($_FILES["fileUpload"]["tmp_name"],"MyResize/".$img);
		$width=100; //*** Fix Width & Heigh (Autu caculate) ***//
		$size=GetimageSize($images);
		$height=100;
		
		switch(strtolower($size['mime']))
			{
    		case 'image/png':
     	  $images_orig = imagecreatefrompng($images);
       	 break;
   		 case 'image/jpeg':
        $images_orig = imagecreatefromjpeg($images);
        break;
   		 case 'image/gif':
        $images_orig = imagecreatefromgif($images);
        break;
   	   default: die();
	
	 }
		
		
		$photoX = ImagesX($images_orig);
		$photoY = ImagesY($images_orig);
		$images_fin = ImageCreateTrueColor($width, $height);
		ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
		ImageJPEG($images_fin,"MyResize/".$new_images);
		ImageDestroy($images_orig);
		ImageDestroy($images_fin);
		
		/* Insert Record */
		$objConnect = mysql_connect("localhost","root","n") or die("Error Connect to Database");
		$objDB = mysql_select_db("TOH");
		$strSQL = "INSERT INTO photo ";
		$strSQL .="(thumb,p_name) VALUES ('".$new_images."','".$img."')";
		$objQuery = mysql_query($strSQL);
		
		
	}
?>
<b>Original Size</b><br>
<img src="<?="MyResize/".$img;?>">
<hr>
<b>New Resize</b><br>
<img src="<?="MyResize/".$new_images;?>">
</body>
</html>
