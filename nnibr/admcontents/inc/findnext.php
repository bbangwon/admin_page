<?
include "_da.php";
switch($cat_depth){
	case "2":
		$cat_id = substr($cat_id, 0, 3);
		break;
	case "3":
		$cat_id = substr($cat_id, 0, 6);
		break;
	case "4":
		$cat_id = substr($cat_id, 0, 9);
		break;
	default:
		exit;
		break;
}
$sql = "SELECT * FROM da_category WHERE cat_id LIKE '{$cat_id}%' AND cat_depth='$cat_depth' ORDER BY cat_seq";
$lists = $dbcon->getList($sql);
?>
<? foreach($lists as $row){ ?>
<option value="<?=$row[cat_id]?>" <?=$data["m_major"]==$row[cat_id]?'selected="selected"':''?>><?=$row["cat_nm"]?></option>
<? } ?>
