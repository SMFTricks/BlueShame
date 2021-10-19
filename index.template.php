<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

/*	This theme is a work of SMF Tricks Team. For more information please visit
	http://www.smftricks.com/
	This theme was designed by Diego Andrés and is a free theme.
	Visit SMF Tricks for more Free Themes and Premium Themes.
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = true;
}

// The main sub template above the content.
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', !empty($txt['lang_locale']) ? ' lang="' . str_replace("_", "-", substr($txt['lang_locale'], 0, strcspn($txt['lang_locale'], "."))) . '"' : '', '>
<head>';

	// The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/fontawesome.min.css" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/buttonlistvariant.css" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/responsive.css" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	// Here comes the JavaScript bits!
	echo '
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/dropdown.js?fin20"></script>
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	echo '
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body>';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	
	echo '	
	<div class="top_bar">
		<div class="top_bar_inner"', !empty($settings['forum_width']) ? ' style="width: ' . $settings['forum_width'] . '"' : '', '>
			<div class="user_info">
				<div class="nav-bar"><div class="nav-bar-inner">
					<ul class="usermenu">';
	
				// User info....
				if ($context['user']['is_logged'])
				{		
					echo '
						<li class="dropdown">
							<span class="name">
								<img class="avatar_drop" src="', !empty($context['user']['avatar']['href']) ? $context['user']['avatar']['href'] : $settings['images_url']. '/theme/noavatar.png', '" alt="', $txt['profile'], '" />
								<span class="move_name">', $context['user']['name'], '</span>
							</span>
							<ul class="dropdown-menu">
								<li>
									<a href="'. $scripturl. '?action=profile">
										<span><i class="fa fa-user"></i>&nbsp;&nbsp;', $txt['profile'], '</span>
									</a>
								</li>
								<li>
									<a href="'. $scripturl. '?action=profile;area=account">
										<span><i class="fa fa-cog"></i>&nbsp;&nbsp;', $txt['account'], '</span>
									</a>
								</li>
								<li>
									<a href="'. $scripturl. '?action=profile;area=forumprofile">
										<span><i class="fa fa-wrench"></i>&nbsp;&nbsp;', $txt['forumprofile'], '</span>
									</a>
								</li>
								<li>
									<a href="'. $scripturl. '?action=logout;', $context['session_var'], '=', $context['session_id'], '">
										<span><i class="fa fa-sign-out-alt"></i>&nbsp;&nbsp;', $txt['logout'], '</span>
									</a>
								</li>
							</ul>
						</li>';
				}
				else
				{
					echo '
						<li class="dropdown">
							<span class="name">
								<span class="move_name">', $txt['hello_guest'], ' ', $context['user']['name'], '</span>
							</span>
							<ul class="dropdown-menu">
								<li>
									<a href="'. $scripturl. '?action=login">
										<span><i class="fa fa-sign-in-alt"></i>&nbsp;&nbsp;', $txt['login'], '</span>
									</a>
								</li>
								<li>
									<a href="'. $scripturl. '?action=register">
										<span><i class="fa fa-key"></i>&nbsp;&nbsp;', $txt['register'], '</span>
									</a>
								</li>
							</ul>
						</li>';
				}
			
		echo '
				</ul>
				</div></div>
			</div>
			<div id="quicknav">
				<ul>';
				
					if(!empty($settings['twitter_username']))
						echo '
					<li><a class="social_icon twitter" href="https://twitter.com/', $settings['twitter_username'] , '" target="_blank" rel="noopener"></a></li>';
						
					if(!empty($settings['facebook_username']))
						echo '
					<li><a class="social_icon facebook" href="https://facebook.com/', $settings['facebook_username'] , '" target="_blank" rel="noopener"></a></li>';
											
					if(!empty($settings['youtube_username']))
						echo '
					<li><a class="social_icon youtube" href="https://youtube.com/user/', $settings['youtube_username'] , '" target="_blank" rel="noopener"></a></li>';
						
						echo '
					<li><a class="social_icon rss" href="', empty($settings['rss_url']) ? '' . $scripturl . '?action=.xml;type=rss' : '' . $settings['rss_url'] . '', '" target="_blank" rel="noopener"></a></li>';
					
			echo '
				</ul>	
			</div>
		</div>
	</div>';

	echo '
	<div id="header"><div class="h_frame"', !empty($settings['forum_width']) ? ' style="width: ' . $settings['forum_width'] . '"' : '', '>
		<h1 class="forumtitle">
			<a href="', $scripturl, '">', empty($context['header_logo_url_html_safe']) ? '<img src="' . $settings['images_url'] . '/theme/logo.png" alt="' . $context['forum_name'] . '" />' : '<img src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '</a>
		</h1>
		
		<form class="sb-wrapper cfst" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
			<input type="text" name="search" value="', $txt['search'], '..." onfocus="this.value = \'\';" onblur="if(this.value==\'\') this.value=\'', $txt['search'], '...\';" />
			<button type="submit" name="submit">', $txt['search'], '</button>
			<input type="hidden" name="advanced" value="0" />';

		// Search within current topic?
		if (!empty($context['current_topic']))
			echo '
			<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
		// If we're on a certain board, limit it to this board ;).
		elseif (!empty($context['current_board']))
			echo '
			<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

	echo '
		</form>
	</div></div>';

	// The main content should go here.
	echo !empty($settings['forum_width']) ? '
<div id="wrapper" style="width: ' . $settings['forum_width'] . '">' : '', '

	', template_menu(), '
	
	', theme_linktree(), '
	
	<div id="content_section">
		<div id="main_content_section">';

	// Custom banners and shoutboxes should be placed here, after the linktree.

}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
		</div>
	</div>', !empty($settings['forum_width']) ? '
</div>' : '';

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
	<div id="footer_section"><div class="frame"', !empty($settings['forum_width']) ? ' style="width: ' . $settings['forum_width'] . '"' : '', '>
		<ul class="reset floatright" style="text-align: right;">
			<li class="copyright">', $context['forum_name'], ' &copy; ', date('Y'), '</li>
			<li class="copyright">', bs_copyright(), '</li>
		</ul>
		<ul class="reset">
			<li class="copyright">', theme_copyright(), '</li>
		</ul>';

	// Show the load time?
	if ($context['show_load_time'])
		echo '
		<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	echo '
	</div></div>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree, $scripturl;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="navigate_section">
		<ul>
			<li class="home"><a href="', $scripturl, '"><i class="fa fa-home"></i></a></li>';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo ' /';

		echo '
			</li>';
	}
	echo '
		</ul>
	</div>';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<div class="menu_responsive">
			<span class="menu_t">', $txt['st_menu'], '</span>
			<a href="#" id="pull"><i class="fa fa-navicon"></i></a>
			<ul class="menu">';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '', 'firstlevel" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						<span class="', isset($button['is_last']) ? 'last ' : '', 'firstlevel">', $button['title'], '</span>
					</a>';
		if (!empty($button['sub_buttons']))
		{
			echo '
					<ul>';

			foreach ($button['sub_buttons'] as $childbutton)
			{
				echo '
						<li>
							<a href="', $childbutton['href'], '"', isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', '>
								', $childbutton['title'], !empty($childbutton['sub_buttons']) ? '...' : '', '
							</a>';
							
				// 3rd level menus :)
				if (!empty($childbutton['sub_buttons']))
				{
					echo '
							<ul>';

					foreach ($childbutton['sub_buttons'] as $grandchildbutton)
						echo '
								<li>
									<a href="', $grandchildbutton['href'], '"', isset($grandchildbutton['target']) ? ' target="' . $grandchildbutton['target'] . '"' : '', '>
										', $grandchildbutton['title'], '
									</a>
								</li>';

					echo '
							</ul>';
				}

				echo '
						</li>';
			}
				echo '
					</ul>';
		}
		echo '
				</li>';
	}

	echo '
			</ul>
		</div>';
}

// Theme Copyright... Please DO NOT REMOVE THIS!
function bs_copyright()
{
	$BS = 'Theme by <a href="https://smftricks.com">SMF Tricks</a>';
	
	return $BS;
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>',
				implode('', $buttons), '
			</ul>
		</div>';
}

?>