<?php
/*
    Total Users 1.0
    http://zourbuth.com/plugins/total-users
    Copyright 2012  zourbuth.com  (email : zourbuth@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/*
 * Get roles, total user per each roles, total all user using WordPress global variable $wp_roles;
 * Get total using count_users() WordPress function that returns 'total_users' and 'avail_roles' that hides roles with empty users
 * Return the roles in array indexed by number starts from 0
 * For arrangement style, the total is added to the array */
function total_users_lite_get_roles( $total = true ) {
	global $wp_roles;
	$allroles = $wp_roles->roles;
	
	$count = count_users();
	
	// Add 0 for the role with empty users
	$roles = array();
	foreach ( $allroles as $key => $role ) {
		$roles[$key] = isset($count['avail_roles'][$key]) ? $count['avail_roles'][$key] : 0;
	}
	
	// OK there, let's add the total to the roles for further use. Never found another best way to do this.
	if ($total) $roles['total'] = $count['total_users'];
	
	return $roles;
}


/**
 * Main function to generate user backend interface
 * See $defaults for function arguments
 */
function total_users( $args ) {
	$defaults = array(
		'id' 				=> '',
		'totalLabel' 		=> __('Total Users', 'tup'),
		'hideempty' 		=> false,
		'show' 				=> array(), // Array ( [administrator] => on [editor] => on [author] => on [contributor] => on [subscriber] => on [total] => on ) 
		'roles' 			=> array(), // Array ( [0] => administrator [1] => editor [2] => author [3] => contributor [4] => subscriber [5] => total ) 
		'float' 			=> 'none',
		'total' 			=> true
	);

	// Merge the user-selected arguments with the defaults.
	$args = wp_parse_args( (array) $args, $defaults );
	
	extract( $args );

	$html  = '';
	$id = empty($id) ? '' : 'id="total-users-' .$id . '"';
	$html .= "<div $id class='total-users'>";
		global $wp_roles;
		$names = $wp_roles->get_names();
		$total = total_users_lite_get_roles();

		foreach ( $roles as $key ) {
			$name = isset($names[$key]) ? $names[$key] : $totalLabel ;
			
			if (isset($show[$key])) {
				if ( $hideempty ) {
					if ( $total[$key] > 0 ) $html .= "<p class='p$float'><span class='role'><span>$name</span>$total[$key]</span></p>";
				} else  {
					$html .= "<p class='p$float'><span class='role'><span>$name</span>$total[$key]</span></p>";
				}
			}
		}	
	$html .= '</div>';
	return $html;
} 
?>