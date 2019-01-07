<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

function clean_words($mode, &$entry, &$stopword_list, &$synonym_list)
{
	static $drop_char_match =   array('^', '$', '&', '(', ')', '<', '>', '`', '\'', '"', '|', ',', '@', '_', '?', '%', '-', '~', '+', '.', '[', ']', '{', '}', ':', '\\', '/', '=', '#', '\'', ';', '!');
	static $drop_char_replace = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', '',  '',   ' ', ' ', ' ', ' ', '',  ' ', ' ', '',  ' ',  ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' , ' ', ' ', ' ', ' ',  ' ', ' ');

	$entry = ' ' . strip_tags(strtolower($entry)) . ' ';

	if ($mode == 'post')
	{
		// Replace line endings by a space
		$entry = preg_replace('/[\n\r]/is', ' ', $entry);
		// HTML entities like &nbsp;
		$entry = preg_replace('/\b&[a-z]+;\b/', ' ', $entry);
		// Remove URL's
		$entry = preg_replace('/\b[a-z0-9]+:\/\/[a-z0-9\.\-]+(\/[a-z0-9\?\.%_\-\+=&\/]+)?/', ' ', $entry);
		// Quickly remove BBcode.
		$entry = preg_replace('/\[img:[a-z0-9]{10,}\].*?\[\/img:[a-z0-9]{10,}\]/', ' ', $entry);
		$entry = preg_replace('/\[\/?url(=.*?)?\]/', ' ', $entry);
		$entry = preg_replace('/\[\/?[a-z\*=\+\-]+(\:?[0-9a-z]+)?:[a-z0-9]{10,}(\:[a-z0-9]+)?=?.*?\]/', ' ', $entry);
	}
	elseif ($mode == 'search')
	{
		$entry = str_replace(' +', ' and ', $entry);
		$entry = str_replace(' -', ' not ', $entry);
	}

	//
	// Filter out strange characters like ^, $, &, change "it's" to "its"
	//
	for($i = 0; $i < sizeof($drop_char_match); $i++)
	{
		$entry =  str_replace($drop_char_match[$i], $drop_char_replace[$i], $entry);
	}

	if ($mode == 'post')
	{
		$entry = str_replace('*', ' ', $entry);

		// 'words' that consist of <3 or >20 characters are removed.
		$entry = preg_replace('/[ ]([\S]{1,2}|[\S]{21,})[ ]/',' ', $entry);
	}

	if (!empty($stopword_list))
	{
		for ($j = 0; $j < sizeof($stopword_list); $j++)
		{
			$stopword = trim($stopword_list[$j]);

			if ($mode == 'post' || ($stopword != 'not' && $stopword != 'and' && $stopword != 'or'))
			{
				$entry = str_replace(' ' . trim($stopword) . ' ', ' ', $entry);
			}
		}
	}

	if (!empty($synonym_list))
	{
		for ($j = 0; $j < sizeof($synonym_list); $j++)
		{
			list($replace_synonym, $match_synonym) = explode(' ', trim(strtolower($synonym_list[$j])));
			if ($mode == 'post' || ($match_synonym != 'not' && $match_synonym != 'and' && $match_synonym != 'or'))
			{
				$entry = str_replace(' ' . trim($match_synonym) . ' ', ' ' . trim($replace_synonym) . ' ', $entry);
			}
		}
	}

	return $entry;
}

function split_words($entry, $mode = 'post')
{
	// If you experience problems with the new method, uncomment this block.
/*
	$rex = ($mode == 'post') ? "/\b([\w±µ-ÿ][\w±µ-ÿ']*[\w±µ-ÿ]+|[\w±µ-ÿ]+?)\b/" : '/(\*?[a-z0-9±µ-ÿ]+\*?)|\b([a-z0-9±µ-ÿ]+)\b/';
	preg_match_all($rex, $entry, $split_entries);

	return $split_entries[1];
*/
	// Trim 1+ spaces to one space and split this trimmed string into words.
	return explode(' ', trim(preg_replace('#\s+#', ' ', $entry)));
}

