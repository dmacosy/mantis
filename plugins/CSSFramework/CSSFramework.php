<?php
require_once( config_get( 'class_path' ) . 'MantisPlugin.class.php' );

class CSSFrameworkPlugin extends MantisPlugin{
    function register () {
        $this->name = 'CSS Framework';
        $this->description = 
            'This plugin provide new CSS class based on the bootstrap Twitter v2.0.4<br>
             Useful for new plugin development, this bootstrap helps you to design your pages properly.<br>
             For more information about the bootstrap, follow this link http://bootstrapdocs.com/v2.0.4/docs/base-css.html
            ';
        $this->version = '1.0';
        $this->requires = array(    
            'MantisCore' => '1.2.8',
            'jQuery' => '1.7.2',
        );
        $this->author = 'Kelson Kien';
        $this->contact = 'kelson.kien@me.com';
        $this->url = "http://kelson.lenyx.org";
    }

    function hooks( ) {
        $hooks = array(
            'EVENT_LAYOUT_RESOURCES' => 'include_ressource',
            'EVENT_LAYOUT_BODY_BEGIN' => 'navbar',
        );

        return $hooks;
    }

    function include_ressource( ) {
        $t_return = '';
        $t_return .= '<link rel="stylesheet" href="'.plugin_file('bootstrap.css').'">';
        $t_return .= '<link rel="stylesheet" href="'.plugin_file('style.css').'">';
        $t_return .= '<script type="text/javascript" src="'.plugin_file('bootstrap.js').'"></script>';
        $t_return .= '<script type="text/javascript" src="'.plugin_file('script.js').'"></script>';
		
		return $t_return;
    }	

    function navbar(){
        if( auth_is_user_authenticated() ) {
        echo '
        <div class="navbar navbar-fixed-top" style="padding : 0px;">
            <div class="navbar-inner">
                <div class="container-fluid">
                   
                    <div class="btn-group pull-right">
                ';
                $this->get_account_menu();
                echo ' 
                    </div>
                    <div class="pull-right">
                                                          ';
                            $this->get_bug_jump_input();
                echo        '  
                    </div>

                    <div class="nav-collapse">
                        <ul class="nav">
                        ';
                $this->get_navbar();
                $this->get_plugin_menu();
                echo '  
                    </div>

                </div>
            </div>
        </div>
        ';
        }
    }

    private function get_project(){
        $t_protected = current_user_get_field( 'protected' );
        $t_current_project = helper_get_current_project();

        $a_projects = project_cache_all();

        $t_menu_options = array();

        foreach ( $a_projects as $r ) {
            $t_menu_options[] = '<a type="submit" href="#">'.$r['name'].'</a>';
        }
        foreach ( $a_projects as $r ) {
            $t_menu_options[] = '<a type="submit" href="#">'.$r['name'].'</a>';
        }        foreach ( $a_projects as $r ) {
            $t_menu_options[] = '<a type="submit" href="#">'.$r['name'].'</a>';
        }        foreach ( $a_projects as $r ) {
            $t_menu_options[] = '<a type="submit" href="#">'.$r['name'].'</a>';
        }
        echo '
            <a class="btn dropdown-toggle btn-inverse" data-toggle="dropdown" href="#">
            ';
        echo user_get_realname(current_user_get_field('id'));
        echo '
                </a>
                <ul class="dropdown-menu">
            ';
        echo '<li>'.implode( $t_menu_options, ' </li> <li> ' ). '</li>';
        unset( $t_menu_options );

        # Account Page (only show accounts that are NOT protected)
        if( OFF == $t_protected ) {
            $t_menu_options[] = '<a href="' . helper_mantis_url( 'account_page.php">' ) . lang_get( 'account_link' ) . '</a>';
        }

        # Logout (no if anonymously logged in)
        if( !current_user_is_anonymous() ) {
            $t_menu_options[] = '<a href="' . helper_mantis_url( 'logout_page.php">' ) . lang_get( 'logout_link' ) . '</a>';
        }

        echo '<li class="divider"></li>';
        echo '<li>'.implode( $t_menu_options, ' </li> <li> ' ). '</li>';

        echo '</ul>';
    }

    private function get_navbar(){
        $t_protected = current_user_get_field( 'protected' );
        $t_current_project = helper_get_current_project();

        $t_menu_options = array();

        # Plugin / Event added options
        $t_event_menu_options = event_signal( 'EVENT_MENU_MAIN_FRONT' );
        foreach( $t_event_menu_options as $t_plugin => $t_plugin_menu_options ) {
            foreach( $t_plugin_menu_options as $t_callback => $t_callback_menu_options ) {
                if( is_array( $t_callback_menu_options ) ) {
                    $t_menu_options = array_merge( $t_menu_options, $t_callback_menu_options );
                } else {
                    if ( !is_null( $t_callback_menu_options ) ) {
                        $t_menu_options[] = $t_callback_menu_options;
                    }
                }
            }
        }
        # Home
//        $page = 'main_page.php';
//        $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
//        $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ). '">' . lang_get( 'main_link' ) . '</a></li>';

        # My View
        $page = 'my_view_page.php';
        $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
        $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ). '">' . lang_get( 'my_view_link' ) . '</a></li>';

