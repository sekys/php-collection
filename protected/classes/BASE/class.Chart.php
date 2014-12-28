<?php
/*    http://code.google.com/intl/sk/apis/chart/types.html
    typ: 
    
    gom
    p
    p3
    lc
    ls
    lxy
    bhs
    bvs
    bhg
    bhg
    bvg
    bvg
*/
class Chart
{
    public static function Google($typ, $next = '') {
        return '<img border="0" src="http://chart.apis.google.com/chart?cht='.$typ.$next.'" alt="" >';
    }
}
?>