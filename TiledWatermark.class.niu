<?php

/** 
* 文字图案水印平铺工具类 
* 
* @time 2017.02.16 23:26:33
* @author Joe 
* @link https://tahenniu.com 
*
*/

class TiledWatermark
{
      private static $_instance;
      private $use_size_array = true;
      private $is_run = false;
      # 基础配置
      private $all_config = array();

      private function __construct()
      {}
      private function __clone()
      {}

      public static function getInstance()
      {
            if (!self::$_instance instanceof self)
            {
                  self::$_instance = new self;
            }
      return self::$_instance;
      }

      /** 
      * mime类型 
      * 
      * @var array 
      */ 
      private static $mime_types = array(
            2 => 'jpeg', 
            3 => 'png' 
      ); 

      /** 
      * size数组
      * 
      * @var array 
      *
      * 参数分别为：背景width范围,font-size,对应offset坐标位置偏移量
      * 可根据实际使用情况调整以达到最佳显示效果
      */
      private $size_array = array( 
            400 => array(12, 140), 
            800 => array(16, 200),
            1200 => array(18, 220),
            1500 => array(22, 350),
            1800 => array(30, 400),
            2000 => array(38, 470),
            2500 => array(50, 600),
            3500 => array(60, 700),
            'max_size' => array(68, 760)
      );

      /** 
      * 检查图片 
      *  
      */
      private function ckeckImage($image_path_type = 'bg')
      {

            # 检查图片
            $this->image_type_name = $image_path_type =='bg' ? '背景' : 'logo';
            $this->image_path = $image_path_type =='bg' ? $this->all_config['draw_bg'] : $this->all_config['logo_img'];

            if(!$this->image_path || !is_file($this->image_path))
            { 
                  trigger_error($this->image_type_name .'图片不存在！"' . $image_path . '"', E_USER_ERROR); 
            }

            # 图片数据，0 width, 1 height mime mime类型 
            $tmp_data = getimagesize($this->image_path); 

            # 检查图片尺寸
            if(!isset($tmp_data[0]) || $tmp_data[0] < 1 || !isset($tmp_data[1]) || $tmp_data[1] < 1) { 
                  trigger_error('路径：' . $this->image_path . '无效或已损毁的'.$this->image_type_name.'图片！'); 
            }

            # 检查图片类型
            if(!isset($tmp_data[2]) || !isset(self::$mime_types[$tmp_data[2]])) { 
                  trigger_error('路径：' . $this->image_path . '不被允许的'.$this->image_type_name.'图片类型！', E_USER_ERROR); 
            }

            if ($this->image_type_name =='背景')
            {
                  $this->tmp_data = getimagesize($this->all_config['draw_bg']);
                  list($this->img_w, $this->img_h) = $this->tmp_data;
            }
            else
            {
                  $this->logo_tmp_data = getimagesize($this->all_config['logo_img']);
                  list($this->logo_w, $this->logo_h) = $this->logo_tmp_data;

            }
      }

      /** 
      * 初始化 
      */ 
      private function initialize()
      { 
            if(!$this->is_run)
            {    
                  $this->ckeckImage('bg');
                  
                  # 是否已加载GD库 
                  if(!function_exists('getimagesize'))
                  { 
                        trigger_error(__CLASS__ . '运行依赖GD库，请检查扩展是否安装或加载！', E_USER_ERROR); 
                  } 
                  if($this->all_config['draw_type'] =='img')
                  {
                        # 检查logo文件
                        $this->ckeckImage('logo');
                  }
                  else
                  {

                        # 检查字体文件
                        if(!is_file($this->all_config['font_file'])) { 
                              trigger_error('字体文件不存在！', E_USER_ERROR); 
                        } 

                        # 是否为水印设置了有效的RBG参数
                        if($this->all_config['text_rgb'] === null || strlen($this->all_config['text_rgb']) < 1)
                        {
                              # 设置水印默认RGB值
                              $this->all_config['text_rgb'] = 'x0b4';
                        } 

                        # 是否为阴影设置了有效的RGB参数
                        if($this->all_config['shadow'])
                        { 
                              if($this->all_config['shadow_rgb'] === null || strlen($this->all_config['shadow_rgb']) < 1)
                              {
                                    # 默认阴影RGB
                                    $this->all_config['shadow_rgb'] = '0x00';
                              } 
                        } 

                        # 检查水印文本 
                        if(strlen($this->all_config['watermark_text']) < 1 || str_replace(' ','',$this->all_config['watermark_text']) =='')
                        { 
                              trigger_error('无效的水印文字！', E_USER_ERROR); 
                        } 
                  }
                  # 可以开始绘制水印
                  $this->is_run; 
            } 
      }

