<?php
$dict=array("1"=>"abooreyhan", "2"=>"amirkabir", "3"=>"ebne-sina", "4"=>"ebtehaj", "5"=>"etesami", "6"=>"ferdowsi", "7"=>"hafez", "8"=>"hesabi", "9"=>"kadekani", "10"=>"molana", "11"=>"panahi", "12"=>"saadi", "13"=>"sepehri", "14"=>"shajarian", "15"=>"shariati", "16"=>"takhti");
$prand = rand(1,16);
$file = fopen("messages.txt", "r");
$counter=0;
$payam=array();
while( !feof($file) ){
    $payam[$counter] = fgets($file);
	$counter++;
}
fclose($file);
$getjson=file_get_contents("people.json");
$names= json_decode($getjson, false);
$question = isset($_POST["question"]) ? $_POST["question"] : '';
$en_name = isset($_POST["person"]) ? $_POST['person'] : 'abooreyhan';
if ($question=='') {
	$en_name = $dict["$prand"];
}
$fa_name = $names->$en_name;
$qhash = base_convert(md5($question.$en_name), 16, 10);
$mrand = ($qhash/1000000000000000000000000000000)%16;

$aya = mb_substr($question, 0, 3, "UTF-8");
$soal = mb_substr($question, -1, 1, "UTF-8");

if ($question!='') {
	if ($aya!="آیا") {
		$msg = "سوال درستی پرسیده نشده";
	}
	elseif ($soal!='?' && $soal!='؟') {
		$msg = "سوال درستی پرسیده نشده";
	}
	else {
		$msg=$payam[$mrand];
	}
}
else {
	$msg='سوال خود را بپرس!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <?php 
		if ($question=='') { ?>
			<span id="label"></span>
		<?php } 
		else { ?>
			<span id="label">پرسش:</span>
			<span id="question"><?php echo $question ?></span>
		<?php } ?>
    </div>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post" action="index.php">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="پرسش خود را تایپ کنید"/>
            را از
            <select name="person">
                <?php
					foreach ($dict as $esm) { 
						$asaami = $names->$esm;
						if ($en_name==$esm) {
						?>
						<option value="<?php echo $esm ?>" selected><?php echo $asaami ?></option>
						<?php } 
						else {?>
						<option value="<?php echo $esm ?>"><?php echo $asaami ?></option>
						<?php } ?>
					<?php 
					} ?>
				
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>