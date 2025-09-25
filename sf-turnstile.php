<?php

/**
 * Plugin Name: Smithy Factory Turnstile
 * Plugin URI: https://smithyfactory.co.uk
 * Description: Turnstile integration for Breakdance forms.
 * Author: DigiSmith
 * Author URI: https://smithyfactory.co.uk
 * License: GPLv2
 * Text Domain: sf-turnstile
 * Domain Path: /languages/
 * Version: 2.71
 */

namespace Breakdance\Forms\Actions;

if (!defined('ABSPATH')) {
    exit;
}

use function Breakdance\BreakdanceOxygen\Strings\__bdox;
use function Breakdance\Elements\control;

class SmithyTurnstile extends Action {

        public static function name() {
            return 'Smithy Turnstile';
        }

        public static function slug() {
            return 'smithy_turnstile';
        }

        public function controls() {
            return []; // No controls needed, settings handled by plugin
        }

        public function run($form, $settings, $extra) {
            if (!function_exists('cfturnstile_check')) {
                return [
                    'error' => 'Turnstile plugin not active.'
                ];
            }
            $result = cfturnstile_check();
            if (empty($result['success'])) {
                return [
                    'error' => !empty($result['error-codes']) ? implode(', ', (array)$result['error-codes']) : 'Turnstile verification failed.'
                ];
            }
            return [
                'success' => true
            ];
        }
    }

    // Register the action with Breakdance
    $provider = ActionProvider::getInstance();
    $provider->registerAction(new \Breakdance\Forms\Actions\SmithyTurnstile(), 3);

    // Inject the widget into Breakdance forms
    add_action('breakdance_form_before_footer', function() {
        if (function_exists('cfturnstile_field_show')) {
            echo do_shortcode('[simple-turnstile]');
        }
    });