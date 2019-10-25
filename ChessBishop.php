<?php
$text;
$color;
$o = 0;
$cells = [];
$data = null;
$letters = "ABCDEFGH";
$startingСoordinates = null;
$destinationСoordinates = null;
if(file_exists("Cache"))
{
    $array = unserialize(file_get_contents("Cache"));
    $cells = $array[2];
    $startingСoordinates = $array[0];
    $destinationСoordinates = $array[1];
    unlink("Cache");
}
if(!empty($_POST["cell"]) && strlen($destinationСoordinates = $_POST["cell"]) == 2 && $startingСoordinates != null && $startingСoordinates != $destinationСoordinates)
{
   if(strpos($letters, $destinationСoordinates[0])%2 != $destinationСoordinates[1]%2)
   {
        $_POST["cell"] = null;
        $destinationСoordinates = null;
        $text = 'Choose your coordinates in black cells !';
   }
   else if(abs(strpos($letters, $startingСoordinates[0]) - strpos($letters, $destinationСoordinates[0])) == abs($startingСoordinates[1] - $destinationСoordinates[1]))
   {
        $text = '1';
        unset($cells);
        $_POST["cell"] = null;
        $startingСoordinates = null;
        $destinationСoordinates = null;
   }
   else
   {
        $cells = [];
        $text = '0';
        $_POST["cell"] = null;
        for($i = 0; $i < 8; $i++)
        {
            $indexOfLetterOfStartingСoordinates = abs(strpos($letters, $startingСoordinates[0]) - $i);
            for($n = 1; $n <= 8; $n++)
                if($indexOfLetterOfStartingСoordinates == abs($startingСoordinates[1] - $n))
                    for($m = 0; $m < 8; $m++)
                    {
                        $indexOfLetterOfDestinationСoordinates = abs(strpos($letters, $destinationСoordinates[0]) - $m);
                        for($r = 1; $r <= 8; $r++)
                            if($indexOfLetterOfDestinationСoordinates == abs($destinationСoordinates[1] - $r) && $i == $m && $n == $r)
                            {
                                $cells[$o++] = $letters[$i].(string)$n;
                                break;
                            }
                    }      
        }
   }
}
else if(!empty($_POST["cell"]) && strlen($startingСoordinates = $_POST["cell"]) == 2 && strpos($letters, $startingСoordinates[0])%2 == $startingСoordinates[1]%2)
{
    $_POST["cell"] = null;
    $text = 'Starting Coordinates : '.$startingСoordinates;
}
else
{
    unset($cells);
    $_POST["cell"] = null;
    $startingСoordinates = null;
    $destinationСoordinates = null;
    $text = 'Choose your coordinates';
}
if($startingСoordinates != null)
   file_put_contents("Cache", serialize([$startingСoordinates, $destinationСoordinates, $cells]));
for ($i = 1; $i < 8; $i++) 
{
    $data .= '<tr>';  
    for($n = 0; $n < 8; $n++)
    {    
        $value = "ABCDEFGH"[$n].(string)($i + 1);
        if($startingСoordinates == $value)
            $color = 'green';
        else
            $color = !empty($cells) && in_array($value, $cells) ? 'blue' : 'white';
        $data .= '<td><form method = "post"><input name = "cell" type = "hidden" value = '.$value.'><input type = "submit" style = "cursor : pointer; border : 0px; width : 100px; height : 100px; background-color : #'.(($n%2 == 0 && $i%2 == 0) || ($n%2 != 0 && $i%2 != 0) ? "fff;" : "000; color : ".$color.";").' text-align : center" value = '.$value.'></form></td>';
    }
    $data .= '</tr>'; 
}
$data .= '<tr><td colspan = "8" style = "text-align : center; border-style : solid"><form method = "post"><input name = "cell" type = "hidden" value = "Choose your coordinates"><input type = "submit" style = "cursor : pointer; border : 0px; font-size : 30px; font : bold 200% serif; width : 700px; height : 100px; background-color : #fff; text-align : center" value = "'.$text.'"></form></td></tr>';
?>
<!DOCTYPE html>
<html>
<head>
<style> 
#chess {border : solid 1px black; cursor : pointer; border-collapse : collapse; height : 600px; width : 500px}
</style>
<head>
<body>
<table id = "chess"><?php echo $data ?></table>
</body>
</html>