      /** 
      * RGB字符串转换为数组 
      * 
      * @param mixed $rgb_string 
      * @return array 0 red, 1 green, 2 blue.
      */ 
      private function rgbStringToArray($rgb_string)
      { 
            $rgb = array(
                  0 => null,
                  1 => null,
                  2 => null
                  );
            # 单个值
            if(strpos($rgb_string, ',') === false)
            { 
                  for($i = 0; $i < 3; $i++)
                  { 
                        $rgb[$i] = $rgb_string; 
                  } 
            }
            # 多个值
            else
            {
                  $rgb_string = explode(',', $rgb_string);  
                  if(count($rgb_string) == 3)
                  { 
                        for($i = 0; $i < count($rgb_string); $i++)
                        { 
                              $rgb[$i] = trim($rgb_string[$i]); 
                        } 
                  }
                  else
                  { 
                        trigger_error('rgb参数不正确！', E_USER_ERROR); 
                  } 
            }
            return $rgb; 
      } 

      /** 
      * 计算标记水印 
      * 
      * @staticvar int $i 
      * @param array $points 
      * @param int $p 
      * @param int $max_x 
      * @return array 
      */ 
      private function markPoints($points = array(), $p = 20, $max_x = 0)
      { 
            static $i = 0; 
            if(count($points) > 0) { 
                  $i++; 
                  $rand = rand(0, count($points) - 1); 
                  if(isset($points[$rand]))
                  { 
                        # 确保位置不超出范围 
                        if(($points[$rand]['x'] < $max_x && $points[$rand]['y'] != $p && $points[$rand]['y'] < $max_x) || $i >= 100)
                        { 
                              $points[$rand]['flag'] = true; 
                        }
                        else
                        { 
                              return $this->markPoints($points, $p, $max_x); 
                        } 
                  } 
            } 
            return $points; 
      } 

