<?php

function list_menu(){
	return [
		['icon'=>'fas fa-database','name'=>'master','display'=>'Data Master','submenu'=>[
			['icon'=>'far fa-flag','name'=>'master_idn','display'=>'Wilayah Indonesia','submenu'=>[
				['icon'=>'fas fa-ellipsis-h','name'=>'master_idn_provinsi','display'=>'Provinsi','link'=>'master/idn/provinsi'],
				['icon'=>'fas fa-ellipsis-h','name'=>'master_idn_kota','display'=>'Kota / Kabupaten','link'=>'master/idn/kota'],
				['icon'=>'fas fa-ellipsis-h','name'=>'master_idn_kecamatan','display'=>'Kecamatan','link'=>'master/idn/kecamatan'],
			]],
			['icon'=>'fas fa-flag','name'=>'master_mys','display'=>'Wilayah Malaysia','submenu'=>[
				['icon'=>'fas fa-ellipsis-h','name'=>'master_mys_negeri','display'=>'Negeri','link'=>'master/mys/negeri'],
				['icon'=>'fas fa-ellipsis-h','name'=>'master_mys_daerah','display'=>'Daerah','link'=>'master/mys/daerah'],
				['icon'=>'fas fa-ellipsis-h','name'=>'master_mys_distrik','display'=>'Distrik','link'=>'master/mys/distrik'],
			]],
			['icon'=>'fas fa-briefcase','name'=>'master_pelayanan','display'=>'Pelayanan','link'=>'master/pelayanan'],
			['icon'=>'fas fa-id-card','name'=>'master_jenis_identitas','display'=>'Jenis Identitas','link'=>'master/jenis-identitas'],
			['icon'=>'fas fa-user-tie','name'=>'master_user','display'=>'Users','link'=>'master/users'],
			['icon'=>'fas fa-users','name'=>'master_identitas','display'=>'Identitas / Person','link'=>'data-identitas'],
			['icon'=>'fas fa-users','name'=>'master_identitas_main_server','display'=>'Identitas (MAIN SERVER)','link'=>'server_master/identitas'],
		]],
	];
}

function create_menus($menu,$active_menu,$parent=""){
	$menus="";
	foreach ($menu as $key => $val) {
		if(empty($val['submenu'])){
			$menus.="<li class='nav-item'>";
				$menus.="<a class=\"nav-link ".menu_active($active_menu,[$val['name']])."\" href='".site_url($val['link'])."'>";
					$menus.=(!empty($val['icon'])?"<i class='nav-icon $val[icon]'></i>&nbsp;":"")." <p>$val[display]</p>";
				$menus.="</a>";
			$menus.="</li>";
		}else{
			$menu_names = getName_menu($val['submenu']);
			$open = in_array($active_menu, $menu_names);
			$menus.="<li class='nav-item ".(($open)?'menu-open':'')."'>";
				$menus.="<a href='#' class='nav-link'>";
					$menus.=(!empty($val['icon'])?"<i class='nav-icon $val[icon]'></i>&nbsp;":"")." <p>$val[display] ";
				$menus.='<i class="right fas fa-angle-left"></i></p>';
				$menus.="</a>";
				// $menus.="<ul class=\"nav nav-treeview \" ".(($open)?' style="display: block;"':'').">".create_menus($val['submenu'],$active_menu,$val['name'])."</ul>";
				$menus.="<ul class=\"nav nav-treeview \" ".(($open)?' style="display: block;"':'').">".create_menus($val['submenu'],$active_menu,$val['name'])."</ul>";
			$menus.="</li>";

		}
	}
	return $menus;
}

function getName_menu($menu){
	$array_name=[];
	foreach ($menu as $key1 => $val1) {
		if(empty($val1['submenu'])){
			$array_name[] = $val1['name'];
		}else{
			$names = getName_menu($val1['submenu']);
			array_push($array_name,$names);
		}
	}

	return $array_name;
}

function menu_active($curent,$menu_names, $x=""){
	return (in_array($curent,$menu_names)?' active '.$x:'');
}