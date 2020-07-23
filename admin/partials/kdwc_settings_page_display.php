<?php
	if ( ! defined( 'ABSPATH' ) ) exit; /* Prevent direct access */


	require_once(IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');

	use KDWC\Services\PluginSettingsService;

	$settingsServiceInstance
	    = PluginSettingsService\PluginSettingsService::get_instance();

	$pagesVisitedOption = 
		$settingsServiceInstance->pagesVisitedOption->get();
	$durationValue = $pagesVisitedOption->get_duration_value();
	$durationType = $pagesVisitedOption->get_duration_type();

	$removePluginDataOption = 
		$settingsServiceInstance->removePluginDataOption->get();

	$applyTheContentFilterOption = 
		$settingsServiceInstance->applyTheContentFilterOption->get();

	$removeAutoPTagOption = 
		$settingsServiceInstance->removeAutoPTagOption->get();

	$removePageVisitsCookie =
		$settingsServiceInstance->removePageVisitsCookie->get();

	$allowFragmentedCacheOption = 
		$settingsServiceInstance->allowFragmentedCacheOption->get();

	$allowShortcodesInTitle = 
		$settingsServiceInstance->allowShortcodesInTitle->get();

	$disableCache = 
		$settingsServiceInstance->disableCache->get();

	$ajaxAnalytics = $settingsServiceInstance->ajaxAnalytics->get();

	$disableAnalytics = $settingsServiceInstance->disableAnalytics->get();

	$userGroupLimit = $settingsServiceInstance->userGroupLimit->get();

	$groupsCookieLifespan = $settingsServiceInstance->groupsCookieLifespan->get();

	$renderTriggersViaAjax = $settingsServiceInstance->renderTriggersViaAjax->get();
?>

<div class="wrap">
	<h2>
		<?php _e('Kd-Wc Dynamic Content | Settings', 'kd-wc'); ?>
	</h2>

	<div class="kdwc-settings-page-wrapper">

		<form method="post" action="options.php" class="kdwc-settings-form">

			<table class="form-table kdwc-settings-tbl">
				<tbody>
					<tr class="kdwc-settings-title" valign="top">
						<th class="kdwc-settings-td" scope="row" valign="top">
							<?php _e('GENERAL', 'kd-wc'); ?>
						</th>
					</tr>
					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Page caching compatibility', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input
								type="checkbox"
								<?php echo ($renderTriggersViaAjax ? "CHECKED" : ""); ?>
								name="kdwc_settings_page_render_triggers_via_ajax"
								type="text"
								class="kdwc_settings_page_option"
                                value="render_triggers_via_ajax" /><i><?php _e('Check this box to set Ajax as the default way to render triggers. The dynamic content will be loaded in a separate request after the loading of the cached content.', 'kd-wc');?> <a target="_blank" href="https://www.kd-wc.com/help/documentation/ajax-loading/?utm_source=Plugin&utm_medium=settings&utm_campaign=AjaxLoading-learnMore"><?php _e('Learn more.','kd-wc') ?></a> </i>
						</td>
					</tr>
					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Allow shortcodes in titles', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input
								type="checkbox"
								<?php echo ($allowShortcodesInTitle ? "CHECKED" : ""); ?>
								name="kdwc_settings_pages_allow_shortcodes"
								type="text"
								class="kdwc_settings_page_option"
								value="allow_shortcodes" /><i><?php _e('Check this box to allow shortcode usage in pages and post titles.', 'kd-wc'); ?></i>
						</td>
					</tr>
					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="top">
							<b><?php _e('Apply “the_content” filter', 'kd-wc'); ?></b>
						</td>
						<td>
							<input
								type="checkbox"
								<?php echo ($applyTheContentFilterOption ? "CHECKED" : ""); ?>
								name="kdwc_settings_pages_apply_the_content_filter"
								type="text"
								class="kdwc_settings_page_option"
								value="apply_the_content_filter" />
							<i><?php _e('Check this box if you are using a third party content editor and encounter bugs.', 'kd-wc'); ?></i>
						</td>
					</tr>
					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Remove wrapping Paragraph Tags', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input
								type="checkbox"
								<?php echo ($removeAutoPTagOption ? "CHECKED" : ""); ?>
								name="kdwc_settings_pages_remove_auto_p_tag"
								type="text"
								class="kdwc_settings_page_option"
								value="remove_auto_p_tag" /><i><?php _e('Check this box to prevent WordPress from wrapping Kd-Wc shortcodes with &lt;p&gt; tags.', 'kd-wc'); ?></i>
						</td>
					</tr>

					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Remove data on uninstall', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline">
							<input 
								type="checkbox"
								<?php echo ($removePluginDataOption ? "CHECKED" : ""); ?>
								name="kdwc_settings_pages_remove_data_uninstall"
								type="text"
								class="kdwc_settings_page_option"
								value="remove_data_on_uninstall" />
								<i><?php _e('When unchecked, you can delete Kd-Wc while keeping all of your triggers and settings for future usage.', 'kd-wc'); ?></i>
						</td>
					</tr>
					<tr class="kdwc-settings-title" valign="top">
						<th class="kdwc-settings-td" scope="row" valign="top">
							<?php _e('ANALYTICS', 'kd-wc'); ?>
						</th>
					</tr>
					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Disable analytics', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline">
							<input
								type="checkbox"
								<?php echo ($disableAnalytics ? "CHECKED" : ""); ?>
								name="kdwc_settings_pages_analytics_disabled"
								type="text"
								class="kdwc_settings_page_option"
								value="analytics_disabled" />
							<i><?php _e('Check this box to disable statistic collection', 'kd-wc'); ?></i>
						</td>
					</tr>
					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Use Ajax for analytics calls', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline">
							<input
								type="checkbox"
								<?php echo ($ajaxAnalytics ? "CHECKED" : ""); ?>
								name="kdwc_settings_pages_analytics_with_ajax"
								type="text"
								class="kdwc_settings_page_option"
								value="analytics_with_ajax" />
							<i><?php _e('When the box is checked data collection will be performed using Ajax. Uncheck the box to perform collection during the rendering of the page.<br> Do not uncheck this box if you are using the Gutenberg editor.', 'kd-wc'); ?> <a href="https://www.kd-wc.com/help/documentation/analytics/?utm_source=Plugin&utm_medium=settings&utm_campaign=analyticsAjax-learnMore#anc_ajax-vs-rendering" target="_blank"><?php _e('Learn more', 'kd-wc');?></a></i>
						</td>
					</tr>
					<tr valign="top">
						<td  class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Reset all analytics data', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline">
							<i>
								<a class="resetAllAnalyticsCounters" href="javascript:resetAllAnalyticsDataAction();"><?php _e('Click here'); ?></a>
								<?php _e("to reset all of Kd-Wc's analytics data", 'kd-wc'); ?>
							</i>

						</td>
					</tr>
					<tr class="kdwc-settings-title" valign="top">
						<th class="kdwc-settings-td" scope="row" valign="top">
							<?php _e('PAGES VISITED CONDITION', 'kd-wc'); ?>
						</th>
					</tr>
					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Deactivate "Pages Visited" Cookie', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input
								type="checkbox"
								<?php echo ($removePageVisitsCookie ? "CHECKED" : ""); ?>
								name="kdwc_settings_pages_remove_visits_cookie"
								type="text"
								class="kdwc_settings_page_option"
								value="remove_visits_cookie" /><i><?php _e('The “Pages Visited” condition relies on this cookie. Check this box if you are not using or planning to use the condition.', 'kd-wc'); ?></i>
						</td>
					</tr>
					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('“Pages visited” tracking time', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline">
							<input
								name="kdwc_settings_pages_visited_value"
								type="text"
								class="kdwc_settings_page_option kdwc_setting_page_option_number_select"
								value="<?php echo $durationValue ?>" />
							<select name="kdwc_settings_pages_visited_type">
								<option value="minutes" <?php echo ($durationType == "minutes" ? "SELECTED" : ""); ?>>
									<?php _e('Minutes', 'kd-wc'); ?>
								</option>
								<option value="hours" <?php echo ($durationType == "hours" ? "SELECTED" : ""); ?>>
									<?php _e('Hours', 'kd-wc'); ?>
								</option>
								<option value="days" <?php echo ($durationType == "days" ? "SELECTED" : ""); ?>>
									<?php _e('Days', 'kd-wc'); ?>
								</option>
								<option value="weeks" <?php echo ($durationType == "weeks" ? "SELECTED" : ""); ?>>
									<?php _e('Weeks', 'kd-wc'); ?>
								</option>
								<option value="months" <?php echo ($durationType == "months" ? "SELECTED" : ""); ?>>
									<?php _e('Months', 'kd-wc'); ?>
								</option>
							</select>

							<i><?php _e("The lifespan of the 'Pages Visited' condition cookie. Dynamic content will be displayed if a visitor has previously visited the selected pages in this time period.", 'kd-wc'); ?></i>
						</td>
					</tr>
					<!--<tr style="display:none" valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Disable Cache On...', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input 
								type="checkbox"
								<?php echo ($disableCache ? "CHECKED" : ""); ?>
								name="kdwc_settings_pages_disable_cache"
								type="text"
								class="kdwc_settings_page_option"
								value="disable_cache" /><i><?php _e('Check this box to disable cache on...', 'kd-wc'); ?></i>
						</td>
					</tr>

					 <tr valign="baseline">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Allow fragmented cache');?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input 
								type="checkbox"
								<?php echo ($allowFragmentedCacheOption ? "CHECKED" : ""); ?>
								name="kdwc_settings_pages_allow_fragmented_cache"
								type="text"
								class="kdwc_settings_page_option"
								value="allow_fragmented_cache" /><?php _e('FILL BY ASAF'); ?>
						</td>
					</tr> -->


					<tr class="kdwc-settings-title" valign="top">
						<th class="kdwc-settings-td" scope="row" valign="top">
							<?php _e('AUDIENCES', 'kd-wc'); ?>
						</th>
					</tr>
					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Max. Audiences per user', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline">
							<input
								type="number"
								name="kdwc_settings_pages_user_group_limit"
								class="kdwc_settings_page_option"
								value="<?php echo $userGroupLimit; ?>" />
							<i><?php _e('The maximum number of audiences a user can belong to. Adding a user to an audience beyond this limit will remove them from the earliest audience they were added to.', 'kd-wc'); ?></i>
						</td>
					</tr>
					<tr valign="top">
						<td class="kdwc-settings-td" scope="row" valign="baseline">
							<b><?php _e('Audiences cookie lifespan', 'kd-wc'); ?></b>
						</td>
						<td valign="baseline">
							<input
								<?php echo ($ajaxAnalytics ? "CHECKED" : ""); ?>
								name="kdwc_settings_pages_groups_cookie_lifespan"
								type="number"
								class="kdwc_settings_page_option"
								value="<?php echo $groupsCookieLifespan; ?>" /><span> Days.</span>
							<i><?php _e('The lifespan of the cookie responsible for associating the user with Audiences. The lifespan resets every time the user is added or removed from a group.','kd-wc'); ?></i>
						</td>
					</tr>



					<tr valign="top">
						<td>
							<?php
								wp_nonce_field( 
									'kdwc_settings_nonce',
									'kdwc_settings_nonce' 
								);
							?>
							<input
								type="submit"
								class="button-primary"
								name="kdwc_settings_page_update"
								value="<?php _e('Save', 'kd-wc'); ?>"/>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>