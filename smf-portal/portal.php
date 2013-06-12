<?php

  require 'SSI.php';
  require 'portal-lib/simplepie.inc'; 
  require 'portal-lib/twitter.inc'; 
  require 'portal-lib/ical.inc'; 
  require 'portal-lib/helper.inc'; 

  require 'portal-lib/Portal.'.$user_info['language'].'.inc'

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link type="image/x-icon" href="<?= $settings['images_url'] ?>/favicon.ico" rel="shortcut icon">
	<link rel="stylesheet" type="text/css" href="<?= $settings['theme_url'] ?>/css/index.css?fin20" />
	<link rel="stylesheet" type="text/css" href="portal.css" />
	<script type="text/javascript" src="<?= $settings['default_theme_url'] ?>/scripts/script.js?fin20"></script>
	<script type="text/javascript" src="<?= $settings['theme_url'] ?>/scripts/theme.js?fin20"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "<?= $settings['theme_url'] ?>";
		var smf_default_theme_url = "<?= $settings['default_theme_url'] ?>";
		var smf_images_url = "<?= $settings['images_url'] ?>";
		var smf_scripturl = "index.php";
		var smf_iso_case_folding = false;
		var smf_charset = "UTF-8";
		var ajax_notification_text = "Loading...";
		var ajax_notification_cancel_text = "Cancel";

		var ppshipdomove = true;
		var ppship,ppsmove=1,ppsmovetime=200,ppshipmin=23,ppshipmax=46;
		function movePPShip() {
			if (!ppshipdomove) return;
			ppship = ppship == undefined ? document.getElementById('ppship') : ppship;
			if (ppship) {
				// imageCenter-72 is the lowest wave, -+300 from the lowest are the highest ones
				var l = ppship.offsetLeft+ppsmove, w = parseInt(ppship.offsetParent.offsetWidth/2)-52, x = Math.abs((l-w)%600);
				if (l > ppship.offsetParent.offsetWidth) l=0;
				ppship.style.left = l+'px';
				if (x > 300)
					ppship.style.top = (ppshipmin+( (ppshipmax-ppshipmin)/300*(x-300)) )+'px';
				else
					ppship.style.top = (ppshipmax-( (ppshipmax-ppshipmin)/300*(x)))+'px';
			}
			window.setTimeout(movePPShip, ppsmovetime);
		}
		addLoadEvent(movePPShip);
	// ]]></script>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="<?= pps($user_info['language']) ?> - Portal / Forum" />
	<title><?= pps($user_info['language']) ?> - Portal / Forum</title>
	<link rel="canonical" href="index.php" />
	<link rel="help" href="index.php?action=help" />
	<link rel="search" href="index.php?action=search" />
	<link rel="contents" href="index.php" />
	<link rel="alternate" type="application/rss+xml" title="<?= pps($user_info['language']) ?> - RSS" href="index.php?type=rss;action=.xml" />
