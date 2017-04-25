$(function () {
	$('ul.sitemap').hide();

	$('h2.sitemap').click(function () {
		$(this).next().toggle();
		$(this).toggleClass('minimise sitemap');
	});
});

