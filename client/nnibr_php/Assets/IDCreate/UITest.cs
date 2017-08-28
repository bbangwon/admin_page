using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class UITest : MonoBehaviour {

    public Text UIIF_Nickname;
    public InputField UIIF_NickName;
    public Text UIIF_Text;

    private void Start()
    {
        ConnectManager.GetNick(r =>
        {
            if (r.result == 1)
            {
                UIIF_Nickname.text = r.nickname;
                ConnectManager.VisitCheck(r.nickname, vc => { });   //방문 체크
            }
            else
            {
                UIIF_Nickname.text = "닉네임을 생성하지 않았음";
            }
        });
    }

    public void CreateNickname()
    {
        ConnectManager.IsExistNick(UIIF_NickName.text, r => {
            if (r.result > 0)
            {
                UIIF_Text.text = "이미 존재하는 닉입니다.";
            }
            else
            {
                ConnectManager.CreateNick(UIIF_NickName.text, r2 =>
                {
                    if (r2.result == 1)
                    {
                        UIIF_Text.text = "닉을 생성/수정 했습니다.";
                        ConnectManager.GetNick(r3 =>
                        {
                            UIIF_Nickname.text = r3.nickname;
                            ConnectManager.VisitCheck(r3.nickname, vc => { });
                        });
                    }
                    else
                    {
                        UIIF_Text.text = "닉을 생성하지 못했습니다.";
                    }
                });
            }
        });
    }
}
