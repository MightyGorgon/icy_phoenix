$(function () {
	"use strict";
	var $posts, lastPostId, highestSinglePost;

	// refresh our $posts data as well as lastPostId
	function refreshViewtopicData() {
		$posts = $('[data-post-id]');
		lastPostId = +$posts.last().data('post-id');

		// can't do $posts.last, otherwise we get the separator <tr>
		var $singlePostNumber = $posts.find('.single-post-number').last();

		// substr 1 to remove #
		highestSinglePost = +$singlePostNumber.text().substr(1);
	}
	refreshViewtopicData(); // immediately invoked

	// callback when posting a new reply
	function appendNewReplies($newPage) {
		var $newPagePosts = $newPage.find('[data-post-id]');
		// the posts not currently in the page
		var $newPosts = $newPagePosts.filter(function(_, el) {
			return $(el).data('post-id') > lastPostId;
		});

		// re-number posts (before inserting wrong values)
		$newPosts.find('.single-post-number').each(function () {
			$(this).text('#' + (++highestSinglePost));
		});

		$newPosts
			.css('opacity', 0)
			.insertAfter($posts.last())

			// progressively show the new posts over 1.5s
			.animate({opacity: 1}, 1500)
		;
		refreshViewtopicData();

		return $newPosts.length;
	}

	var $error = $('#quick_reply_error');
	var $form = $('#quick_reply_form');
	var $submit = $form.find('input.mainoption');

	function submitPost(data) {
		$.post($form.attr('action'), data)
			.done(function (newPage) {
				var $newPage = $(newPage);
				var $newError = $newPage.find('.error-message');

				// if there's an error, display it
				if ($newError.length > 0) {
					$error
						.empty()
						.append($newError)
						.show();
				} else {
					// if we did post, hide the previous error, clear textbox, and show new replies
					$error.hide();
					$form.find('textarea').val('');
					appendNewReplies($newPage);
				}
			})
			.always(function () {
				// un-disable the form
				$submit.attr('disabled', false);
			})
	}

	// fetch new posts. Calls the callback "cb" with the number of new posts.
	// "cb" is optional. Will not be called if undefined.
	function fetchNewPosts(cb) {
		$.get(ajaxPostData.S_TOPIC_URL_AFTER + lastPostId, function (newPage) {
			var numNewPosts = appendNewReplies($(newPage));
			if (cb) {
				cb(numNewPosts);
			}
		});
	}

	// shows a warning saying users cross-posted
	function showNewPostWarning() {
		$error
			.html(ajaxPostData.L_WARN_NEW_POST)
			.show();
		// un-disable the form
		$submit.attr('disabled', false);
	}

	// prepare to fetch new posts every X seconds.
	var isRefreshing = false;
	var wantsToPostAfterRefresh = false;
	if (ajaxPostData.REFRESH_INTERVAL > 0) {
		setInterval(function () {
			// form is disable while refreshing
			isRefreshing = true;

			fetchNewPosts(function (numNewPosts) {
				// after we fetched new posts, we're not refreshing anymore
				isRefreshing = false;

				// if the user pressed "Submit" while we were refresh, deal with it now
				if (wantsToPostAfterRefresh) {
					wantsToPostAfterRefresh = false;

					if (numNewPosts > 0) {
						showNewPostWarning();
					} else {
						$submit.click();
					}
				}
			});
		}, ajaxPostData.REFRESH_INTERVAL < 300 ? 300 : ajaxPostData.REFRESH_INTERVAL);
	}

	// TODO preview (the AJAX mod should do it already)
	$submit.click(function () {
		// always change submit text
		setSubmitText('...');

		// prevent posting while refreshing data
		if (isRefreshing) {
			wantsToPostAfterRefresh = true;
			return false;
		}

		$submit.attr('disabled', true);
		var data = {submit: true, confirm: true, ajax: true};
		var formData = $form.serializeArray();
		for (var i = 0; i < formData.length; ++i) {
			data[formData[i].name] = formData[i].value;
		}
		delete data.post_time; // we don't want to check if there has been new replies

		// first, try to get new posts from the current topic.
		// we abort post submit if there are
		fetchNewPosts(function (numNewPosts) {
			if (numNewPosts > 0) {
				showNewPostWarning();
			} else {
				submitPost(data);
				// submitPost will re-enable the form after posting
			}
			setSubmitText();
		});

		return false;
	});

	// Changes the submit button, so that the user know something is happening.
	var originalSubmitText = $submit.val();
	function setSubmitText(text) {
		$submit.val(text || originalSubmitText);
	}
});