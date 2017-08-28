using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class pushmsg : MonoBehaviour {

    public Text UI_Nickname;
    public Dropdown UI_Pushmsg;
    public Text UI_Result;

    // Use this for initialization
    void Start () {
        ConnectManager.GetNick(r =>
        {
            if (r.result == 1)
            {
                UI_Nickname.text = r.nickname;
                UI_Pushmsg.value = r.pushmsg;
                ConnectManager.VisitCheck(r.nickname, vc => { });   //방문 체크
            }
        });


    }

    public void OnButtonChange()
    {
        ConnectManager.SetPushPublish(UI_Nickname.text, UI_Pushmsg.value, r =>
        {
            if (r.result == 1)
            {
                UI_Result.text = "변경완료!";
            }
        });

    }

}