        # View Bugs
        $page = 'view_all_bug_page.php';
        $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
        $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ) . '">' . lang_get( 'view_bugs_link' ) . '</a></li>';

        # Report Bugs
        if( access_has_project_level( config_get( 'report_bug_threshold' ) ) ) {
            $page = 'bug_report_page.php';
            $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
            $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ) . '">' . lang_get( 'report_issue' ) . '</a></li>';
        }

        # Project Documentation Page
        if( ON == config_get( 'enable_project_documentation' ) ) {
            $page = 'proj_doc_page.php';
            $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
            $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ) . '">' . lang_get( 'docs_link' ) . '</a></li>';
        }

        # Project Wiki
        if( config_get_global( 'wiki_enable' ) == ON ) {
            $page = 'wiki.php?type=project&amp;id=';
            $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
            $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ) . $t_current_project . '">' . lang_get( 'wiki' ) . '</a></li>';
        }


//        # Changelog Page
//        if( access_has_project_level( config_get( 'view_changelog_threshold' ) ) ) {
//            $page = 'changelog_page.php';
//            $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
//            $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ) . '">' . lang_get( 'changelog_link' ) . '</a></li>';
//        }

        # Roadmap Page
//        if( access_has_project_level( config_get( 'roadmap_view_threshold' ) ) ) {
//            $page = 'roadmap_page.php';
//            $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
//            $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ) . '">' . lang_get( 'roadmap_link' ) . '</a></li>';
//        }

//        # Summary Page
//        if( access_has_project_level( config_get( 'view_summary_threshold' ) ) ) {
//            $page = 'summary_page.php';
//            $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
//            $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ) . '">' . lang_get( 'summary_link' ) . '</a></li>';
//        }

        # News Page
        if ( news_is_enabled() && access_has_project_level( config_get( 'manage_news_threshold' ) ) ) {

            # Admin can edit news for All Projects (site-wide)
            if( ALL_PROJECTS != helper_get_current_project() || current_user_is_administrator() ) {
                $page = 'news_menu_page.php';
                $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
                $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ) . '">' . lang_get( 'edit_news_link' ) . '</a></li>';
            } else {
                $page = 'login_select_proj_page.php';
                $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
                $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ) . '">' . lang_get( 'edit_news_link' ) . '</a></li>';
            }
        }

        # Add custom options
        $t_custom_options = prepare_custom_menu_options( 'main_menu_custom_options' );
        $t_menu_options = array_merge( $t_menu_options, $t_custom_options );

        # Time Tracking / Billing
        if( config_get( 'time_tracking_enabled' ) && access_has_global_level( config_get( 'time_tracking_reporting_threshold' ) ) ) {
            $page = 'billing_page.php';
            $class = ( preg_match( '/'.str_replace('.php', '', $page).'/', $_SERVER['REQUEST_URI'] ) ) ? 'class="active"' : NULL;
            $t_menu_options[] = '<li '.$class.'><a href="' . helper_mantis_url( $page ) . '">' . lang_get( 'time_tracking_billing_link' ) . '</a></li>';
        }

