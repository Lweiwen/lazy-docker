<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Controller\BaseController;
use App\Model\Attachments;
use App\Services\AttachmentService;
use Hyperf\Di\Annotation\Inject;
use Swow\Psr7\Message\ResponsePlusInterface;

class AttachmentController extends BaseController
{
    #[Inject]
    protected AttachmentService $service;

    /**
     * 图片上传
     * @return ResponsePlusInterface
     * @throws \Throwable
     * @author LWW
     */
    public function upload()
    {
        $file = $this->request->file('images');
//        if (!$file) {
//            return $this->response->fail('请上传图片');
//        }
//        $maxSize = Attachment::maxSize;
        $allowType = Attachments::allowType['image'];
        $rules = [
            'images.*' => sprintf(
                'required|image|mimetypes:%s|mimes:%s',
                $allowType['mime_type'],
                $allowType['extension'],
            ),
        ];
        $message = [
            'required'  => '请上传:attribute ',
            'image'     => ':attribute必须是图片',
            'mimetypes' => ':attributeMIME TYPE不正确',
            'mimes'     => ':attribute文件扩展名不正确',
        ];
        $attributes = [
            'images.*' => '图片',
        ];
        $files = [];
        if (is_array($file)) {
            if (count($file) > 9) {
                $this->response->fail('最多上传9张图片');
            }
            $files = $file;
        } else {
            $files[0] = $file;
        }
        $this->validatorCheck(['images' => $files], $rules, $message, $attributes);
        return $this->response->success($this->service->upload($files));
    }

}