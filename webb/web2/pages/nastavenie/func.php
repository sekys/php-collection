<?
function je_aktivny($udaj) {	
	return ($udaj) ? '- <span style="color:green;"><strong>Akt&iacute;vny</strong></span>' : '- <span style="color:red;"><strong>Deaktivovan&yacute;</strong></span>';
}
function nastavenia_header($id, $name) {
    echo '                              
    <tr><td colspan="2" class="bb cursor paddingtop" onclick="mShowMe(\'option_', $id, '\');">', $name, '</td></tr>
    <tr>
        <td colspan="2">
        <table width="100%" id="option_', $id, '" cellspacing="0" cellpadding="0" style="display:none;">
            <tr>
                <td><br />';
}
function nastavenia_footer(){
        echo'        </td>
                </tr>
            </table>
        </td>
    </tr>';
}