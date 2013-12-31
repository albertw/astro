<?php
header('Content-type: text/plain');
#print("<pre>");
#print("  1915 1953 EA       |2013 11 04.000|0.570460  |2.544271| 20.3971|162.9713 |347.8039 | 2000| 32.7163  |18.97| 0.10|   0.00\n");
$NEOCP="http://www.minorplanetcenter.net/iau/NEO/neocp.txt";
$orbitbaseurl="http://scully.cfa.harvard.edu/cgi-bin/showobsorbs.cgi?Obj=";

$neos=file($NEOCP);
foreach ($neos as $neo){
	$id=strtok($neo," ");
	
	if (strpos($neo,'Added') !== false){
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
	
	$epocharray=array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8,
	'9' => 9, 'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15,
	'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22,
	'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29,
	'U' => 30, 'V' => 31);
	$Year=substr($Epoch,0,1);
	$Month=substr($Epoch,3,1);
	$Day=substr($Epoch,4,1);
	$epochstring=$epocharray[$Year].substr($Epoch,1,2)." ".$epocharray[$Month]." ".$epocharray[$Day].".000";
	printf("  %-19.19s|%-14.14s|%8.8s  |%8.8s| %7.7s|%8.8s |%8.8s | 2000| %-7.7s  |%-5s|%5s|   0.00\r\n",$name, $epochstring, $e,$a,$Incl,$Node,$Peri,$M,$H,$G);

	/*
	 *  #1915 1953 EA       |2013 11 04.000|0.570460  |2.544271| 20.3971|162.9713 |347.8039 | 2000| 32.7163  |18.97| 0.10|   0.00
        #name               |epoch         |e         |a       |i       |Node     |Peri     |2000 |M         |H    |G    |??
        print '  {:<19}'.format(self.ID) + "|"+'{:14}'.format(self._unpack_date())+"|" + '{:<10}'.format(self.e) + "|" + \
            '{:<8}'.format(self.a) + "|"+ ' {:<8}'.format(self.Incl) + "|" + '{:<9}'.format(self.Node) + "|" + \
            '{:<9}'.format(self.Peri) + "| 2000|" + '{:<10}'.format(self.M) + "|" + '{:<5}'.format(self.H) + "|" + \
            '{:<5}'.format(self.G) + "|   0.00"
            
	 */
}
#print("</pre>");

?>