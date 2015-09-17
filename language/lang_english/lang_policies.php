<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(

	'COOKIE_POLICY' => '
	<h2>Cookie Policy</h2><br />

	<p>Like most websites, this Website uses cookies.</p><br />

	<p>Cookies are small text files stored on your computer/device by your browser. They are used for many things, such as remembering whether you have visited the site before, so that you remain logged in - or to help us work out few statistics. They contain information about the use of your computer but don\'t include personal information about you (they don\'t store your name, for instance).</p><br />

	<p><strong>This policy explains how cookies are used on this website in general.</strong></p><br />

	<p>By accessing this Website, you agree that this Cookie Policy will apply whenever you access the Website on any device.</p><br />

	<p>Any changes to this policy will be posted here. We reserve the right to vary this Cookie Policy from time to time and such changes shall become effective as soon as they are posted. Your continued use of the Website constitutes your agreement to all such changes.</p><br />

	<p>We may collect information automatically when you visit the Website, using cookies.</p><br />

	<p>The cookies allow us to identify your computer/device and find out details about your last visit.</p><br />

	<p>You can choose, below, not to allow cookies. If you do, we can\'t guarantee that your experience with the Website will be as good as if you do allow cookies. If you don\'t allow cookies, you won\'t be able to login for example.</p><br />

	<p>The information collected by cookies does not personally identify you; it includes general information about your computer settings, your connection to the Internet e.g. operating system and platform, IP address, your browsing patterns and timings of browsing on the Website and your location.</p><br />

	<p>Most internet browsers accept cookies automatically, but you can change the settings of your browser to erase cookies or prevent automatic acceptance if you prefer.</p><br />

	<p>These links explain how you can control cookies via your browser - remember that if you turn off cookies in your browser then these settings apply to all websites not just this one:</p>

	<ul>
		<li><p><strong>Internet Explorer</strong> <a href="http://support.microsoft.com/kb/278835">http://support.microsoft.com/kb/278835</a></p></li>
		<li><p><strong>Chrome:</strong> <a href="http://support.google.com/chrome/bin/answer.py?hl=en-GB&amp;answer=95647">http://support.google.com/chrome/bin/answer.py?hl=en-GB&amp;answer=95647</a></p></li>
		<li><p><strong>Safari:</strong> <a href="http://docs.info.apple.com/article.html?path=Safari/5.0/en/9277.html">http://docs.info.apple.com/article.html?path=Safari/5.0/en/9277.html</a> (or <a href="http://support.apple.com/kb/HT1677">http://support.apple.com/kb/HT1677</a> for mobile versions)</p></li>
		<li><p><strong>Firefox:</strong> <a href="http://support.mozilla.org/en-US/kb/Enabling%20and%20disabling%20cookies">http://support.mozilla.org/en-US/kb/ Enabling%20and%20disabling%20cookies</a></p></li>
		<li><p><strong>Blackberries:</strong> <a href="http://docs.blackberry.com/en/smartphone_users/deliverables/32004/Turn_off_cookies_in_the_browser_60_1072866_11.jsp">http://docs.blackberry.com/en/smartphone_users/deliverables/ 32004/Turn_off_cookies_in_the_browser_60_1072866_11.jsp</a></p></li>
		<li><p><strong>Android:</strong> <a href="http://support.google.com/mobile/bin/answer.py?hl=en&amp;answer=169022">http://support.google.com/mobile/bin/answer.py?hl=en&amp;answer=169022</a></p></li>
		<li><p><strong>Opera:</strong> <a href="http://www.opera.com/browser/tutorials/security/privacy/">http://www.opera.com/browser/tutorials/security/privacy/</a></p></li>
	</ul>

	<br /><br /><br />

	<h4>Types of cookie that may be used during your visit to the Website</h4><br />

	<p>The following types of cookie are used on this site. We don\'t list every single cookie used by name - but for each type of cookie we tell you how you can control its use.</p><br />
	<ol>
		<li><p><strong>Site Management and Personalisation cookies</strong>: used to maintain your identity or session on the Website and to recognise repeated visits to the Website. These cookies cannot be turned off individually but you could change your browser setting to refuse all cookies (see above) if you do not wish to accept them.</p></li>
		<li><p><strong>Analytics cookies</strong>: used to monitor how visitors move around the Website and how they reached it. This is used so that we can see total (not individual) figures on which types of content users enjoy most, for instance. You can opt out of these if you want: <a href="https://tools.google.com/dlpage/gaoptout">https://tools.google.com/dlpage/gaoptout</a></p></li>
		<li>
			<p><strong>Third-party service cookies</strong>: Social sharing, video and other services we offer are run by other companies. These companies may drop cookies on your computer when you use them on our site or if you are already logged in to them.</p>
			<p>Here is a list of places where you can find out more about specific services that we may use and their use of cookies:</p>
			<ul>
				<li><p><strong>Facebook</strong> data use policy: <a href="http://www.facebook.com/about/privacy/your-info-on-other">http://www.facebook.com/about/privacy/your-info-on-other</a></p></li>
				<li><p><strong>Twitter</strong> privacy policy: <a href="https://twitter.com/privacy">https://twitter.com/privacy</a></p></li>
				<li><p><strong>Google And YouTube</strong> cookie policy: <a href="http://www.google.com/intl/en/policies/privacy/faq/#toc-cookies">http://www.google.com/intl/en/policies/privacy/faq/#toc-cookies</a> (Google standard terms).</p></li>
			</ul>
		</li>
	</ol>
	',

	'PRIVACY_POLICY' => '
	<h2>Privacy Policy</h2><br />

	<p>By accessing the Website, you agree to be bound by these terms and conditions whenever you access the Website on any device. This Privacy Policy forms part of and is incorporated into our Website Terms and Conditions</p><br />

	<p>Any changes to this policy will be posted here. We reserve the right to vary this Privacy Policy from time to time and such changes shall become effective as soon as they are posted. Your continued use of the Website constitutes your agreement to all such changes.</p><br />

	<p>We are committed to protecting the personal information you give us and telling you how we use the information we gather about you.</p><br /><br />

	<h4>Why We Collect Information About You</h4><br />

	<p>We may use information about you to help us customise the Website, to remember you and to improve its usefulness to you. We may use this information to notify you about changes to the Website or products, services or promotions of ours and others (with your consent) that we think you might find of interest. It can also help us to choose articles and services we think will interest you. Information about you helps us sell space to advertisers of products and services relevant to you so that we can continue to fund the Website through advertising and you can continue to use the Website for free. We may send you administrative and promotional emails relating to the Website and updates about the Website. We may personalise your visits to the Website and develop the design and style of the Website to improve the services provided to you. We may need to contact you to about a comment you have submitted or material you have posted on the Website or in order to verify your identity from time to time.</p><br />

	<h4>What Information Do We Collect From You?</h4><br />

	<p>We may collect information from you when you fill in an online registration form for any of the services available on our Website, (e.g., chat areas, your Profile page, forums, etc.). The type of information we will collect includes, for example, your name, mailing address, email address, telephone numbers, gender, preferences and few other details.</p><br />

	<p>We collect information about your use of the Website and services it offers through cookies. For more information on cookies please see our Cookie Policy which forms part of this Privacy Policy.</p><br /><br />

	<h4>How We Ensure Privacy Is Maintained</h4><br />

	<p>We endeavour to ensure that your data is stored securely and to prevent unauthorised access. We have security measures in place to protect your information which we monitor regularly. Unfortunately, despite our measures, because of the nature of the Internet, we cannot guarantee that your information will remain at all times 100% secure. The continuing efforts of hackers to defeat even the newest of security systems means that we can never make this promise.</p><br />

	<p>Please be aware that if you disclose information on chat areas, your Profile page, comment areas, forums or other public services it may be possible for other people to use this information. We are not responsible for the disclosure of any information you post in this way.</p><br /><br />

	<h4>Disclosing Your Information And Your Consent</h4><br />

	<p>We will not make use of your personal information for direct marketing activities, or supply this information to third parties for their direct marketing activities without your consent. By direct marketing activities, we mean the communication directly to particular individuals (by e-mail, post or telephone) of any advertising or marketing material.</p><br />

	<p>If having given your consent you subsequently decide you no longer wish to receive direct marketing or information from us or our associated companies or third parties or no longer want us to pass your information to third parties, please notify us by using the &quot;Contact Us&quot; form.</p><br />

	<p>We may disclose details about use of the Website to other businesses e.g. to demonstrate patterns of use to advertisers and other business partners. The information we pass on will not include any personal information by which you may be identified.</p><br />

	<p>We endeavour to prevent unauthorised disclosures of your personal information by other people, but we are not responsible for any unauthorised disclosures or other breaches of security or for the actions of others if the information was passed to them with your authority or with the authority of anybody other than us or our associated companies.</p><br />

	<p>We may transfer, sell or assign any of the information described in this policy to third parties as a result of a sale, merger, consolidation, change of control, transfer of assets or reorganisation of our business.</p><br /><br />

	<h4>Obtaining Your Personal Information</h4><br />

	<p>If you wish to receive a copy of the personal information we hold about you, or have any other queries or concerns about the way we are collecting and using your personal information, please write to us (including full details of your request) by using the &quot;Contact Us&quot; form, and we will instruct on how to send us an official request. We may charge an administration fee (not exceeding the maximum permitted by law) in relation to fulfilling a request for access to personal information.</p><br />

	',

	)
);

?>