#        echo '<li>'.implode( $t_menu_options, ' </li> <li> ' ). '</li>';
        echo implode( $t_menu_options, '' );

    }

    private function get_account_menu(){
        $t_protected = current_user_get_field( 'protected' );
        $t_current_project = helper_get_current_project();

#        $t_menu_options = array();

        $t_menu_options = $this->get_manage_menu();

        echo '
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="icon-user"></i> ';
        echo user_get_realname(current_user_get_field('id'));
        echo        '
                <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" style="text-align:left;">
            ';

        echo '<li><a href="'.helper_mantis_url('manage_overview_page.php').'">Manage</a></li>';

        echo '<li>'.implode( $t_menu_options, ' </li> <li> ' ). '</li>';

        if ( $t_menu_options ) {
            echo '<li class="divider"></li>';
        }
        
        unset( $t_menu_options );

        # Account Page (only show accounts that are NOT protected)
        if( OFF == $t_protected ) {
            $t_menu_options[] = '<a href="' . helper_mantis_url( 'account_page.php">' ) .'<i class="icon-user"></i> '. lang_get( 'account_link' ) . '</a>';
        }

        # Logout (no if anonymously logged in)
        if( !current_user_is_anonymous() ) {
            $t_menu_options[] = '<a href="' . helper_mantis_url( 'logout_page.php">' ) .'<i class="icon-off"></i> '. lang_get( 'logout_link' ) . '</a>';
            echo '<li>'.implode( $t_menu_options, ' </li> <li> ' ). '</li>';
        }

        
        //echo '<li>'.implode( $t_menu_options, ' </li> <li> ' ). '</li>';

        echo '</ul>';
    }


    private function get_plugin_menu(){
        $t_protected = current_user_get_field( 'protected' );
        $t_current_project = helper_get_current_project();

        $t_menu_options = array();

        # Plugin / Event added options
        $t_event_menu_options = event_signal( 'EVENT_MENU_MAIN' );
        foreach( $t_event_menu_options as $t_plugin => $t_plugin_menu_options ) {
            foreach( $t_plugin_menu_options as $t_callback => $t_callback_menu_options ) {
                if( is_array( $t_callback_menu_options ) ) {
                    $t_menu_options = array_merge( $t_menu_options, $t_callback_menu_options );
                } else {
                    if ( !is_null( $t_callback_menu_options ) ) {
                        $t_menu_options[] = $t_callback_menu_options;
                    }
                }
            }
        }

        if ( $t_menu_options ) {
            echo '
                <li class="divider-vertical"></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Plugin <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                ';
                echo '<li>'.implode( $t_menu_options, ' </li> <li> ' ). '</li>';
                echo        '  
                                </ul>
                            </li>
                        </ul>
                ';
            
        }
    }

    private function get_bug_jump_input(){

        echo '<form class="navbar" method="post" action="' . helper_mantis_url( 'jump_to_bug.php">' );
        # CSRF protection not required here - form does not result in modifications

        echo '<div class="input-append">';
        if( ON == config_get( 'use_javascript' ) ) {
            $t_bug_label = lang_get( 'issue_id' );
            echo "<input type=\"text\" name=\"bug_id\" class=\"span1\" value=\"$t_bug_label\" onfocus=\"if (this.value == '$t_bug_label') this.value = ''\" onblur=\"if (this.value == '') this.value = '$t_bug_label'\" />";
        } else {
            echo "<input type=\"text\" name=\"bug_id\" class=\"span1\" />";
        }

        echo '<button type="submit" class="btn" style="margin-left:0px;">' . lang_get( 'jump' ) . '</button>';
        echo '</div>';
        echo '</form>';
    }

    function get_manage_menu() {
        $t_manage_user_page = 'manage_user_page.php';
        $t_manage_project_menu_page = 'manage_proj_page.php';
        $t_manage_custom_field_page = 'manage_custom_field_page.php';
        $t_manage_plugin_page = 'manage_plugin_page.php';
        $t_manage_config_page = 'adm_config_report.php';
        $t_manage_prof_menu_page = 'manage_prof_menu_page.php';
        $t_manage_tags_page = 'manage_tags_page.php';

        $icon = '<i class="icon-chevron-right"></i>';

        $t_menu_options = array();

        if( access_has_global_level( config_get( 'manage_user_threshold' ) ) ) {
            $t_menu_options[] = '<a href="'. helper_mantis_url( $t_manage_user_page ).'">' .$icon.' '.lang_get( 'manage_users_link' ) .'</a>';
        }
        if( access_has_project_level( config_get( 'manage_project_threshold' ) ) ) {
            $t_menu_options[] = '<a href="'. helper_mantis_url( $t_manage_project_menu_page ).'">'.$icon.' '.lang_get( 'manage_projects_link' ) .'</a>';
        }
        if( access_has_global_level( config_get( 'tag_edit_threshold' ) ) ) {
            $t_menu_options[] = '<a href="'. helper_mantis_url( $t_manage_tags_page ).'">'.$icon.' '.lang_get( 'manage_tags_link' ) .'</a>';
        }
        if( access_has_global_level( config_get( 'manage_custom_fields_threshold' ) ) ) {
            $t_menu_options[] = '<a href="'. helper_mantis_url( $t_manage_custom_field_page ).'">'.$icon.' '.lang_get( 'manage_custom_field_link' ) .'</a>';
        }
        if( access_has_global_level( config_get( 'manage_global_profile_threshold' ) ) ) {
            $t_menu_options[] = '<a href="'. helper_mantis_url( $t_manage_prof_menu_page ).'">'.$icon.' '.lang_get( 'manage_global_profiles_link' ) .'</a>';
        }
        if( access_has_global_level( config_get( 'manage_plugin_threshold' ) ) ) {
            $t_menu_options[] = '<a href="'. helper_mantis_url( $t_manage_plugin_page ).'">'.$icon.' '.lang_get( 'manage_plugin_link' ) .'</a>';
        }
        if( access_has_project_level( config_get( 'view_configuration_threshold' ) ) ) {
            $t_menu_options[] = '<a href="'. helper_mantis_url( $t_manage_config_page ).'">'.$icon.' '.lang_get( 'manage_config_link' ) .'</a>';
        }

        # Plugin / Event added options
        $t_event_menu_options = event_signal( 'EVENT_MENU_MANAGE' );

        if( $t_menu_options ) {
            foreach( $t_event_menu_options as $t_plugin => $t_plugin_menu_options ) {
                foreach( $t_plugin_menu_options as $t_callback => $t_callback_menu_options ) {
                    if( is_array( $t_callback_menu_options ) ) {
                        $t_menu_options = array_merge( $t_menu_options, $t_callback_menu_options );
                    } else {
                        if ( !is_null( $t_callback_menu_options ) ) {
                            $t_menu_options[] = $t_callback_menu_options;
                        }
                    }
                }
            }

            // Plugins menu items
/*            foreach( $t_menu_options as $t_menu_item ) {
                print_bracket_link_prepared( $t_menu_item );
            }*/
        }
        
        return $t_menu_options;

    }

}
?>
