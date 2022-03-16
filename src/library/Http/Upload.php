<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterWeb\Http;

use DigPHP\Framework\Config;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

class Upload extends Common
{

    public function post(
        ServerRequestInterface $request,
        Config $config
    ) {
        foreach ($request->getUploadedFiles() as $file) {
            /**
             * @var UploadedFileInterface $file
             */
            $ext = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
            $type = $file->getClientMediaType();

            if (!in_array($ext, explode(',', $config->get('upload.exts@ebcms.ucenter-web', 'jpg,png,gif')))) {
                return $this->error('不支持的上传类型！');
            }
            if (!in_array($type, explode(',', $config->get('upload.types@ebcms.ucenter-web', 'image/gif,image/jpeg,image/jpg,image/pjpeg,image/x-png,image/png')))) {
                return $this->error('不支持的上传类型！');
            }

            $filename = uniqid();
            $path = './uploads/' . date('Y/m-d');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            $file->moveTo($path . '/' . $filename . '.' . $ext);

            $script_name = '/' . implode('/', array_filter(explode('/', $_SERVER['SCRIPT_NAME'])));
            $root = strlen(dirname($script_name)) > 1 ? dirname($script_name) : '';

            return $this->success('上传成功！', '', [
                'src' => $this->getRoot() . substr($path, 1) . '/' . $filename . '.' . $ext,
                'extension' => $ext,
                'filename' => $file->getClientFilename(),
                'size' => $file->getSize(),
                'type' => $file->getClientMediaType(),
            ]);
        }
    }

    private function getRoot(): string
    {
        if (
            (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https')
            || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')
        ) {
            $schema = 'https';
        } else {
            $schema = 'http';
        }

        $script_name = '/' . implode('/', array_filter(explode('/', $_SERVER['SCRIPT_NAME'])));
        $root = strlen(dirname($script_name)) > 1 ? dirname($script_name) : '';

        return $schema . '://' . $_SERVER['HTTP_HOST'] . $root;
    }
}
