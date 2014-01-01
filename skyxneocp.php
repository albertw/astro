<?php
/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
$db="small";
$idb=$_GET["db"];
if ($idb == "large"){
    $db="large";
}

header('Content-type: text/plain');
$NEOCP="http://www.minorplanetcenter.net/iau/NEO/neocp.txt";
$orbitbaseurl="http://scully.cfa.harvard.edu/cgi-bin/showobsorbs.cgi?Obj=";

$neos=file($NEOCP);
print "
# The output contains the orbits of NEO's listed on the Minor Planet 
# Center NEOCP page in a format that can be added as an asteroid 
# (".$db." database) file in TheSkyX.
# NOTE1: ONS asteroids that have ony one nights data have 'ONS' appended
# to the name. DO NOT use the name with 'ONS' appended when subnitting
# astrometry to the MPC!
# NOTE2: To stack images you will still need the rate and position angle which
# can be determined from querying the NEOCP page.
";
foreach ($neos as $neo){
	$id=strtok($neo," ");
	
	if (strpos($neo,'Added') !== false){
		# Add 'ONS' to the newly added asteroids for convenience
		# Don't add ONS to the name in MPC submission!
		$name=$id." ONS";
	} else {
		$name = $id;
	}

	
	$orbiturl=$orbitbaseurl.$id."&orb=y";
	$orbits=file($orbiturl);
	$orbit=$orbits[2];
	$values=preg_split("/\s+/",$orbit);
	$H=$values[1];
	$G=$values[2];
	$Epoch=$values[3];
	$M=$values[4];
	$Peri=$values[5];
	$Node=$values[6];
	$Incl=$values[7];
	$e=$values[8];
	$n=$values[9];
	$a=$values[10];

	# Unpack the packed date format
	$epocharray=array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8,
	'9' => 9, 'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15,
	'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22,
	'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29,
	'U' => 30, 'V' => 31);
	$Year=substr($Epoch,0,1);
	$Month=substr($Epoch,3,1);
	$Day=substr($Epoch,4,1);
	$epochstring=$epocharray[$Year].substr($Epoch,1,2)." ".$epocharray[$Month]." ".$epocharray[$Day].".000";

	# Print as TheSkyX small asteroid databae format
	if ($db=="small"){
	    printf("  %-19.19s|%-14.14s|%8.8s  |%8.8s| %7.7s|%8.8s |%8.8s | 2000| %-7.7s  |%-5s|%5s|   0.00\r\n",$name, $epochstring, $e,$a,$Incl,$Node,$Peri,$M,$H,$G);
	} else {
	    # Large database format. http://www.minorplanetcenter.org/iau/info/MPOrbitFormat.html
	    printf("%7.7s %5.5s %5.5s %5.5s %+9.9s  %+9.9s  %+9.9s  %+9.9s  %+9.9s %11.11s %11.11s  0 NEOCP      0    0   0         0    0   0   0          0     %27.27s        \r\n", $name, $H, $G, $Epoch, $M, $Peri, $Node, $Incl, $e, $n, $a, $name);
	}

}

?>
