<?php

require_once './TiledWatermark.class.niu'; 

$test = TiledWatermark::getInstance();

$config = array(
	 # 设置绘制类型'img'图片水印，'txt'文字水印
     'draw_type' => 'img',
     # 背景图片，支持jpeg,png
     'draw_bg' => './resources/test.jpg',
     # 水印透明度 0-127
     'opacity' => 33,
     # 水印是否随机位置
     'random_location' => false,
     # logo水印
     'logo_img' => './resources/ohcodes_logo.png',
     # 字体文件
     'font_file' => './resources/1.ttf',
     # 倾斜度，仅文字水印生效
     'rotate_angle' => 22,
     # 水印文字
     'watermark_text'=> '某某有限责任公司',
     # 水平偏移量
     'horizontal' => 0,
     # 垂直偏移量
     'vertical' => 0,
     # font size
     'font_size' => 12,
     # 水印文字颜色13同等于RGB 13,13,13
     'text_rgb' => 13,
     # 文字水印是否开启阴影
     'shadow' => false,
     # 文字水印阴影颜色
     'shadow_rgb' => '160,210,119',
     # 阴影偏移量，允许负值如-3
     'shadow_offset' => 3,

      );

$test->okIsRun($config);

?>