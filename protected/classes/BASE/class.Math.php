<?php

/* TIPS
	rand(1, 1e9);
*/	
class Math {
	const EPSILON = 1.0e-8;

	public static function real_cmp($r1, $r2) { return (abs($r1 - $r2) < self::EPSILON ); }
	public static function real_cmp2($r1, $r2) {
		(float)$diff = $r1 - $r2;
		if( abs($diff) < self::EPSILON ) return 0;
		return $diff < 0 ? -1 : 1;
	}
    public static function Distance($ax, $ay, $bx, $by) { return SQRT( POW($ax-$bx, 2) + POW($ay-$by, 2));}
}
?>