function add_search_words($mode, $post_id, $post_text, $post_title = '')
{
	global $db, $config, $lang;
	global $stopwords_array, $synonyms_array;

	stopwords_synonyms_init();

	$search_raw_words = array();
	$search_raw_words['text'] = split_words(clean_words('post', $post_text, $stopwords_array, $synonyms_array));
	$search_raw_words['title'] = split_words(clean_words('post', $post_title, $stopwords_array, $synonyms_array));

	@set_time_limit(0);

	$word = array();
	$word_insert_sql = array();
	while (list($word_in, $search_matches) = @each($search_raw_words))
	{
		$word_insert_sql[$word_in] = '';
		if (!empty($search_matches))
		{
			for ($i = 0; $i < sizeof($search_matches); $i++)
			{
				$search_matches[$i] = trim($search_matches[$i]);

				if($search_matches[$i] != '')
				{
					$word[] = $search_matches[$i];
					if (!strstr($word_insert_sql[$word_in], "'" . $search_matches[$i] . "'"))
					{
						$word_insert_sql[$word_in] .= ($word_insert_sql[$word_in] != "") ? ", '" . $search_matches[$i] . "'" : "'" . $search_matches[$i] . "'";
					}
				}
			}
		}
	}

	if (sizeof($word))
	{
		sort($word);

		$prev_word = '';
		$word_text_sql = '';
		$temp_word = array();
		for($i = 0; $i < sizeof($word); $i++)
		{
			if ($word[$i] != $prev_word)
			{
				$temp_word[] = $word[$i];
				$word_text_sql .= (($word_text_sql != '') ? ', ' : '') . "'" . $word[$i] . "'";
			}
			$prev_word = $word[$i];
		}
		$word = $temp_word;

		$check_words = array();
		$value_sql = '';
		$match_word = array();
		for ($i = 0; $i < sizeof($word); $i++)
		{
			$new_match = true;
			if (isset($check_words[$word[$i]]))
			{
				$new_match = false;
			}

			if ($new_match)
			{
				$value_sql .= (($value_sql != '') ? ', ' : '') . '(\'' . $word[$i] . '\', 0)';
			}
		}

		if ($value_sql != '')
		{
			$sql = "INSERT IGNORE INTO " . SEARCH_WORD_TABLE . " (word_text, word_common)
							VALUES $value_sql";
			$db->sql_query($sql);
		}
	}

	while(list($word_in, $match_sql) = @each($word_insert_sql))
	{
		$title_match = ($word_in == 'title') ? 1 : 0;

		if ($match_sql != '')
		{
			$sql = "INSERT INTO " . SEARCH_MATCH_TABLE . " (post_id, word_id, title_match)
				SELECT $post_id, word_id, $title_match
					FROM " . SEARCH_WORD_TABLE . "
					WHERE word_text IN ($match_sql)";
			$db->sql_query($sql);
		}
	}

	if ($mode == 'single')
	{
		remove_common('single', 4/10, $word);
	}

	return;
}

//
// Check if specified words are too common now
//
function remove_common($mode, $fraction, $word_id_list = array())
{
	global $db;

	$sql = "SELECT COUNT(post_id) AS total_posts
		FROM " . POSTS_TABLE;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	if ($row['total_posts'] >= 100)
	{
		$common_threshold = floor($row['total_posts'] * $fraction);

		if ($mode == 'single' && sizeof($word_id_list))
		{
			$word_id_sql = '';
			for($i = 0; $i < sizeof($word_id_list); $i++)
			{
				$word_id_sql .= (($word_id_sql != '') ? ', ' : '') . "'" . $word_id_list[$i] . "'";
			}

			$sql = "SELECT m.word_id
				FROM " . SEARCH_MATCH_TABLE . " m, " . SEARCH_WORD_TABLE . " w
				WHERE w.word_text IN ($word_id_sql)
					AND m.word_id = w.word_id
				GROUP BY m.word_id
				HAVING COUNT(m.word_id) > $common_threshold";
		}
		else
		{
			$sql = "SELECT word_id
				FROM " . SEARCH_MATCH_TABLE . "
				GROUP BY word_id
				HAVING COUNT(word_id) > $common_threshold";
		}
		$result = $db->sql_query($sql);

		$common_word_id = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$common_word_id .= (($common_word_id != '') ? ', ' : '') . $row['word_id'];
		}
		$db->sql_freeresult($result);

		if ($common_word_id != '')
		{
			$sql = "UPDATE " . SEARCH_WORD_TABLE . "
				SET word_common = " . TRUE . "
				WHERE word_id IN ($common_word_id)";
			$db->sql_query($sql);

			$sql = "DELETE FROM " . SEARCH_MATCH_TABLE . "
				WHERE word_id IN ($common_word_id)";
			$db->sql_query($sql);
		}
	}

	return;
}