</head>
<body>
<div id="wrapper" style="width: 100%">
	<div id="header">
		<div class="ppship-container"><div class="inner"><div id="ppship"></div></div></div>
		<div class="frame">
			<div id="top_section">
				<h1 class="forumtitle">
					<a href="portal.php"><img src="<?= $settings['images_url'] ?>/<?= $user_info['language'] ?>/logo.png" alt="<?= pps($user_info['language']) ?>" /></a>
					<span class="siteslogan"></span>
				</h1>
				<!--<div id="siteslogan" class="floatright"></div>-->
				<div class="small_search"><?php ssi_quickSearch(); ?></div>

			</div>
			<?php ssi_menubar(); ?>
			<div class="navigate_section">
				<ul>
					<li>
						<a href="/index.php"><span><?= $txt['pps_forum'] ?></span></a>
					</li>
					<li class="last">
						<a href="/portal.php"><span><?= $txt['pps_portal'] ?></span></a>
					</li>
				</ul>
			</div>
			<img id="upshrink" src="<?= $settings['images_url'] ?>/upshrink.png" alt="*" title="Shrink or expand the header." style="display: none;" />
			<br class="clear" />
		</div>
	</div>
	<div id="content_section"><div class="frame">
		<div id="main_content_section">
		<div id="upper_section" class="middletext">
			<div class="user">
				<script type="text/javascript" src="<?= $settings['default_theme_url'] ?>/scripts/sha1.js"></script>
                                <?php 
                                        portal_welcome(); 
                                ?><br>
                                <?php if (!empty($context['open_mod_reports']) && $context['show_open_reports']): ?>
                                        <?= '<a href="', $scripturl, '?action=moderate;area=reports">', sprintf($txt['mod_reports_waiting'], $context['open_mod_reports']), '</a><br>' ?>
                                <?php endif; ?>
                                <?php if (!$user_info['is_guest']): ?>
                                        <?php if (empty($user_info['ignoreboards'])): ?>
                                                <?= $txt['ignore_no_boards'] ?>
                                        <?php else: ?>
                                                <?= $txt['ignore_following_boards'] ?>
						<?php foreach ($user_info['ignoreboards'] as $board): ?>
                                                        <?php loadBoard(); ?>
							<a href="http://forum.piratenpartei.ch/index.php/board,<?= $board ?>.0.html">
                                                                <?= $board_info['name'].", " ?>
                                                        </a>
						<?php endforeach; ?>
                                        <?php endif; ?>
                                        <?= $txt['configure_ignore_boards'] ?>
                                        <a href="index.php?action=profile;area=ignoreboards"><?= $txt['configure_here'] ?></a><br>
                                <?php endif; ?>
			</div>
                </div>
		<script type="text/javascript"><!-- // --><![CDATA[
			var oMainHeaderToggle = new smc_Toggle({
				bToggleEnabled: true,
				bCurrentlyCollapsed: false,
				aSwappableContainers: [
					'upper_section'
				],
				aSwapImages: [
					{
						sId: 'upshrink',
						srcExpanded: smf_images_url + '/upshrink.png',
						altExpanded: 'Shrink or expand the header.',
						srcCollapsed: smf_images_url + '/upshrink2.png',
						altCollapsed: 'Shrink or expand the header.'
					}
				],
				oThemeOptions: {
					bUseThemeSettings: false,
					sOptionName: 'collapse_header',
					sSessionVar: 'd4a1a73a465e',
					sSessionId: '113a370aa1b55c5eee3b5397f0707aab'
				},
				oCookieOptions: {
					bUseCookie: true,
					sCookieName: 'upshrink'
				}
			});
		// ]]></script>

	<div id="boardindex_table">
		<div id="portalheader">
		</div>
		<div id="portalcontent;">
		<div id="portalcontent2;">
			<div id="portalleft">
                                <?php if ($user_info['is_guest']): ?>
					<div class="roundframe">
		                                <?php ssi_login($redirect_to = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'], $output_method = 'echo'); ?>
					</div>		
				<?php endif; ?>
				<div class="roundframe calendar">
					<h4 class="titlebg">
						<span class="ie6_header floatleft">
							<a href="http://www.<?= languageDomain($user_info['language']) ?>/calendar-event"><img class="icon" src="<?= $settings['images_url'] ?>/icons/calendar.gif" alt="Calendar" />
							<?= $txt['calendar'] ?></a>
						</span>
					</h4>
					<ul>
						<?php foreach (getPirateEvents() as $event): ?>
							<?= renderDaySeperator($event, &$lastday, $txt) ?>
							<?= renderEvent($event) ?>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="roundframe">
					<h4 class="titlebg">
						<span class="ie6_header floatleft">
							<a href="http://www.piratenpartei.ch/sektionen"><img class="icon" src="portal-icons/pirate.png" alt="Sections" />
							     <?= $txt['sections'] ?>
                                                        </a>
						</span>
					</h4>
					<object id="svgObj" type="image/svg+xml" data="portal-icons/sections.svg" width="180" height="120">
						<!-- Workaround IE limitation, see http://joliclic.free.fr/html/object-tag/en/object-svg.html -->
						<param name="src" value="portal-icons/sections.svg"/>
						<!-- Tweak ASV using the same approach -->
						<param name="wmode" value="transparent"/>

						<!-- Try SVG Web (Flash-based SVG renderer) as a fallback -->

						<object id="flashObj" src="portal-icons/sections.svg" classid="image/svg+xml" width="180" height="120">

						<!-- Finally, give up and display plain HTML -->
							Your browser can't display SVG. Please install
							<a href="http://www.adobe.com/svg/viewer/install/">Adobe SVG Viewer</a> (plug-in for Internet Explorer versions below 9) or use
							<a href="http://www.getfirefox.com/">Firefox</a>,
							<a href="http://www.opera.com/">Opera</a>,
							<a href="http://www.apple.com/safari/download/">Safari</a> or
							<a href="http://www.google.com/chrome/">Chrome</a> instead.
						</object>
					</object>
			                <a href="http://ag.<?= languageDomain($user_info['language']) ?>">AG</a>,
			                <a href="http://ai.<?= languageDomain($user_info['language']) ?>">AI</a>,
			                <a href="http://ar.<?= languageDomain($user_info['language']) ?>">AR</a>,
			                <a href="http://be.<?= languageDomain($user_info['language']) ?>">BE</a>,
			                <a href="http://bl.<?= languageDomain($user_info['language']) ?>">BL</a>,
			                <a href="http://bs.<?= languageDomain($user_info['language']) ?>">BS</a>,
			                <a href="http://fr.<?= languageDomain($user_info['language']) ?>">FR</a>,
			                <a href="http://ge.<?= languageDomain($user_info['language']) ?>">GE</a>,
			                <a href="http://lu.<?= languageDomain($user_info['language']) ?>">LU</a>,
			                <a href="http://nw.<?= languageDomain($user_info['language']) ?>">NW</a>,
			                <a href="http://ne.<?= languageDomain($user_info['language']) ?>">NE</a>,
			                <a href="http://ow.<?= languageDomain($user_info['language']) ?>">OW</a>,
			                <a href="http://sg.<?= languageDomain($user_info['language']) ?>">SG</a>,
			                <a href="http://sh.<?= languageDomain($user_info['language']) ?>">SH</a>,
			                <a href="http://sz.<?= languageDomain($user_info['language']) ?>">SZ</a>,
			                <a href="http://tg.<?= languageDomain($user_info['language']) ?>">TG</a>,
			                <a href="http://ti.<?= languageDomain($user_info['language']) ?>">TI</a>,
			                <a href="http://ur.<?= languageDomain($user_info['language']) ?>">UR</a>,
			                <a href="http://vd.<?= languageDomain($user_info['language']) ?>">VD</a>,
			                <a href="http://vs.<?= languageDomain($user_info['language']) ?>">VS</a>,
			                <a href="http://zg.<?= languageDomain($user_info['language']) ?>">ZG</a>,
			                <a href="http://zh.<?= languageDomain($user_info['language']) ?>">ZH</a>
				</div>
				<div class="roundframe">
					<a href="http://my.<?= languageDomain($user_info['language']) ?>/">PirateAdmin</a>,
					<a href="http://chat.<?= languageDomain($user_info['language']) ?>/">Chat</a>,
					<a href="http://admin.<?= languageDomain($user_info['language']) ?>/">Admin</a>,
					<a href="http://info.<?= languageDomain($user_info['language']) ?>/">OTRS</a>,
					<a href="http://www.piratenpartei.mobi/">Electionsplattform</a>
				</div>
				<div class="roundframe">
					<h4 class="titlebg">
						<span class="ie6_header floatleft">
							<a href="index.php?action=stats"><img class="icon" src="<?= $settings['images_url'] ?>/icons/info.gif" alt="<?= $txt['forum_stats'] ?>" />
							<?= $txt['forum_stats'] ?></a>
						</span>
					</h4>
					<p>
                        	                <?php $totals = ssi_boardStats($output_method = 'array'); ?>
						<?= $txt['members']; ?>: <a href="<?= $scripturl; ?>?action=mlist"><?= comma_format($totals['members']); ?></a><br />
						<?= $txt['posts']; ?>: <?= comma_format($totals['posts']); ?><br />
						<?= $txt['topics']; ?>: <?= comma_format($totals['topics']); ?><br />
						<?= $txt['board']; ?>: <?= comma_format($totals['boards']); ?> <?= $txt['in']; ?> <?= comma_format($totals['categories']); ?>
						<?php if (!$user_info['is_guest']): ?>
							<?= $txt['totalTimeLogged1']; ?><br>
							<?= $context['user']['total_time_logged_in']['days'] ?><?= $txt['totalTimeLogged2']; ?>
							<?= $context['user']['total_time_logged_in']['hours'] ?><?= $txt['totalTimeLogged3']; ?>
							<?= $context['user']['total_time_logged_in']['minutes'] ?><?= $txt['totalTimeLogged4']; ?>
							<div class="portalavatar"><?= $context['user']['avatar']['image'] ?></div>
						<?php endif; ?>
					</p>
				</div>
				<div class="roundframe portal">
					<?php $feed = new SimplePie('http://parrot.fm/?feed=rss'); ?>
					<h4 class="titlebg">
						<span class="ie6_header floatleft">
							<a href="http://www.parrot.fm"><img class="icon" src="portal-icons/parrot.png" alt="parrot.fm" />
							<?= $feed->get_title(); ?></a>
						</span>
					</h4>
					<ul>
						<?php foreach ($feed->get_items(0, 5) as $item): ?>
							<li>
								<a href="<?= $item->get_permalink(); ?>"><?= $item->get_title(); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>		

			</div>		
			<div id="portalright">
				<div class="roundframe portal">
					<h4 class="titlebg">
						<span class="ie6_header floatleft">
							<a href="http://www.piratenpartei.ch/support"><img class="icon" src="portal-icons/pirate.png" alt="Support" />
                                                                <?= $txt['support_title'] ?>
							</a>
						</span>
					</h4>
					<ul>
						<li>
                                                        <?= $txt['support_text'] ?>
                                                </li>
					</ul>
				</div>		
				<div class="roundframe tweets">
					<h4 class="titlebg">
						<span class="ie6_header floatleft">
							<a href="http://twitter.com/<?= getTweetAccount($user_info['language']) ?>"><img class="icon" src="portal-icons/twitter.png" alt="ppsde" />
							<?= getTweetAccount($user_info['language']) ?> tweets</a>
						</span>
					</h4>
					<ul>
                        <?php $tweets =  twitterUserTimeline(getTweetAccount($user_info['language']), 4);?>
						<?php foreach ($tweets as $tweet): ?>
							<li class="tweet <?= $tweet['RetweetCountClass']; ?>">
	  							<span  title="<?php print $tweet['ScreenName'].": ".$tweet['UserDescription']; ?>" class="tweetname"><a href="http://twitter.com/<?php print $tweet['ScreenName']; ?>"><?= $tweet['ScreenName']; ?></a></span>
								<span class="tweetavatar"><span class="twabox"><a title="<?php print $tweet['Name'].": ".$tweet['UserDescription']; ?>" href="http://twitter.com/<?php print $tweet['ScreenName']; ?>"><img src="<?= $tweet['Avatar']; ?>"></a></span></span>
	  							<span class="tweettext"><?= $tweet['Content']; ?></span>
								<span class="tweettime"><?= $tweet['Ago']; ?></span>
								<span class="tweetsource">via <?= $tweet['Source']; ?></span>
								<span class="retweetcount"><?= $tweet['RetweetCount']; ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>		
				<div class="roundframe portal">
					<?php $feed = new SimplePie('http://www.'.languageDomain($user_info['language']).'/rss.xml'); ?>
					<h4 class="titlebg">
						<span class="ie6_header floatleft">
							<a href="http://www.<?= languageDomain($user_info['language']) ?>"><img class="icon" src="portal-icons/pps.png" alt="PPS" />
							<?= $feed->get_title(); ?></a>
						</span>
					</h4>
					<ul>
						<?php foreach ($feed->get_items(0, 5) as $item): ?>
							<li>
								<a href="<?= $item->get_permalink(); ?>"><?= $item->get_title(); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>		
				<div class="roundframe portal">
					<?php $projectfeed = new SimplePie('http://projects.'.languageDomain($user_info['language']).'/issues.atom'); ?>
					<h4 class="titlebg">
						<span class="ie6_header floatleft">
							<a href="http://projects.<?= languageDomain($user_info['language']) ?>"><img class="icon" src="portal-icons/work.png" alt="Projects" />
							<?= $feed->get_title(); ?></a>
						</span>
					</h4>
					<ul>
						<?php foreach ($projectfeed->get_items(0, 8) as $item): ?>
							<li>
								<a href="<?= $item->get_permalink(); ?>"><?= $item->get_title(); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>		
			</div>
			<div id="portalcenter">
                                <div class="roundframe latest">
					<?php $feed = new SimplePie('http://www.'.languageDomain($user_info['language']).'/rss.xml'); ?>
                                        <span class="titlebg">
                                                <?= $txt['latest_news'] ?>
                                        </span>
                                        <?php $item = $feed->get_item(0); ?>
					<a href="<?= $item->get_permalink(); ?>"><?= $item->get_title(); ?></a>
                                </div>


				<div class="tborder topic_table" id="unread">
				<table class="table_grid" cellspacing="0">
					<thead>
						<tr class="catbg">
							<th scope="col" class="first_th" width="8%">&nbsp;</th>
							<th scope="col">
								<?= $txt['subject'] ?>
							</th>
							<th scope="col" width="14%" align="center">
								<?= $txt['replies'] ?>
							</th>
							<th scope="col" class="smalltext last_th" width="22%">
								<?= $txt['last_post'] ?>
							</th>
						</tr>
					</thead>
					<tbody>
					<?php $topics = ssi_recentTopics($num_recent = 25, $exclude_boards = null, $include_boards = null, $output_method = 'array'); ?>
					<?php foreach($topics as $topic): ?>
						<tr class="<?= isAdminForum($topic); ?>">
							<td class=" icon2 windowbg">
								<?= getTopicIcon($topic); ?>
							</td>
							<td class="subject windowbg2">
								<div>
									<span>
										<?= $topic['link']; ?>
									</span>
									<?php if($topic['is_new']): ?>
										<a href="<?= $topic['href']; ?>"><img src="<?= $settings['images_url'] ?>/<?= $user_info['language']; ?>/new.gif" alt="Neu" /></a>
									<?php endif; ?>
									<p>
										<?= $txt['in'];?> <em><?= $topic['board']['link']; ?></em>
									</p>
								</div>
							</td>
							<td class=" stats windowbg">
								<?= $topic[replies]." ".$txt['replies']; ?>
								<br />
								<?= $topic[views]." ".$txt['views']; ?>
							</td>
							<td class=" lastpost windowbg2">
								<?= $topic[time]; ?><br>
								<?= $txt['by']." ".$topic[poster][link]; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				</div>
			</div>
			</div>
			<div id="portalcleaner">&nbsp;</div>
		</div>
		</div>
		<div id="portalfooter">
		</div>
	</div>
	<br class="clear" />
			<div class="roundframe">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<a href="http://chat.<?= languageDomain($user_info['language']) ?>/?channels=pps"><img class="icon" src="portal-icons/pirate.png" alt="Chat" /></a>
						<?= $txt['chat'] ?>
					</span>
				</h4>
				<p>
					<iframe name="chatbox" src="http://chat.<?= languageDomain($user_info['language']) ?>/?channels=pps<?= $user_info['is_guest'] ? "" : "&nick=".$user_info['name'] ?>" height="400" width="100%">Chatfenster</iframe>
				</p>
			</div>
			<div class="roundframe">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<img class="icon" src="<?= $settings['images_url'] ?>/icons/online.gif" alt="Users Online" />
						<?= $txt['users_online'] ?>
					</span>
				</h4>
				<p class="last smalltext"><?php ssi_logOnline(); ?></p>
			</div>
		</div>
	</div></div>
	<span class="lowerframe"><span></span></span>
		</div>
	</div></div>
	<div id="footer_section"><div class="frame">
		<ul class="reset">
			<li class="copyright">
			<span class="smalltext" style="display: inline; visibility: visible; font-family: Verdana, Arial, sans-serif;"><a href="index.php?action=credits" title="Simple Machines Forum" target="_blank" class="new_win">SMF 2.0.1</a> |
 <a href="http://www.simplemachines.org/about/smf/license.php" title="License" target="_blank" class="new_win">SMF &copy; 2011</a>, <a href="http://www.simplemachines.org" title="Simple Machines" target="_blank" class="new_win">Simple Machines</a>
			</span></li>
			<li><a id="button_xhtml" href="http://validator.w3.org/check?uri=referer" target="_blank" class="new_win" title="Valid XHTML 1.0!"><span>XHTML</span></a></li>
			<li><a id="button_rss" href="index.php?action=.xml;type=rss" class="new_win"><span>RSS</span></a></li>
			<li class="last"><a id="button_wap2" href="index.php?wap2" class="new_win"><span>WAP2</span></a></li>
		</ul>
	</div></div>
</div>

</body>
</html>
