<?php
/*
 * Copyright 2011 Jurrie Overgoor <jurrie@narrowxpres.nl>
 *
 * This file is part of phpNS.
 *
 * phpNS is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * phpNS is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * phpNS. If not, see <http://www.gnu.org/licenses/>.
 */
abstract class Utils
{
	public static function ISO8601Date2UnixTimestamp($iso8601)
	{
		return strtotime($iso8601);
	}

	public static function UnixTimestamp2ISO8601Date($unixTimestamp)
	{
		return date('c', $unixTimestamp);
	}
	
	/**
	 * This function will convert an ISO8601 period to the number of seconds.
	 * The number of seconds a period represents cannot be accurately calculated if you don't know the start of the period, so you need to give this.
	 * @param string $iso8601period The ISO8601 period to be converted
	 * @param long $fromDateTime The start of the period, given as a Unix timestamp
	 */
	public static function ISO8601Period2UnixTimestamp($iso8601period, $fromDateTime)
	{
		if (class_exists('DateInterval'))
		{
			$dateInterval = new DateInterval($iso8601period);
			$toDateTime = strtotime("+".$dateInterval->format("%y")." year +".$dateInterval->format("%m")." month +".$dateInterval->format("%d")." day +".$dateInterval->format("%h")." hour +".$dateInterval->format("%i")." minute +".$dateInterval->format("%s")." second", $fromDateTime);
		}
		else
		{
			// Remove the ambiguity of month and minute: make month = x
			$arr = explode('T', $iso8601period);
			$arr[0] = str_replace('M', 'X', $arr[0]);
			$new = implode('T', $arr);

			// EXPAND THE STRING INTO SOMETHING SENSIBLE
			$new = str_replace('S', 'second ', $new);
			$new = str_replace('M', 'minute ', $new);
			$new = str_replace('H', 'hour ', $new);
			$new = str_replace('T', NULL, $new);
			$new = str_replace('D', 'day', $new);
			$new = str_replace('X', 'month ', $new);
			$new = str_replace('Y', 'year ', $new);
			$new = str_replace('P', NULL, $new);
	
			$toDateTime = strtotime($new, $fromDateTime);
		}
		return $toDateTime - $fromDateTime;
	}

	public static function boolean2String($boolean)
	{
		return ($boolean ? "true" : "false");
	}

	public static function string2Boolean($string)
	{
		return strtolower($string) === "true";
	}

	/**
	 * This returns the distance between two points in kilometers using the spherical law of cosines formula.
	 * All input values are must be in degrees.
	 * Source: http://www.movable-type.co.uk/scripts/latlong.html
	 */
	public static function getDistanceBetweenPoints($latitude1, $longitude1, $latitude2, $longitude2)
	{
		$R = 6371; // Earth's radius: 6371km
		return acos(sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($longitude2-$longitude1))) * $R;
	}
}
?>