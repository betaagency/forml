<?php
namespace forml;
foreach(glob('lib/*.php') as $f)
	require_once $f;

function show($name, $wtf){
	echo "<tr><td><b>".$name.':</b><br>'.$wtf."</td>".
	     "<td><textarea style='width: 500px'>".htmlspecialchars($wtf)."</textarea></td></tr>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"> 
<html> 
<head> 
	<title>Demo of ``forml'' elements</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
</head> 
<body> 

<table border=1 width='100%'>
<tr>
	<th width='50%'>demo</th>
	<th>html</th>
</tr>
<?php
show('Simple text', text('firstname'));
show('Textarea', textarea('text'));
show('Label', label('City:','city'));
show('Radio', select('country', array('russia', 'usa', 'england', 'france')));

?>
</table>
</body>
</html>
