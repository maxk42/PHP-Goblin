<?php
/**
 * Calculate geodesic distance (in meters) between two points specified by
 * latitude/longitude with Vincenty formula
 *
 * from: Vincenty inverse formula - T Vincenty, "Direct and Inverse
 * Solutions of Geodesics on the Ellipsoid with application of nested
 * equations", Survey Review, vol XXII no 176, 1975
 * http://www.ngs.noaa.gov/PUBS_LIB/inverse.pdf
 *
 * @param float $lat1 in form 52.2166667
 * @param float $lat2 in form 52.35
 * @param float $lon1 in form 5.9666667
 * @param float $lon2 in form 4.9166667
 * @return float in form 73.174873 meters
 */
function calc_dist_vincenty($lat1, $lon1, $lat2, $lon2)
{
    $lat1 = deg2rad($lat1);
    $lat2 = deg2rad($lat2);
    $lon1 = deg2rad($lon1);
    $lon2 = deg2rad($lon2);

    $a = 6378137; $b = 6356752.3142; $f = 1/298.257223563; // WGS-84 ellipsoid

    $L = $lon2 - $lon1;

    $U1 = atan((1-$f) * tan($lat1));
    $U2 = atan((1-$f) * tan($lat2));

    $sinU1 = sin($U1); $cosU1 = cos($U1);
    $sinU2 = sin($U2); $cosU2 = cos($U2);

    $lambda = $L; $lambdaP = 2 * M_PI;

    $iterLimit = 20;

    while (abs($lambda - $lambdaP) > 1e-12 && --$iterLimit > 0)
    {
        $sinLambda = sin($lambda);
        $cosLambda = cos($lambda);
        $sinSigma  = sqrt(($cosU2 * $sinLambda) * ($cosU2 * $sinLambda) +
                          ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda) *
                          ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda));

        if ($sinSigma == 0) return 0; // co-incident points

        $cosSigma   = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;
        $sigma      = atan2($sinSigma, $cosSigma); // was atan2
        $alpha      = asin($cosU1 * $cosU2 * $sinLambda / $sinSigma);
        $cosSqAlpha = cos($alpha) * cos($alpha);
        $cos2SigmaM = $cosSigma - 2 * $sinU1 * $sinU2 / $cosSqAlpha;
        $C          = $f / 16 * $cosSqAlpha * (4 + $f * (4 - 3 * $cosSqAlpha));
        $lambdaP    = $lambda;
        $lambda     = $L + (1 - $C) * $f * sin($alpha) *
                      ($sigma + $C * $sinSigma * ($cos2SigmaM + $C * $cosSigma *
                      (-1 + 2 * $cos2SigmaM * $cos2SigmaM)));
    }
    if ($iterLimit == 0) return false; // formula failed to converge

    $uSq = $cosSqAlpha * ($a * $a - $b * $b) / ($b * $b);
    $A   = 1 + $uSq / 16384 * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));
    $B   = $uSq / 1024 * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));

    $deltaSigma = $B * $sinSigma * ($cos2SigmaM + $B / 4 * ($cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM) -
                  $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaM * $cos2SigmaM)));

    $s = $b * $A * ($sigma - $deltaSigma);

    $s = round($s, 3); // round to 1mm precision

    return $s;
}
?>