function remove_search_post($post_id_sql, $remove_subject = true, $remove_message = true)
{
	global $db, $cache;

	$words_removed = false;

	$where_sql = '';
	if (!$remove_subject || !$remove_message)
	{
		$where_sql = ' AND title_match = '. (($remove_subject) ? 1 : 0);
	}

	$sql = "SELECT word_id
		FROM " . SEARCH_MATCH_TABLE . "
		WHERE post_id IN ($post_id_sql)
		$where_sql
		GROUP BY word_id";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		$word_id_sql = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$word_id_sql .= ($word_id_sql != '') ? ', ' . $row['word_id'] : $row['word_id'];
		}

		$sql = "SELECT word_id
			FROM " . SEARCH_MATCH_TABLE . "
			WHERE word_id IN ($word_id_sql)
			$where_sql
			GROUP BY word_id
			HAVING COUNT(word_id) = 1";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if ($result)
		{
			$word_id_sql = '';
			while ($row = $db->sql_fetchrow($result))
			{
				$word_id_sql .= ($word_id_sql != '') ? ', ' . $row['word_id'] : $row['word_id'];
			}

			if ($word_id_sql != '')
			{
				$sql = "DELETE FROM " . SEARCH_WORD_TABLE . "
					WHERE word_id IN ($word_id_sql)";
				$db->sql_query($sql);
				$words_removed = $db->sql_affectedrows();
			}
		}
	}

	$sql = "DELETE FROM " . SEARCH_MATCH_TABLE . "
		WHERE post_id IN ($post_id_sql) $where_sql";
	$db->sql_query($sql);

	return $words_removed;
}

/*
* Username search
*/
function username_search($search_match, $ajax_search = false)
{
	global $db, $config, $template, $images, $theme, $user, $lang;
	global $starttime, $gen_simple_header;

	$username_list = '';
	if (!empty($search_match))
	{
		$username_search = preg_replace('/\*/', '%', phpbb_clean_username($search_match));

		$sql = "SELECT username
			FROM " . USERS_TABLE . "
			WHERE LOWER(username) LIKE '" . $db->sql_escape(strtolower($username_search)) . "' AND user_id <> " . ANONYMOUS . "
			ORDER BY username";
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				$username_list .= '<option value="' . htmlspecialchars($row['username']) . '">' . htmlspecialchars($row['username']) . '</option>';
			}
			while ($row = $db->sql_fetchrow($result));
		}
		else
		{
			$username_list .= '<option>' . $lang['No_match'] . '</option>';
		}
		$db->sql_freeresult($result);
	}

	$target_form_name = preg_replace('/[^A-Za-z0-9-_]+/', '', request_var('target_form_name', 'post'));
	$target_element_name = preg_replace('/[^A-Za-z0-9-_]+/', '', request_var('target_element_name', 'username'));

	$s_hidden_fields = build_hidden_fields(array(
		'target_form_name' => $target_form_name,
		'target_element_name' => $target_element_name,
		)
	);

	$template->assign_vars(array(
		'USERNAME' => (!empty($search_match)) ? phpbb_clean_username($search_match) : '',

		'L_CLOSE_WINDOW' => $lang['Close_window'],
		'L_SEARCH_USERNAME' => $lang['FIND_USERNAME'],
		'L_UPDATE_USERNAME' => $lang['Select_username'],
		'L_SELECT' => $lang['Select'],
		'L_SEARCH' => $lang['Search'],
		'L_SEARCH_EXPLAIN' => $lang['Search_author_explain'],
		'L_CLOSE_WINDOW' => $lang['Close_window'],

		'S_TARGET_FORM_NAME' => $target_form_name,
		'S_TARGET_ELEMENT_NAME' => $target_element_name,

		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_USERNAME_OPTIONS' => $username_list,
		'S_SEARCH_ACTION' => append_sid(CMS_PAGE_SEARCH . '?mode=searchuser')
		)
	);

	if ($ajax_search = true)
	{
		if ($username_list == '')
		{
			$template->assign_var('USERNAME_LIST_VIS', 'style="display: none;"');
		}
	}
	else
	{
		if ($username_list != '')
		{
			$template->assign_block_vars('switch_select_name', array());
		}
	}

	$gen_simple_header = true;
	full_page_generation('search_username.tpl', $lang['Search'], '', '');

	return;
}

?>