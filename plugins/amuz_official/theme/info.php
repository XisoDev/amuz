<?php
return [
    'view' => 'theme',
    'setting' => [
        [
            'section' => [
                'class' => 'section_layout',
                'title' => '섹션 #1 설정'
            ],
            'fields' => [
                'layout' => [
                    '_type' => 'select',
                    'label' => '섹션 #1',
                    'options' => [
                        'particle' => '파티클',
                        'image' => '이미지',
                        'default' => '사용안함'
                    ]
                ],
                'logoText' => [
                    '_type' => 'langText',
                    'label' => '로고 제목',
                    'placeholder' => '로고 대체 텍스트를 입력하세요'
                ],
                'logoImage' => [
                    '_type' => 'image',
                    'label' => '로고 이미지',
                    'description' => '로고 이미지를 등록하세요'
                ],
                'bgImage' => [
                    '_type' => 'image',
                    'label' => '배경 이미지',
                    'description' => '배경 이미지를 등록하세요'
                ],
                'bgColor' => [
                    '_type' => 'text',
                    'label' => '배경 색상',
                    'placeholder' => '배경 색상 코드를 입력하세요 (예) #AABBCC, rgba(0,0,0,.5)'
                ],
                'bgTitle' => [
                    '_type' => 'text',
                    'label' => '섹션 #1 타이틀',
                    'placeholder' => '첫번째 섹션의 제목을 입력하세요'
                ],
                'bgContent' => [
                    '_type' => 'textarea',
                    'label' => '섹션 #1 내용',
                    'placeholder' => '첫번째 섹션의 내용을 입력하세요'
                ],
                'bgTxtColor' => [
                    '_type' => 'text',
                    'label' => '글자 색상',
                    'placeholder' => '타이틀과 내용의 글자 색상 코드를 입력하세요 (예) #AABBCC, rgba(0,0,0,.5)'
                ]
            ]
        ],
        /* 헤더 설정 */
        [
            'section' => [
                'class' => 'section_header',
                'title' => '헤더 설정'
            ],
            'fields' => [
                'mainMenu' => [
                    '_type' => 'menu',
                    'label' => '메인 메뉴 선택'
                ],
                'headerPosition' => [
                    '_type' => 'select',
                    'label' => '메뉴 위치 선택',
                    'options' => [
                        '' => '좌측 고정',
                        'top-fixed' => '상단 고정'
                    ]
                ],
                'headerBgColor' => [
                    '_type' => 'text',
                    'label' => '헤더 배경 색상',
                    'placeholder' => '배경 색상 코드를 입력하세요 (예) #AABBCC, rgba(0,0,0,.5)'
                ]
            ]
        ],
        /* 푸터 설정 */
        [
            'section' => [
                'class' => 'footer-section',
                'title' => '푸터 설정'
            ],
            'fields' => [
                'footerLogoText' => [
                    '_type' => 'langText',
                    'label' => '푸터 로고 제목',
                    'placeholder' => '푸터에 표시될 로고 대체 텍스트를 입력하세요',
                ],
                'footerLogoImage' => [
                    '_type' => 'image',
                    'label' => '푸터 로고 이미지',
                ],
                'footerContents' => [
                    '_type' => 'langTextarea',
                    'label' => '푸터 내용 입력',
                    'placeholder' => '푸터에 별도로 표시하고 싶은 텍스트를 입력하세요',
                ],
                'useFooterMenu' => [
                    '_type' => 'select',
                    'label' => '푸터 메뉴 사용 여부',
                    'options' => [
                        'N' => '사용 안 함',
                        'Y' => '사용'
                    ]
                ],
                'footerMenu' => [
                    '_type' => 'menu',
                    'label' => '푸터 메뉴 선택',
                ],
                'copyright' => [
                    '_type' => 'textarea',
                    'label' => '카피라이트 입력',
                    'placeholder' => 'Copyright @2016 Xpessengine. All rights reserved.',
                ],
                'useFooterLinks' => [
                    '_type' => 'select',
                    'label' => '푸터 링크 사용 여부',
                    'options' => [
                        'Y' => '사용',
                        'N' => '사용 안 함'
                    ]
                ],
                'footerLinkIcon[0]' => [
                    '_type' => 'text',
                    'label' => '푸터 링크1 아이콘',
                ],
                'footerLinkUrl[0]' => [
                    '_type' => 'text',
                    'label' => '푸터 링크1 주소',
                ],
                'footerLinkIcon[1]' => [
                    '_type' => 'text',
                    'label' => '푸터 링크2 아이콘',
                ],
                'footerLinkUrl[1]' => [
                    '_type' => 'text',
                    'label' => '푸터 링크2 주소',
                ],
                'footerLinkIcon[2]' => [
                    '_type' => 'text',
                    'label' => '푸터 링크3 아이콘',
                ],
                'footerLinkUrl[2]' => [
                    '_type' => 'text',
                    'label' => '푸터 링크3 주소',
                ],
                'useMultiLang' => [
                    '_type' => 'select',
                    'label' => '다국어 선택기',
                    'options' => [
                        'Y' => '사용',
                        'N' => '사용 안 함'
                    ]
                ]
            ]
        ]
    ],
    'support' => [
        'mobile' => true,
        'desktop' => true
    ],
    'editable' => [
        'theme.blade.php',
        'gnb.blade.php'
    ]
];
