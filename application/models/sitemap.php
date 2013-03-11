<?php

class Sitemap {

	public static function items() {

		$menu = array(

			'primary' => array(

				'section1' => array(

					'depth' => 0,
					'forward' => __('route.section1_sublevel1'),
					'sub' => array(

						'section1_sublevel1' => array(

							'depth' => 1

						),
						'section1_sublevel2' => array(

							'depth' => 1,
							'sub' => array(

								'section1_sublevel2_subsublevel1' => array(

									'depth' => 2

								),
								'section1_sublevel2_subsublevel2' => array(

									'depth' => 2

								)

							)

						)

					)

				),

				'section2' => array(

					'depth' => 0

				),

				'section3' => array(

					'depth' => 0,
					'sub' => array(

						'section3_sublevel1' => array(

							'depth' => 1

						),

						'section3_sublevel2' => array(

							'depth' => 1

						),

						'section3_sublevel3' => array(

							'depth' => 1

						)

					)

				)

			),

			'secondary' => array(

				'home' => array(

					'depth' => 0

				),

				'util1' => array(

					'depth' => 0,
					'layout' => 'full'

				),

				'util2' => array(

					'depth' => 0,
					'layout' => 'full'

				),

				'util2' => array(

					'depth' => 0,
					'layout' => 'full'

				),

				'switch' => array(

					'depth' => 0

				)

			),

			'admin' => array(

				'users' => array(

					'depth' => 0

				),

				'sections' => array(

					'depth' => 0

				),

				'entries' => array(

					'depth' => 0

				)

			)

		);

		return $menu;

	}

}