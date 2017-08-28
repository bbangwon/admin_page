using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UniRx;
using System;

public class ConnectManager
{

    static string url = "http://127.0.0.1/nnibr/admcontents/cmd/";
    //static string url = "http://www.bbangwon.com/nnibr/admcontents/cmd/";

    [Serializable]
    public struct PointInfo
    {
        public string type;   //포인트를 어떻게 얻었는지?
        public string point;  //획득 포인트
        public string get_time;   //획득시간
    }

    [Serializable]
    public struct ExhibitionList
    {
        public string idx;
        public string title;
        public string publish;
    }

    [Serializable]
    public struct ConnectResult
    {
        public int result;
        public string nickname;
        public int pushmsg;
        public PointInfo[] pointinfos;
        public ExhibitionList[] exhibitionlists;

    }

    [Serializable]
    public struct ConnectParam
    {
        public string method;
        public string deviceid;
        public string nickname;
        public int pushmsg;
        public int kindpoint;
        public int exhibition_id;
    }

    static public void GetNick(Action<ConnectResult> result)  //내 닉네임 가져오기(DeviceID Base)
    {
        SendPost(JsonUtility.ToJson(new ConnectParam()
        {
            method = "GetNickname",
            deviceid = SystemInfo.deviceUniqueIdentifier
        }), result);
    }

    static public void IsExistNick(string nickName, Action<ConnectResult> result) //신규 닉네임 작성시 닉네임 존재여부 확인
    {
        SendPost(JsonUtility.ToJson(new ConnectParam()
        {
            method = "IsExistNickname",
            nickname = nickName
        }), result);
    }

    static public void CreateNick(string nickName, Action<ConnectResult> result)  //닉네임 생성/수정
    {
        SendPost(JsonUtility.ToJson(new ConnectParam()
        {
            method = "CreateNickname",
            deviceid = SystemInfo.deviceUniqueIdentifier,
            nickname = nickName
        }), result);
    }

    static public void VisitCheck(string nickName, Action<ConnectResult> result)  //방문 체크
    {
        SendPost(JsonUtility.ToJson(new ConnectParam()
        {
            method = "VisitCheck",
            nickname = nickName
        }), result);
    }

    static public void GetPointList(string nickName, Action<ConnectResult> result)  //방문 체크
    {
        SendPost(JsonUtility.ToJson(new ConnectParam()
        {
            method = "GetPointList",
            nickname = nickName
        }), result);
    }

    static public void AddPoint(string nickName, int kindPoint, Action<ConnectResult> result)
    {
        SendPost(JsonUtility.ToJson(new ConnectParam()
        {
            method = "AddPoint",
            nickname = nickName,
            kindpoint = kindPoint
            
        }), result);
    }

    static public void GetExhibitionList(Action<ConnectResult> result)
    {
        SendPost(JsonUtility.ToJson(new ConnectParam()
        {
            method = "GetExhibitionList"
        }), result);
    }

    static public void AddExhibition(string nickname, int exhibition_id, Action<ConnectResult> result)
    {
        SendPost(JsonUtility.ToJson(new ConnectParam()
        {
            method = "AddExhibition",
            nickname = nickname,
            exhibition_id = exhibition_id
        }), result);
    }

    static public void AddAppPlayCount(string nickname, Action<ConnectResult> result)
    {
        SendPost(JsonUtility.ToJson(new ConnectParam()
        {
            method = "AddAppPlayCount",
            nickname = nickname
        }), result);
    }

    static public void SetPushPublish(string nickname, int pushmsg, Action<ConnectResult> result)
    {
        SendPost(JsonUtility.ToJson(new ConnectParam()
        {
            method = "SetPushPublish",
            nickname = nickname,
            pushmsg = pushmsg
        }), result);
    }

    static void SendPost(string param_json, Action<ConnectResult> result)
    {
        Debug.Log("send : " + param_json);
        WWWForm frm = new WWWForm();
        frm.AddField("param", param_json);

        ObservableWWW.Post(url, frm)
        .Subscribe(_ =>
        {
            Debug.Log("result : " + _);
            if (result != null)
            {
                ConnectResult r = JsonUtility.FromJson<ConnectResult>(_);
                result.Invoke(r);
            }
        });
    }
}
