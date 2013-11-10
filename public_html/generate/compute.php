<?php

function get_distance_between_circles($rleft, $rright, $overlap) {
    $R = max($rleft,$rright);
    $r = min($rleft,$rright);
    $d = $R;
    $dprev = -1;
    $r2 = $r*$r;
    $R2 = $R*$R;
    $count = 0;
    while (abs($d - $dprev) > 0.0001 && $count < 100) {
      $count += 1;
      $q = sqrt(($d + $r - $R)*($d - $r + $R)*(-$d + $r + $R)*($d + $r + $R));
      $d2 = $d*$d;
      $arg1 = ($d2 + $r2 - $R2)/(2*$d*$r);
      $arg2 = ($d2 + $R2 - $r2)/(2*$d*$R);
      $val = $r2*acos($arg1) + $R2*acos($arg2) - $q/2 - $overlap;
      $deriv = -$q/$d;
      $dprev = $d;
      $d -= $val/$deriv;
      if ($d < 0) {
        $d = 0.0001;
      }
    }
    return $d;
}

function get_neither_radius($inner_area, $neither_area, $rleft, $rright, $d) {
    $R = max($rleft,$rright);
    $r = min($rleft,$rright);
    $rdiff = $R - $r;
    $d2 = $d*$d;
    $pi = pi();
    $rsum = $R + $r;
    $rsumprev = -1;
    $count = 0;
    while (abs($rsum - $rsumprev) > 0.0001 && $count < 100) {
      $R = ($rsum + $rdiff)/2; $r = $rsum - $R;
      $R2 = $R*$R; $r2 = $r*$r;
      $count += 1;
      $q = sqrt(($d + $r - $R)*($d - $r + $R)*(-$d + $r + $R)*($d + $r + $R));
      $arg1 = ($d2 + $r2 - $R2)/(2*$d*$r);
      $arg2 = ($d2 + $R2 - $r2)/(2*$d*$R);
      $intersectarea = $r2*acos($arg1) + $R2*acos($arg2) - $q/2;
      $val = $pi*$R2 + $pi*$r2 - $intersectarea - $inner_area - $neither_area;
      $deriv = $pi*$rsum - $r*acos(($d2 - $rdiff*$rsum)/(2*$d*$r)) - 
                           $R*acos(($d2 + $rdiff*$rsum)/(2*$d*$R));
      $rsumprev = $rsum;
      $rsum -= $val/$deriv;
    }
    if ($rleft > $rright) {
        return array(($rsum + $rdiff)/2, ($rsum - $rdiff)/2);
    } else {
        return array(($rsum - $rdiff)/2, ($rsum + $rdiff)/2);
    }
}

function radius_from_circle_area($area) {
    return sqrt($area/pi());
}

?>