      /** 
      * 绘制水印 
      * 
      */ 
      public function okIsRun($config)
      { 
            $this->all_config = $config;
            $this->initialize(); 

            # 使用尺寸数组 
            if(is_array($this->size_array) && count($this->size_array) > 0)
            { 
                  $img_max = $this->img_w > $this->img_h ? $this->img_w : $this->img_h; 
                  $font_set = false; 
                  foreach($this->size_array as $size => $v)
                  { 
                        if($size !== 'max_size')
                        {
                              if($img_max < $size && isset($v[0]) && isset($v[1]))
                              { 
                                    $this->all_config['font_size'] = (int)$v[0];
                                     
                                    $this->all_config['horizontal'] = (int)$v[1]; 
                                    $this->all_config['vertical'] = (int)$v[1]; 
                                    $font_set = true; 
                                    break; 
                              } 
                        } 
                  }
                  
                  # 设置最大尺寸 
                  if(!$font_set && isset($this->size_array['max_size'][0]) && isset($this->size_array['max_size'][1]))
                  { 
                        $this->all_config['font_size'] = (int)$this->size_array['max_size'][0]; 
                        $this->all_config['horizontal'] = (int)$this->size_array['max_size'][1]; 
                        $this->all_config['vertical'] = (int)$this->size_array['max_size'][1]; 
                  } 
            }


            # png
            if($this->tmp_data[2] == 3)
            { 
                  $img = imagecreatetruecolor($this->img_w, $this->img_h); 
                  $img_source = imagecreatefrompng($this->all_config['draw_bg']); 
                  imagecopyresampled($img, $img_source, 0, 0, 0, 0, $this->img_w, $this->img_h, $this->img_w, $this->img_h); 
            }
            else
            { 
                  # 按需生成图像资源 
                  $f = 'imagecreatefrom' . self::$mime_types[$this->tmp_data[2]];
                  $img = $f($this->all_config['draw_bg']); 
            } 

            # 设置水印颜色 
            $rgb = $this->rgbStringToArray($this->all_config['text_rgb']); 
            $rgb2 = $this->rgbStringToArray($this->all_config['shadow_rgb']); 

            # 如果设置透明度需要使用透明效果 
            if((int)$this->all_config['opacity'] > 0 && (int)$this->all_config['opacity'] <= 127)
            { 
                  $wm_color = imagecolorallocatealpha($img, $rgb[0], $rgb[1], $rgb[2], (int)$this->all_config['opacity']); 
                  if($this->all_config['shadow'])
                  { 
                        $wm_shadow_color = imagecolorallocatealpha($img, $rgb2[0], $rgb2[1], $rgb2[2], (int)$this->all_config['opacity']); 
                  } 
            }
            else
            { 
                  $wm_color = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]); 
                  if($this->all_config['shadow'])
                  { 
                        $wm_shadow_color = imagecolorallocate($img, $rgb2[0], $rgb2[1], $rgb2[2]); 
                  } 
            } 

            # 生成绘制坐标点 
            $p = $this->all_config['random_location'] ? rand(10,100) : 25;

            $i = $max_x = 0; 
            $points = array(); 
            for($x = $p; $x < $this->img_w; $x += $this->all_config['horizontal'])
            { 
                  if($x > $max_x)
                  { 
                        $max_x = $x; 
                  } 
                  for($y = $p; $y < $this->img_h; $y += $this->all_config['vertical'])
                  { 
                        $points[$i] = array('x' => $x, 'y' => $y); 
                        if($this->all_config['shadow'])
                        { 
                              $points[$i]['x2'] = $x + $this->all_config['shadow_offset']; 
                              $points[$i]['y2'] = $y + $this->all_config['shadow_offset']; 
                        } 
                        $i++; 
                  } 
            } 

            switch ($this->all_config['draw_type'])
            {
                  case 'txt':
                  foreach($points as $m)
                  { 
                        if(isset($m['x2']) && isset($m['y2']))
                        { 
                              # 绘制阴影 
                              imagettftext($img, $this->all_config['font_size'], $this->all_config['rotate_angle'], $m['x2'], $m['y2'], 
                                    $wm_shadow_color, $this->all_config['font_file'], $this->all_config['watermark_text']); 
                        } 

                        # 绘制水印
                        imagettftext($img, $this->all_config['font_size'], $this->all_config['rotate_angle'], $m['x'], $m['y'], $wm_color, 
                        $this->all_config['font_file'], $this->all_config['watermark_text']); 
                        
                  }
                  break;
                  case 'img':
                  
                  $f = 'imagecreatefrom' . self::$mime_types[$this->logo_tmp_data[2]]; 
                  $logo_img = $f($this->all_config['logo_img']);


                  foreach($points as $m)
                  {
                        # 合并图像 
                        imagecopy($img,$logo_img,$m['x'],$m['y'],0,0,$this->logo_w,$this->logo_h);
                  }
                  break;
                  default:
                  trigger_error('水印类型参数不正确！', E_USER_ERROR);
                  break;
            }
 
            if ($this->tmp_data[2] == 3 || (isset($this->logo_tmp_data[2]) && $this->logo_tmp_data[2] == 3))
            {
                  $f = 'imagepng';
            }
            else
            {
                  $f = 'image' . self::$mime_types[$this->tmp_data[2]];
            }
            
            if(isset($this->logo_tmp_data[2]) && $this->logo_tmp_data[2] == 3)
            {
                  $this->out_image_mime = $this->logo_tmp_data['mime'];
            }
            else 
            { 
                  $this->out_image_mime = $this->tmp_data['mime'];
            }

            # 输出图像
            header('Content-type: '.$this->out_image_mime); 
            $f($img);
            # 释放资源
            if (isset($logo_img))
            {
                  imagedestroy($logo_img);
            }

            imagedestroy($img);

            
      }


}