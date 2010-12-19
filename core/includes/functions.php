<?php

	/**
	 * Builds a pagination sequence based upon the current and the total number of pages.
	 * Code borrowed from Spoon Library - http://www.spoon-library.com
	 * @param int $currentPage
	 * @param int $numPages
	 * @param int $limit
	 * @param int $breakpoint
	 */
	function buildPaginationSequence($currentPage, $numPages, $limit = 7, $breakpoint = 4)
	{
		
		$items = array();
		
		/**
		 * Less than or 7 pages. We know all the keys, and we put them in the array
		 * that we will use to generate the actual pagination.
		 */
		if($numPages <= $limit)
		{
			for($i = 1; $i <= $numPages; $i++) $items[$i] = $i;
		}

		// more than 7 pages
		else
		{
			// first page
			if($currentPage == 1)
			{
				// [1] 2 3 4 5 6 7 8 9 10 11 12 13
				for($i = 1; $i <= $limit; $i++) $items[$i] = $i;
				$items[$limit + 1] = '...';
			}


			// last page
			elseif($currentPage == $numPages)
			{
				// 1 2 3 4 5 6 7 8 9 10 11 12 [13]
				$items[$numPages - $limit - 1] = '...';
				for($i = ($numPages - $limit); $i <= $numPages; $i++) $items[$i] = $i;
			}

			// other page
			else
			{
				// 1 2 3 [4] 5 6 7 8 9 10 11 12 13

				// define min & max
				$min = $currentPage - $breakpoint + 1;
				$max = $currentPage + $breakpoint - 1;

				// minimum doesnt exist
				while($min <= 0)
				{
					$min++;
					$max++;
				}

				// maximum doesnt exist
				while($max > $numPages)
				{
					$min--;
					$max--;
				}

				// create the list
				if($min != 1) $items[$min - 1] = '...';
				for($i = $min; $i <= $max; $i++) $items[$i] = $i;
				if($max != $numPages) $items[$max + 1] = '...';
			}
		}
		
		return $items;
		
	}