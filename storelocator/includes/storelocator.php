<?php

class StoreLocator {
	
	public function __construct() {
		// Labels
		$this->labels = ["Point de vente","Points de vente"];
		// Add the store CPT. 
		add_action('init', array($this, 'register_post_type'));
		add_action('rest_api_init', array($this, 'register_rest_routes'));
	}

	public function register_post_type() {
		$labels = [
			"name" => $this->labels[1],
			"singular_name" => $this->labels[0]
		];

		$args = [
			"label" => $label[0],
			"labels" => $labels,
			'publicly_queryable' => false,
			'show_in_nav_menus' => false,
			'show_ui' => true,
			"capability_type" => "post",
			'has_archive' => false,
			'rewrite' => false,
			'supports' => ['title']
		];

		register_post_type( "store", $args );
	}

	public function register_rest_routes() {
		register_rest_route( 'storelocator/v1', '/markers', array(
			'methods' => 'GET',
			'callback' => array($this, 'stores_rest_endpoint'),
		) );
	}


	public function stores_rest_endpoint( $request_data )
	{
		global $post;
		$request_data = ($request_data['lat']) ? $request_data : ['lat' => '46.341148', 'lng' => '-72.54608'];

		$args = [
			'post_type' => 'store',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
		];
		$query = new WP_Query($args);

		$stores = [];
		
		while ($query->have_posts()) {
			$query->the_post();

			$item = ['title' => $post->post_title,'ID' => $post->ID];
			$item = array_merge(get_fields(get_the_ID()),$item);
			$item['lat'] = $item['location']['lat'];
			$item['lng'] = $item['location']['lng'];
			$item['distance'] = $this->store_distance($item['lat'],$item['lng'],$request_data['lat'],$request_data['lng']);
			$stores[] = $item;
		}

		wp_reset_postdata();

		$stores = usort($stores,function($a,$b) {
			return $a['distance'] <=> $b['distance'];
		});
		return ['status' => 'OK', 'stores' => $stores];
	}

	public function store_distance($lat1, $lng1, $lat2, $lng2, $unit = 'k') {
		$earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
		$rlo1 = deg2rad($lng1);
		$rla1 = deg2rad($lat1);
		$rlo2 = deg2rad($lng2);
		$rla2 = deg2rad($lat2);
		$dlo = ($rlo2 - $rlo1) / 2;
		$dla = ($rla2 - $rla1) / 2;
		$a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
		$d = 2 * atan2(sqrt($a), sqrt(1 - $a));
		//
		$meter = ($earth_radius * $d);
		if ($unit == 'k') {
			return $meter / 1000;
		}
		return $meter;
	}

}

new StoreLocator;