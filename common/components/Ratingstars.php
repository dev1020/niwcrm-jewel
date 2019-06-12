<?php
namespace common\components;
 
use Yii;
use yii\base\Component;



class Ratingstars extends Component {

    public static function showstars($number) {
		// Convert any entered number into a float
		// Because the rating can be a decimal e.g. 4.5
		$number = number_format ( $number, 1 );
	 
		// Get the integer part of the number
		$intpart = floor ( $number );
	 
		// Get the fraction part
		$fraction = $number - $intpart;
	 
		// Rating is out of 5
		// Get how many stars should be left blank
		$unrated = 5 - ceil ( $number );
	 
		$starrating = '';
		// Populate the full-rated stars
		if ( $intpart <= 5 ) {
			for ( $i=0; $i<$intpart; $i++ )
			$starrating .= '<span class="star-icon full">☆</span>';
		}
	 
		// Populate the half-rated star, if any
		if ( $fraction == 0.5 ) {
			$starrating .= '<span class="star-icon half">☆</span>';
		}
	 
		// Populate the unrated stars, if any
		if ( $unrated > 0 ) {
			for ( $j=0; $j<$unrated; $j++ )
			$starrating .='<span class="star-icon">☆</span>';
		}
		$starrating .='';
		return ltrim($starrating);
        
    }
	public static function showstarsfraction($number,$container=Null) {
		
		if($container){
			$class = $container;
		}else{
			$class = 'star-sm'; 
		}
		// Convert any entered number into a float
		// Because the rating can be a decimal e.g. 4.5
		$number = number_format ( $number, 1 );
	 
		// Get the integer part of the number
		$intpart = floor ( $number );
	 
		// Get the fraction part
		$fraction = $number - $intpart;
	 
		// Rating is out of 5
		// Get how many stars should be left blank
		$unrated = 5 - ceil ( $number );
	 
		$starrating = '<span class="'.$class.'">';
		// Populate the full-rated stars
		if ( $intpart <= 5 ) {
			for ( $i=0; $i<$intpart; $i++ )
			$starrating .= '<span class="s10"></span>';
		}
	 
		// Populate the half-rated star, if any
		if ($fraction) {
			$starrating .= '<span class="s'.($fraction*10).'"></span>';
		}
	 
		// Populate the unrated stars, if any
		if ( $unrated > 0 ) {
			for ( $j=0; $j<$unrated; $j++ )
			$starrating .='<span class="s0"></span>';
		}
		$starrating .='</span>';
		return ltrim($starrating);
        
    }
	
	 
}
