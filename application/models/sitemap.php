<?php

class Sitemap {

	public static function items() {

		$menu = array(

			'type' => array(

				'section1' => array(

					'depth' => 0,
					'title' => __('title.section1'),
					'url' => __('route.section1'),
					'forward' => __('route.section1_sublevel1'),
					'sub' => array(

						'section1_sublevel1' => array(

							'depth' => 1,
							'title' => __('title.section1_sublevel1'),
							'url' => __('route.section1_sublevel1')

						),
						'section1_sublevel2' => array(

							'depth' => 1,
							'title' => __('title.section1_sublevel2'),
							'url' => __('route.section1_sublevel2')

						)

					)

				),

				'section2' => array(

					'depth' => 0,
					'title' => __('title.section2'),
					'url' => __('route.section2')

				),

				'section3' => array(

					'depth' => 0,
					'title' => __('title.section3'),
					'url' => __('route.section3'),
					'sub' => array(

						'section3_sublevel1' => array(

							'depth' => 1,
							'title' => __('title.section3_sublevel1'),
							'url' => __('route.section3_sublevel1')

						),

						'section3_sublevel2' => array(

							'depth' => 1,
							'title' => __('title.section3_sublevel2'),
							'url' => __('route.section3_sublevel2')

						),

						'section3_sublevel3' => array(

							'depth' => 1,
							'title' => __('title.section3_sublevel3'),
							'url' => __('route.section3_sublevel3')

						)

					)

				)

			),

			'secondary' => array(

				'home' => array(

					'depth' => 0,
					'title' => __('title.home'),
					'url' => __('route.home')

				),

				'util1' => array(

					'depth' => 0,
					'title' => __('title.util1'),
					'url' => __('route.util1'),
					'layout' => 'full'

				),

				'util2' => array(

					'depth' => 0,
					'title' => __('title.util2'),
					'url' => __('route.util2'),
					'layout' => 'full'

				),

				'switch' => array(

					'depth' => 0,
					'title' => __('title.switch'),
					'url' => __('route.switch')

				)
			)

		);

		return $menu;

	}

}