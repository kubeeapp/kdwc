<?php
/**
 * This class holds a model of the conditions (rules) availible in the plugin as well as some helper methods to work with them
 *
 * @since      1.4.4
 * @author     Nick Martianov
 */

namespace KDWC\PublicFace\Models\DataRulesModel;

class DataRulesModel {
    /**
     * An array of conditions availible to triggers in the plugin and their fields in (whatever database)
     * formated as [CONDITION NAME=>[ARRAY OF RELEVANT FIELDS]]
     *
     * @since    1.4.4
     * @access   private
     * @var      array    $conditions    An array of conditions availible to triggers in the plugin
     */
    private $conditions = [
        'general' => ['trigger_type','testing-mode','freeze-mode','recurrence_option','recurrence_custom_units','recurrence_custom_value','recurrence-override','views','bounce','conversion','add_to_group','remove_from_group'],
        'AB-Testing' => ['AB-Testing', 'ab-testing-sessions','ab-testing-custom-no-sessions','number_of_views'],
        'advertising-platforms' => ['advertising_platforms','advertising_platforms_option'],
        'Cookie' => ['Cookie','cookie-input','cookie-value-input'],
        'Device' => ['user-behavior-device-mobile','user-behavior-device-tablet','user-behavior-device-desktop'],
        'url' => ['compare'],
        'UserIp' => ['ip-values','ip-input'],
        'Geolocation' => ['geolocation_data','geolocation_behaviour'],
        'PageUrl' => ['page-url-operator','page-url-compare'],
        'PageVisit' => ['page_visit_data'],
        'referrer' => ['trigger','page','chosen-common-referrers','custom','operator','compare'],
        'Time-Date' => ['Time-Date-Schedule-Selection','Date-Time-Schedule','Time-Date-Start','Time-Date-End','time-date-end-date','time-date-start-date'],
        'User-Behavior' => ['User-Behavior','user-behavior-browser-language-primary-lang','user-behavior-browser-language','user-behavior-logged','user-behavior-returning','user-behavior-retn-custom'],
        'Utm' => ['utm-type','utm-relation','utm-value'],
        'Groups' => ['group-name','user-group-relation'],
        'userRoles' => ['user-role-relationship','user-role'],
    ];

    public function __construct(){
        $this->conditions = apply_filters('kdwc_data_rules_model_filter',$this->conditions);    //For custom triggers extension
    }

    public function get_condition_fields($cond){
        if(array_key_exists($cond,$this->conditions)){
            return $this->conditions[$cond];
        }
        return false;
    }

    /**
     * Remove the unused fields for a version depending on the trigger type and return the resulting array
     *
     * @param array $source
     *
     * @return array
     */
    public function trim_version_data_rules($source){   //remove useless fields from the data rules of the version
       // if(isset($source['trigger_type']) && !empty($source['trigger_type'])){
            $type =  $source['trigger_type'];
            $allowed = (!empty($type)) ? $this->get_condition_fields($type) : [];
            $general_allowed = $this->get_condition_fields('general');
            $ret = $source;
            //if($allowed){
                foreach($ret as $conditionName => $conditionTitle){
                    if(!in_array($conditionName,$allowed) && !in_array($conditionName,$general_allowed)){
                        unset($ret[$conditionName]);
                    }
                }
           // }
            return $ret;
      //  }
       // return false;
    }

    public function get_trigger_types(){
        $ret = array_keys($this->conditions);
        $ret = array_diff($ret,['general']);
        return $ret;
    }
}