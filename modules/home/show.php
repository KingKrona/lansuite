<?php
$func->DeleteOldReadStates();

if (!$cfg['home_item_count']) $cfg['home_item_count'] = 8;

if ($auth["type"] == 1 or $auth["type"] == 2 or $auth["type"] == 3) $home_page = $cfg["home_login"];
else $home_page = $cfg["home_logout"];

switch ($home_page) {
	// Show overview
	default:
		$dsp->NewContent(t('Startseite'), t('Willkommen! Hier sehen Sie eine kleine Übersicht der wichtigsten Aktivitäten.'));

    $ModOverviews = array();
    if (in_array('news', $ActiveModules)) $ModOverviews[] = 'news';
    if (in_array('board', $ActiveModules)) $ModOverviews[] = 'board';
    if (in_array('mail', $ActiveModules) and $auth['login']) $ModOverviews[] = 'mail';
    if (in_array('server', $ActiveModules)) $ModOverviews[] = 'server';
    if (in_array('poll', $ActiveModules)) $ModOverviews[] = 'poll';
    if (in_array('bugtracker', $ActiveModules)) $ModOverviews[] = 'bugtracker';
    if (in_array('tournament2', $ActiveModules)) $ModOverviews[] = 'tournament';
    if (in_array('partylist', $ActiveModules)) $ModOverviews[] = 'partylist';
		if (in_array('stats', $ActiveModules)
      and ($party->count > 0 or $auth['type'] >= 2)
      and (in_array('troubleticket', $ActiveModules)))
      $ModOverviews[] = 'stats';

    $z = 0;
    foreach($ModOverviews as $ModOverview) {
      if ($z % 2 == 0) {
        $MainContent .= '<ul class="Line">';
        if ($z != (count($ModOverviews) - 1)) $MainContent .= '<li class="LineLeftHalf">';
        else $MainContent .= '<li class="LineRightHalf">';
      } else $MainContent .= '<li class="LineRightHalf">';
      include('modules/home/'. $ModOverview .'.inc.php');
      $smarty->assign('content', $content);
      $MainContent .= $smarty->fetch('modules/home/templates/show_item.htm');
      $MainContent .= '</li>';
      if ($z % 2 == 1) $MainContent .= '</ul>';
      $z++;
    }
    if ($z % 2 == 1) $MainContent .= '</ul>';

		if ($party->count > 1) $party->get_party_dropdown_form();
	break;

	// Show News
	case 1:
		if ($party->count > 1) $party->get_party_dropdown_form();
		include ("modules/news/show.php");
	break;
	
	// Show Logout-Text
	case 2:
		$dsp->NewContent(t('Startseite'), t('Willkommen! Zum Einloggen verwenden Sie bitte, die Login-Box auf der rechten Seite'));
		$logout_hometext = file_get_contents("ext_inc/home/logout.txt");
		$dsp->AddSingleRow($func->text2html($logout_hometext));
		$dsp->AddHRuleRow();

		$dsp->AddSingleRow(t("Die letzten News:"));
		$get_news_caption = $db->qry("SELECT newsid, caption FROM	%prefix%news ORDER BY date DESC LIMIT 3");
		$i = 1;
		while($row=$db->fetch_array($get_news_caption)) {
			$dsp->AddDoubleRow("", "<a href=\"index.php?mod=news&action=show&newsid={$row["newsid"]}\">{$row["caption"]}</a>");
			$i++;
		}
		$db->free_result($get_news_caption);

		if ($party->count > 1) $party->get_party_dropdown_form();
  break;
}
?>