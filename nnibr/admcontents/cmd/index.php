<?
$da_path = "../..";
$da_adm_path = $da_path."/admcontents";
include_once($da_path."/dacommon.php");

$param = $_POST['param'];
$json_data = json_decode(stripslashes($param));

function GetUserIdx($nickName)
{
    global $dbcon;
    $nickName = $dbcon->db_escape_string($nickName);
    $publish_nick = $dbcon->getOneCol("select idx from nnibr_user where nickname='".$nickName."'");
    if($publish_nick)
        return $publish_nick;
    else
        return "";
}

function GetPointFromType($type)
{
    $point = 0;
    switch($type)
    {
        case 0:
        $point = 1000;
        break;
        case 1:
        $point = 100;
        break;
        case 2:
        $point = 10;
        break;
        case 3:
        $point = 5;
        break;
        case 4:
        $point = 10;
        break;
        case 5:
        $point = 300;
        break;
        case 6:
        $point = 1000;
        break;
        case 7:
        $point = 500;
        break;
    }
    return $point;
}

switch($json_data->method)
{
    case "GetNickname": //현재 닉네임 가져오기
        $json_data->deviceid = $dbcon->db_escape_string($json_data->deviceid);
        $publish_nick = $dbcon->getOneRow("select nickname, pushmsg from nnibr_user where device_id='".$json_data->deviceid."'" );
        if($publish_nick)
        {
            echo json_encode(array("result"=>1, "nickname"=>$publish_nick[nickname], "pushmsg"=>$publish_nick[pushmsg]));
        }
        else
        {
            echo json_encode(array("result"=>0));
        }
    break;

    case "IsExistNickname": //생성할때 닉네임이 존재하는지 확인
        $json_data->nickname = $dbcon->db_escape_string($json_data->nickname);
        $publish_count = $dbcon->getOneCol("select count(*) from nnibr_user where nickname='".$json_data->nickname."'" );
        echo json_encode(array("result"=>$publish_count));
    break;

    case "CreateNickname": //닉네임 생성/수정 
        $json_data->deviceid = $dbcon->db_escape_string($json_data->deviceid);
        $cnt = $dbcon->getOneCol("select count(*) from nnibr_user where device_id='".$json_data->deviceid."'" );
        if($cnt > 0)
        {
            $json_data->nickname = $dbcon->db_escape_string($json_data->nickname);
            $json_data->deviceid = $dbcon->db_escape_string($json_data->deviceid);
            $sql = "update nnibr_user set ";
            $sql .= "nickname='".$json_data->nickname."' ";
            $sql .= " where device_id= '".$json_data->deviceid."'";
            $idkey = $dbcon->doQuery( $sql );

            echo json_encode(array("result"=>1));
        }
        else
        {
            $json_data->deviceid = $dbcon->db_escape_string($json_data->deviceid);
            $json_data->nickname = $dbcon->db_escape_string($json_data->nickname);
            $json_data->pushmsg = $dbcon->db_escape_string($json_data->pushmsg);
            $sql = "insert into nnibr_user (device_id, nickname, pushmsg, regtime, app_playcount) values (";
            $sql .= "'".$json_data->deviceid."',";
            $sql .= "'".$json_data->nickname."',";
            $sql .= $json_data->pushmsg.",";
            $sql .= "now(),";
            $sql .= "1";    //app playcount
            $sql .= ")";
            $idkey = $dbcon->doQuery($sql);

            echo json_encode(array("result"=>1));
        }
    break;
    case "VisitCheck":
        $idx = GetUserIdx($json_data->nickname);
        if($idx)
        {
            $idx = $dbcon->db_escape_string($idx);
            $sql = "select count(*) from nnibr_visit where user_id = ".$idx." and date(timestamp) = date(now());";
            $cnt = $dbcon->getOneCol($sql);
            if(!$cnt)
            {               
                $idx = $dbcon->db_escape_string($idx); 
                $sql2 = "insert into nnibr_visit set user_id = ".$idx;
                $idkey = $dbcon->doQuery($sql2); 

                if(!$idkey)
                {               
                    echo json_encode(array("result"=>0));
                    exit(0);
                }
            }
            echo json_encode(array("result"=>1));
        }
    break;   
    case "GetPointList":
        $idx = GetUserIdx($json_data->nickname);
        if($idx)
        {
            $idx = $dbcon->db_escape_string($idx);
            $sql = "select type, point, get_time from nnibr_point where user_id = ".$idx;
            $list = $dbcon->getList($sql);
            
            $arrPoint = array();
            for($i=0; $i<count($list); $i++){
                $row = $list[$i];
                $a = array("type"=>$row[type], "point"=>$row[point], "get_time"=>$row[get_time]);
                array_push($arrPoint, $a);              
            }

            echo json_encode(array("result"=>1, "pointinfos"=>$arrPoint));
        }
    break;
    case "AddPoint":

        $point = GetPointFromType($json_data->kindpoint);
        $idx = GetUserIdx($json_data->nickname);
        if($idx)
        {
            $idx = $dbcon->db_escape_string($idx);
            $json_data->kindpoint = $dbcon->db_escape_string($json_data->kindpoint);
            $point = $dbcon->db_escape_string($point);

            $sql = "insert into nnibr_point (user_id, type, point, get_time) values (";
            $sql .= $idx.", ";
            $sql .= $json_data->kindpoint.", ";
            $sql .= $point.", now())";

            $idkey = $dbcon->doQuery($sql);
            
            if($idkey)
                echo json_encode(array("result"=>1));
            else
                echo json_encode(array("result"=>0));
        }    
    break;
    case "GetExhibitionList":    
        $sql = "select idx, tit_c, publish from da_data";
        $list = $dbcon->getList($sql);

        $arrPoint = array();
        for($i=0; $i<count($list); $i++){
            $row = $list[$i];
            $a = array("idx"=>$row[idx], "title"=>$row[tit_c], "publish"=>$row[publish]);
            array_push($arrPoint, $a);              
        }

        echo json_encode(array("result"=>1, "exhibitionlists"=>$arrPoint));
    break;
    case "AddExhibition":
        $idx = GetUserIdx($json_data->nickname);
        if($idx)
        {
            $idx = $dbcon->db_escape_string($idx);
            $json_data->exhibition_id = $dbcon->db_escape_string($json_data->exhibition_id);
            $cnt = $dbcon->getOneCol("select count(*) from nnibr_play where user_id='".$idx."' and exhibition_id=".$json_data->exhibition_id);
            if($cnt <= 0)
            {
                $idx = $dbcon->db_escape_string($idx);
                $json_data->exhibition_id = $dbcon->db_escape_string($json_data->exhibition_id);
                $sql = "insert into nnibr_play (user_id, exhibition_id) values (";
                $sql .= $idx.", ";
                $sql .= $json_data->exhibition_id.") ";
                $idkey = $dbcon->doQuery($sql);
            }
            echo json_encode(array("result"=>1));
        }
    break;
    case "AddAppPlayCount":
        $idx = GetUserIdx($json_data->nickname);
        if($idx)
        {
            $idx = $dbcon->db_escape_string($idx);
            $sql = "select count(*) from nnibr_appplaycnt where user_id = ".$idx." and date(timestamp) = date(now());";
            $cnt = $dbcon->getOneCol($sql);
            if(!$cnt)
            {
                $idx = $dbcon->db_escape_string($idx);
                $sql2 = "insert into nnibr_appplaycnt set user_id = ".$idx;
                $idkey = $dbcon->doQuery($sql2); 

                if(!$idkey)
                {               
                    echo json_encode(array("result"=>0));
                    exit(0);
                }
            }
            echo json_encode(array("result"=>1));
        }
    break;
    case "SetPushPublish":
        $json_data->pushmsg = $dbcon->db_escape_string($json_data->pushmsg);
        $json_data->nickname = $dbcon->db_escape_string($json_data->nickname);
        $sql = "update nnibr_user set pushmsg=".$json_data->pushmsg." where nickname = '".$json_data->nickname."';";
        $idkey = $dbcon->doQuery($sql); 

        echo json_encode(array("result"=>1));
    break;
exit(0); 
}
?>