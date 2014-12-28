<?php

function info_parser($id, $name) {
    echo '
    <table width="100%" cellspacing="0" cellpadding="2" border="0" >
        <tr>
            <td onclick="mShowMe(\'', $id, '\');" class="cursor">
                <img border="0" id="toggle', $id, '" alt="+" src="', ROOT, 'web2/images/tool/toggle.png" /> <strong>', $name, '</strong>
            </td>
        </tr>
    </table>';
}
function kandidovat_action($id) {
    $kat = 4;
    echo '
    <tr>
        <td align="right" colspan="2" class="voteadmin_td_left">',
        	Buttons::LIKE_UP($kat, $id, 'Prija&#357;'),
        	Buttons::LIKE_DOWN($kat, $id, 'Neprija&#357;'),
            '<input name="vymazat" onlick="kdel(', $id, ')" type="submit" value="Vymaza&#357;" />
        </td>
    </tr>';
}
function info_status_log($sprava) {
    echo '    
    <tr>
        <td class="admin_status voteadmin_td_right">
            <span class="10pxfont">', $sprava, '</span>
        </td>
    </tr>';
}
