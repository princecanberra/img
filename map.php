
 <html>
<head>
<meta charset="utf-8" />
<title>Get Location Map using Address</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
</head>
<body>
<?php
$exif_data = exif_read_data("6.JPG");




    

        $emake = $exif_data['Make'];
        $emodel = $exif_data['Model'];
        $eexposuretime = $exif_data['ExposureTime'];
        $efnumber = $exif_data['FNumber'];
        $eiso = $exif_data['ISOSpeedRatings'];
        $edate = $exif_data['DateTime'];
       

    echo "Make: ". $emake . "<br>";
        echo "Model: ". $emodel . "<br>";
        echo "Exp: ". $eexposuretime . "<br>";
        echo "Date: ". $edate . "<br>";
        echo "ISO: ". $eiso . "<br>";
        
       
?>

<?php

function getGps($exifCoord, $hemi) {

    $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
    $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
    $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;

    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

}

function gps2Num($coordPart) {

    $parts = explode('/', $coordPart);

    if (count($parts) <= 0)
        return 0;

    if (count($parts) == 1)
        return $parts[0];

    return floatval($parts[0]) / floatval($parts[1]);
}

$lon = getGps($exif_data["GPSLongitude"], $exif_data['GPSLongitudeRef']);
$lat = getGps($exif_data["GPSLatitude"], $exif_data['GPSLatitudeRef']);

// get location name

echo "<br/>";

function reverse_geocode($lat, $lon) {
    $url = "http://maps.google.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=false";
    $data = json_decode(file_get_contents($url));
    if (!isset($data->results[0]->formatted_address)){
        return "unknown Place";
    }
    return $data->results[0]->formatted_address;
}

echo  reverse_geocode($lat, $lon);

/// below is display the map
?>
<script type="text/javascript">
$(document).ready(function () {
    // Define the latitude and longitude positions
    var latitude = parseFloat("<?php echo $lat; ?>"); // Latitude get from above variable
    var longitude = parseFloat("<?php echo $lon; ?>"); // Longitude from same
    var latlngPos = new google.maps.LatLng(latitude, longitude);
    // Set up options for the Google map
    var myOptions = {
        zoom: 14,
        center: latlngPos,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoomControlOptions: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE
        }
    };
    // Define the map
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    // Add the marker
    var marker = new google.maps.Marker({
        position: latlngPos,
        map: map,
        title: "Photo was taken here"
        
      
    });
});
</script>
<div id="map" style="width:350px;height:150px;  margin:20px auto 0;"></div>
</body>
</html>
