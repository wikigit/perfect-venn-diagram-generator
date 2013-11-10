<?php
include (__DIR__ . '/../compute.php');

$filetype = $_GET['filetype'];
if ($filetype != "svg" && $filetype != "png" && $filetype != "pdf" && $filetype != "ps") {
    echo("Invalid filetype.");
    exit(0);
}
if (isset($_GET['download']) && $_GET['download']) {
    header('Content-Disposition: attachment; filename="venn.' . $filetype . '"');
}

$temp_prefix = 'perfect-venn-diagram-generator';
$svg_filename = tempnam(sys_get_temp_dir(), $temp_prefix);

$left = $_GET['left']; $right = $_GET['right']; $overlap = $_GET['both']; $neither = $_GET['neither'];
$total = $left + $right - $overlap + $neither;
$rleft = radius_from_circle_area($left);
$rright = radius_from_circle_area($right);
$d = get_distance_between_circles($rleft, $rright, $overlap);
$R = max($rleft,$rright);
$width_pixels = $_GET['width'];
$units_per_pixel = ($rleft+$d+$rright)/$width_pixels;
$margin = 2*$units_per_pixel;
$stroke_width = 1.5*$units_per_pixel;

$minBoxArea = ($rleft+$d+$rright+2*$stroke_width)*(2*$R+2*$stroke_width);
if ($neither == "" || $neither == 0) {
    $neitherSVG = '';
    $viewBox = array(-$rleft - $margin, -$R - $margin, $rleft+$d+$rright + 2*$margin, 2*$R + 2*$margin);
} elseif ($neither >= $minBoxArea - ($left + $right - $overlap)) {
    $aspectratio = ($rleft+$d+$rright)/(2*$R); # TODO: make this an option
    $height = sqrt($total/$aspectratio);
    $width = $aspectratio*$height;
    $stroke_width = 1.5*$width/$width_pixels;
    $midpoint = ($d+$rright-$rleft)/2;
    $rectanglex = $midpoint - $width/2;
    $rectangley = -$height/2;
    $viewBox = array($rectanglex, $rectangley, $width, $height);
    $neitherSVG = <<<EOT
  <rect x="$rectanglex" y="$rectangley" width="$width" height="$height" fill-opacity="1.0" style="fill:rgb(255,255,128);"/>
EOT;
} else {
    list($rleftneither, $rrightneither) =
	get_neither_radius($left + $right - $overlap, $neither, $rleft, $rright, $d);
    $Rneither = max($rleftneither,$rrightneither);
    $viewBox = array(-$rleftneither - $margin, -$Rneither - $margin, $rleftneither+$d+$rrightneither + 2*$margin, 2*$Rneither + 2*$margin);
    $neitherSVG = <<<EOT
  <circle cx="0" cy="0" r="$rleftneither" fill-opacity="1.0" style="fill:rgb(255,255,128);"/>
  <circle cx="$d" cy="0" r="$rrightneither" fill-opacity="1.0" style="fill:rgb(255,255,128);"/>
EOT;
}
$height_pixels = (int)($width_pixels*$viewBox[3]/$viewBox[2]);
$units_per_pixel = $viewBox[2]/$width_pixels;
$stroke_width = 1.5*$units_per_pixel;

file_put_contents($svg_filename, <<<EOT
<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="{$width_pixels}px" height="{$height_pixels}px" viewBox="{$viewBox[0]} {$viewBox[1]} {$viewBox[2]} {$viewBox[3]}">
$neitherSVG
  <circle cx="0" cy="0" r="$rleft" fill-opacity="1.0" fill="white"/>
  <circle cx="$d" cy="0" r="$rright" fill-opacity="1.0" fill="white"/>
  <circle cx="0" cy="0" r="$rleft" stroke="black" stroke-width="$stroke_width" fill-opacity="0.5" fill="red"/>
  <circle cx="$d" cy="0" r="$rright" stroke="black" stroke-width="$stroke_width" fill-opacity="0.5" fill="blue"/>
</svg>
EOT
);
# echo('<p>.svg contents: ' . file_get_contents($svg_filename) . '</p>');
if ($filetype == "svg") {
    header("Content-type: image/svg+xml");
    readfile($svg_filename);
    unlink($svg_filename);
    exit(0);
}
$png_filename = tempnam("../media", $temp_prefix);
$rsvg_output = `/data/project/perfect-venn-diagram-generator/rsvg-convert -f $filetype $svg_filename 3>&1 2>&3 > $png_filename`;
if (strlen($rsvg_output) > 0) {
    echo('<p>ERROR: ' . $rsvg_output . '</p>');
    unlink($png_filename);
    unlink($svg_filename);
    exit(0);
}
if (filesize($png_filename) == 0) {
    echo('<p>ERROR: png file empty</p>');
    unlink($png_filename);
    unlink($svg_filename);
    exit(0);
}
if ($filetype == "png") {
    header("Content-type: image/png");
} else if ($filetype == "pdf") {
    header("Content-type: application/pdf");
} else if ($filetype == "ps") {
    header("Content-type: application/postscript");
}
readfile($png_filename);
unlink($png_filename);
unlink($svg_filename);
?>
