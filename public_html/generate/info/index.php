<?php
# Set $width_pixels before including this.
include (__DIR__ . '/../compute.php');

$left = $_GET['left']; $right = $_GET['right']; $overlap = $_GET['both']; $neither = $_GET['neither'];
$total = $left + $right - $overlap + $neither;
$rleft = radius_from_circle_area($left);
$rright = radius_from_circle_area($right);
$d = get_distance_between_circles($rleft, $rright, $overlap);
$R = max($rleft,$rright);
$units_per_pixel = ($rleft+$d+$rright)/$width_pixels;
$margin = 2*$units_per_pixel;
$stroke_width = 1.5*$units_per_pixel;

function f($x) {
    return number_format($x,3);
}

echo ('<h3>Geometry information</h3>');

echo ('<p><b>Center point of left circle:</b> (0, 0)<br/>');
echo ('<b>Radius, diameter of left circle:</b> ' . f($rleft) . ', ' . f(2*$rleft) . '<br/>');
echo ('<b>Bounding box of left circle:</b> top-left: (' . f(-$rleft) . ', ' . f(-$rleft) . '), bottom-right: (' . f($rleft) . ', ' . f($rleft) . ')</p>');
echo ('<p><b>Center point of right circle:</b> (' . f($d) . ', 0)<br/>');
echo ('<b>Radius, diameter of right circle:</b> ' . f($rright) . ', ' . f(2*$rright) . '<br/>');
echo ('<b>Bounding box of right circle:</b> top-left: (' . f(-$rright+$d) . ', ' . f(-$rright) . '), bottom-right: (' . f($rright+$d) . ', ' . f($rright) . ')</p>');

$minBoxArea = ($rleft+$d+$rright+2*$stroke_width)*(2*$R+2*$stroke_width);
if ($neither == "" || $neither == 0) {
    $neitherSVG = '';
    $viewBox = array(-$rleft - $margin, -$R - $margin, $rleft+$d+$rright + 2*$margin, 2*$R + 2*$margin);
} elseif ($neither >= $minBoxArea - ($left + $right - $overlap)) {
    $aspectratio = ($rleft+$d+$rright)/(2*$R); # TODO: make this an option
    $height = sqrt($total/$aspectratio);
    $width = $aspectratio*$height;
    $midpoint = ($d+$rright-$rleft)/2;
    $rectanglex = $midpoint - $width/2;
    $rectangley = -$height/2;
    $viewBox = array($rectanglex, $rectangley, $width, $height);
    echo ('<p><b>Rectangle width &times; height:</b> ' . f($width) . ' &times; ' . f($height) . '<br/>');
    echo ('<b>Rectangle aspect ratio:</b> ' . f($width/$height) . '<br/>');
    echo ('<b>Bounding box of rectangle:</b> top-left: (' . f($rectanglex) . ', ' . f($rectangley) . '), bottom-right: (' . f($rectanglex+$width) . ', ' . f($rectangley+$height) . ')</p>');
} else {
    list($rleftneither, $rrightneither) =
	get_neither_radius($left + $right - $overlap, $neither, $rleft, $rright, $d);
    $Rneither = max($rleftneither,$rrightneither);
    $viewBox = array(-$rleftneither - $margin, -$Rneither - $margin, $rleftneither+$d+$rrightneither + 2*$margin, 2*$Rneither + 2*$margin);

    echo ('<p><b>Radius, diameter of left yellow circle:</b> ' . f($rleftneither) . ', ' . f(2*$rleftneither) . '<br/>');
    echo ('<b>Bounding box of right circle:</b> top-left: (' . f(-$rleftneither) . ', ' . f(-$rleftneither) . '), bottom-right: (' . f($rleftneither) . ', ' . f($rleftneither) . ')</p>');
    echo ('<p><b>Radius, diameter of right yellow circle:</b> ' . f($rrightneither) . ', ' . f(2*$rrightneither) . '<br/>');
    echo ('<b>Bounding box of left circle:</b> top-left: (' . f(-$rrightneither+$d) . ', ' . f(-$rrightneither) . '), bottom-right: (' . f($rrightneither+$d) . ', ' . f($rrightneither) . ')</p>');
}
$height_pixels = (int)($width_pixels*$viewBox[3]/$viewBox[2]);
print("<p><b>Image width &times; height:</b> $width_pixels &times; $height_pixels pixels</p>");
?>
