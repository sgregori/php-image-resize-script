<?php
if (!isset($_FILES['image'])) {
	?>
	<form method="POST" action="#" enctype="multipart/form-data">
	<label>Image: <input type="file" name="image"/></label><br/>
	<label>Max Height: <input type="text" size="4" maxlength="4" name="height" value="600"/></label><br/>
	<label>Max Width: <input type="text" size="4" maxlength="4" name="width" value="540"/></label><br/>
	<input type="submit" value="Upload"/>
	</form>
	
	<?php
}
else {
	header ("Content-type: image/jpeg");
	
	//var_dump($_FILES);
	
	$img = $_FILES['image']['tmp_name'];
	$dw = $_POST['width'];
	$dh = $_POST['height'];

	// d = desired dimensions
	// o = old dimensions

	// get image size of img
	$x = @getimagesize($img);
	// image width
	$ow = $x[0];
	// image height
	$oh = $x[1];
	
	if ($oh > $ow) {
		$h = $dh;
		$w = ($dh/$oh) * $ow;
	}
	else {
		$w = $dw;
		$h = ($dw/$ow) * $oh;
	}
	
	$im = @ImageCreateFromJPEG ($img) or // Read JPEG Image
	$im = @ImageCreateFromPNG ($img) or // or PNG Image
	$im = @ImageCreateFromGIF ($img) or // or GIF Image
	$im = false; // If image is not JPEG, PNG, or GIF

	if (!$im) {
		// We get errors from PHP's ImageCreate functions...
		// So let's echo back the contents of the actual image.
		readfile ($img);
	} else {
		// Create the resized image destination
		$thumb = @ImageCreateTrueColor ($w, $h);
		// Copy from image source, resize it, and paste to image destination
		@ImageCopyResampled ($thumb, $im, 0, 0, 0, 0, $w, $h, $ow, $oh);
		// Output resized image
		@ImageJPEG ($thumb);
	}
}
?>