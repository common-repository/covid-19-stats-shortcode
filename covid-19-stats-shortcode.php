<?php
/*
Plugin Name: COVID-19 Stats [Shortcode]
Plugin URI: https://iamjagdish.com/covid-19
Description: Display Coronavirus Stats through shortcode on Any Page or Post.
Author: Jagdish Kashyap
Version: 1.2
License: GPLv2 or later
Author URI: https://iamjagdish.com
*/

defined( 'ABSPATH') or die( 'Na na na na na...' );


class Covid19StatsShortcode
{

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'covid_19_scripts_style' ) );
		add_shortcode( 'covid-19', array( $this, 'covid_19_code_output' ) );
		add_shortcode( 'covid-19-country-stats', array( $this, 'covid_19_country_stats_code_output' ) );
	}

	public function covid_19_scripts_style() {
		wp_register_style( 'covid-19-box', plugins_url( 'assets/css/box.css', __FILE__ ), '', '', 'all' );
		wp_register_style( 'covid-19-table', plugins_url( 'assets/css/table.css', __FILE__ ), '', '', 'all' );
	}

	public function covid_19_stats_shortcode_card() {

		$url              = 'https://corona.lmao.ninja/v2/countries';
		$response         = file_get_contents( $url );
		$beautifyResponse1 = json_decode( $response, true );

		wp_enqueue_style( 'covid-19-box' );

		// Caching Response
		set_transient( 'covid_19_shortcode_countries_card', $beautifyResponse1, '86400' );
		$beautifyResponse = get_transient( 'covid_19_shortcode_countries_card' );

		$output = '';
		$output .= '<section id="covid-19">';
		$output .= '<div class="row">';
		foreach ( $beautifyResponse as $country ) {
			$output .= '<div class="col-xl-5">';
			$output .= '<div class="card">';
			$output .= '<div class="card-header" style="display: inline-flex;align-items: center;">';
			$output .= '<img src=" ' . $country['countryInfo']['flag'] . ' " style="width: 20px;height: 20px;" alt="' . $country['country'] . '"> <h2 style="margin: 0px;font-weight: 700;">' . $country['country'] . '</h2>';
			$output .= '</div>';
			$output .= '<div class="card-body">';
			$output .= '<span><strong>Cases :</strong></span><span> ' . $country['cases'] . '</span></br>';
			$output .= '<span><strong>Today Cases:</strong></span><span> ' . $country['todayCases'] . '</span></br>';
			$output .= '<span><strong>Deaths:</strong></span><span> ' . $country['deaths'] . '</span></br>';
			$output .= '<span><strong>Today Deaths:</strong></span><span> ' . $country['todayDeaths'] . '</span></br>';
			$output .= '<span><strong>Recovered:</strong></span><span> ' . $country['recovered'] . '</span></br>';
			$output .= '<span><strong>Active:</strong></span><span> ' . $country['active'] . '</span></br>';
			$output .= '<span><strong>Critical:</strong></span><span> ' . $country['critical'] . '</span></br>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
		}
		$output .= '</section>';
		echo $output;

	}
	public function covid_19_stats_shortcode_table() {

		$url              = 'https://corona.lmao.ninja/v2/countries';
		$response         = file_get_contents( $url );
		$beautifyResponse2 = json_decode( $response, true );

		wp_enqueue_style( 'covid-19-table' );

		// Caching Response
		set_transient( 'covid_19_shortcode_countries_table', $beautifyResponse2, '86400' );
		$beautifyResponse = get_transient('covid_19_shortcode_countries_table');

		$output = '';
		$output .= '<table id="covid-19" class="table table-striped">';
		$output .= '<thead>';
		$output .= '<td>Country</td>';
		$output .= '<td>Cases</td>';
		$output .= '<td>Today Cases</td>';
		$output .= '<td>Deaths</td>';
		$output .= '<td>Today Deaths</td>';
		$output .= '<td>Recovered</td>';
		$output .= '<td>Active</td>';
		$output .= '<td>Critical</td>';
		$output .= '</thead>';
		foreach ( $beautifyResponse as $country ) {
			$output .= '<tr>';
			$output .= '<td>' . $country['country'] . ' <img src="' . $country['countryInfo']['flag'] . '" style="width: 20px;height: 20px;"/></td>';
			$output .= '<td>' . $country['cases'] . '</td>';
			$output .= '<td>' . $country['todayCases'] . '</td>';
			$output .= '<td>' . $country['deaths'] . '</td>';
			$output .= '<td>' . $country['todayDeaths'] . '</td>';
			$output .= '<td>' . $country['recovered'] . '</td>';
			$output .= '<td>' . $country['active'] . '</td>';
			$output .= '<td>' . $country['critical'] . '</td>';
			$output .= '</tr>';
		}
		$output .= '</table>';
		echo $output;

	}
	public function covid_19_stats_shortcode_all() {

		$url              = 'https://corona.lmao.ninja/v2/all';
		$response         = file_get_contents( $url );
		$beautifyResponse3 = json_decode( $response, true );

		// Caching Response
		set_transient( 'covid_19_shortcode_all', $beautifyResponse3, '86400' );
		$beautifyResponse = get_transient('covid_19_shortcode_all');

		$output = '';
		$output .= '<section>';
		$output .= '<div style="text-align: center;">';
		$output .= '<span style="font-size: 50px">Coronavirus Cases</span></br>';
		$output .= '<span style="font-size: 40px;color:gray;">'. $beautifyResponse['cases'] .'</span></br>';
		$output .= '<span style="font-size: 50px">Deaths</span></br>';
		$output .= '<span style="font-size: 40px;color:black;">'. $beautifyResponse['deaths'] .'</span></br>';
		$output .= '<span style="font-size: 50px">Recovered</span></br>';
		$output .= '<span style="font-size: 40px;color:green;">'. $beautifyResponse['recovered'] .'</span></br>';
		$output .= '</div>';
		$output .= '</section>';
		echo $output;

	}


	public function covid_19_code_output( $options ) {

		$options = shortcode_atts(
			array(
				'style' => 'card',
				'sort'  => 'country',
			),
			$options,
			'covid-19'
		);

		if ( $options['sort'] == 'country' ) {

			if ( $options['style'] == 'card' ) {

				$this->covid_19_stats_shortcode_card();

			} elseif ( $options['style'] == 'table' ) {

				$this->covid_19_stats_shortcode_table();
			}


		} elseif ( $options['sort'] == 'all' ) {

			$this->covid_19_stats_shortcode_all();

		}

	}

	public function covid_19_country_stats_code_output( $options ) {

		$options = shortcode_atts(
			array(
				'sort'  => '',
			),
			$options,
			'covid-19-country-stats'
		);

		if ( ! empty( $options['sort'] ) ) {

			$countryName = $options['sort'];

			$url              = "https://corona.lmao.ninja/v2/countries/$countryName";
			$response         = file_get_contents( $url );
			$country1 = json_decode( $response, true );

			// Caching Response
			set_transient( 'covid_19_shortcode_countries', $country1, '86400' );
			$country = get_transient('covid_19_shortcode_countries');

			wp_enqueue_style( 'covid-19-box' );

			$output = '';
			$output .= '<section id="covid-19">';
			$output .= '<div class="row">';
			$output .= '<div class="col-xl-5">';
			$output .= '<div class="card">';
			$output .= '<div class="card-header" style="display: inline-flex;align-items: center;">';
			$output .= '<img src=" ' . $country['countryInfo']['flag'] . ' " style="width: 20px;height: 20px;" alt="' . $country['country'] . '"> <h2 style="margin: 0px;font-weight: 700;">' . $country['country'] . '</h2>';
			$output .= '</div>';
			$output .= '<div class="card-body">';
			$output .= '<span><strong>Cases :</strong></span><span> ' . $country['cases'] . '</span></br>';
			$output .= '<span><strong>Today Cases:</strong></span><span> ' . $country['todayCases'] . '</span></br>';
			$output .= '<span><strong>Deaths:</strong></span><span> ' . $country['deaths'] . '</span></br>';
			$output .= '<span><strong>Today Deaths:</strong></span><span> ' . $country['todayDeaths'] . '</span></br>';
			$output .= '<span><strong>Recovered:</strong></span><span> ' . $country['recovered'] . '</span></br>';
			$output .= '<span><strong>Active:</strong></span><span> ' . $country['active'] . '</span></br>';
			$output .= '<span><strong>Critical:</strong></span><span> ' . $country['critical'] . '</span></br>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</section>';
			echo $output;


		}

	}


}

new Covid19StatsShortcode();

