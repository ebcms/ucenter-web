<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use DigPHP\Framework\Config;
use DigPHP\Framework\Framework;
use DigPHP\Template\Template;
use SplPriorityQueue;

class Index extends Common
{

    public function get(
        Config $config,
        Template $template
    ) {
        $menus = new SplPriorityQueue;
        foreach (array_keys(Framework::getAppList()) as $value) {
            $tmp = $config->get('ucenter_menus@' . $value);
            if (is_array($tmp)) {
                foreach ($tmp as $value) {
                    $value = array_merge([
                        'title' => '',
                        'url' => '',
                        'icon' => '',
                        'badge' => '',
                        'priority' => 50
                    ], (array)$value);
                    if (
                        $value['title'] &&
                        $value['url'] &&
                        $value['icon']
                    ) {
                        if (strpos($value['icon'], '<svg ') === 0) {
                            $value['icon'] = 'data:image/svg+xml;base64,' . base64_encode($value['icon']);
                        }
                        $menus->insert($value, $value['priority']);
                    }
                }
            }
        }
        return $this->html($template->renderFromFile('index@ebcms/ucenter-web', [
            'menus' => iterator_to_array($menus),
        ]));
    }
}
