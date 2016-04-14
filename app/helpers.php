<?php
	// Some common helper functions
	
	function checkForZeroAndBlank($value) 
	{
		if ($value === ' ')
		{
			return 'NA';
		}
		else if (floatval($value) === 0.0)
		{
            return 'ND';
		}
		else 
		{
			return $value;
		}
	}

?>