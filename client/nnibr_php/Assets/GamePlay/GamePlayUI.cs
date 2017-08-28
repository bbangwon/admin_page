using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class GamePlayUI : MonoBehaviour {

    public Text UIIF_Nickname;
    public Dropdown gameplayWhy;

    public Text UIIF_Result;
    public List<int> gamePlayIdxs;

    // Use this for initialization
    void Start () {

        ConnectManager.GetNick(r =>
        {
            if (r.result == 1)
            {
                UIIF_Nickname.text = r.nickname;
                ConnectManager.VisitCheck(r.nickname, vc => { });   //방문 체크
            }
        });

        gameplayWhy.options.Clear();
        ConnectManager.GetExhibitionList(r =>
        {
            if(r.result == 1)
            {
                gamePlayIdxs.Clear();
                for (int i = 0; i < r.exhibitionlists.Length; i++)
                {
                    gameplayWhy.options.Add(new Dropdown.OptionData(r.exhibitionlists[i].title));
                    gamePlayIdxs.Add(int.Parse(r.exhibitionlists[i].idx)); //IDX저장
                }
            }
        });
    }
	
    public void OnPlayButton()
    {
        ConnectManager.AddExhibition(UIIF_Nickname.text, gamePlayIdxs[gameplayWhy.value], r =>
        {
            if(r.result == 1)
            {
                UIIF_Result.text = "성공!!";
            }
        });
    }
}
