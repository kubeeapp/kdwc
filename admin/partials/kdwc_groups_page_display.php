<?php
$groups_service = KDWC\PublicFace\Services\GroupsService\GroupsService::get_instance();
$groups_list = $groups_service->get_groups();

function generate_version_symbol($version_number) {
    //This function appears in multiple places - move to a utility class - DRY
    $version_number += 65;
    $num_of_characters_in_abc = 26;
    $base_ascii = 64;
    $version_number = intval($version_number) - $base_ascii;

    $postfix = '';
    if ($version_number > $num_of_characters_in_abc) {
        $postfix = intval($version_number / $num_of_characters_in_abc) + 1;
        $version_number %= $num_of_characters_in_abc;
        if ($version_number == 0) {
            $version_number = $num_of_characters_in_abc;
            $postfix -= 1;
        }
    }

    $version_number += $base_ascii;
    return chr($version_number) . strval($postfix);
}
?>
<div class="wrap">
    <h2>
        <?php
        _e('Kd-Wc Dynamic Content | Audiences');
        ?>
    </h2>
    <form class="add_new_group" method="post"  action="<?php echo admin_url('admin-ajax.php'); ?>" >
        <input name="group_name" type="text" required placeholder="<?php _e('Type in a audience name', 'kd-wc');?>">
        <input type="hidden" name="kdwc_groups_action" value="add_group">
        <input type="hidden" name="action" value="kdwc_groups_req">
        <button class="button button-primary" type="submit"><?php _e('Create a New Audience', 'kd-wc'); ?></button>
    </form>
    <table id="kdwc-all-groups-table" class="widefat striped">
        <colgroup>
            <col span="1" style="width: 20%;">
            <col span="1" style="width: 60%;">
            <col span="1" style="width: 20%;">
        </colgroup>
        <thead>
        <tr>
            <th><?php _e('Audience name', 'kd-wc');?></th>
            <th><?php _e('Triggers affecting the audience', 'kd-wc');?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($groups_list) && is_array($groups_list) && !empty($groups_list)){
            foreach ($groups_list as $group){
                $occurences = '';
                foreach($groups_service->scanTriggersForGroupOccurence($group) as $occ){
                    $versionsText='';
                    if(isset($occ['versions']) && is_array($occ['versions'])){
                        foreach($occ['versions'] as $version=>$action){
                            $versionName = generate_version_symbol($version);
                            $versionsText .= "Version {$versionName} ({$action}), ";
                        }
                        $versionsText = substr($versionsText, 0, -2);
                    }
                    $link = "<a href={$occ['link']} target='_blank '>{$occ['title']}</a>";
                    $versions = "<span>{$versionsText}</span>";
                    $occurences .= $link . '<br>&nbsp;&nbsp;&nbsp;&nbsp;' . $versions .  '<br>';
                }
                $delme = admin_url('admin-ajax.php?action=kdwc_groups_req&kdwc_groups_action=remove_group&group_name=' . $group);
                echo "<tr>
                        <td> {$group}</td>
                        <td>{$occurences}</td>
                        <td><a class='delete' href='{$delme}'>Delete Audience</a></td>
                    </tr>";
            }
        }
        ?>
        <tbody>
    </table>
    <?php
        if(!isset($groups_list) || empty($groups_list))
            echo "<p style='text-align:center;font-style:italic;'>You haven't created any audiences yet</p>";
    ?>
</div>
