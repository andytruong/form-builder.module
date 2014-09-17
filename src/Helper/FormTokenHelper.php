<?php

namespace Drupal\form_builder\Helper;

use Drupal\form_builder\FormEntity;

class FormTokenHelper
{

    private $hashKey = 'FormBuilder';

    private function encrypt($string)
    {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->hashKey), $string, MCRYPT_MODE_CBC, md5(md5($this->hashKey))));
    }

    private function decrypt($encrypted)
    {
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->hashKey), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($this->hashKey))), "\0");
    }

    /**
     * Generate a token for a form
     */
    public function generate(FormEntity $form, $pageNumber = 1)
    {
        $sessId = session_id();
        return $this->encrypt("{$sessId}.{$form->fid}.{$pageNumber}");
    }

    public function getDrupalCacheId($encrypted)
    {
        list($sessId, $formId, ) = explode('.', $this->decrypt($encrypted));
        return 'formBuilder:' . $sessId . ':' . $formId;
    }

}
