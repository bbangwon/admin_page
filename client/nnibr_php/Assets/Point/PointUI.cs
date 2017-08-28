using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using System.Linq;

public class PointUI : MonoBehaviour {

    public Text UIIF_Nickname;
    public Text UIIF_Point;

    public Dropdown pointWhy;

    // Use this for initialization
    void Start() {
        pointWhy.options.Clear();
        pointWhy.options.Add(new Dropdown.OptionData("설치 - 1000p"));
        pointWhy.options.Add(new Dropdown.OptionData("종획득 - 100p"));
        pointWhy.options.Add(new Dropdown.OptionData("출석 - 10p"));
        pointWhy.options.Add(new Dropdown.OptionData("먹이 - 5p"));
        pointWhy.options.Add(new Dropdown.OptionData("게임포인트 - 10p"));
        pointWhy.options.Add(new Dropdown.OptionData("레벨업 - 300p"));
        pointWhy.options.Add(new Dropdown.OptionData("모든종획득 - 1000p"));
        pointWhy.options.Add(new Dropdown.OptionData("재방문 - 500p"));

        ConnectManager.GetNick(r =>
        {          
            if (r.result == 1)
            {
                UIIF_Nickname.text = r.nickname;

                //포인트 확인
                ConnectManager.GetPointList(UIIF_Nickname.text, r2 =>
                {
                    if (r2.result == 1)
                    {
                        UIIF_Point.text = r2.pointinfos.Select(_ => int.Parse(_.point)).Sum().ToString();
                    }
                });

                ConnectManager.AddAppPlayCount(UIIF_Nickname.text, r3 => { });
            }
        });
    }

    public void PointsInfo()    //포인트 주기
    {
        ConnectManager.AddPoint(UIIF_Nickname.text, pointWhy.value, r =>
        {
            if (r.result == 1)
            {
                //포인트 확인
                ConnectManager.GetPointList(UIIF_Nickname.text, r2 =>
                {                    
                    if (r2.result == 1)
                    {
                        UIIF_Point.text = r2.pointinfos.Select(_ => int.Parse(_.point)).Sum().ToString();
                    }
                });
            }

        });
    }
	

}
