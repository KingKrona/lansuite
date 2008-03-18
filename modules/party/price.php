<?php

include_once('modules/mastersearch2/class_mastersearch2.php');
$ms2 = new mastersearch2('party');

$ms2->query['from'] = "{$config["tables"]["party_prices"]} AS p LEFT JOIN {$config['tables']['partys']} AS party ON p.party_id = party.party_id";
$ms2->query['default_order_by'] = 'p.price_text DESC';

$ms2->config['EntriesPerPage'] = 20;

$party_list = array('' => 'Alle');
$row = $db->query("SELECT party_id, name FROM {$config['tables']['partys']}");
while($res = $db->fetch_array($row)) $party_list[$res['party_id']] = $res['name'];
$db->free_result($row);
$ms2->AddTextSearchDropDown('Party', 'p.party_id', $party_list, $party->party_id);

$ms2->AddResultField(t('Text für Eintrittspreis'), 'p.price_text');
$ms2->AddResultField(t('Preis'), 'p.price');
$ms2->AddResultField('Party', 'party.name');

if ($auth['type'] >= 2) $ms2->AddIconField('edit', 'index.php?mod=party&action=price_edit&price_id=', t('Editieren'));

if ($auth['type'] >= 3) $ms2->AddMultiSelectAction(t('Löschen'), 'index.php?mod=party&action=price_del', 1);

$ms2->PrintSearch('index.php?mod=party&action=price', 'p.price_id');

$dsp->AddSingleRow($dsp->FetchButton('index.php?mod=party&action=price_edit', 'add'));
$dsp->AddContent();

?>