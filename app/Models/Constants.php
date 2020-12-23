<?php


namespace App\Models;


class Constants
{
    const DEFAULT_PASSWORD = '12345678';

    const GENDER_FEMALE = 0;
    const GENDER_MALE = 1;
    const GENDER_LABEL = [
        self::GENDER_FEMALE => "Nữ",
        self::GENDER_MALE => "Nam"
    ];

    const TEXT_CODE_TELEX = 0;
    const TEXT_CODE_VNI = 1;
    const TEXT_CODE_LABEL = [
        self::TEXT_CODE_TELEX => "TELEX",
        self::TEXT_CODE_VNI => "VNI"
    ];

    const ALLOW_UPLOAD_MIME_TYPE = [
        'mpeg',
        'mp4',
        'png',
        'jpeg',
        'gif',
        'doc',
        'docx',
        'pdf',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
        'html',
        'htm',
        'xlm',
        'rtf',
    ];
}
