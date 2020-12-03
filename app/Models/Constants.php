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

    const HC_PHO_THONG = 1;
    const HC_NGOAI_GIAO = 2;
    const HC_CONG_VU = 3;
    const HC_LABEL = [
        self::HC_PHO_THONG => "Hộ chiếu phổ thông",
        self::HC_NGOAI_GIAO => "Hộ chiếu ngoại giao",
        self::HC_CONG_VU => "Hộ chiếu công vụ",
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
