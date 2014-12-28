<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xml:lang="pl" lang="pl" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Hierarchia admin syst&eacute;mu</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="sk" />
	<meta name='description' content='Administrat&iacute;va ' />
	<meta name='keywords' content='GeCom::Lekos' />

	<meta name="robots" content="index,follow,all" />
	<meta name="googlebot" content="all" /> 
	<meta name="msnbot" content="all" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="Global" />
	<meta name="rating" content="General" />
	<meta http-equiv="expires" content="0" />
	<meta name="revisit-after" content="7 Days" />

	<link rel='stylesheet' type='text/css' href='/web2/web2/css/admin_team.css' />	
</head>
<?
	// Spojenie
	@$spojenie = mysql_connect("localhost", "cstrike" , "asda6mdks5dfds") or die('Databaza je nepristupna.');
	@$sql_db = DB::select_db("cstrike") or die('Databaza je nepristupna.');

function avatar($id) {
	return ( $id == " " or $id == "") ? "/themes/seky_web2/img/avatar.gif" : "http://www.cs.gecom.sk/images/avatars/".$id;
}	
?>	
<body>		
	<table cellpadding='0' cellspacing='0' id='structure_table' align='center'>
		<tr>
			<td class='gecom_struct_choose_option'>&nbsp;</td>
		</tr>
		<tr>
			<td class='gecom_struct_top'>
				<span class='gecom_struct_top_font'> Administrat&iacute;va  </span>
			</td>
		</tr>
		<tr>
			<td>
				<table align='center' cellpadding='0' cellspacing='0'>
					<tr>
						<td class='gecom_management'><span class='gecom_management_font'>Mana&#382;ment</span></td>
					</tr>
					<tr>
						<?
						
				$admin = mysql_query("SELECT user_id, user_name, user_avatar, cs_meno FROM fusion_users WHERE `user_groups` LIKE '%.31%' ");
				$cpocet = mysql_num_rows($admin);
				$pocet = ($cpocet*110)+($cpocet-1)*15;
				$rank = 0;
				
				echo	"<td style='width:".$pocet."px;'>";
				while( $data = mysql_fetch_array($admin) )
				{	
						echo "
							<div class='normal' ".( !$rank ? '' : 'style="padding-left: 15px;"')."'>
								<div class='gecom_struct_avatar'>
									<img src='".avatar($data['user_avatar'])."' alt='".$data['user_name']."' border='0' width='90' height='90' alt='' hspace='0' vspace='0' />
								</div>
								<div class='gecom_struct_membername'>
									<a href='http://www.cs.gecom.sk/profile.php?lookup=".$data['user_id']."' class='gecom_membername_font' target='_blank'>
										".$data['user_name']."
									</a>
									<br />
									<span class='gecom_steamid_font'>".htmlentities($data['cs_meno'], ENT_QUOTES)."</span>
								</div>
								<div><span class='arrow'> &darr; </span></div>
							</div>";
					$rank++;		
				}										
						?>	
						</td>
					</tr>
				</table>
				<table align='center' cellpadding='0' cellspacing='0' style='width:<? echo $cpocet*84; ?>px;'>
					<tr>
						<td class='gecom_struct_border'>&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- Oddelene kategorie -->
		<tr>
			<td>
				<div class='arrow'>&darr;</div>
				<table cellspacing="0" cellpadding="0" align="center" style="width: 67.5%;">
					<tr>
						<td class="gecom_struct_border">&nbsp;</td>
					</tr>
				</table>
				
			</td>
		</tr>
		<!-- /Oddelene kategorie -->
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td valign="top">
							<table cellspacing="0" cellpadding="0" align="center" >
								<tr>
									<td>
										<div class='arrow'>&darr;</div>
										<div class="gecom_struct_info gecom_struct_web">Webov&yacute; Team</div>
										<div class='arrow'>&darr;</div>
									</td>
								</tr>
							</table>
						<!--Web -->
						<?
							//	headadmin 		admin-skupina 		nazov
							unset($server);
							$server = 47;							
						?>
							<table align='center' cellpadding='0' cellspacing='0' style='width:52%;'>
								<tr><td class='gecom_struct_border'>&nbsp;</td></tr>
							</table>
							<table align='center' cellpadding='0' cellspacing='0' >
								<tr>
									<td>
									<?	
									$admin = mysql_query("SELECT user_id, user_name, user_avatar, cs_meno FROM fusion_users WHERE `user_groups` LIKE '%.".$server."%' ");
									while($data = mysql_fetch_array($admin))
									{
										echo "	
											<div class='sekcia_server'>
												<!-- Moderator-->	
												<div class='gecom_struct_arrow'><span class='arrow'>&darr;</span></div>

												<div class='gecom_struct_membername'>
													<span class='gecom_reg_newsman_font'>Moder&aacute;tor</span>
													<br />
													<span class='gecom_title_font'>&nbsp;</span>
												</div>	
												
												<div class='gecom_struct_avatar'>
													<img src='".avatar($data['user_avatar'])."' border='0' width='90' height='90' alt='Avatar' hspace='0' vspace='0' />
												</div>	
												
												<div class='gecom_struct_membername'>
													<a href='http://www.cs.gecom.sk/profile.php?lookup=".$data['user_id']."' class='gecom_membername_font' target='_blank'>
														".$data['user_name']."
													</a>
													<br />
													<span class='gecom_steamid_font'>".htmlentities($data['cs_meno'], ENT_QUOTES)."</span>
												</div>
												<!-- /Moderator-->	
											</div>";	
									}		
									
											echo "
											<div class='sekcia_server'>	
												<div class='gecom_struct_arrow'><span class='arrow'>&darr;</span></div>

												<div class='gecom_struct_membername'>
													<span class='gecom_reg_newsman_font'>Moder&aacute;tor</span>
													<br />
													<span class='gecom_title_font'>Vo&#318;ln&eacute; miesto</span>
												</div>	
												
												<div class='gecom_struct_avatar'>
													<img src='/images/cancel.png' border='0' width='90' height='90' alt='Avatar' hspace='0' vspace='0' />
												</div>	
												
												<div class='gecom_struct_membername'>
													<a href='http://www.cs.gecom.sk/forum/' class='gecom_membername_font' target='_blank'>
													H&#318;ad&aacute;me<br />z&aacute;ujemcu
													</a>
												</div>
											</div>";
									?>		
									</td>
								</tr>
							</table>
						<!-- /Web  -->		
						</td>
						<td width="20">&nbsp;</td>
						<td valign="top">
							<table cellspacing="0" cellpadding="0" align="center" >
								<tr>
									<td>
										<div class='arrow'>&darr;</div>
										<div class="gecom_struct_info gecom_struct_admint">Admin Team</div>
										<div class='arrow'>&darr;</div>
									</td>
								</tr>
							</table>
						<!-- Admin-->
						<?
							//	headadmin 		admin-skupina 		nazov
							unset($server);
							$server[] = array(17, 15, 'Public');
							$server[] = array(21, 37, 'D2 0nly');
							$server[] = array(23, 39, 'Zombie');
							//$server[] = array(33, 41, 'Deathrun');
							
							$pocet = count($server);
						?>
							<table align='center' cellpadding='0' cellspacing='0' style='width:76%;'>
								<tr><td class='gecom_struct_border'>&nbsp;</td></tr>
							</table>
							<table align='center' cellpadding='0' cellspacing='0' width='450'>
								<tr>
									<td>
									<?	
									for($i=0; $i < $pocet; $i++)
									{
										$admin = mysql_query("SELECT user_id, user_name, user_avatar, cs_meno FROM fusion_users WHERE `user_groups` LIKE '%.".$server[$i][0]."%' ");
										$data = mysql_fetch_array($admin);
										echo "	
											<!-- Sekcia serveru -->	
											<div class='sekcia_server'>
												<!-- Headadmin-->	
												<div class='gecom_struct_arrow'><span class='arrow'>&darr;</span></div>

												<div class='gecom_struct_membername'>
													<span class='gecom_reg_headadmin_font'>HeadAdmin</span>
													<br />
													<span class='gecom_title_font'>".$server[$i][2]."</span>
												</div>	
												
												<div class='gecom_struct_avatar'>
													<img src='".avatar($data['user_avatar'])."' border='0' width='90' height='90' alt='Avatar' hspace='0' vspace='0' />
												</div>	
												
												<div class='gecom_struct_membername'>
													<a href='http://www.cs.gecom.sk/profile.php?lookup=".$data['user_id']."' class='gecom_membername_font' target='_blank'>
														".$data['user_name']."
													</a>
													<br />
													<span class='gecom_steamid_font'>".htmlentities($data['cs_meno'], ENT_QUOTES)."</span>
												</div>
												<!-- /Headadmin-->									 									 									 									 									 									 									 									 
																																																 
												 
												<div class='normal'><span class='arrow'>&darr;</span></div>
												<div class='gecom_struct_admin'><span class='gecom_reg_cs16admin'>Admini</span></div>
												<div class='normal'><span class='arrow'>&darr;</span></div>
												";
							// Admini	
										
										$admin = mysql_query("SELECT user_id, user_name, user_avatar, cs_meno FROM fusion_users WHERE `user_groups` LIKE '%.".$server[$i][1]."%' ");
										while($data = mysql_fetch_array($admin))
										{
										echo "		
												<div class='gecom_struct_admin_space'>
													<a href='http://www.cs.gecom.sk/profile.php?lookup=".$data['user_id']."' class='gecom_adminname_font' target='_blank'>
														".$data['user_name']."
													</a>
													<br />
													<span class='gecom_steamid_font'>".htmlentities($data['cs_meno'], ENT_QUOTES)."</span>
												</div>";
										}							
										echo "
											</div>
											<!-- /Sekcia serveru -->";								
									}		
									?>
									</td>
								</tr>
							</table>
						<!-- /Admin  -->		
						</td>			
						<td width="20">&nbsp;</td>
						<td valign="top">
							<table cellspacing="0" cellpadding="0" align="center" >
								<tr>
									<td>
										<div class='arrow'>&darr;</div>
										<div class="gecom_struct_info gecom_struct_liga">Ligov&yacute; Team</div>
										<div class='arrow'>&darr;</div>
									</td>
								</tr>
							</table>
						<!--Liga -->
						<?
							//	headadmin 		admin-skupina 		nazov
							unset($server);
							$server = array(27, 29);
					
						?>
							<table align='center' cellpadding='0' cellspacing='0' style='width:1%;'>
								<tr><td class='gecom_struct_border'>&nbsp;</td></tr>
							</table>
							<table align='center' cellpadding='0' cellspacing='0' >
								<tr>
									<td>
									<?	
									$admin = mysql_query("SELECT user_id, user_name, user_avatar, cs_meno FROM fusion_users WHERE `user_groups` LIKE '%.".$server[0]."%' ");
									while($data = mysql_fetch_array($admin))
									{
										echo "	
											<div class='sekcia_server'>
												<div class='gecom_struct_arrow'><span class='arrow'>&darr;</span></div>

												<div class='gecom_struct_membername'>
													<span class='gecom_reg_liga_font'>HeadAdmin</span>
													<br />
													<span class='gecom_title_font'>Ligov&yacute;</span>
												</div>	
												
												<div class='gecom_struct_avatar'>
													<img src='".avatar($data['user_avatar'])."' border='0' width='90' height='90' alt='Avatar' hspace='0' vspace='0' />
												</div>	
												
												<div class='gecom_struct_membername'>
													<a href='http://www.cs.gecom.sk/profile.php?lookup=".$data['user_id']."' class='gecom_membername_font' target='_blank'>
														".$data['user_name']."
													</a>
													<br />
													<span class='gecom_steamid_font'>".htmlentities($data['cs_meno'], ENT_QUOTES)."</span>
												</div>
							 									 									 									 									 									 									 									 																																																 												 
												<div class='normal'><span class='arrow'>&darr;</span></div>
												<div class='gecom_struct_admin'><span class='gecom_reg_cs16admin'>Admini</span></div>
												<div class='normal'><span class='arrow'>&darr;</span></div>
												";
							// Admini	
										
										$admin = mysql_query("SELECT user_id, user_name, user_avatar, cs_meno FROM fusion_users WHERE `user_groups` LIKE '%.".$server[1]."%' ");
										while($data = mysql_fetch_array($admin))
										{
										echo "		
												<div class='gecom_struct_admin_space'>
													<a href='http://www.cs.gecom.sk/profile.php?lookup=".$data['user_id']."' class='gecom_adminname_font' target='_blank' >
														".$data['user_name']."
													</a>
													<br />
													<span class='gecom_steamid_font'>".htmlentities($data['cs_meno'], ENT_QUOTES)."</span>
												</div>";
										}							
										echo "																				
												<div class='gecom_struct_admin_space'>
													<a href='http://www.cs.gecom.sk/forum/' class='gecom_adminname_font' target='_blank' >
														Vo&#318;n&eacute; miesto
													</a>
												</div>
											
											</div>";											
									}		
									?>
									</td>
								</tr>
							</table>
						<!-- /Liga  -->		
						</td>
						<td width="20">&nbsp;</td>
						<td valign="top">
							<table cellspacing="0" cellpadding="0" align="center" >
								<tr>
									<td>
										<div class='arrow'>&darr;</div>
										<div class="gecom_struct_info gecom_struct_developer">Developer Team</div>
										<div class='arrow'>&darr;</div>
									</td>
								</tr>
							</table>
						<!--Developer -->
						<?
							//	headadmin 		admin-skupina 		nazov
							unset($server);
							$server = 49;							
						?>
							<table align='center' cellpadding='0' cellspacing='0' style='width:52%;'>
								<tr><td class='gecom_struct_border'>&nbsp;</td></tr>
							</table>
							<table align='center' cellpadding='0' cellspacing='0' width="230">
								<tr>
									<td>
									<?	
									$admin = mysql_query("SELECT user_id, user_name, user_avatar, cs_meno FROM fusion_users WHERE `user_groups` LIKE '%.".$server."%' ");
									while($data = mysql_fetch_array($admin))
									{
										echo "	
											<div class='sekcia_server'>
												<!-- Moderator-->	
												<div class='gecom_struct_arrow'><span class='arrow'>&darr;</span></div>

												<div class='gecom_struct_membername'>
													<span class='gecom_reg_developer'>Developer</span>
													<br />
													<span class='gecom_title_font'>&nbsp;</span>
												</div>	
												
												<div class='gecom_struct_avatar'>
													<img src='".avatar($data['user_avatar'])."' border='0' width='90' height='90' alt='Avatar' hspace='0' vspace='0' />
												</div>	
												
												<div class='gecom_struct_membername'>
													<a href='http://www.cs.gecom.sk/profile.php?lookup=".$data['user_id']."' class='gecom_membername_font' target='_blank'>
														".$data['user_name']."
													</a>
													<br />
													<span class='gecom_steamid_font'>".htmlentities($data['cs_meno'], ENT_QUOTES)."</span>
												</div>
												<!-- /Moderator-->	
											</div>";	
									}		
									
											echo "
											<div class='sekcia_server'>	
												<div class='gecom_struct_arrow'><span class='arrow'>&darr;</span></div>

												<div class='gecom_struct_membername'>
													<span class='gecom_reg_developer'>Developer</span>
													<br />
													<span class='gecom_title_font'>Vo&#318;ln&eacute; miesto</span>
												</div>	
												
												<div class='gecom_struct_avatar'>
													<img src='/images/cancel.png' border='0' width='90' height='90' alt='Avatar' hspace='0' vspace='0' />
												</div>	
												
												<div class='gecom_struct_membername'>
													<a href='http://www.cs.gecom.sk/forum/' class='gecom_membername_font' target='_blank'>
													H&#318;ad&aacute;me<br />z&aacute;ujemcu
													</a>
												</div>
											</div>";
									?>		
									</td>
								</tr>
							</table>
						<!-- /Web  -->		
						</td>
					</tr>
				</table>
			</td>	
		</tr>		
		<tr>
			<td style='height:20px;'> </td>
		</tr>
				<tr><td class='gecom_struct_bottom'></td></tr>
		<tr>
			<td>
				<div class='footer' style='float:left;'> <span class='gecom_struct_bottom_font'> GeCom::Lekos Administrat&iacute;va </span></div>
				<div class='footer' style='float:right;'> <span class='gecom_struct_bottom_font'> v 1.0  </span></div>
			</td>
		</tr>
	</table>
	
</body>
</html>
