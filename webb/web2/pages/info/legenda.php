<?php
$objekt = new Cache(-1, 'legenda', false, false);
$objekt->SubZlozka('page');
if($objekt->File()) {
?>
<div class="pravidla_head legenda">
<div class="pravidla">U&#382;ivatelia</div>
<ul>
	<li><img border="0" align="absmiddle" src="<? echo ROOT; ?>web2/images/tool/goldkey.png" alt="Slot" title="Slot aktívny"/> - Zak&uacute;pen&yacute; SLOT</strong></li>
</ul>
<div class="pravidla">Ocenenia</div>
<ul>
	<li><img border="0" align="absmiddle" src="<? echo ROOT; ?>web2/images/tool/trophy_1.png" title="1. miesto" alt="1. miesto"/> - 1.miesto</strong></li>
	<li><img border="0" align="absmiddle" src="<? echo ROOT; ?>web2/images/tool/trophy_2.png" title="2. miesto" alt="2. miesto"/> - 2.miesto</strong></li>
	<li><img border="0" align="absmiddle" src="<? echo ROOT; ?>web2/images/tool/trophy_3.png" title="3. miesto" alt="3. miesto"/> - 3.miesto</strong></li>
	<li><img border="0" align="absmiddle" src="<? echo ROOT; ?>web2/images/tool/vip.png" alt="VIP" title="VIP aktívne"/> - Zak&uacute;pen&eacute; VIP</strong></li>
	<li><img border="0" align="absmiddle" src="<? echo ROOT; ?>web2/images/tool/goldkey.png" alt="Slot" title="Slot aktívny"/> - Zak&uacute;pen&yacute; SLOT</strong></li>
	<li><img border="0" align="absmiddle" src="<? echo ROOT; ?>web2/images/tool/korun.png" title="Korún" alt="SVK"/> - Kor&uacute;n</strong></li>
	<li><img border="0" align="absmiddle" src="<? echo ROOT; ?>web2/images/tool/zombie_body.png" title="Zombie bodov" alt="Zombie bodov"/> - Zombie bodov</strong></li>
	<li><img border="0" align="absmiddle" src="<? echo ROOT; ?>web2/images/tool/dr_body.png" title="Deathrun bodov" alt="Deathrun bodov"/> - Deathrun bodov</li>
</ul>

<div class="pravidla">Hodnosti</div>
<ul>
<?
	$query = DB::Query("SELECT group_name, group_image, group_description FROM `cstrike`.`fusion_user_groups` ORDER BY group_id");
	while( $data = $query->fetch_assoc()) {
		if($data['group_name']) {
			echo '<li><img border="0" align="absmiddle" src="', ROOT, 'images/ranks/', $data['group_image'], '" alt="', $data['group_name'], '" title="', $data['group_description'], '"/> - ', $data['group_name'], '</strong></li>';	
		}
	}
?>
</ul>

<div class="pravidla">Servery </div>
<ul>
<?
    $sql = 'SELECT img, nazov, skratka FROM `servers` ORDER BY id ASC';
    $sql = DB::Query($sql);
    while($data = $sql->fetch_assoc()) {
	    echo '<li><img height="16" width="16" align="absmiddle" "border="0" src="', ROOT, 'web2/images/server/', $data['img'], '.png" alt="', $data['nazov'], '"/> - ', $data['skratka'], '</strong></li>';
	}
?>
</ul>
<div class="pravidla">Kredity</div>
<ul>
	<li><img title="100 Kreditov" border="0" align="absmiddle" alt="20" src="<? echo ROOT; ?>web2/images/tool/med4.png"/> - 100 Kreditov</strong></li>
	<li><img title="20 Kreditov" border="0" align="absmiddle" alt="20" src="<? echo ROOT; ?>web2/images/tool/med3.png"/> - 20 Kreditov</strong></li>
	<li><img title="5 Kreditov" border="0" align="absmiddle" alt="5" src="<? echo ROOT; ?>web2/images/tool/med1.png"/> - 5 Kreditov</strong></li>
</ul>

<div class="pravidla">Ostatn&eacute; </div>
<ul>
	<li><img align="absmiddle" border="0" src="<? echo ROOT; ?>web2/images/tool/plus1.png" alt="Prida&#357;" /> - Prida&#357;</strong></li>
	<li><img border="0" align="absmiddle" src="<? echo ROOT; ?>web2/images/tool/delete.gif" title="Zmaza&#357;" alt="X"/> - Zmaza&#357;</strong></li>
	<li><img align="absmiddle" border="0" src="<? echo ROOT; ?>web2/images/tool/comments3.png" title="Koment&aacute;r" alt="Koment&aacute;r"/> - Koment&aacute;r / Hlas </strong></li>
	<li><img border="0" align="absmiddle" alt="D&aacute;tum" src="<? echo ROOT; ?>web2/images/tool/clock.png" /> - D&aacute;tum </strong></li>
</ul>

</div>

<?
}
$objekt->File();
?>