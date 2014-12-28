<?
function rank_user_sql($id) {
    global $dnesok, $minulost;
    $sqltxt = "
    SELECT user_id, user_name, user_avatar, user_location, cs_meno, amxid,
        COALESCE(`hlas_0`, 0) as `hlas_0`,
        COALESCE(`hlas_1`, 0) as `hlas_1`,
        COALESCE(`hlas_2`, 0) as `hlas_2`,
        "; 
        // COALESCE(`onlinecas`, 0) as `onlinecas`,
        //  COALESCE(`akt`, 0) as `akt`,
        // `ps`.`plrid` as `plrid`,
        $sqltxt .= hlasovanie_sql_head();
        $sqltxt .= " FROM `cstrike`.`fusion_users` u 
        JOIN ( SELECT id, access FROM `phpbanlist`.`amx6_amxadmins` ) p
            on `u`.`amxid` = `p`.`id`
        ";
        $sqltxt .= hlasovanie_sql_body2('`u`.`user_id`');
        /*
        LEFT JOIN ( SELECT `plrid`, `uniqueid` FROM `psychostats`.`ps_plr`  ) ps
            on `u`.`cs_meno` = `ps`.`uniqueid` COLLATE utf8_slovak_ci                                                
        LEFT JOIN ( SELECT `plrid`, SUM(`onlineTime`) as `onlinecas`,( SUM(`onlineTime`)*5  / ".HLAS_AKTIVITA.") as `akt` FROM `psychostats`.`ps_plr_data` WHERE  `statdate` < '".$dnesok."' AND `statdate` > '".$minulost."' GROUP BY `plrid` )  `online`
            on `ps`.`plrid` = `online`.`plrid`
        */
        $sqltxt .= "                                 

        WHERE user_name LIKE '".DB::Vstup($id)."' AND amxid IS NOT NULL";
    return $sqltxt;
}
function rank_user_tr($name, $data) {
    echo '<tr>
        <td>
            <table width="100%" style="border-bottom: 1px dotted rgb(177, 177, 177);">
                <tr>
                    <td width="120px" align="left"><b>', $name, ':</b></td>
                    <td>', $data, '</td>
                </tr>
            </table>
        </td>
    </tr>';
} 
function rank_user_popis() {
echo '
<table cellspacing="0" cellpadding="0" width="100%" align="center">
    <tr>
        <td valign="top" align="center"><img alt="Kult&uacute;ra" src="', ROOT, 'web2/images/rankadmin/culture.png"/></td>
        <td align="left">
            Kult&uacute;ra<br/>
            <span class="info_gray">
                Charakterizu pr&iacute;stup k &#318;u&#271;om, re&scaron;pektovanie seba aj ostatn&yacute;ch.
            </span>
        </td>
    </tr>
    <tr>
        <td valign="top" align="center"><img alt="Objekt&iacute;vnos&#357;" src="', ROOT, 'web2/images/rankadmin/objectivism.png"/></td>
        <td align="left">
            Objekt&iacute;vnos&#357;<br/>
            <span class="info_gray">
                &Uacute;&#269;as&#357; na strane pravdy, spravodlivosti, opr&aacute;vnen&eacute; tvrdenia, objektivita, objekt&iacute;vnos&#357;.
            </span>
        </td>
    </tr>
    <tr>
        <td valign="top" align="center"><img alt="Asertivita" src="', ROOT, 'web2/images/rankadmin/assertiveness.png"/></td>
        <td align="left">
            Asertivita<br/>
            <span class="info_gray">
                Spo&#269;&iacute;va v uznan&iacute; skuto&#269;nosti, &#382;e jeden je rovnako d&ocirc;le&#382;it&yacute; ako ostatn&yacute;, obhajuje svoje vlastn&eacute; z&aacute;ujmy a z&aacute;rove&#328; re&scaron;pektuje z&aacute;ujmy inej osoby. Schopnos&#357; prija&#357; kritiku, hodnotenie a chv&aacute;lu, ovl&aacute;d&aacute;&#357; svoje em&oacute;cie, nieje manipulat&iacute;vny a zvl&aacute;da emo&#269;n&eacute; tlaky ostatn&yacute;ch.
            </span>
        </td>
    </tr>
    <tr>
        <td valign="top" align="center"><img alt="Aktivita" src="', ROOT, 'web2/images/rankadmin/activity.png"/></td>
        <td align="left">
            Aktivita<br/>
            <span class="info_gray">
                Je hodnoten&aacute; automaticky za posledn&yacute;ch 14 dn&iacute; na z&aacute;klade str&aacute;ven&eacute;ho &#269;asu na servery.
            </span>
        </td>
    </tr>
    <tr> 
        <td valign="top" align="center"><img alt="V&scaron;eobecn&yacute; rank " src="', ROOT, 'web2/images/rankadmin/general_rate.png"/></td>
        <td align="left">
            V&scaron;eobecn&yacute; rank <br/>
            <span class="info_gray">
                Je priemer s&uacute;&#269;tu hodnotenia kult&uacute;ry, objektivity, asertivity a aktivity.<br />
                Hodnotenie poskytovan&eacute; pou&#382;&iacute;vate&#318;om sa n&aacute;sob&iacute; 2x, zatia&#318; &#269;o aktivita iba 1x.
            </span>
        </td>
    </tr>
</table>
';
}
?>